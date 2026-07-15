<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use Illuminate\Support\Carbon;

/**
 * Normalises and SHA-256 hashes customer information for Meta's Conversions
 * API, following Meta's documented rules exactly.
 *
 * Every method here is pinned by tests/Unit/Tracking/PiiHasherTest.php against
 * Meta's own published input -> hash vectors. That matters more than usual: a
 * wrong normalisation still produces a perfectly valid-looking 64-char hash,
 * Meta accepts it, and it simply never matches a user. There is no error to
 * notice — Event Match Quality just quietly drops. The vectors are the only
 * thing standing between "working" and "silently worthless".
 *
 * One rule is easy to get wrong: names keep their accents and non-Latin
 * characters. Meta normalises "Valéry" to "valéry" (not "valry") and "정" to
 * "정". Stripping to [a-z] would produce a valid hash that matches nobody.
 *
 * @see https://developers.facebook.com/docs/marketing-api/conversions-api/parameters/customer-information-parameters
 */
class PiiHasher
{
    /** Already-hashed input passes through untouched, so callers can't double-hash. */
    public static function hash(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (self::isSha256($value)) {
            return strtolower($value);
        }

        return hash('sha256', $value);
    }

    public static function isSha256(string $value): bool
    {
        return (bool) preg_match('/^[a-f0-9]{64}$/i', $value);
    }

    // ─── Normalisation ──────────────────────────────────────────────────────

    /** Trim, lowercase. "John_Smith@gmail.com" -> "john_smith@gmail.com" */
    public static function normalizeEmail(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = mb_strtolower(trim($value));

        // A value that cannot be an email would only ever hash to a non-match.
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null;
    }

    /**
     * Digits only, with a country code and no leading zero after it.
     * "(650)555-1212" + cc "1" -> "16505551212"
     * "01712345678"  + cc "880" -> "8801712345678"   (Bangladesh national form)
     *
     * Without a full E.164 library the country code cannot be inferred, so the
     * store's own code is assumed for national-format numbers — which is what
     * this store actually stores. A number already in international form (with
     * "+", "00", or the country code prefix) is left alone.
     */
    public static function normalizePhone(?string $value, ?string $countryCode = null): ?string
    {
        if (blank($value)) {
            return null;
        }

        $cc = $countryCode ?: (string) config('tracking.default_phone_country_code', '880');
        $international = str_starts_with(trim($value), '+') || str_starts_with(trim($value), '00');

        $digits = preg_replace('/\D+/', '', $value) ?? '';

        if (blank($digits)) {
            return null;
        }

        // "00" international prefix -> drop it; the country code follows.
        if ($international && str_starts_with($digits, '00')) {
            $digits = ltrim(substr($digits, 2), '0');

            return $digits ?: null;
        }

        if ($international) {
            return $digits;
        }

        // National form ("0..."): strip the trunk zero and prepend the country code.
        if (str_starts_with($digits, '0')) {
            return $cc.ltrim($digits, '0');
        }

        // Already carries the country code.
        if (str_starts_with($digits, $cc)) {
            return $digits;
        }

        return $cc.$digits;
    }

    /**
     * Lowercase, punctuation removed, letters preserved in any script.
     * "Mary" -> "mary", "Valéry" -> "valéry", "정" -> "정"
     */
    public static function normalizeName(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = mb_strtolower(trim($value));

        // \p{P} punctuation, \p{S} symbols, \p{Z}+\s whitespace. Letters and
        // combining marks survive — that is what keeps "valéry" intact.
        $value = preg_replace('/[\p{P}\p{S}\p{Z}\s\d]+/u', '', $value) ?? '';

        return $value !== '' ? $value : null;
    }

    /** "m" or "f"; anything else is not a value Meta accepts. */
    public static function normalizeGender(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $g = mb_substr(mb_strtolower(trim($value)), 0, 1);

        return in_array($g, ['m', 'f'], true) ? $g : null;
    }

    /** Any parseable date -> YYYYMMDD. "2/16/1997" -> "19970216" */
    public static function normalizeBirthdate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $raw = trim($value);

        // Already normalised.
        if (preg_match('/^\d{8}$/', $raw)) {
            return $raw;
        }

        try {
            return Carbon::parse($raw)->format('Ymd');
        } catch (\Throwable) {
            return null;
        }
    }

    /** Lowercase, no punctuation, no spaces. "New York" -> "newyork" */
    public static function normalizeCity(?string $value): ?string
    {
        return self::normalizeName($value);
    }

    /**
     * Lowercase, no punctuation, no spaces. US states use the 2-letter ANSI
     * code; everywhere else is just normalised text.
     */
    public static function normalizeState(?string $value): ?string
    {
        return self::normalizeName($value);
    }

    /**
     * Lowercase, no spaces or dashes. US ZIP+4 is cut to the first 5 digits.
     * "94035-1234" -> "94035", "M1 1AE" -> "m11ae"
     */
    public static function normalizeZip(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $zip = mb_strtolower(trim($value));
        $zip = preg_replace('/[\s\-]+/u', '', $zip) ?? '';

        if ($zip === '') {
            return null;
        }

        // A 9-digit all-numeric postcode is US ZIP+4; nothing else uses that shape.
        if (preg_match('/^\d{9}$/', $zip)) {
            $zip = substr($zip, 0, 5);
        }

        return $zip;
    }

    /**
     * ISO 3166-1 alpha-2, lowercase. "United States" -> "us", "Bangladesh" -> "bd"
     * Only the country names this store actually serves are mapped; an unknown
     * multi-word name yields null rather than a guess that would never match.
     */
    public static function normalizeCountry(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $raw = mb_strtolower(trim($value));

        if (preg_match('/^[a-z]{2}$/', $raw)) {
            return $raw;
        }

        $map = [
            'bangladesh' => 'bd',
            'united states' => 'us',
            'united states of america' => 'us',
            'usa' => 'us',
            'united kingdom' => 'gb',
            'uk' => 'gb',
            'india' => 'in',
            'canada' => 'ca',
            'australia' => 'au',
        ];

        return $map[$raw] ?? null;
    }

    // ─── Normalise + hash ───────────────────────────────────────────────────

    public static function email(?string $v): ?string
    {
        return self::hash(self::normalizeEmail($v));
    }

    public static function phone(?string $v, ?string $cc = null): ?string
    {
        return self::hash(self::normalizePhone($v, $cc));
    }

    public static function firstName(?string $v): ?string
    {
        return self::hash(self::normalizeName($v));
    }

    public static function lastName(?string $v): ?string
    {
        return self::hash(self::normalizeName($v));
    }

    public static function gender(?string $v): ?string
    {
        return self::hash(self::normalizeGender($v));
    }

    public static function birthdate(?string $v): ?string
    {
        return self::hash(self::normalizeBirthdate($v));
    }

    public static function city(?string $v): ?string
    {
        return self::hash(self::normalizeCity($v));
    }

    public static function state(?string $v): ?string
    {
        return self::hash(self::normalizeState($v));
    }

    public static function zip(?string $v): ?string
    {
        return self::hash(self::normalizeZip($v));
    }

    public static function country(?string $v): ?string
    {
        return self::hash(self::normalizeCountry($v));
    }

    /** Stable internal user id. Trimmed and hashed; no other normalisation. */
    public static function externalId(int|string|null $v): ?string
    {
        return blank($v) ? null : self::hash(trim((string) $v));
    }
}
