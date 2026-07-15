<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use App\Models\TrackingSetting;
use Illuminate\Http\Request;

/**
 * Decides a visitor's Consent Mode v2 default state, and whether they need to
 * be shown a consent banner at all.
 *
 * The store's market is Bangladesh, where GDPR does not apply, so BD and
 * rest-of-world visitors default to granted — denying them by default would
 * cost real conversion data for no compliance benefit, and they never see a
 * banner. EU/EEA/UK visitors default to denied and get the banner.
 *
 * A visitor whose country cannot be determined is treated as EU: fail safe,
 * not fail open.
 */
class ConsentResolver
{
    /** Name of the cookie holding a visitor's recorded choice. */
    public const COOKIE = 'tracking_consent';

    public function __construct(private TrackingSettingsService $settings) {}

    /**
     * The consent state to send as Consent Mode defaults for this request.
     *
     * A visitor who has already chosen has their choice honoured; otherwise the
     * regional default applies.
     *
     * @return array<string, string>
     */
    public function defaultsFor(Request $request): array
    {
        if ($stored = $this->storedChoice($request)) {
            return $stored;
        }

        return $this->regionalDefaults($request);
    }

    /** @return array<string, string> */
    public function regionalDefaults(Request $request): array
    {
        $s = $this->settings->current();

        return $this->deniedRegion($request)
            ? ($s->consent_default_eu ?: TrackingSetting::defaultEuConsent())
            : ($s->consent_default_row ?: TrackingSetting::defaultRowConsent());
    }

    /**
     * Show the banner only to a visitor who both needs to consent (their
     * regional default denies something) and has not already answered. BD
     * customers therefore never see a cookie popup.
     */
    public function shouldShowBanner(Request $request): bool
    {
        $s = $this->settings->current();

        if (! $s->consent_mode_enabled || ! $s->consent_banner_enabled) {
            return false;
        }

        if ($this->storedChoice($request) !== null) {
            return false;
        }

        return in_array('denied', array_values($this->regionalDefaults($request)), true);
    }

    /**
     * True when analytics_storage is granted — the gate for sending GA4
     * Measurement Protocol events for this user.
     */
    public function analyticsGranted(Request $request): bool
    {
        return ($this->defaultsFor($request)['analytics_storage'] ?? 'denied') === 'granted';
    }

    /** True when ad_storage is granted. */
    public function adStorageGranted(Request $request): bool
    {
        return ($this->defaultsFor($request)['ad_storage'] ?? 'denied') === 'granted';
    }

    /**
     * Is this visitor in a region that defaults to denied (EU/EEA/UK)?
     *
     * The repo has no geo-IP library and adding one is a big dependency for a
     * small slice of traffic, so we read the country from the CDN/proxy header
     * when present. When it is absent we cannot tell, and an unknown visitor is
     * treated as EU rather than assumed local.
     */
    public function deniedRegion(Request $request): bool
    {
        $country = $this->country($request);

        if ($country === null) {
            return true; // fail safe
        }

        return in_array($country, config('tracking.consent_denied_regions', []), true);
    }

    /** Two-letter country from a CDN/proxy header, or null when unknown. */
    public function country(Request $request): ?string
    {
        foreach (['CF-IPCountry', 'X-Geo-Country', 'X-AppEngine-Country'] as $header) {
            $value = $request->headers->get($header);

            if (filled($value) && preg_match('/^[A-Za-z]{2}$/', $value)) {
                $upper = strtoupper($value);

                // Cloudflare sends XX for unknown and T1 for Tor.
                if (! in_array($upper, ['XX', 'T1'], true)) {
                    return $upper;
                }
            }
        }

        return null;
    }

    /**
     * A previously recorded choice, or null. Anything malformed is ignored so a
     * tampered cookie cannot widen consent beyond the regional default.
     *
     * @return array<string, string>|null
     */
    public function storedChoice(Request $request): ?array
    {
        $raw = $request->cookie(self::COOKIE);

        if (blank($raw)) {
            return null;
        }

        $decoded = json_decode((string) $raw, true);

        if (! is_array($decoded)) {
            return null;
        }

        $keys = ['ad_storage', 'analytics_storage', 'ad_user_data', 'ad_personalization'];
        $result = [];

        foreach ($keys as $key) {
            $value = $decoded[$key] ?? null;

            if (! in_array($value, ['granted', 'denied'], true)) {
                return null;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
