<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\TrackingSecret;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The single row of runtime tracking configuration.
 *
 * Read through TrackingSettingsService rather than directly — it adds caching
 * and graceful degradation. Secrets are decrypted on access, so never pass this
 * model (or its toArray()) to a view or JSON response; use
 * TrackingSettingsService::publicConfig() for anything client-facing.
 */
class TrackingSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /** Fields that must never be echoed back to a client or written to an audit row. */
    public const SECRET_FIELDS = ['meta_access_token', 'ga4_api_secret'];

    protected function casts(): array
    {
        return [
            'meta_access_token' => TrackingSecret::class,
            'ga4_api_secret' => TrackingSecret::class,
            'meta_enabled' => 'boolean',
            'gtm_enabled' => 'boolean',
            'ga4_enabled' => 'boolean',
            'gsc_enabled' => 'boolean',
            'consent_mode_enabled' => 'boolean',
            'consent_banner_enabled' => 'boolean',
            'consent_default_row' => 'array',
            'consent_default_eu' => 'array',
        ];
    }

    /**
     * Consent Mode v2 defaults for visitors outside the denied regions —
     * Bangladesh and the rest of the world. Granted: GDPR does not apply to
     * this store's local customers, and denying by default would cost real
     * conversion data for no compliance benefit.
     *
     * @return array<string, string>
     */
    public static function defaultRowConsent(): array
    {
        return [
            'ad_storage' => 'granted',
            'analytics_storage' => 'granted',
            'ad_user_data' => 'granted',
            'ad_personalization' => 'granted',
        ];
    }

    /**
     * Consent Mode v2 defaults for EU/EEA/UK visitors, and for any visitor
     * whose country cannot be resolved (fail safe, not fail open).
     *
     * @return array<string, string>
     */
    public static function defaultEuConsent(): array
    {
        return [
            'ad_storage' => 'denied',
            'analytics_storage' => 'denied',
            'ad_user_data' => 'denied',
            'ad_personalization' => 'denied',
        ];
    }
}
