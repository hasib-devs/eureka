<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sends server-side events to Meta's Conversions API.
 *
 * The browser pixel and this service both report the same user actions. Meta
 * collapses them into one event when event_name AND event_id match — so the
 * event_id is generated once, rendered into the page for fbq, and handed to
 * this service for the same action. Sending both maximises coverage (ad
 * blockers and ITP eat a large share of browser-only events) without
 * double-counting.
 *
 * @see https://developers.facebook.com/docs/marketing-api/conversions-api
 */
class MetaCapiService
{
    /** How long a sent event_id is remembered, to make retries idempotent. */
    private const IDEMPOTENCY_TTL = 3600;

    private const MAX_ATTEMPTS = 3;

    public function __construct(private TrackingSettingsService $settings) {}

    /**
     * Deliver one event. Returns true on success, false on a permanent failure
     * or when the event was already sent.
     *
     * @param  array<string, mixed>  $userData  already normalised + hashed where required
     * @param  array<string, mixed>  $customData
     */
    public function send(
        string $eventName,
        string $eventId,
        array $userData,
        array $customData = [],
        ?string $eventSourceUrl = null,
        ?int $eventTime = null,
    ): bool {
        if (! $this->settings->metaCapiEnabled()) {
            return false;
        }

        if ($this->alreadySent($eventId)) {
            Log::info('Meta CAPI event skipped (already sent).', [
                'event_name' => $eventName,
                'event_id' => $eventId,
            ]);

            return false;
        }

        $payload = $this->payload($eventName, $eventId, $userData, $customData, $eventSourceUrl, $eventTime);

        return $this->dispatchWithRetry($eventName, $eventId, $payload);
    }

    /**
     * Verifies the pixel + token pair by asking the Graph API for the pixel.
     * Used by the admin panel's Test Connection button.
     *
     * @return array{ok: bool, message: string}
     */
    public function testConnection(): array
    {
        $s = $this->settings->current();

        if (blank($s->meta_pixel_id) || blank($s->meta_access_token)) {
            return ['ok' => false, 'message' => 'Pixel ID and Access Token are both required.'];
        }

        try {
            // Bearer header, not a query parameter: Guzzle puts the full URI in
            // its exception messages, so a token in the query string would leak
            // into the admin panel on any DNS failure or timeout.
            $response = Http::timeout(10)
                ->acceptJson()
                ->withToken($s->meta_access_token)
                ->get($this->graphUrl($s->meta_pixel_id), ['fields' => 'name,id']);

            if ($response->successful()) {
                $name = $response->json('name');

                return [
                    'ok' => true,
                    'message' => 'Connected to pixel '.($name ? '"'.$name.'" ' : '').'('.$s->meta_pixel_id.').',
                ];
            }

            $error = $response->json('error.message') ?? 'HTTP '.$response->status();

            return [
                'ok' => false,
                'message' => 'Meta rejected the request: '.$this->scrub($error),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => 'Could not reach Meta: '.$this->scrub($e->getMessage()),
            ];
        }
    }

    /**
     * @param  array<string, mixed>  $userData
     * @param  array<string, mixed>  $customData
     * @return array<string, mixed>
     */
    public function payload(
        string $eventName,
        string $eventId,
        array $userData,
        array $customData = [],
        ?string $eventSourceUrl = null,
        ?int $eventTime = null,
    ): array {
        $s = $this->settings->current();

        $event = array_filter([
            'event_name' => $eventName,
            'event_time' => $eventTime ?? time(),
            'event_id' => $eventId,
            'event_source_url' => $eventSourceUrl ?: $this->settings->siteUrl(),
            'action_source' => 'website',
            'user_data' => array_filter($userData, fn ($v) => $v !== null && $v !== []),
            'custom_data' => array_filter($customData, fn ($v) => $v !== null && $v !== []),
        ], fn ($v) => $v !== null && $v !== []);

        $payload = [
            'data' => [$event],
            'access_token' => $s->meta_access_token,
        ];

        if (filled($s->meta_test_event_code)) {
            $payload['test_event_code'] = $s->meta_test_event_code;
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function dispatchWithRetry(string $eventName, string $eventId, array $payload): bool
    {
        $s = $this->settings->current();
        $url = $this->graphUrl($s->meta_pixel_id.'/events');

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                $response = Http::timeout(10)->acceptJson()->post($url, $payload);

                if ($response->successful()) {
                    // Marked only after Meta confirms, so a failed send can retry.
                    $this->markSent($eventId);

                    Log::info('Meta CAPI event sent.', [
                        'event_name' => $eventName,
                        'event_id' => $eventId,
                        'status' => $response->status(),
                        'attempt' => $attempt,
                    ]);

                    return true;
                }

                // 4xx other than 429 is our bug (bad payload/token) — retrying
                // will not fix it and just burns requests.
                if ($response->clientError() && $response->status() !== 429) {
                    Log::warning('Meta CAPI event rejected; not retrying.', [
                        'event_name' => $eventName,
                        'event_id' => $eventId,
                        'status' => $response->status(),
                        'error' => $this->scrub($response->json('error.message')),
                        'attempt' => $attempt,
                    ]);

                    return false;
                }

                $this->backoff($attempt, $eventName, $eventId, 'HTTP '.$response->status());
            } catch (\Throwable $e) {
                // Network-level failure — worth retrying. Scrubbed: a Guzzle
                // exception message carries the full request URI.
                $this->backoff($attempt, $eventName, $eventId, $this->scrub($e->getMessage()));
            }
        }

        Log::error('Meta CAPI event failed after retries.', [
            'event_name' => $eventName,
            'event_id' => $eventId,
            'attempts' => self::MAX_ATTEMPTS,
        ]);

        return false;
    }

    private function backoff(int $attempt, string $eventName, string $eventId, string $reason): void
    {
        Log::warning('Meta CAPI event attempt failed.', [
            'event_name' => $eventName,
            'event_id' => $eventId,
            'attempt' => $attempt,
            'reason' => $reason,
        ]);

        if ($attempt < self::MAX_ATTEMPTS) {
            // Exponential: base, base*2, base*4.
            $base = (int) config('tracking.retry_backoff_ms', 1000);

            if ($base > 0) {
                usleep($base * 1000 * (2 ** ($attempt - 1)));
            }
        }
    }

    /** Never surface or log anything derived from an exception unscrubbed. */
    private function scrub(?string $text): string
    {
        return TrackingRedactor::scrub($text, [$this->settings->current()->meta_access_token]);
    }

    private function graphUrl(string $path): string
    {
        $version = $this->settings->current()->meta_api_version ?: 'v25.0';

        return 'https://graph.facebook.com/'.$version.'/'.$path;
    }

    private function idempotencyKey(string $eventId): string
    {
        return 'tracking.capi.sent.'.$eventId;
    }

    public function alreadySent(string $eventId): bool
    {
        try {
            return Cache::has($this->idempotencyKey($eventId));
        } catch (\Throwable) {
            // A cache outage must not stop events; at worst Meta dedupes on event_id.
            return false;
        }
    }

    private function markSent(string $eventId): void
    {
        try {
            Cache::put($this->idempotencyKey($eventId), true, self::IDEMPOTENCY_TTL);
        } catch (\Throwable) {
            // Non-fatal — see alreadySent().
        }
    }
}
