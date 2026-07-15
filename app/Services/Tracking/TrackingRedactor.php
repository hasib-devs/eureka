<?php

declare(strict_types=1);

namespace App\Services\Tracking;

/**
 * Strips secrets out of text that is about to be shown to a human or written
 * to a log.
 *
 * This exists because of a specific, non-obvious leak. Guzzle appends the full
 * request URI — query string included — to every cURL-level exception message,
 * and Psr7's own redaction only strips `user:pass@` userinfo, never the query.
 * GA4's Measurement Protocol requires api_secret in the query string, so any
 * DNS blip or timeout turns an error message into a secret disclosure. The
 * admin panel's Test Connection button renders those messages straight into the
 * DOM, which would hand back the very secret the write-only form is designed
 * never to show.
 *
 * Anything derived from an exception or an HTTP response must pass through here
 * before it reaches a response or a log.
 */
class TrackingRedactor
{
    private const MASK = '[redacted]';

    /**
     * Redact known secret values, plus anything that looks like a credential in
     * a query string even if we were not given the value.
     *
     * @param  array<int, string|null>  $secrets  known values to remove verbatim
     */
    public static function scrub(?string $text, array $secrets = []): string
    {
        if (blank($text)) {
            return '';
        }

        $text = (string) $text;

        // Known values first — catches them anywhere in the string, including
        // inside a URL, and both raw and percent-encoded.
        foreach ($secrets as $secret) {
            if (blank($secret) || strlen((string) $secret) < 6) {
                continue;
            }

            $text = str_replace(
                [(string) $secret, rawurlencode((string) $secret), urlencode((string) $secret)],
                self::MASK,
                $text
            );
        }

        // Defence in depth: redact credential-shaped query parameters whose
        // values we were not handed (a rotated secret, a nested URL, etc).
        $text = preg_replace(
            '/\b(api_secret|access_token|client_secret|token|password)=([^&\s"\'\)]+)/i',
            '$1='.self::MASK,
            $text
        ) ?? $text;

        return $text;
    }
}
