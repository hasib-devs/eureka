<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Settings encryption key
    |--------------------------------------------------------------------------
    |
    | Encrypts the tracking secrets (meta_access_token, ga4_api_secret) at rest,
    | separately from the rest of the app. Optional: when unset we fall back to
    | APP_KEY so a fresh deploy works with no server access, and the admin panel
    | surfaces a notice recommending a dedicated key.
    |
    | Generate one with:  php artisan tracking:key
    |
    | Rotating it invalidates stored secrets — re-paste them in the admin panel.
    |
    */

    'encryption_key' => env('SETTINGS_ENCRYPTION_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default phone country code
    |--------------------------------------------------------------------------
    |
    | Meta requires phone numbers to carry a country code before hashing, and a
    | national-format number ("01712345678") carries none. This store's numbers
    | are Bangladeshi, so 880 is assumed for national-format input. Numbers
    | already in international form are left alone.
    |
    */

    'default_phone_country_code' => env('TRACKING_PHONE_COUNTRY_CODE', '880'),

    /*
    |--------------------------------------------------------------------------
    | Retry backoff base (milliseconds)
    |--------------------------------------------------------------------------
    |
    | Failed Meta CAPI / GA4 sends retry with exponential backoff: base, base*2,
    | base*4. Set to 0 in tests so the suite does not actually sleep.
    |
    */

    'retry_backoff_ms' => (int) env('TRACKING_RETRY_BACKOFF_MS', 1000),

    /*
    |--------------------------------------------------------------------------
    | Settings cache TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | Short by design: an admin update invalidates the cache immediately, so
    | this TTL only bounds staleness if an invalidation is ever missed (e.g. a
    | row edited straight in the database).
    |
    */

    'cache_ttl' => 60,

    /*
    |--------------------------------------------------------------------------
    | Regions whose visitors default to denied consent
    |--------------------------------------------------------------------------
    |
    | EU/EEA + UK. Visitors from anywhere else (notably Bangladesh, this store's
    | market) default to granted. An unresolvable country is treated as denied.
    |
    */

    'consent_denied_regions' => [
        // EU
        'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR',
        'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK',
        'SI', 'ES', 'SE',
        // EEA (non-EU)
        'IS', 'LI', 'NO',
        // UK + Switzerland (FADP mirrors GDPR closely enough to treat alike)
        'GB', 'CH',
    ],

];
