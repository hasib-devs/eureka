<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use App\Models\TrackingSetting;
use App\Models\TrackingSettingAudit;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * The one way to read tracking configuration.
 *
 * Reads come from the database (env is only a local-dev fallback for site_url),
 * are cached for a short TTL, and the cache is dropped the moment an admin
 * saves. If the database or cache is unreachable the site must still serve
 * pages, so every read degrades to "everything disabled" plus a logged warning
 * rather than throwing.
 */
class TrackingSettingsService
{
    public const CACHE_KEY = 'tracking.settings';

    private static ?TrackingSetting $memo = null;

    private static bool $degraded = false;

    /**
     * The settings row, or a disabled in-memory stand-in when the row cannot be
     * read. Never returns null — callers should not have to null-check.
     */
    public function current(): TrackingSetting
    {
        if (self::$memo instanceof TrackingSetting) {
            return self::$memo;
        }

        try {
            $attributes = Cache::remember(
                self::CACHE_KEY,
                (int) config('tracking.cache_ttl', 60),
                fn () => TrackingSetting::query()->first()?->getRawOriginal() ?? []
            );

            self::$degraded = false;

            // Rehydrated from raw columns so the casts (and decryption) apply
            // exactly as they would on a fresh read.
            $row = new TrackingSetting;
            $row->setRawAttributes($attributes, true);
            $row->exists = $attributes !== [];

            return self::$memo = $row;
        } catch (\Throwable $e) {
            // A tracking outage must never take the storefront down.
            Log::warning('Tracking settings unavailable; tracking disabled for this request.', [
                'exception' => $e->getMessage(),
            ]);

            self::$degraded = true;

            return self::$memo = new TrackingSetting;
        }
    }

    /** True when the last read fell back to the disabled stand-in. */
    public function degraded(): bool
    {
        $this->current();

        return self::$degraded;
    }

    /** The editable row, created on first use. */
    public function currentForEdit(): TrackingSetting
    {
        if ($row = TrackingSetting::query()->first()) {
            return $row;
        }

        $row = TrackingSetting::query()->create([
            'consent_default_row' => TrackingSetting::defaultRowConsent(),
            'consent_default_eu' => TrackingSetting::defaultEuConsent(),
            'site_url' => rtrim((string) config('app.url'), '/'),
        ]);

        // Reload so the columns filled by database defaults (the enabled flags)
        // are populated in memory. Without this a fresh row reports null for
        // them, and update() would diff against the wrong "old" value.
        return $row->refresh();
    }

    /**
     * Canonical site URL — the single source of truth for event_source_url,
     * GA4 document_location, canonical tags, og:url, sitemap and robots.
     * Falls back to APP_URL so a fresh install is never blank.
     */
    public function siteUrl(): string
    {
        $url = $this->current()->site_url ?: config('app.url');

        return rtrim((string) $url, '/');
    }

    /** Absolute URL on the canonical domain for a request path. */
    public function canonical(string $path = ''): string
    {
        return $this->siteUrl().'/'.ltrim($path, '/');
    }

    /**
     * The host configured in site_url differs from the host actually serving
     * this request — i.e. a domain migration where site_url was not updated.
     * Surfaced in the admin panel so it is caught immediately rather than
     * silently mis-attributing every event.
     */
    public function hostMismatch(?string $requestHost): bool
    {
        if (blank($requestHost) || blank($this->current()->site_url)) {
            return false;
        }

        $configured = parse_url($this->siteUrl(), PHP_URL_HOST);

        return filled($configured) && strcasecmp((string) $configured, $requestHost) !== 0;
    }

    // ─── Per-integration enablement ─────────────────────────────────────────
    //
    // Each requires both its toggle AND the values it needs, so a half-filled
    // form can never emit a broken tag.

    public function metaPixelEnabled(): bool
    {
        $s = $this->current();

        return $s->meta_enabled && filled($s->meta_pixel_id);
    }

    public function metaCapiEnabled(): bool
    {
        $s = $this->current();

        return $s->meta_enabled && filled($s->meta_pixel_id) && filled($s->meta_access_token);
    }

    public function gtmEnabled(): bool
    {
        $s = $this->current();

        return $s->gtm_enabled && filled($s->gtm_container_id);
    }

    public function ga4Enabled(): bool
    {
        $s = $this->current();

        return $s->ga4_enabled && filled($s->ga4_measurement_id);
    }

    public function ga4MpEnabled(): bool
    {
        return $this->ga4Enabled() && filled($this->current()->ga4_api_secret);
    }

    public function gscEnabled(): bool
    {
        $s = $this->current();

        return $s->gsc_enabled && filled($s->gsc_verification_code);
    }

    public function consentModeEnabled(): bool
    {
        return (bool) $this->current()->consent_mode_enabled;
    }

    /**
     * The safe subset the browser is allowed to see. Secrets are structurally
     * absent — not masked, not filtered later, simply never added.
     *
     * @param  array<string, string>  $consentDefaults
     * @return array<string, mixed>
     */
    public function publicConfig(array $consentDefaults, bool $showBanner): array
    {
        $s = $this->current();

        return [
            'pixelId' => $this->metaPixelEnabled() ? $s->meta_pixel_id : null,
            'gtmId' => $this->gtmEnabled() ? $s->gtm_container_id : null,
            'ga4Id' => $this->ga4Enabled() ? $s->ga4_measurement_id : null,
            'siteUrl' => $this->siteUrl(),
            'consentMode' => $this->consentModeEnabled(),
            'consentDefaults' => $consentDefaults,
            'consentBanner' => $showBanner,
        ];
    }

    /**
     * Apply an admin's changes, writing one audit row per changed field.
     *
     * A blank secret means "leave unchanged" — the form never re-renders an
     * existing secret, so a blank submit must not wipe it. Secret values are
     * masked in the audit trail: who and when are recorded, the value is not.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(array $input, ?User $admin): TrackingSetting
    {
        $row = $this->currentForEdit();

        foreach ($input as $field => $value) {
            $isSecret = in_array($field, TrackingSetting::SECRET_FIELDS, true);

            // Blank secret submitted -> keep what is stored.
            if ($isSecret && blank($value)) {
                continue;
            }

            $old = $row->{$field};

            if ($this->unchanged($old, $value)) {
                continue;
            }

            $row->{$field} = $value;

            TrackingSettingAudit::create([
                'user_id' => $admin?->id,
                'user_name' => $admin?->name,
                'field' => $field,
                'old_value' => $this->auditValue($old, $isSecret),
                'new_value' => $this->auditValue($value, $isSecret),
            ]);
        }

        $row->save();

        $this->flush();

        return $row;
    }

    /** Drop caches so the next read is authoritative. */
    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        self::$memo = null;
        self::$degraded = false;
    }

    private function unchanged(mixed $old, mixed $new): bool
    {
        return $this->comparable($old) === $this->comparable($new);
    }

    /**
     * A comparable representation of a field value.
     *
     * Booleans are tagged rather than cast to string: PHP renders false as ''
     * and true as '1', which would make false indistinguishable from null or an
     * empty string — and a toggle being switched off would silently look like
     * "no change" and never save.
     */
    private function comparable(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value) ?: '';
        }

        // Tagged, so a boolean can never collide with a string field that
        // happens to hold "1" (a test event code, say).
        if (is_bool($value)) {
            return $value ? 'bool:1' : 'bool:0';
        }

        return $value === null ? '' : (string) $value;
    }

    private function auditValue(mixed $value, bool $isSecret): ?string
    {
        if ($isSecret) {
            return blank($value) ? null : TrackingSettingAudit::MASK;
        }

        if (is_array($value)) {
            return json_encode($value) ?: null;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return blank($value) ? null : (string) $value;
    }
}
