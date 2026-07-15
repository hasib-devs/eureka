<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use Illuminate\Http\Request;

/**
 * Per-request signals Meta wants sent UNHASHED: the real client IP, the verbatim
 * user agent, the fbp/fbc browser cookies, and the canonical event source URL.
 *
 * These carry a lot of the Event Match Quality weight, and unlike PII they must
 * not be hashed or normalised — Meta matches them as-is.
 */
class TrackingContext
{
    public function __construct(private TrackingSettingsService $settings) {}

    /**
     * The client's real IP. The app sits behind a proxy in production, so
     * REMOTE_ADDR is the proxy — X-Forwarded-For's first entry is the client.
     *
     * Only consulted for the forwarded headers Laravel already trusts via its
     * TrustProxies configuration; a spoofed header is no worse than a spoofed
     * IP for matching purposes, and Meta simply fails to match on a bad one.
     */
    public function clientIp(Request $request): ?string
    {
        $forwarded = $request->headers->get('X-Forwarded-For');

        if (filled($forwarded)) {
            // "client, proxy1, proxy2" — the left-most entry is the origin.
            $first = trim(explode(',', $forwarded)[0]);

            if ($this->validIp($first)) {
                return $first;
            }
        }

        foreach (['CF-Connecting-IP', 'True-Client-IP', 'X-Real-IP'] as $header) {
            $value = $request->headers->get($header);

            if (filled($value) && $this->validIp(trim($value))) {
                return trim($value);
            }
        }

        $ip = $request->ip();

        return $this->validIp((string) $ip) ? $ip : null;
    }

    /** Verbatim — Meta matches on the exact string the browser sent. */
    public function userAgent(Request $request): ?string
    {
        $ua = $request->userAgent();

        return filled($ua) ? $ua : null;
    }

    /** The _fbp browser cookie, set by the pixel. */
    public function fbp(Request $request): ?string
    {
        $fbp = $request->cookie('_fbp');

        return filled($fbp) ? (string) $fbp : null;
    }

    /**
     * The _fbc click identifier. Prefer the cookie; if the visitor arrived on
     * this very request with an fbclid and the pixel has not cookied it yet,
     * build it per Meta's format so the first event still carries click
     * attribution instead of losing it.
     *
     * Format: fb.{subdomainIndex}.{creationTimeMs}.{fbclid}
     */
    public function fbc(Request $request): ?string
    {
        $cookie = $request->cookie('_fbc');

        if (filled($cookie)) {
            return (string) $cookie;
        }

        $fbclid = $request->query('fbclid');

        if (blank($fbclid) || ! is_string($fbclid)) {
            return null;
        }

        return sprintf(
            'fb.%d.%d.%s',
            $this->subdomainIndex($request->getHost()),
            (int) (microtime(true) * 1000),
            $fbclid
        );
    }

    /**
     * Canonical URL for this event, always on the configured site_url host —
     * never the raw request host. That is what keeps event_source_url correct
     * through a domain migration and consistent across environments.
     */
    public function eventSourceUrl(Request $request): string
    {
        return $this->settings->canonical($request->path() === '/' ? '' : $request->path());
    }

    /**
     * The cookie domain to write with, derived from the request host — never a
     * hardcoded string, so a domain migration needs no code change.
     *
     * Returns null for a bare host or an IP/localhost, which tells the browser
     * to scope the cookie to the exact host (the correct behaviour there).
     */
    public function cookieDomain(Request $request): ?string
    {
        $host = $request->getHost();

        if (filter_var($host, FILTER_VALIDATE_IP) || ! str_contains($host, '.')) {
            return null;
        }

        return $host;
    }

    /**
     * Meta's subdomainIndex: how many dot-separated parts precede the
     * registrable domain. "example.com" -> 1, "www.example.com" -> 2.
     */
    private function subdomainIndex(string $host): int
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return 1;
        }

        $parts = substr_count($host, '.') + 1;

        // A bare registrable domain (example.com) is 1; each extra label adds one.
        return max(1, $parts - 1);
    }

    private function validIp(string $ip): bool
    {
        return (bool) filter_var($ip, FILTER_VALIDATE_IP);
    }
}
