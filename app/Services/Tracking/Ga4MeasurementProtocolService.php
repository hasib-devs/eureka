<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GA4 server-side parity via the Measurement Protocol.
 *
 * Same reasoning as Meta CAPI: browser-only measurement loses a meaningful
 * share of events to ad blockers and ITP. Sending the conversion server-side
 * too, with the SAME client_id as the browser hit, keeps the session and
 * attribution intact rather than creating a second, unattributed user.
 *
 * Consent: GA4 Measurement Protocol hits are NOT covered by the browser's
 * Consent Mode signals — gtag never sees them — so a server hit for a user who
 * denied analytics_storage would bypass their choice entirely. We therefore
 * gate on consent ourselves and simply do not send.
 *
 * @see https://developers.google.com/analytics/devguides/collection/protocol/ga4
 */
class Ga4MeasurementProtocolService
{
    private const ENDPOINT = 'https://www.google-analytics.com/mp/collect';

    private const DEBUG_ENDPOINT = 'https://www.google-analytics.com/debug/mp/collect';

    private const MAX_ATTEMPTS = 3;

    public function __construct(private TrackingSettingsService $settings) {}

    /**
     * @param  array<string, mixed>  $params
     */
    public function send(string $eventName, string $clientId, array $params = [], ?string $userId = null): bool
    {
        if (! $this->settings->ga4MpEnabled()) {
            return false;
        }

        $payload = $this->payload($eventName, $clientId, $params, $userId);

        return $this->dispatchWithRetry($eventName, $payload);
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function payload(string $eventName, string $clientId, array $params = [], ?string $userId = null): array
    {
        $payload = [
            'client_id' => $clientId,
            'events' => [[
                'name' => $eventName,
                'params' => array_filter($params, fn ($v) => $v !== null),
            ]],
        ];

        if (filled($userId)) {
            $payload['user_id'] = $userId;
        }

        return $payload;
    }

    /**
     * The client_id GA4 already assigned this browser, read from the _ga
     * cookie, so the server hit joins the same session as the browser hit
     * instead of inventing a second user.
     *
     * _ga looks like "GA1.1.1234567890.1687000000"; the client id is the last
     * two dot-separated parts. Falls back to a stable per-session UUID when the
     * cookie is absent (consent denied, first hit, blocker).
     */
    public function clientIdFor(Request $request): string
    {
        $ga = $request->cookie('_ga');

        if (filled($ga) && preg_match('/^GA\d\.\d\.(\d+\.\d+)$/', (string) $ga, $m)) {
            return $m[1];
        }

        if ($request->hasSession()) {
            $existing = $request->session()->get('tracking.ga4_client_id');

            if (filled($existing)) {
                return (string) $existing;
            }

            $generated = $this->generateClientId();
            $request->session()->put('tracking.ga4_client_id', $generated);

            return $generated;
        }

        return $this->generateClientId();
    }

    /**
     * Validates the configuration against GA4's debug endpoint, which returns
     * the validation messages the live endpoint silently swallows.
     *
     * @return array{ok: bool, message: string}
     */
    public function testConnection(): array
    {
        $s = $this->settings->current();

        if (blank($s->ga4_measurement_id)) {
            return ['ok' => false, 'message' => 'Measurement ID is required.'];
        }

        if (! preg_match('/^G-[A-Z0-9]{4,}$/i', $s->ga4_measurement_id)) {
            return ['ok' => false, 'message' => 'Measurement ID must look like G-XXXXXXXXXX.'];
        }

        if (blank($s->ga4_api_secret)) {
            return ['ok' => false, 'message' => 'Measurement ID format is valid, but an API Secret is required to send server-side events.'];
        }

        try {
            $response = Http::timeout(10)->acceptJson()->post(
                $this->endpointUrl(self::DEBUG_ENDPOINT),
                $this->payload('page_view', $this->generateClientId(), ['debug_mode' => 1])
            );

            if (! $response->successful()) {
                return ['ok' => false, 'message' => 'GA4 returned HTTP '.$response->status().'.'];
            }

            $messages = $response->json('validationMessages', []);

            if (filled($messages)) {
                $first = $messages[0]['description'] ?? 'Validation failed.';

                return ['ok' => false, 'message' => 'GA4 rejected the event: '.$first];
            }

            return ['ok' => true, 'message' => 'Measurement Protocol reachable and the payload validated.'];
        } catch (\Throwable $e) {
            // Scrubbed: Google requires api_secret in the query string, and a
            // Guzzle exception message carries the full URI — so a DNS blip or
            // timeout would otherwise render the secret into the admin panel.
            return ['ok' => false, 'message' => 'Could not reach GA4: '.$this->scrub($e->getMessage())];
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function dispatchWithRetry(string $eventName, array $payload): bool
    {
        $url = $this->endpointUrl(self::ENDPOINT);

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                $response = Http::timeout(10)->post($url, $payload);

                // The live endpoint returns 204 with no body on success.
                if ($response->successful()) {
                    Log::info('GA4 MP event sent.', [
                        'event_name' => $eventName,
                        'status' => $response->status(),
                        'attempt' => $attempt,
                    ]);

                    return true;
                }

                if ($response->clientError() && $response->status() !== 429) {
                    Log::warning('GA4 MP event rejected; not retrying.', [
                        'event_name' => $eventName,
                        'status' => $response->status(),
                        'attempt' => $attempt,
                    ]);

                    return false;
                }
            } catch (\Throwable $e) {
                Log::warning('GA4 MP attempt failed.', [
                    'event_name' => $eventName,
                    'attempt' => $attempt,
                    // The exception message embeds the endpoint URL, which
                    // carries api_secret.
                    'reason' => $this->scrub($e->getMessage()),
                ]);
            }

            if ($attempt < self::MAX_ATTEMPTS) {
                $base = (int) config('tracking.retry_backoff_ms', 1000);

                if ($base > 0) {
                    usleep($base * 1000 * (2 ** ($attempt - 1)));
                }
            }
        }

        Log::error('GA4 MP event failed after retries.', [
            'event_name' => $eventName,
            'attempts' => self::MAX_ATTEMPTS,
        ]);

        return false;
    }

    /** Never surface or log anything derived from an exception unscrubbed. */
    private function scrub(?string $text): string
    {
        return TrackingRedactor::scrub($text, [$this->settings->current()->ga4_api_secret]);
    }

    private function endpointUrl(string $base): string
    {
        $s = $this->settings->current();

        // Credentials go in the query string per Google's spec — this URL is
        // never logged, precisely because it carries the api_secret.
        return $base.'?'.http_build_query([
            'measurement_id' => $s->ga4_measurement_id,
            'api_secret' => $s->ga4_api_secret,
        ]);
    }

    private function generateClientId(): string
    {
        return random_int(1_000_000_000, 9_999_999_999).'.'.time();
    }
}
