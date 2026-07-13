<?php

namespace App\Services;

/**
 * District-based delivery charge. An order placed in the "inside" district
 * (setting shipping_range_inside, default Dhaka) pays setting shipping_charge
 * per seller; every other district pays shipping_charge_out_of_range per
 * seller. Orders whose subtotal reaches shipping_free_above ship free.
 */
class ShippingCharge
{
    /** The district that gets the inside-area rate. */
    public static function insideArea(): string
    {
        return trim((string) (setting('shipping_range_inside') ?: 'Dhaka'));
    }

    /** Charge for a single seller's parcel to the given district ($city is a legacy fallback). */
    public static function single(?string $district, ?string $city = null): float
    {
        $area = trim((string) ($district ?: $city));

        return (float) (strcasecmp($area, self::insideArea()) === 0
            ? setting('shipping_charge')
            : setting('shipping_charge_out_of_range'));
    }

    /**
     * Charge for a whole order.
     *
     * @return array{single: float, total: float}
     */
    public static function forOrder(float $subtotal, int $sellerCount, ?string $district, ?string $city = null): array
    {
        $freeAbove = (float) (setting('shipping_free_above') ?? 0);

        if ($freeAbove > 0 && $subtotal >= $freeAbove) {
            return ['single' => 0.0, 'total' => 0.0];
        }

        $single = self::single($district, $city);

        return ['single' => $single, 'total' => $single * $sellerCount];
    }
}
