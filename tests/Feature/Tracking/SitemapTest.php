<?php

declare(strict_types=1);

use App\Models\TrackingSetting;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
    app(TrackingSettingsService::class)->flush();
});

it('serves robots.txt from a route', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertHeader('content-type', 'text/plain; charset=UTF-8')
        // The previous static file allowed everything; that must not change.
        ->assertSee('User-agent: *');
});

it('points robots.txt at the sitemap on the canonical domain', function () {
    TrackingSetting::create(['site_url' => 'https://shop.example']);
    app(TrackingSettingsService::class)->flush();

    $this->get('/robots.txt')->assertSee('Sitemap: https://shop.example/sitemap.xml');
});

it('follows site_url when the domain changes, with no code edit', function () {
    // The whole point of Step 2: a migration is one field, not a deploy.
    TrackingSetting::create(['site_url' => 'https://old-domain.example']);
    app(TrackingSettingsService::class)->flush();

    $this->get('/robots.txt')->assertSee('Sitemap: https://old-domain.example/sitemap.xml');

    TrackingSetting::query()->update(['site_url' => 'https://new-domain.example']);
    app(TrackingSettingsService::class)->flush();

    $this->get('/robots.txt')
        ->assertSee('Sitemap: https://new-domain.example/sitemap.xml')
        ->assertDontSee('old-domain.example');
});

it('keeps admin out of the index but leaves the vendor storefront indexable', function () {
    // vendor/{slug} is a public catalogue page (routes/web.php), so the /vendor
    // prefix must not be blocked — the vendor dashboard shares it but sits
    // behind auth, where a crawler only ever sees a redirect.
    $this->get('/robots.txt')
        ->assertSee('Disallow: /admin')
        ->assertDontSee('Disallow: /vendor');
});

it('serves a valid sitemap.xml', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk()->assertHeader('content-type', 'application/xml; charset=UTF-8');

    $xml = simplexml_load_string($response->getContent());

    expect($xml)->not->toBeFalse()
        ->and($xml->getName())->toBe('urlset');
});

it('builds sitemap urls from site_url rather than the request host', function () {
    TrackingSetting::create(['site_url' => 'https://shop.example']);
    app(TrackingSettingsService::class)->flush();

    $this->get('/sitemap.xml')
        ->assertSee('<loc>https://shop.example/</loc>', false)
        ->assertDontSee('localhost');
});

it('lists published products in the sitemap', function () {
    $product = makeProduct();
    $product->update(['status' => true]);

    $this->get('/sitemap.xml')->assertSee('product/'.$product->slug, false);
});

it('leaves unpublished products out of the sitemap', function () {
    $product = makeProduct();
    $product->update(['status' => false]);

    $this->get('/sitemap.xml')->assertDontSee('product/'.$product->slug, false);
});

it('falls back to APP_URL when site_url is unset', function () {
    config()->set('app.url', 'https://fallback.example');

    $this->get('/robots.txt')->assertSee('Sitemap: https://fallback.example/sitemap.xml');
});
