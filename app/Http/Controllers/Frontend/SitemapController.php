<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

/**
 * robots.txt and sitemap.xml, both built from the canonical site_url.
 *
 * These are served from routes rather than static files in public/ for one
 * reason: a static file cannot follow the site_url setting. After a domain
 * migration a hardcoded sitemap URL keeps pointing at the old host until
 * someone remembers to edit it. Here, changing site_url in the admin panel is
 * the whole migration.
 */
class SitemapController extends Controller
{
    /** How many products to list; a sitemap is capped at 50,000 URLs by spec. */
    private const PRODUCT_LIMIT = 5000;

    public function __construct(private TrackingSettingsService $settings) {}

    public function robots(): Response
    {
        // Preserves the previous static file's behaviour (allow everything) and
        // adds the sitemap reference, which is what search engines look for.
        //
        // Deliberately NOT disallowing /vendor: routes/web.php:88 serves the
        // public vendor storefront at vendor/{slug}, so blocking that prefix
        // would deindex real catalogue pages. The vendor dashboard shares the
        // prefix but sits behind auth, so crawlers only ever see a redirect.
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Allow: /',
            '',
            'Sitemap: '.$this->settings->canonical('sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap(): Response
    {
        $urls = [
            ['loc' => $this->settings->canonical(), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $this->settings->canonical('product'), 'priority' => '0.9', 'changefreq' => 'daily'],
        ];

        foreach ($this->categories() as $category) {
            $urls[] = [
                'loc' => $this->settings->canonical('category/'.$category->slug),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ];
        }

        foreach ($this->products() as $product) {
            $urls[] = [
                'loc' => $this->settings->canonical('product/'.$product->slug),
                'lastmod' => $product->updated_at?->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ];
        }

        return response($this->render($urls), 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /** @return Collection<int, Product> */
    private function products()
    {
        try {
            return Product::query()
                ->where('status', true)
                ->whereNotNull('slug')
                ->latest('id')
                ->limit(self::PRODUCT_LIMIT)
                ->get(['slug', 'updated_at']);
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }

    /** @return Collection<int, Category> */
    private function categories()
    {
        try {
            return Category::query()->whereNotNull('slug')->get(['slug']);
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }

    /**
     * @param  array<int, array<string, string|null>>  $urls
     */
    private function render(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.e($url['loc'])."</loc>\n";

            if (! empty($url['lastmod'])) {
                $xml .= '    <lastmod>'.e($url['lastmod'])."</lastmod>\n";
            }

            $xml .= '    <changefreq>'.e($url['changefreq'])."</changefreq>\n";
            $xml .= '    <priority>'.e($url['priority'])."</priority>\n";
            $xml .= "  </url>\n";
        }

        return $xml.'</urlset>'."\n";
    }
}
