<?php

namespace App\Services;

use App\Models\Order;

class OrderSms
{
    /**
     * Send an order-confirmation SMS to the customer.
     *
     * Never throws: an SMS failure must never break order placement.
     * Gated by the `order_sms_status` setting so it stays off until an
     * admin enables it (SMS costs money).
     */
    public static function send(Order $order): void
    {
        try {
            if (setting('order_sms_status') != 1) {
                return;
            }

            $phone = trim((string) ($order->phone ?? ''));
            $apiUrl = setting('SMS_API_URL');

            if ($phone === '' || empty($apiUrl) || empty(setting('SMS_API_KEY'))) {
                return;
            }

            $message = self::buildMessage($order);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'api_key' => setting('SMS_API_KEY'),
                'senderid' => setting('SMS_API_SENDER_ID'),
                'number' => $phone,
                'message' => $message,
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Build the SMS body from the admin template (with placeholders) or a
     * sensible default. Placeholders: {name} {invoice} {total} {site} {track_url}
     */
    public static function buildMessage(Order $order): string
    {
        $site = setting('site_title') ?: config('app.name');
        $invoice = $order->invoice ?: $order->order_id;
        $total = rtrim(rtrim(number_format((float) $order->total, 2, '.', ''), '0'), '.');
        $trackUrl = url('/order/track');
        $name = trim(($order->first_name ?? '').' '.($order->last_name ?? '')) ?: 'Customer';

        $replacements = [
            '{name}' => $name,
            '{invoice}' => $invoice,
            '{total}' => $total,
            '{site}' => $site,
            '{track_url}' => $trackUrl,
        ];

        $template = setting('order_sms_template');

        if (! empty($template)) {
            return strtr($template, $replacements);
        }

        return strtr(
            'Dear {name}, your order {invoice} at {site} has been placed successfully. Amount: {total}. Track: {track_url}. Thank you!',
            $replacements
        );
    }
}
