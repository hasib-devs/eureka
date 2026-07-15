<?php

declare(strict_types=1);

use App\Models\Setting;
use App\Models\TrackingSetting;
use App\Models\TrackingSettingAudit;
use App\Models\User;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

// adminUser() is provided globally by tests/Pest.php (role_id 1).

beforeEach(function () {
    Cache::flush();
    app(TrackingSettingsService::class)->flush();
});

// ─── Access control ─────────────────────────────────────────────────────────

it('blocks guests from the tracking panel', function () {
    $this->get('/admin/setting/tracking')->assertRedirect(route('login'));
});

it('blocks a non-admin user from the tracking panel', function () {
    seedRoles();
    // role_id 3 = User. The panel holds ad credentials; a customer must not reach it.
    $customer = User::factory()->create(['role_id' => 3]);

    $this->actingAs($customer)->get('/admin/setting/tracking')->assertRedirect(route('login'));
});

it('blocks a vendor from the tracking panel', function () {
    seedRoles();
    $vendor = User::factory()->create(['role_id' => 2]);

    $this->actingAs($vendor)->get('/admin/setting/tracking')->assertRedirect(route('login'));
});

it('allows an admin into the tracking panel', function () {
    $this->actingAs(adminUser())->get('/admin/setting/tracking')->assertOk();
});

it('blocks a non-admin from saving tracking settings', function () {
    seedRoles();
    $customer = User::factory()->create(['role_id' => 3]);

    $this->actingAs($customer)
        ->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890'])
        ->assertRedirect(route('login'));

    expect(TrackingSetting::query()->first()?->meta_pixel_id)->not->toBe('1234567890');
});

it('blocks a non-admin from running a connection test', function () {
    seedRoles();
    $customer = User::factory()->create(['role_id' => 3]);

    $this->actingAs($customer)->post('/admin/setting/tracking/test/meta')->assertRedirect(route('login'));
});

// ─── Secrets ────────────────────────────────────────────────────────────────

it('never renders a saved secret back to the page', function () {
    app(TrackingSettingsService::class)->currentForEdit()->update([
        'meta_access_token' => 'SUPER-SECRET-TOKEN',
        'ga4_api_secret' => 'SUPER-SECRET-GA4',
    ]);

    $html = $this->actingAs(adminUser())->get('/admin/setting/tracking')->getContent();

    expect($html)->not->toContain('SUPER-SECRET-TOKEN')
        ->and($html)->not->toContain('SUPER-SECRET-GA4')
        // It should still say a secret exists, without revealing it.
        ->and($html)->toContain('saved');
});

it('stores secrets encrypted rather than in plain text', function () {
    $this->actingAs(adminUser())->put('/admin/setting/tracking', [
        'meta_access_token' => 'PLAINTEXT-TOKEN',
    ]);

    $raw = DB::table('tracking_settings')->value('meta_access_token');

    expect($raw)->not->toBe('PLAINTEXT-TOKEN')
        ->and($raw)->not->toContain('PLAINTEXT-TOKEN')
        // ...but still decrypts back through the cast.
        ->and(TrackingSetting::query()->first()->meta_access_token)->toBe('PLAINTEXT-TOKEN');
});

it('keeps an existing secret when the field is submitted blank', function () {
    // The form never re-renders a secret, so a blank submit must not wipe it.
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_access_token' => 'ORIGINAL-TOKEN']);
    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_access_token' => '', 'meta_pixel_id' => '1234567890']);

    expect(TrackingSetting::query()->first()->meta_access_token)->toBe('ORIGINAL-TOKEN');
});

// ─── Saving ─────────────────────────────────────────────────────────────────

it('saves every integration field', function () {
    $this->actingAs(adminUser())->put('/admin/setting/tracking', [
        'meta_pixel_id' => '1234567890',
        'meta_api_version' => 'v25.0',
        'meta_enabled' => '1',
        'gtm_container_id' => 'GTM-ABC1234',
        'gtm_enabled' => '1',
        'ga4_measurement_id' => 'G-ABC123XYZ',
        'ga4_enabled' => '1',
        'gsc_verification_code' => 'verify-me',
        'gsc_enabled' => '1',
        'site_url' => 'https://example.com/',
    ])->assertRedirect(route('admin.setting.tracking'));

    $row = TrackingSetting::query()->first();

    expect($row->meta_pixel_id)->toBe('1234567890')
        ->and($row->meta_enabled)->toBeTrue()
        ->and($row->gtm_container_id)->toBe('GTM-ABC1234')
        ->and($row->ga4_measurement_id)->toBe('G-ABC123XYZ')
        ->and($row->gsc_verification_code)->toBe('verify-me')
        // Trailing slash normalised so canonical URLs never double up.
        ->and($row->site_url)->toBe('https://example.com');
});

it('can turn a toggle back off', function () {
    // Unchecked boxes are absent from the payload; they must still switch off.
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890', 'meta_enabled' => '1']);
    expect(TrackingSetting::query()->first()->meta_enabled)->toBeTrue();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890']);
    expect(TrackingSetting::query()->first()->meta_enabled)->toBeFalse();
});

it('rejects malformed ids', function () {
    $this->actingAs(adminUser())
        ->put('/admin/setting/tracking', [
            'meta_pixel_id' => 'not-a-pixel',
            'gtm_container_id' => 'nope',
            'ga4_measurement_id' => 'nope',
        ])
        ->assertSessionHasErrors(['meta_pixel_id', 'gtm_container_id', 'ga4_measurement_id']);
});

it('invalidates the settings cache immediately on save', function () {
    $settings = app(TrackingSettingsService::class);

    TrackingSetting::create(['meta_pixel_id' => '1111111111', 'meta_enabled' => true]);
    expect($settings->current()->meta_pixel_id)->toBe('1111111111');

    $this->actingAs(adminUser())->put('/admin/setting/tracking', [
        'meta_pixel_id' => '2222222222',
        'meta_enabled' => '1',
    ]);

    // No redeploy, no cache wait — the change is live.
    expect(app(TrackingSettingsService::class)->current()->meta_pixel_id)->toBe('2222222222');
});

// ─── Audit log ──────────────────────────────────────────────────────────────

it('records who changed a field and when', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890']);

    $audit = TrackingSettingAudit::where('field', 'meta_pixel_id')->first();

    expect($audit)->not->toBeNull()
        ->and($audit->user_id)->toBe($admin->id)
        ->and($audit->new_value)->toBe('1234567890')
        ->and($audit->created_at)->not->toBeNull();
});

it('masks secret values in the audit log', function () {
    $this->actingAs(adminUser())->put('/admin/setting/tracking', ['meta_access_token' => 'SECRET-TOKEN']);

    $audit = TrackingSettingAudit::where('field', 'meta_access_token')->first();

    // The fact and time of the change are recorded; the value never is.
    expect($audit)->not->toBeNull()
        ->and($audit->new_value)->toBe(TrackingSettingAudit::MASK)
        ->and($audit->new_value)->not->toContain('SECRET-TOKEN');
});

it('does not write an audit row when nothing changed', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890']);
    $countAfterFirst = TrackingSettingAudit::count();

    // Identical payload — every field diffs as unchanged, so nothing is logged.
    $this->actingAs($admin)->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890']);

    expect(TrackingSettingAudit::count())->toBe($countAfterFirst);
});

it('turns a toggle off on the very first save', function () {
    // Regression: PHP casts false to '' and true to '1', so comparing toggles
    // as strings made "switch off" look identical to "unchanged" and the write
    // was skipped. consent_mode_enabled defaults on, so a payload omitting it
    // must actually turn it off.
    $this->actingAs(adminUser())->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890']);

    $row = TrackingSetting::query()->first();

    expect($row->consent_mode_enabled)->toBeFalse()
        ->and($row->consent_banner_enabled)->toBeFalse()
        ->and(TrackingSettingAudit::where('field', 'consent_mode_enabled')->first()?->new_value)->toBe('0');
});

it('shows the audit log in the panel', function () {
    $this->actingAs(adminUser())->put('/admin/setting/tracking', ['meta_pixel_id' => '1234567890']);

    $this->actingAs(adminUser())
        ->get('/admin/setting/tracking')
        ->assertSee('meta_pixel_id')
        ->assertSee('Change log');
});

// ─── Legacy pixel conflict ──────────────────────────────────────────────────

it('warns when the legacy snippet still holds a pixel id', function () {
    Setting::updateOrCreate(['name' => 'fb_pixel'], [
        'value' => "<script>fbq('init', '9876543210');</script>",
    ]);
    Cache::forget('settings');

    $this->actingAs(adminUser())
        ->get('/admin/setting/tracking')
        ->assertSee('9876543210')
        ->assertSee('Conflict', false);
});

it('imports the legacy pixel id and clears the snippet only when asked', function () {
    Setting::updateOrCreate(['name' => 'fb_pixel'], [
        'value' => "<script>fbq('init', '9876543210');</script>",
    ]);
    Cache::forget('settings');

    $this->actingAs(adminUser())->post(route('admin.setting.tracking.import-legacy'))->assertRedirect();

    expect(TrackingSetting::query()->first()->meta_pixel_id)->toBe('9876543210')
        ->and(setting('fb_pixel'))->toBe('')
        // Imported, but deliberately NOT auto-enabled.
        ->and(TrackingSetting::query()->first()->meta_enabled)->toBeFalse();
});

it('extracts a pixel id from the noscript fallback too', function () {
    Setting::updateOrCreate(['name' => 'fb_pixel'], [
        'value' => '<noscript><img src="https://www.facebook.com/tr?id=5555555555&ev=PageView"/></noscript>',
    ]);
    Cache::forget('settings');

    $this->actingAs(adminUser())->post(route('admin.setting.tracking.import-legacy'));

    expect(TrackingSetting::query()->first()->meta_pixel_id)->toBe('5555555555');
});

it('reports when there is no legacy pixel to import', function () {
    Setting::updateOrCreate(['name' => 'fb_pixel'], ['value' => '']);
    Cache::forget('settings');

    $this->actingAs(adminUser())
        ->post(route('admin.setting.tracking.import-legacy'))
        ->assertSessionHas('error');
});

// ─── Test Connection ────────────────────────────────────────────────────────

it('reports a successful meta connection test', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'token',
        'meta_enabled' => true,
    ]);

    Http::fake(['graph.facebook.com/*' => Http::response(['id' => '1234567890', 'name' => 'My Pixel'])]);

    $this->actingAs(adminUser())
        ->post('/admin/setting/tracking/test/meta')
        ->assertOk()
        ->assertJson(['ok' => true]);
});

it('reports a failed meta connection test without leaking the token', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'bad-token-value',
        'meta_enabled' => true,
    ]);

    Http::fake([
        'graph.facebook.com/*' => Http::response(['error' => ['message' => 'Invalid OAuth access token.']], 400),
    ]);

    $response = $this->actingAs(adminUser())->post('/admin/setting/tracking/test/meta');

    $response->assertOk()->assertJson(['ok' => false]);
    expect($response->json('message'))->toContain('Invalid OAuth')
        ->and($response->json('message'))->not->toContain('bad-token-value');
});

it('validates the gtm container id format', function () {
    TrackingSetting::create(['gtm_container_id' => 'GTM-ABC1234', 'gtm_enabled' => true]);

    $this->actingAs(adminUser())
        ->post('/admin/setting/tracking/test/gtm')
        ->assertJson(['ok' => true]);
});

it('rejects an ga4 measurement id of the wrong shape', function () {
    TrackingSetting::create(['ga4_measurement_id' => 'UA-12345', 'ga4_enabled' => true]);

    $this->actingAs(adminUser())
        ->post('/admin/setting/tracking/test/ga4')
        ->assertJson(['ok' => false]);
});

it('confirms the gsc tag renders on the live site', function () {
    TrackingSetting::create([
        'gsc_verification_code' => 'live-code-123',
        'gsc_enabled' => true,
        'site_url' => 'https://example.com',
    ]);

    Http::fake([
        'https://example.com' => Http::response('<meta name="google-site-verification" content="live-code-123">'),
    ]);

    $this->actingAs(adminUser())
        ->post('/admin/setting/tracking/test/gsc')
        ->assertJson(['ok' => true]);
});

it('reports when the gsc tag is missing from the live site', function () {
    TrackingSetting::create([
        'gsc_verification_code' => 'live-code-123',
        'gsc_enabled' => true,
        'site_url' => 'https://example.com',
    ]);

    Http::fake(['https://example.com' => Http::response('<html>no tag here</html>')]);

    $this->actingAs(adminUser())
        ->post('/admin/setting/tracking/test/gsc')
        ->assertJson(['ok' => false]);
});

// ─── Domain migration ───────────────────────────────────────────────────────

it('warns when site_url does not match the host being served', function () {
    TrackingSetting::create(['site_url' => 'https://old-domain.example']);

    $this->actingAs(adminUser())
        ->get('/admin/setting/tracking')
        ->assertSee('Site URL does not match this domain');
});

it('does not warn when site_url matches the host', function () {
    TrackingSetting::create(['site_url' => 'http://localhost']);

    $this->actingAs(adminUser())
        ->get('/admin/setting/tracking')
        ->assertDontSee('Site URL does not match this domain');
});
