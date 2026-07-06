<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckoutOtpController extends Controller
{
    private const OTP_TTL_MINUTES = 5;

    private const VERIFIED_TTL_MINUTES = 30;

    private const RESEND_COOLDOWN_SECONDS = 60;

    public function sendOtp(Request $request)
    {
        if (setting('sms_config_status') != 1) {
            return response()->json(['message' => 'OTP verification is not enabled.'], 400);
        }

        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = trim($request->phone);
        $cooldownKey = "checkout_otp_cooldown_{$phone}";

        if (Cache::has($cooldownKey)) {
            return response()->json(['message' => 'Please wait before requesting another OTP.'], 429);
        }

        $otp = rand(100000, 999999);
        $message = config('app.nickname').' Order verification code: '.$otp.'. It expires in '.self::OTP_TTL_MINUTES.' minutes.';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, setting('SMS_API_URL'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'api_key' => setting('SMS_API_KEY'),
            'senderid' => setting('SMS_API_SENDER_ID'),
            'number' => $phone,
            'message' => $message,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        if (! in_array($result['response_code'] ?? null, [200, 202], true)) {
            return response()->json(['message' => $result['error_message'] ?? 'Failed to send OTP. Please try again.'], 422);
        }

        Cache::put("checkout_otp_{$phone}", $otp, now()->addMinutes(self::OTP_TTL_MINUTES));
        Cache::forget("checkout_otp_verified_{$phone}");
        Cache::put($cooldownKey, true, now()->addSeconds(self::RESEND_COOLDOWN_SECONDS));

        return response()->json(['message' => 'OTP sent to your phone.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp' => 'required|string',
        ]);

        $phone = trim($request->phone);
        $stored = Cache::get("checkout_otp_{$phone}");

        if (! $stored || (string) $stored !== trim($request->otp)) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        Cache::forget("checkout_otp_{$phone}");
        Cache::put("checkout_otp_verified_{$phone}", true, now()->addMinutes(self::VERIFIED_TTL_MINUTES));

        return response()->json(['message' => 'Phone number verified.']);
    }
}
