<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Pathao Merchant Courier API (aladdin). Credentials and mode live in
 * settings (PATHAO_*) so the admin panel manages everything; the sandbox
 * base URL is used unless PATHAO_MODE is "live". Tokens are cached until
 * shortly before expiry and the cache key embeds the credentials, so
 * switching mode or account in admin never reuses a stale token.
 */
class PathaoCourierService
{
    public static function enabled(): bool
    {
        return setting('PATHAO_STATUS') == 1;
    }

    public static function baseUrl(): string
    {
        return setting('PATHAO_MODE') === 'live'
            ? 'https://api-hermes.pathao.com'
            : 'https://courier-api-sandbox.pathao.com';
    }

    public static function forgetToken(): void
    {
        Cache::forget(self::tokenCacheKey());
    }

    /** Bearer token via the password grant; null when auth fails. */
    public static function token(): ?string
    {
        $key = self::tokenCacheKey();

        if ($cached = Cache::get($key)) {
            return $cached;
        }

        $response = Http::acceptJson()->post(self::baseUrl().'/aladdin/api/v1/issue-token', [
            'client_id' => setting('PATHAO_CLIENT_ID'),
            'client_secret' => setting('PATHAO_CLIENT_SECRET'),
            'grant_type' => 'password',
            'username' => setting('PATHAO_USERNAME'),
            'password' => setting('PATHAO_PASSWORD'),
        ]);

        $token = $response->successful() ? $response->json('access_token') : null;

        if ($token) {
            $ttl = max(60, (int) $response->json('expires_in', 3600) - 300);
            Cache::put($key, $token, now()->addSeconds($ttl));
        }

        return $token;
    }

    /** @return array<int, array<string, mixed>> */
    public static function stores(): array
    {
        return self::get('/aladdin/api/v1/stores')['data']['data'] ?? [];
    }

    /** @return array<int, array<string, mixed>> */
    public static function cities(): array
    {
        return self::get('/aladdin/api/v1/city-list')['data']['data'] ?? [];
    }

    /** @return array<int, array<string, mixed>> */
    public static function zones(int $cityId): array
    {
        return self::get("/aladdin/api/v1/cities/{$cityId}/zone-list")['data']['data'] ?? [];
    }

    /** @return array<int, array<string, mixed>> */
    public static function areas(int $zoneId): array
    {
        return self::get("/aladdin/api/v1/zones/{$zoneId}/area-list")['data']['data'] ?? [];
    }

    /**
     * Create a consignment. Returns ok/message plus the consignment_id
     * Pathao assigns on success.
     *
     * @return array{ok: bool, message: string, consignment_id: ?string}
     */
    public static function createOrder(array $payload): array
    {
        $token = self::token();

        if (! $token) {
            return ['ok' => false, 'message' => 'Pathao authentication failed — check the API credentials.', 'consignment_id' => null];
        }

        $response = Http::acceptJson()
            ->withToken($token)
            ->post(self::baseUrl().'/aladdin/api/v1/orders', $payload);

        if (! $response->successful()) {
            $errors = collect($response->json('errors') ?? [])->flatten()->implode(' ');

            return [
                'ok' => false,
                'message' => trim(($response->json('message') ?? 'Pathao order failed.').' '.$errors),
                'consignment_id' => null,
            ];
        }

        return [
            'ok' => true,
            'message' => $response->json('message') ?? 'Order placed with Pathao.',
            'consignment_id' => $response->json('data.consignment_id'),
        ];
    }

    /** @return array<string, mixed> */
    private static function get(string $path): array
    {
        $token = self::token();

        if (! $token) {
            return [];
        }

        $response = Http::acceptJson()->withToken($token)->get(self::baseUrl().$path);

        return $response->successful() ? ($response->json() ?? []) : [];
    }

    private static function tokenCacheKey(): string
    {
        return 'pathao_token_'.md5(self::baseUrl().setting('PATHAO_CLIENT_ID').setting('PATHAO_USERNAME'));
    }
}
