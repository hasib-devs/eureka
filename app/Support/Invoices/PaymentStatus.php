<?php

namespace App\Support\Invoices;

class PaymentStatus
{
    /**
     * Derive an order's invoice payment status from its total, amount paid,
     * and the legacy `pay_staus` flag.
     */
    public static function forOrder(float $total, float $paid, $payStaus): string
    {
        if ($total > 0 && $paid >= $total) {
            return 'Paid';
        }
        if ($paid > 0) {
            return 'Partially Paid';
        }
        if ($payStaus == 1) {
            return 'Paid';
        }

        return 'Unpaid';
    }
}
