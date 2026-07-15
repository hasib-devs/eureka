<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TrackingSetting;
use App\Models\TrackingSettingAudit;
use App\Services\Tracking\Ga4MeasurementProtocolService;
use App\Services\Tracking\MetaCapiService;
use App\Services\Tracking\TrackingCrypt;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Admin "Tracking & Integrations" panel.
 *
 * Access is restricted by the existing `admin` middleware (role_id == 1) on the
 * route group — see routes/admin.php. Vendors and customers cannot reach it.
 *
 * Secrets are write-only: the form never renders a stored token back, and a
 * blank secret field means "leave unchanged" rather than "clear".
 */
class TrackingController extends Controller
{
    public function __construct(private TrackingSettingsService $settings) {}

    public function index(Request $request): View
    {
        $tracking = $this->settings->currentForEdit();

        return view('admin.e-commerce.setting.trackingIndex', [
            'tracking' => $tracking,
            // Only whether a secret exists — never the value.
            'hasMetaToken' => filled($tracking->meta_access_token),
            'hasGa4Secret' => filled($tracking->ga4_api_secret),
            'usingDedicatedKey' => TrackingCrypt::usingDedicatedKey(),
            'hostMismatch' => $this->settings->hostMismatch($request->getHost()),
            'requestHost' => $request->getHost(),
            'legacyPixelId' => $this->legacyPixelId(),
            'audits' => TrackingSettingAudit::with('user')->latest()->limit(50)->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meta_pixel_id' => ['nullable', 'string', 'regex:/^\d{6,20}$/'],
            'meta_access_token' => ['nullable', 'string', 'max:1000'],
            'meta_test_event_code' => ['nullable', 'string', 'max:50'],
            'meta_api_version' => ['nullable', 'string', 'regex:/^v\d+\.\d+$/'],
            'meta_enabled' => ['nullable', 'boolean'],

            'gtm_container_id' => ['nullable', 'string', 'regex:/^GTM-[A-Z0-9]{4,}$/i'],
            'gtm_enabled' => ['nullable', 'boolean'],

            'ga4_measurement_id' => ['nullable', 'string', 'regex:/^G-[A-Z0-9]{4,}$/i'],
            'ga4_api_secret' => ['nullable', 'string', 'max:255'],
            'ga4_enabled' => ['nullable', 'boolean'],

            'gsc_verification_code' => ['nullable', 'string', 'max:255'],
            'gsc_enabled' => ['nullable', 'boolean'],

            'consent_mode_enabled' => ['nullable', 'boolean'],
            'consent_banner_enabled' => ['nullable', 'boolean'],
            'consent_default_row' => ['nullable', 'array'],
            'consent_default_eu' => ['nullable', 'array'],
            'consent_default_row.*' => ['in:granted,denied'],
            'consent_default_eu.*' => ['in:granted,denied'],

            'site_url' => ['nullable', 'url', 'max:255'],
        ], [
            'meta_pixel_id.regex' => 'The Pixel ID should be the numeric ID from Events Manager.',
            'gtm_container_id.regex' => 'The GTM container ID should look like GTM-XXXXXXX.',
            'ga4_measurement_id.regex' => 'The GA4 measurement ID should look like G-XXXXXXXXXX.',
            'meta_api_version.regex' => 'The Graph API version should look like v25.0.',
        ]);

        // Unchecked checkboxes are absent from the payload entirely, which would
        // read as "unchanged" and make a toggle impossible to turn off.
        foreach ([
            'meta_enabled', 'gtm_enabled', 'ga4_enabled', 'gsc_enabled',
            'consent_mode_enabled', 'consent_banner_enabled',
        ] as $toggle) {
            $validated[$toggle] = $request->boolean($toggle);
        }

        foreach (['consent_default_row', 'consent_default_eu'] as $group) {
            $validated[$group] = $this->consentGroup($request, $group);
        }

        // A blank secret means "unchanged", so clearing one needs an explicit
        // request — otherwise a leaked token could never be revoked here.
        foreach (TrackingSetting::SECRET_FIELDS as $secret) {
            if ($request->boolean('clear_'.$secret)) {
                $validated[$secret] = TrackingSettingsService::CLEAR_SENTINEL;
            }
        }

        if (filled($validated['site_url'] ?? null)) {
            $validated['site_url'] = rtrim($validated['site_url'], '/');
        }

        $this->settings->update($validated, Auth::user());

        return redirect()
            ->route('admin.setting.tracking')
            ->with('success', 'Tracking settings updated.');
    }

    /**
     * Test Connection, per integration. Returns JSON so the panel can report
     * each one independently without a page reload.
     */
    public function test(Request $request, string $integration): JsonResponse
    {
        // Test against what the admin is currently looking at, not a stale cache.
        $this->settings->flush();

        $result = match ($integration) {
            'meta' => app(MetaCapiService::class)->testConnection(),
            'ga4' => app(Ga4MeasurementProtocolService::class)->testConnection(),
            'gtm' => $this->testGtm(),
            'gsc' => $this->testGsc(),
            default => ['ok' => false, 'message' => 'Unknown integration.'],
        };

        return response()->json($result);
    }

    /**
     * Copy the Pixel ID out of the legacy fb_pixel snippet and clear it, in one
     * step, so enabling the new pixel cannot double-count PageView.
     *
     * Deliberately admin-triggered rather than automatic: this edits a field an
     * admin pasted by hand, and silently rewriting it on deploy would be a
     * surprise. Nothing here runs unless the button is pressed.
     */
    public function importLegacyPixel(Request $request): RedirectResponse
    {
        $pixelId = $this->legacyPixelId();

        if (blank($pixelId)) {
            return back()->with('error', 'No Pixel ID could be found in the legacy Facebook Pixel Code field.');
        }

        $this->settings->update(['meta_pixel_id' => $pixelId], Auth::user());

        Setting::updateOrCreate(['name' => 'fb_pixel'], ['value' => '']);

        return back()->with('success', "Imported Pixel ID {$pixelId} and cleared the legacy snippet. Enable Meta below when you're ready.");
    }

    /**
     * The Pixel ID inside the legacy raw snippet, if there is one. Used to warn
     * about the double-count conflict before it happens.
     */
    private function legacyPixelId(): ?string
    {
        $snippet = (string) setting('fb_pixel');

        if (blank($snippet)) {
            return null;
        }

        if (preg_match("/fbq\s*\(\s*['\"]init['\"]\s*,\s*['\"](\d{6,20})['\"]/i", $snippet, $m)) {
            return $m[1];
        }

        // The <noscript> fallback image carries the id too.
        if (preg_match('/facebook\.com\/tr\?id=(\d{6,20})/i', $snippet, $m)) {
            return $m[1];
        }

        return null;
    }

    /** @return array{ok: bool, message: string} */
    private function testGtm(): array
    {
        $id = $this->settings->current()->gtm_container_id;

        if (blank($id)) {
            return ['ok' => false, 'message' => 'Container ID is required.'];
        }

        if (! preg_match('/^GTM-[A-Z0-9]{4,}$/i', $id)) {
            return ['ok' => false, 'message' => 'Container ID must look like GTM-XXXXXXX.'];
        }

        return ['ok' => true, 'message' => 'Container ID format is valid. Confirm it is publishing in GTM Preview.'];
    }

    /**
     * Confirms the verification tag actually renders on the live site — which
     * is what Google checks — rather than just that a code was saved.
     *
     * @return array{ok: bool, message: string}
     */
    private function testGsc(): array
    {
        $code = $this->settings->current()->gsc_verification_code;

        if (blank($code)) {
            return ['ok' => false, 'message' => 'Verification code is required.'];
        }

        if (! $this->settings->gscEnabled()) {
            return ['ok' => false, 'message' => 'Saved, but Search Console is disabled — the tag will not render until you enable it.'];
        }

        try {
            $response = Http::timeout(10)->get($this->settings->siteUrl());

            if (! $response->successful()) {
                return ['ok' => false, 'message' => 'Could not load '.$this->settings->siteUrl().' (HTTP '.$response->status().').'];
            }

            return str_contains($response->body(), $code)
                ? ['ok' => true, 'message' => 'Verification tag is live on '.$this->settings->siteUrl().'.']
                : ['ok' => false, 'message' => 'The tag was not found on '.$this->settings->siteUrl().'. If you just saved, try again — the page may be cached.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Could not reach the site: '.$e->getMessage()];
        }
    }

    /**
     * @return array<string, string>
     */
    private function consentGroup(Request $request, string $group): array
    {
        $submitted = $request->input($group, []);
        $defaults = $group === 'consent_default_eu'
            ? TrackingSetting::defaultEuConsent()
            : TrackingSetting::defaultRowConsent();

        $result = [];

        foreach (array_keys($defaults) as $signal) {
            $value = $submitted[$signal] ?? null;
            $result[$signal] = in_array($value, ['granted', 'denied'], true) ? $value : $defaults[$signal];
        }

        return $result;
    }
}
