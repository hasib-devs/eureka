<?php

declare(strict_types=1);

use App\Jobs\SendGa4MpEvent;
use App\Jobs\SendMetaCapiEvent;
use App\Models\TrackingSetting;
use App\Services\Tracking\PiiHasher;
use App\Services\Tracking\TrackingEvents;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
    app(TrackingSettingsService::class)->flush();

    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'test-token',
        'meta_enabled' => true,
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_api_secret' => 'test-secret',
        'ga4_enabled' => true,
        'site_url' => 'https://shop.example',
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);

    app(TrackingSettingsService::class)->flush();
});

// makeOrder(), makeProduct() and adminUser() are provided globally by tests/Pest.php.

// ─── Deduplication contract ─────────────────────────────────────────────────

it('uses one event id for both the browser and server legs of a purchase', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    $eventId = app(TrackingEvents::class)->purchase($request, makeOrder());

    // The browser leg carries the same id the server sent...
    $queued = app(TrackingEvents::class)->drainBrowserEvents($request);
    expect($queued)->toHaveCount(1)
        ->and($queued[0]['meta'])->toBe('Purchase')
        ->and($queued[0]['eventId'])->toBe($eventId);

    // ...and so does the CAPI job. Matching event_name + event_id is what makes
    // Meta count this once rather than twice.
    Bus::assertDispatched(SendMetaCapiEvent::class, function ($job) use ($eventId) {
        return $job->eventName === 'Purchase' && $job->eventId === $eventId;
    });
});

it('mints a distinct event id per event', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    $events = app(TrackingEvents::class);

    expect($events->purchase($request, makeOrder(['order_id' => 'ORD-A', 'invoice' => 'INV-A'])))
        ->not->toBe($events->purchase($request, makeOrder(['order_id' => 'ORD-B', 'invoice' => 'INV-B'])));
});

// ─── Purchase payload ───────────────────────────────────────────────────────

it('sends full ecommerce parameters with purchase', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    $order = makeOrder();
    $productId = (string) $order->orderDetails()->first()->product_id;

    app(TrackingEvents::class)->purchase($request, $order);

    Bus::assertDispatched(SendMetaCapiEvent::class, function ($job) use ($productId) {
        $d = $job->customData;

        return $d['currency'] === 'BDT'
            && $d['value'] === 1060.0
            && $d['content_type'] === 'product'
            && $d['content_ids'] === [$productId]
            && $d['num_items'] === 2
            && $d['order_id'] === 'ORD-TEST1234'
            && $d['contents'][0] === ['id' => $productId, 'quantity' => 2, 'item_price' => 500.0];
    });
});

it('hashes purchase PII and leaves the match signals unhashed', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    app(TrackingEvents::class)->purchase($request, makeOrder());

    Bus::assertDispatched(SendMetaCapiEvent::class, function ($job) {
        $u = $job->userData;

        return $u['em'] === PiiHasher::email('John_Smith@gmail.com')
            && $u['ph'] === PiiHasher::phone('01712345678')
            && $u['fn'] === PiiHasher::firstName('John')
            && $u['ln'] === PiiHasher::lastName('Doe')
            && $u['country'] === PiiHasher::country('Bangladesh')
            && $u['zp'] === PiiHasher::zip('1207')
            // Raw values must never appear.
            && ! in_array('John_Smith@gmail.com', $u, true)
            && ! in_array('01712345678', $u, true);
    });
});

it('derives event_source_url from site_url rather than the request host', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    app(TrackingEvents::class)->purchase($request, makeOrder());

    Bus::assertDispatched(SendMetaCapiEvent::class, function ($job) {
        // Survives a domain migration because it follows the setting.
        return str_starts_with((string) $job->eventSourceUrl, 'https://shop.example');
    });
});

// ─── GA4 parity + consent gating ────────────────────────────────────────────

it('sends a matching GA4 measurement protocol event for a purchase', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    $request->headers->set('CF-IPCountry', 'BD');

    $order = makeOrder();
    $productId = (string) $order->orderDetails()->first()->product_id;

    app(TrackingEvents::class)->purchase($request, $order);

    Bus::assertDispatched(SendGa4MpEvent::class, function ($job) use ($productId) {
        return $job->eventName === 'purchase'
            && $job->params['transaction_id'] === 'ORD-TEST1234'
            && $job->params['value'] === 1060.0
            && $job->params['items'][0]['item_id'] === $productId;
    });
});

it('does not send either server leg for a visitor who denied consent', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // EU visitor: everything denied by default. The browser's consent signals
    // never reach a server-side hit, so both server legs have to check it here
    // or the visitor's choice is bypassed entirely.
    $request->headers->set('CF-IPCountry', 'DE');

    app(TrackingEvents::class)->purchase($request, makeOrder());

    Bus::assertNotDispatched(SendGa4MpEvent::class);
    // Meta CAPI is gated too, on ad_storage. An earlier version of this argued
    // Meta was exempt because Google's analytics_storage signal doesn't apply
    // to it — but that reasoning let a denied visitor's hashed email, phone and
    // address ship to Meta anyway. Denied means denied on both legs.
    Bus::assertNotDispatched(SendMetaCapiEvent::class);
});

// ─── Disabled = silent ──────────────────────────────────────────────────────

it('dispatches nothing when the integrations are disabled', function () {
    TrackingSetting::query()->update(['meta_enabled' => false, 'ga4_enabled' => false]);
    app(TrackingSettingsService::class)->flush();

    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    app(TrackingEvents::class)->purchase($request, makeOrder());

    Bus::assertNotDispatched(SendMetaCapiEvent::class);
    Bus::assertNotDispatched(SendGa4MpEvent::class);
});

it('does not dispatch CAPI without an access token', function () {
    TrackingSetting::query()->update(['meta_access_token' => null]);
    app(TrackingSettingsService::class)->flush();

    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    app(TrackingEvents::class)->purchase($request, makeOrder());

    Bus::assertNotDispatched(SendMetaCapiEvent::class);
});

// ─── Guest identifier persistence ───────────────────────────────────────────

it('reuses identifiers captured earlier in the session for later events', function () {
    Bus::fake();

    $request = request();
    $request->setLaravelSession(app('session.store'));
    // A Bangladeshi customer: consent granted by default. Meta CAPI is now
    // gated on ad_storage, so an unresolved country would (correctly) deny.
    $request->headers->set('CF-IPCountry', 'BD');

    $events = app(TrackingEvents::class);

    // A guest types their details at checkout...
    $events->rememberIdentifiers($request, ['em' => 'guest@example.com', 'ph' => '01799999999']);

    // ...and a later event in the same session still matches on them, rather
    // than only Purchase carrying identifiers.
    $events->initiateCheckout($request);

    Bus::assertDispatched(SendMetaCapiEvent::class, function ($job) {
        return $job->eventName === 'InitiateCheckout'
            && $job->userData['em'] === PiiHasher::email('guest@example.com')
            && $job->userData['ph'] === PiiHasher::phone('01799999999');
    });
});
