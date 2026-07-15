<?php

declare(strict_types=1);

use App\Jobs\SendGa4MpEvent;
use App\Jobs\SendMetaCapiEvent;
use App\Models\TrackingSetting;
use App\Models\TrackingSettingAudit;
use App\Services\Tracking\Ga4MeasurementProtocolService;
use App\Services\Tracking\MetaCapiService;
use App\Services\Tracking\TrackingEvents;
use App\Services\Tracking\TrackingRedactor;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Regression tests
|--------------------------------------------------------------------------
|
| Each of these pins a bug found by review AFTER the feature "worked" and the
| whole suite was green. Every one of them failed silently — no exception, no
| log line, just tracking quietly not working. That is exactly the failure mode
| the brief warns about, so they get permanent guards.
|
*/

beforeEach(function () {
    Cache::flush();
    app(TrackingSettingsService::class)->flush();
});

// ─── Cookies must survive EncryptCookies ────────────────────────────────────

it('does not null out the JS-written cookies the server depends on', function () {
    // EncryptCookies decrypts every incoming cookie and silently replaces
    // anything unreadable with null. _fbp/_fbc/_ga/tracking_consent are written
    // as plaintext by JavaScript, so without an exception list the server reads
    // null for all of them: EMQ collapses, server GA4 hits invent a second
    // user, and an EU visitor's consent never arrives. Nothing errors.
    $middleware = app(EncryptCookies::class);

    $request = Request::create('/', 'GET', [], [
        '_fbp' => 'fb.1.1700000000000.1234567890',
        '_fbc' => 'fb.1.1700000000000.IwAR123abc',
        '_ga' => 'GA1.1.1234567890.1700000000',
        'tracking_consent' => '{"ad_storage":"granted","analytics_storage":"granted","ad_user_data":"granted","ad_personalization":"granted"}',
    ]);

    $seen = [];

    $middleware->handle($request, function (Request $r) use (&$seen) {
        $seen = [
            '_fbp' => $r->cookie('_fbp'),
            '_fbc' => $r->cookie('_fbc'),
            '_ga' => $r->cookie('_ga'),
            'tracking_consent' => $r->cookie('tracking_consent'),
        ];

        return response('ok');
    });

    expect($seen['_fbp'])->toBe('fb.1.1700000000000.1234567890')
        ->and($seen['_fbc'])->toBe('fb.1.1700000000000.IwAR123abc')
        ->and($seen['_ga'])->toBe('GA1.1.1234567890.1700000000')
        ->and($seen['tracking_consent'])->toContain('granted');
});

// ─── Secrets must not leak through error paths ──────────────────────────────

it('does not leak the meta access token when the request throws', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'SUPER_SECRET_META_TOKEN',
        'meta_enabled' => true,
    ]);
    app(TrackingSettingsService::class)->flush();

    // Guzzle appends the full request URI to cURL-level exception messages, and
    // Test Connection renders the message straight into the admin's DOM. A DNS
    // blip must not become a secret disclosure.
    Http::fake(fn () => throw new RuntimeException(
        'cURL error 6: Could not resolve host: graph.facebook.com for '
        .'https://graph.facebook.com/v25.0/1234567890?fields=name&access_token=SUPER_SECRET_META_TOKEN'
    ));

    $result = app(MetaCapiService::class)->testConnection();

    expect($result['ok'])->toBeFalse()
        ->and($result['message'])->not->toContain('SUPER_SECRET_META_TOKEN');
});

it('does not leak the ga4 api secret when the request throws', function () {
    TrackingSetting::create([
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_api_secret' => 'SUPER_SECRET_GA4_VALUE',
        'ga4_enabled' => true,
    ]);
    app(TrackingSettingsService::class)->flush();

    // GA4 requires api_secret in the query string, so this one cannot be moved
    // to a header — the message has to be scrubbed.
    Http::fake(fn () => throw new RuntimeException(
        'cURL error 28: Operation timed out for '
        .'https://www.google-analytics.com/debug/mp/collect?measurement_id=G-TEST12345&api_secret=SUPER_SECRET_GA4_VALUE'
    ));

    $result = app(Ga4MeasurementProtocolService::class)->testConnection();

    expect($result['ok'])->toBeFalse()
        ->and($result['message'])->not->toContain('SUPER_SECRET_GA4_VALUE');
});

it('keeps the meta access token out of the request url entirely', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'SUPER_SECRET_META_TOKEN',
        'meta_enabled' => true,
    ]);
    app(TrackingSettingsService::class)->flush();

    Http::fake(['graph.facebook.com/*' => Http::response(['id' => '1', 'name' => 'P'])]);

    app(MetaCapiService::class)->testConnection();

    // Sent as a Bearer header, so it can never appear in an exception message.
    Http::assertSent(fn ($request) => ! str_contains($request->url(), 'SUPER_SECRET_META_TOKEN'));
});

it('redacts credential-shaped query parameters it was not given', function () {
    // Defence in depth: a rotated secret we no longer hold must still be
    // scrubbed out of an error string.
    $text = 'failed for https://x.test/collect?measurement_id=G-1&api_secret=ROTATED_VALUE&x=1';

    expect(TrackingRedactor::scrub($text))->not->toContain('ROTATED_VALUE')
        ->and(TrackingRedactor::scrub($text))->toContain('measurement_id=G-1');
});

// ─── Nothing changes when tracking is disabled ──────────────────────────────

it('does not touch the session while tracking is disabled', function () {
    // The producer must not write what the consumer will not drain: otherwise
    // the queue grows on every page view of a store with tracking off, and the
    // whole backlog fires the moment an admin enables it.
    $request = request();
    $request->setLaravelSession(app('session.store'));

    $events = app(TrackingEvents::class);

    foreach (range(1, 10) as $i) {
        $events->queueBrowserEvent($request, 'ViewContent', 'view_item', "evt-{$i}");
    }

    expect($request->session()->get(TrackingEvents::QUEUE_KEY))->toBeNull();
});

it('queues browser events once a tag is enabled', function () {
    TrackingSetting::create(['meta_pixel_id' => '1234567890', 'meta_enabled' => true]);
    app(TrackingSettingsService::class)->flush();

    $request = request();
    $request->setLaravelSession(app('session.store'));

    app(TrackingEvents::class)->queueBrowserEvent($request, 'ViewContent', 'view_item', 'evt-1');

    expect($request->session()->get(TrackingEvents::QUEUE_KEY))->toHaveCount(1);
});

it('caps the browser queue so a JSON-only visitor cannot grow it forever', function () {
    TrackingSetting::create(['meta_pixel_id' => '1234567890', 'meta_enabled' => true]);
    app(TrackingSettingsService::class)->flush();

    $request = request();
    $request->setLaravelSession(app('session.store'));

    $events = app(TrackingEvents::class);

    foreach (range(1, TrackingEvents::QUEUE_LIMIT + 15) as $i) {
        $events->queueBrowserEvent($request, 'ViewContent', 'view_item', "evt-{$i}");
    }

    $queued = $request->session()->get(TrackingEvents::QUEUE_KEY);

    expect($queued)->toHaveCount(TrackingEvents::QUEUE_LIMIT)
        // The newest are kept; the oldest are the least useful to replay.
        ->and(end($queued)['eventId'])->toBe('evt-'.(TrackingEvents::QUEUE_LIMIT + 15));
});

// ─── The queue survives background requests ─────────────────────────────────

it('keeps a queued event alive across intervening background requests', function () {
    // The layout POSTs a session-backed heartbeat every 5 seconds. Flash data
    // survives exactly one request, so a flashed event queued on a JSON
    // response (the Lead capture) was aged out before any page could render it
    // — the browser leg vanished while the CAPI leg had already been sent.
    TrackingSetting::create(['meta_pixel_id' => '1234567890', 'meta_enabled' => true]);
    app(TrackingSettingsService::class)->flush();

    $session = app('session.store');
    $request = request();
    $request->setLaravelSession($session);

    app(TrackingEvents::class)->queueBrowserEvent($request, 'Lead', 'generate_lead', 'evt-lead');

    // Simulate the heartbeat requests that age flash data.
    $session->ageFlashData();
    $session->ageFlashData();
    $session->ageFlashData();

    expect($session->get(TrackingEvents::QUEUE_KEY))->toHaveCount(1)
        ->and($session->get(TrackingEvents::QUEUE_KEY)[0]['eventId'])->toBe('evt-lead');
});

it('drains a queued event exactly once', function () {
    TrackingSetting::create(['meta_pixel_id' => '1234567890', 'meta_enabled' => true]);
    app(TrackingSettingsService::class)->flush();

    $request = request();
    $request->setLaravelSession(app('session.store'));

    $events = app(TrackingEvents::class);
    $events->queueBrowserEvent($request, 'Purchase', 'purchase', 'evt-once');

    expect($events->drainBrowserEvents($request))->toHaveCount(1)
        // Second render must not fire it again.
        ->and($events->drainBrowserEvents($request))->toHaveCount(0);
});

// ─── Degradation must not be sticky ─────────────────────────────────────────

it('recovers as soon as the database comes back', function () {
    // Memoizing the disabled stand-in would turn one transient blip into
    // tracking being off for the rest of the request or job.
    $settings = app(TrackingSettingsService::class);

    Schema::drop('tracking_settings');
    expect($settings->degraded())->toBeTrue();

    // Rebuild from the real migration rather than restating the schema here.
    // (`migrate --path` is a no-op: the migration is already recorded as run.)
    require_once __DIR__.'/../../../database/migrations/2026_07_15_012044_create_tracking_settings_table.php';
    (include __DIR__.'/../../../database/migrations/2026_07_15_012044_create_tracking_settings_table.php')->up();

    TrackingSetting::create(['meta_pixel_id' => '1234567890', 'meta_enabled' => true]);
    Cache::flush();

    expect($settings->current()->meta_pixel_id)->toBe('1234567890')
        ->and($settings->degraded())->toBeFalse();
});

// ─── A leaked secret must be revocable ──────────────────────────────────────

it('can clear a saved secret so a leaked token can be revoked', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_access_token' => 'LEAKED-TOKEN']);
    expect(TrackingSetting::query()->first()->meta_access_token)->toBe('LEAKED-TOKEN');

    // "Blank means unchanged" is what makes the write-only form usable, but it
    // would also make a secret permanent. Clearing needs its own signal.
    $this->actingAs($admin)->put('/admin/setting/tracking', [
        'meta_access_token' => '',
        'clear_meta_access_token' => '1',
    ]);

    expect(TrackingSetting::query()->first()->meta_access_token)->toBeNull();
});

it('still treats a blank secret as unchanged when clear is not requested', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['ga4_api_secret' => 'KEEP-ME']);
    $this->actingAs($admin)->put('/admin/setting/tracking', ['ga4_api_secret' => '']);

    expect(TrackingSetting::query()->first()->ga4_api_secret)->toBe('KEEP-ME');
});

it('records a cleared secret in the audit log without its value', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_access_token' => 'LEAKED-TOKEN']);
    $this->actingAs($admin)->put('/admin/setting/tracking', ['clear_meta_access_token' => '1']);

    $audit = TrackingSettingAudit::where('field', 'meta_access_token')->latest('id')->first();

    expect($audit->new_value)->toBeNull()
        ->and($audit->old_value)->toBe(TrackingSettingAudit::MASK);
});

it('disables CAPI once the token is cleared', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', [
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'LEAKED-TOKEN',
        'meta_enabled' => '1',
    ]);
    expect(app(TrackingSettingsService::class)->metaCapiEnabled())->toBeTrue();

    $this->actingAs($admin)->put('/admin/setting/tracking', [
        'meta_pixel_id' => '1234567890',
        'meta_enabled' => '1',
        'clear_meta_access_token' => '1',
    ]);

    // The browser pixel keeps working; only the server leg stops.
    expect(app(TrackingSettingsService::class)->metaCapiEnabled())->toBeFalse()
        ->and(app(TrackingSettingsService::class)->metaPixelEnabled())->toBeTrue();
});

it('does not log a spurious audit for a reordered consent array', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', [
        'consent_mode_enabled' => '1',
        'consent_default_row' => [
            'ad_storage' => 'granted',
            'analytics_storage' => 'granted',
            'ad_user_data' => 'granted',
            'ad_personalization' => 'granted',
        ],
    ]);

    $before = TrackingSettingAudit::count();

    // Same values, different key order.
    $this->actingAs($admin)->put('/admin/setting/tracking', [
        'consent_mode_enabled' => '1',
        'consent_default_row' => [
            'ad_personalization' => 'granted',
            'ad_user_data' => 'granted',
            'analytics_storage' => 'granted',
            'ad_storage' => 'granted',
        ],
    ]);

    expect(TrackingSettingAudit::count())->toBe($before);
});

// ─── GA4 has no dedup, so exactly one leg may own each conversion ───────────

it('lets the server own the GA4 conversion when the measurement protocol will send it', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'token',
        'meta_enabled' => true,
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_api_secret' => 'secret',
        'ga4_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);
    app(TrackingSettingsService::class)->flush();

    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    $request->headers->set('CF-IPCountry', 'BD');

    $events = app(TrackingEvents::class);
    $events->purchase($request, makeOrder());

    $queued = $events->drainBrowserEvents($request);

    // Meta still fires in the browser (event_id dedups it against CAPI), but
    // the GA4 name is null — GA4 would otherwise count the purchase twice and
    // inflate revenue, since it has no equivalent of Meta's event_id dedup.
    expect($queued[0]['meta'])->toBe('Purchase')
        ->and($queued[0]['ga4'])->toBeNull();

    Bus::assertDispatched(SendGa4MpEvent::class);
});

it('lets the browser own the GA4 conversion when the server will not send it', function () {
    // GA4 MP not configured: the browser must keep the event, or it is lost.
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'token',
        'meta_enabled' => true,
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);
    app(TrackingSettingsService::class)->flush();

    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    $request->headers->set('CF-IPCountry', 'BD');

    $events = app(TrackingEvents::class);
    $events->purchase($request, makeOrder());

    $queued = $events->drainBrowserEvents($request);

    expect($queued[0]['ga4'])->toBe('purchase');
    Bus::assertNotDispatched(SendGa4MpEvent::class);
});

// ─── Meta CAPI must respect a denied visitor ────────────────────────────────

it('does not ship hashed PII to Meta for a visitor who denied consent', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'token',
        'meta_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);
    app(TrackingSettingsService::class)->flush();

    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // EU visitor: ad_storage denied by default. The pixel honours Consent Mode
    // itself, but a server-side CAPI call is invisible to it — so without an
    // explicit gate this visitor's hashed email, phone and address would be
    // shipped to Meta anyway.
    $request->headers->set('CF-IPCountry', 'DE');

    app(TrackingEvents::class)->purchase($request, makeOrder());

    Bus::assertNotDispatched(SendMetaCapiEvent::class);
});

it('still sends to Meta CAPI for a visitor whose region grants consent', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'token',
        'meta_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);
    app(TrackingSettingsService::class)->flush();

    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    $request->headers->set('CF-IPCountry', 'BD');

    app(TrackingEvents::class)->purchase($request, makeOrder());

    Bus::assertDispatched(SendMetaCapiEvent::class);
});

// ─── robots.txt must not deindex the storefront ─────────────────────────────

it('does not disallow the public vendor storefront', function () {
    // routes/web.php serves vendor/{slug} as a public catalogue page, so
    // blocking the /vendor prefix would deindex real content. The old static
    // robots.txt allowed everything.
    $body = $this->get('/robots.txt')->getContent();

    expect($body)->not->toContain('Disallow: /vendor')
        ->and($body)->toContain('Disallow: /admin');
});

// ─── gtag must exist regardless of Consent Mode ─────────────────────────────

it('defines gtag even when consent mode is switched off', function () {
    // The GA4 config block calls gtag() unconditionally. Defining the shim only
    // inside the consent branch threw "gtag is not defined" and killed GA4 for
    // anyone who turned Consent Mode off.
    TrackingSetting::create([
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_enabled' => true,
        'consent_mode_enabled' => false,
    ]);
    app(TrackingSettingsService::class)->flush();

    $html = $this->get('/')->getContent();

    $shim = strpos($html, 'function gtag(');
    $call = strpos($html, "gtag('js'");

    expect($shim)->not->toBeFalse()
        ->and($call)->not->toBeFalse()
        // Defined before it is called.
        ->and($shim)->toBeLessThan($call)
        ->and($html)->not->toContain("gtag('consent', 'default'");
});

// ─── Settings service lifetime ──────────────────────────────────────────────

it('resolves the settings service as a singleton', function () {
    // The memo is per-instance; a singleton scopes it to one request/job rather
    // than leaving it static and unbounded across a worker's lifetime.
    expect(app(TrackingSettingsService::class))->toBe(app(TrackingSettingsService::class));
});
