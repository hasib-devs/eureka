<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SslWirelessSms
{
    /**
     * Send an SMS through the SSL Wireless gateway. Same endpoint and
     * parameters as the inline cURL calls elsewhere in the app, but via the
     * Http client so it can be faked in tests.
     */
    public static function send(string $phone, string $message): bool
    {
        try {
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->get(config('services.sslwireless.url'), [
                    'api_token' => config('services.sslwireless.api_token'),
                    'sid' => config('services.sslwireless.sid'),
                    'csms_id' => (string) now()->getTimestamp(),
                    'msisdn' => $phone,
                    'sms' => $message,
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}
