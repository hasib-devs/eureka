<?php

declare(strict_types=1);

use App\Models\Setting;
use App\Models\TrackingSetting;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/*
| The "merging this must change nothing" guarantee.
|
| Everything ships disabled, and the legacy setting('fb_pixel') snippet has to
| keep rendering exactly as before until an admin deliberately switches over.
| These tests are what make that claim checkable rather than a promise.
*/

beforeEach(function () {
    Cache::flush();
    app(TrackingSettingsService::class)->flush();
});

it('renders no tracking tags when nothing is configured', function () {
    $html = $this->get('/')->getContent();

    expect($html)->not->toContain('connect.facebook.net')
        ->and($html)->not->toContain('googletagmanager.com')
        ->and($html)->not->toContain('google-site-verification');
});

it('keeps rendering the legacy fb_pixel snippet untouched', function () {
    Setting::updateOrCreate(['name' => 'fb_pixel'], ['value' => '<!--legacy-pixel-marker-->']);
    Cache::forget('settings');

    expect($this->get('/')->getContent())->toContain('<!--legacy-pixel-marker-->');
});

it('does not render the pixel while meta is disabled even with an id present', function () {
    TrackingSetting::create(['meta_pixel_id' => '1234567890', 'meta_enabled' => false]);

    expect($this->get('/')->getContent())->not->toContain('connect.facebook.net');
});

it('renders the pixel once enabled', function () {
    TrackingSetting::create(['meta_pixel_id' => '1234567890', 'meta_enabled' => true]);

    $html = $this->get('/')->getContent();

    expect($html)->toContain('connect.facebook.net')
        ->and($html)->toContain('1234567890');
});

it('does not render the pixel when enabled but the id is blank', function () {
    // A half-filled form must never emit a broken tag.
    TrackingSetting::create(['meta_pixel_id' => null, 'meta_enabled' => true]);

    expect($this->get('/')->getContent())->not->toContain('connect.facebook.net');
});

it('renders the GTM container when enabled', function () {
    TrackingSetting::create(['gtm_container_id' => 'GTM-ABC1234', 'gtm_enabled' => true]);

    expect($this->get('/')->getContent())->toContain('GTM-ABC1234');
});

it('renders the search console tag from the configured code', function () {
    TrackingSetting::create(['gsc_verification_code' => 'test-verification-code', 'gsc_enabled' => true]);

    $html = $this->get('/')->getContent();

    expect($html)->toContain('google-site-verification')
        ->and($html)->toContain('test-verification-code');
});

it('sets consent mode defaults before any tag loads', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_enabled' => true,
        'consent_mode_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);

    $html = $this->get('/')->getContent();

    $consentPos = strpos($html, "gtag('consent', 'default'");
    $pixelPos = strpos($html, 'connect.facebook.net');

    expect($consentPos)->not->toBeFalse()
        ->and($pixelPos)->not->toBeFalse()
        // Load order is the whole point: consent must be set first.
        ->and($consentPos)->toBeLessThan($pixelPos);
});

it('grants consent by default for a non-EU visitor', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);

    $html = $this->withHeader('CF-IPCountry', 'BD')->get('/')->getContent();

    expect($html)->toContain('"ad_storage":"granted"')
        ->and($html)->toContain('"analytics_storage":"granted"');
});

it('denies consent by default for an EU visitor', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);

    $html = $this->withHeader('CF-IPCountry', 'DE')->get('/')->getContent();

    expect($html)->toContain('"ad_storage":"denied"')
        ->and($html)->toContain('"analytics_storage":"denied"');
});

it('denies consent when the country cannot be resolved', function () {
    // Fail safe, not fail open.
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_enabled' => true,
        'consent_default_row' => TrackingSetting::defaultRowConsent(),
        'consent_default_eu' => TrackingSetting::defaultEuConsent(),
    ]);

    expect($this->get('/')->getContent())->toContain('"ad_storage":"denied"');
});

it('never leaks secrets into the rendered page', function () {
    TrackingSetting::create([
        'meta_pixel_id' => '1234567890',
        'meta_access_token' => 'SECRET-META-TOKEN-VALUE',
        'meta_enabled' => true,
        'ga4_measurement_id' => 'G-TEST12345',
        'ga4_api_secret' => 'SECRET-GA4-API-SECRET',
        'ga4_enabled' => true,
    ]);

    $html = $this->get('/')->getContent();

    expect($html)->not->toContain('SECRET-META-TOKEN-VALUE')
        ->and($html)->not->toContain('SECRET-GA4-API-SECRET');
});

it('still renders the page when the settings table is unreadable', function () {
    // A tracking outage must not take the storefront down.
    Schema::drop('tracking_settings');

    $this->get('/')->assertOk();
});
