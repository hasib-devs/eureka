<?php

declare(strict_types=1);

use App\Models\TrackingSetting;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/*
| tests/TestCase.php sets mockConsoleOutput = false for the whole suite, so
| $this->artisan() returns an exit code rather than a PendingCommand. Output is
| therefore captured with Artisan::call() + Artisan::output().
*/

beforeEach(function () {
    Cache::flush();
    app(TrackingSettingsService::class)->flush();
});

it('runs a dry verify without sending anything', function () {
    Http::fake();

    expect(Artisan::call('tracking:verify', ['--dry' => true]))->toBe(0);

    Http::assertNothingSent();
});

it('never prints the access token in a dry run', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'SUPER-SECRET-TOKEN',
        'meta_enabled' => true,
    ]);
    app(TrackingSettingsService::class)->flush();

    Artisan::call('tracking:verify', ['--dry' => true]);
    $output = Artisan::output();

    // The payload is printed to a terminal and its scrollback — the token must
    // be redacted, not merely hard to notice.
    expect($output)->not->toContain('SUPER-SECRET-TOKEN')
        ->and($output)->toContain('[redacted]');
});

it('reports failure when nothing is configured to send', function () {
    Http::fake();

    // Nothing enabled means there is no pipeline to verify — not a pass.
    expect(Artisan::call('tracking:verify'))->toBe(1);

    Http::assertNothingSent();
});

it('sends a sample event through meta capi when enabled', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'test-token',
        'meta_enabled' => true,
        'meta_test_event_code' => 'TEST123',
        'site_url' => 'https://shop.example',
    ]);
    app(TrackingSettingsService::class)->flush();

    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    expect(Artisan::call('tracking:verify'))->toBe(0);

    Http::assertSent(function ($request) {
        $event = $request->data()['data'][0];

        return $event['event_name'] === 'Purchase'
            && $request->data()['test_event_code'] === 'TEST123'
            // Hashed, never raw.
            && $event['user_data']['em'] === hash('sha256', 'test@example.com');
    });
});

it('sends a sample event through GA4 when enabled', function () {
    TrackingSetting::create([
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_api_secret' => 'test-secret',
        'ga4_enabled' => true,
    ]);
    app(TrackingSettingsService::class)->flush();

    Http::fake(['*google-analytics.com/*' => Http::response('', 204)]);

    expect(Artisan::call('tracking:verify'))->toBe(0);

    Http::assertSent(function ($request) {
        $event = $request->data()['events'][0];

        // debug_mode is what makes it visible in DebugView.
        return $event['name'] === 'purchase' && ($event['params']['debug_mode'] ?? null) === 1;
    });
});

it('shares one event id across the meta and GA4 legs', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'test-token',
        'meta_enabled' => true,
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_api_secret' => 'test-secret',
        'ga4_enabled' => true,
    ]);
    app(TrackingSettingsService::class)->flush();

    Http::fake([
        'graph.facebook.com/*' => Http::response(['events_received' => 1]),
        '*google-analytics.com/*' => Http::response('', 204),
    ]);

    expect(Artisan::call('tracking:verify'))->toBe(0);

    $metaEventId = null;
    $ga4EventId = null;

    Http::assertSent(function ($request) use (&$metaEventId, &$ga4EventId) {
        if (str_contains($request->url(), 'graph.facebook.com')) {
            $metaEventId = $request->data()['data'][0]['event_id'];
        }

        if (str_contains($request->url(), 'google-analytics.com')) {
            $ga4EventId = $request->data()['events'][0]['params']['event_id'] ?? null;
        }

        return true;
    });

    // One user action, one id across every leg — the whole point of verifying.
    expect($metaEventId)->not->toBeNull()
        ->and($ga4EventId)->not->toBeNull()
        ->and($metaEventId)->toBe($ga4EventId);
});

it('warns when sending real traffic without a test event code', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'test-token',
        'meta_enabled' => true,
    ]);
    app(TrackingSettingsService::class)->flush();

    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    Artisan::call('tracking:verify');

    expect(Artisan::output())->toContain('count as REAL traffic');
});

it('prints the browser leg with the same event id as the server sent', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'test-token',
        'meta_enabled' => true,
        'meta_test_event_code' => 'TEST123',
    ]);
    app(TrackingSettingsService::class)->flush();

    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    Artisan::call('tracking:verify');
    $output = Artisan::output();

    $sentEventId = null;

    Http::assertSent(function ($request) use (&$sentEventId) {
        $sentEventId = $request->data()['data'][0]['event_id'];

        return true;
    });

    // The printed snippet is only useful if it reuses the server's id.
    expect($output)->toContain('trackEvent(')
        ->and($output)->toContain($sentEventId);
});

it('generates a settings encryption key', function () {
    expect(Artisan::call('tracking:key'))->toBe(0);

    expect(Artisan::output())->toContain('SETTINGS_ENCRYPTION_KEY=base64:');
});
