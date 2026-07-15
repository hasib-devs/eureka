<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use Illuminate\Encryption\Encrypter;
use RuntimeException;

/**
 * Encrypts tracking secrets at rest using a dedicated SETTINGS_ENCRYPTION_KEY
 * when one is configured, falling back to APP_KEY when it is not.
 *
 * The fallback is deliberate. Deploys here are `git push` -> server
 * `git reset --hard origin/main`, and .env is not in the repo, so requiring a
 * new env var would mean the panel silently fails until someone SSHes in. With
 * the fallback the feature works on day one and the dedicated key can be added
 * whenever convenient; the admin panel reports which mode is active.
 */
class TrackingCrypt
{
    private static ?Encrypter $encrypter = null;

    /** True when a dedicated settings key is configured (rather than APP_KEY). */
    public static function usingDedicatedKey(): bool
    {
        return filled(config('tracking.encryption_key'));
    }

    public static function encrypt(string $value): string
    {
        return self::encrypter()->encryptString($value);
    }

    /**
     * Returns null when the payload cannot be decrypted — e.g. the key was
     * rotated. A stale secret must not take the whole page down; callers treat
     * null as "not configured" and the panel prompts for a re-paste.
     */
    public static function decrypt(string $value): ?string
    {
        try {
            return self::encrypter()->decryptString($value);
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    public static function flush(): void
    {
        self::$encrypter = null;
    }

    private static function encrypter(): Encrypter
    {
        if (self::$encrypter instanceof Encrypter) {
            return self::$encrypter;
        }

        $cipher = config('app.cipher', 'AES-256-CBC');
        $key = self::resolveKey();

        return self::$encrypter = new Encrypter($key, $cipher);
    }

    private static function resolveKey(): string
    {
        $key = config('tracking.encryption_key') ?: config('app.key');

        if (blank($key)) {
            throw new RuntimeException(
                'No encryption key available: set SETTINGS_ENCRYPTION_KEY or APP_KEY.'
            );
        }

        if (str_starts_with($key, 'base64:')) {
            $decoded = base64_decode(substr($key, 7), true);

            if ($decoded === false) {
                throw new RuntimeException('Tracking encryption key is not valid base64.');
            }

            return $decoded;
        }

        return $key;
    }
}
