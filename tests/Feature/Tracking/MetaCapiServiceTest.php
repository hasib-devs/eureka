<?php

declare(strict_types=1);

use App\Models\TrackingSetting;
use App\Services\Tracking\MetaCapiService;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
    app(TrackingSettingsService::class)->flush();

    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'test-token',
        'meta_api_version' => 'v25.0',
        'meta_enabled' => true,
        'site_url' => 'https://shop.example',
    ]);

    app(TrackingSettingsService::class)->flush();
});

it('posts to the configured pixel and api version', function () {
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    app(MetaCapiService::class)->send('Purchase', 'evt-1', ['em' => str_repeat('a', 64)]);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.facebook.com/v25.0/1234567890/events';
    });
});

it('follows the api version when it is changed at runtime', function () {
    // No redeploy: bump the version in the admin panel and the next call uses it.
    TrackingSetting::query()->update(['meta_api_version' => 'v24.0']);
    app(TrackingSettingsService::class)->flush();

    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    app(MetaCapiService::class)->send('Purchase', 'evt-ver', []);

    Http::assertSent(fn ($request) => str_contains($request->url(), '/v24.0/'));
});

it('sends the documented event envelope', function () {
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    app(MetaCapiService::class)->send(
        'Purchase',
        'evt-2',
        ['em' => str_repeat('a', 64)],
        ['currency' => 'BDT', 'value' => 100.0],
        'https://shop.example/checkout'
    );

    Http::assertSent(function ($request) {
        $event = $request->data()['data'][0];

        return $event['event_name'] === 'Purchase'
            && $event['event_id'] === 'evt-2'
            && $event['action_source'] === 'website'
            && $event['event_source_url'] === 'https://shop.example/checkout'
            && is_int($event['event_time'])
            && $event['custom_data']['currency'] === 'BDT';
    });
});

it('includes the test event code when one is set', function () {
    TrackingSetting::query()->update(['meta_test_event_code' => 'TEST123']);
    app(TrackingSettingsService::class)->flush();

    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    app(MetaCapiService::class)->send('Lead', 'evt-3', []);

    Http::assertSent(fn ($request) => ($request->data()['test_event_code'] ?? null) === 'TEST123');
});

it('omits the test event code when it is cleared', function () {
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    app(MetaCapiService::class)->send('Lead', 'evt-4', []);

    Http::assertSent(fn ($request) => ! array_key_exists('test_event_code', $request->data()));
});

it('drops empty user data fields rather than sending nulls', function () {
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    app(MetaCapiService::class)->send('Lead', 'evt-5', [
        'em' => str_repeat('a', 64),
        'ph' => null,
        'fn' => null,
    ]);

    Http::assertSent(function ($request) {
        $userData = $request->data()['data'][0]['user_data'];

        return array_key_exists('em', $userData)
            && ! array_key_exists('ph', $userData)
            && ! array_key_exists('fn', $userData);
    });
});

// ─── Idempotency ────────────────────────────────────────────────────────────

it('does not resend an event id that already succeeded', function () {
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);

    $capi = app(MetaCapiService::class);

    expect($capi->send('Purchase', 'evt-dupe', []))->toBeTrue()
        // A retry of the same logical event must not double-count.
        ->and($capi->send('Purchase', 'evt-dupe', []))->toBeFalse();

    Http::assertSentCount(1);
});

it('allows a retry after a failure since nothing was recorded', function () {
    // An event is marked sent only once Meta confirms it, so a failed send is
    // not silently swallowed — it can be retried and still land.
    Http::fakeSequence('graph.facebook.com/*')
        ->push([], 500)   // attempt 1
        ->push([], 500)   // attempt 2
        ->push([], 500)   // attempt 3 — send gives up
        ->push(['events_received' => 1], 200);  // a later retry succeeds

    $capi = app(MetaCapiService::class);

    expect($capi->send('Purchase', 'evt-retry', []))->toBeFalse()
        ->and($capi->alreadySent('evt-retry'))->toBeFalse()
        ->and($capi->send('Purchase', 'evt-retry', []))->toBeTrue();
});

// ─── Retry policy ───────────────────────────────────────────────────────────

it('retries a server error three times then gives up', function () {
    Http::fake(['graph.facebook.com/*' => Http::response([], 500)]);

    expect(app(MetaCapiService::class)->send('Purchase', 'evt-500', []))->toBeFalse();

    Http::assertSentCount(3);
});

it('does not retry a rejected payload', function () {
    // A 4xx is our bug — retrying burns requests and cannot succeed.
    Http::fake([
        'graph.facebook.com/*' => Http::response(['error' => ['message' => 'Invalid parameter']], 400),
    ]);

    expect(app(MetaCapiService::class)->send('Purchase', 'evt-400', []))->toBeFalse();

    Http::assertSentCount(1);
});

it('retries a rate limit response', function () {
    Http::fake(['graph.facebook.com/*' => Http::response([], 429)]);

    app(MetaCapiService::class)->send('Purchase', 'evt-429', []);

    Http::assertSentCount(3);
});

it('succeeds on a retry after a transient failure', function () {
    Http::fakeSequence('graph.facebook.com/*')
        ->push([], 500)
        ->push(['events_received' => 1], 200);

    expect(app(MetaCapiService::class)->send('Purchase', 'evt-flaky', []))->toBeTrue();

    Http::assertSentCount(2);
});

// ─── Disabled ───────────────────────────────────────────────────────────────

it('sends nothing while meta is disabled', function () {
    TrackingSetting::query()->update(['meta_enabled' => false]);
    app(TrackingSettingsService::class)->flush();

    Http::fake();

    expect(app(MetaCapiService::class)->send('Purchase', 'evt-off', []))->toBeFalse();

    Http::assertNothingSent();
});

it('sends nothing without an access token', function () {
    TrackingSetting::query()->update(['meta_access_token' => null]);
    app(TrackingSettingsService::class)->flush();

    Http::fake();

    expect(app(MetaCapiService::class)->send('Purchase', 'evt-notoken', []))->toBeFalse();

    Http::assertNothingSent();
});
