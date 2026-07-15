<?php

declare(strict_types=1);

namespace App\Casts;

use App\Services\Tracking\TrackingCrypt;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Transparently encrypts a secret column with the tracking settings key.
 *
 * Laravel's built-in `encrypted` cast is hardwired to APP_KEY; this one honours
 * SETTINGS_ENCRYPTION_KEY when present. See TrackingCrypt.
 *
 * @implements CastsAttributes<string|null, string|null>
 */
class TrackingSecret implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (blank($value)) {
            return null;
        }

        return TrackingCrypt::decrypt((string) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (blank($value)) {
            return null;
        }

        return TrackingCrypt::encrypt((string) $value);
    }
}
