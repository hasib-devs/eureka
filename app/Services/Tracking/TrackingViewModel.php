<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Assembles everything the tracking head partial renders, so the Blade file
 * stays declarative and this logic stays testable.
 *
 * Hard rule: nothing returned from here may contain a secret. Values are read
 * through TrackingSettingsService::publicConfig(), which never includes
 * meta_access_token or ga4_api_secret — they are not masked here, they are
 * never fetched.
 */
class TrackingViewModel
{
    public function __construct(
        private TrackingSettingsService $settings,
        private ConsentResolver $consent,
        private TrackingContext $context,
        private TrackingEvents $events,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function forRequest(Request $request): array
    {
        // A tracking outage must never take the storefront down.
        if ($this->settings->degraded()) {
            return $this->nothing();
        }

        $consentDefaults = $this->consent->defaultsFor($request);
        $showBanner = $this->consent->shouldShowBanner($request);

        $public = $this->settings->publicConfig($consentDefaults, $showBanner);

        $pixelId = $public['pixelId'];
        $gtmId = $public['gtmId'];
        $ga4Id = $public['ga4Id'];
        $gscCode = $this->settings->gscEnabled()
            ? $this->settings->current()->gsc_verification_code
            : null;

        // Same predicate the queue producer uses, so the two cannot disagree.
        $any = $this->settings->anyBrowserTagEnabled();

        return [
            'any' => $any,
            'pixelId' => $pixelId,
            'gtmId' => $gtmId,
            'ga4Id' => $ga4Id,
            'gscCode' => $gscCode,
            'consentMode' => $public['consentMode'],
            'consentDefaults' => $consentDefaults,
            'cookieDomain' => $this->context->cookieDomain($request),
            'advancedMatching' => $pixelId ? $this->advancedMatching() : [],
            'queued' => $any ? $this->events->drainBrowserEvents($request) : [],
            'js' => [
                'pixelId' => $pixelId,
                'ga4Id' => $ga4Id,
                'gtmId' => $gtmId,
                'currency' => $this->events->currency(),
                'cookieDomain' => $this->context->cookieDomain($request),
                'consentMode' => $public['consentMode'],
                'consentDefaults' => $consentDefaults,
                'consentBanner' => $showBanner,
                'consentCookie' => ConsentResolver::COOKIE,
            ],
        ];
    }

    /**
     * Meta Advanced Matching for a signed-in user.
     *
     * Hashed here, server-side, and injected already-hashed. The browser never
     * receives the raw email or phone, and because both legs use the one
     * PiiHasher the client and server hashes cannot drift — which is the whole
     * point, since a mismatched hash matches nobody and reports no error.
     *
     * @return array<string, string>
     */
    private function advancedMatching(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        return array_filter([
            'em' => PiiHasher::email($user->email ?? null),
            'ph' => PiiHasher::phone($user->phone ?? null),
            'external_id' => PiiHasher::externalId($user->id),
        ], fn ($v) => $v !== null);
    }

    /** @return array<string, mixed> */
    private function nothing(): array
    {
        return [
            'any' => false,
            'pixelId' => null,
            'gtmId' => null,
            'ga4Id' => null,
            'gscCode' => null,
            'consentMode' => false,
            'consentDefaults' => [],
            'cookieDomain' => null,
            'advancedMatching' => [],
            'queued' => [],
            'js' => [],
        ];
    }
}
