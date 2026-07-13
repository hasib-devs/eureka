<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Str;

/**
 * Issues personal, single-use reward coupons that a customer EARNS
 * (for leaving a review, or for placing an order). Each is owned by the
 * customer (Coupon.user_id) so only they can redeem it, and it shows up in
 * their account "My Coupons" wallet.
 *
 * All methods are safe: they never throw, so a failure here never breaks the
 * review/order flow that triggered them.
 */
class RewardCoupon
{
    /** Percentage coupon granted for leaving a product review. */
    public static function forReview(?int $userId): ?Coupon
    {
        if (! $userId || setting('review_coupon_status', '1') == '0') {
            return null;
        }

        $percent = (float) (setting('review_coupon_percent') ?: 10);

        return self::issue($userId, 'review', 'RV', 'percent', $percent);
    }

    /** Fixed-amount coupon granted for placing an order. */
    public static function forPurchase(?int $userId): ?Coupon
    {
        if (! $userId || setting('purchase_coupon_status', '1') == '0') {
            return null;
        }

        $amount = (float) (setting('purchase_coupon_amount') ?: 50);

        return self::issue($userId, 'purchase', 'RW', 'fixed', $amount);
    }

    private static function issue(int $userId, string $source, string $prefix, string $type, float $value): ?Coupon
    {
        if ($value <= 0) {
            return null;
        }

        try {
            $days = (int) (setting('reward_coupon_valid_days') ?: 30);

            do {
                $code = $prefix.'-'.strtoupper(Str::random(6));
            } while (Coupon::where('code', $code)->exists());

            return Coupon::create([
                'code' => $code,
                'description' => ucfirst($source).' reward coupon',
                'source' => $source,
                'user_id' => $userId,
                'discount_type' => $type,
                'discount' => $value,
                'limit_per_user' => 1,
                'total_use_limit' => 1,
                'available_limit' => 1,
                'expire_date' => now()->addDays($days)->toDateString(),
                'status' => 1,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }
}
