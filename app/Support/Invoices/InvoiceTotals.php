<?php

namespace App\Support\Invoices;

class InvoiceTotals
{
    /**
     * Compute invoice money totals from line items and charges.
     *
     * @param  array<int, array{qty?: mixed, unit_price?: mixed}>  $items
     * @return array{subtotal: float, grand_total: float, due_amount: float}
     */
    public static function compute(array $items, $discount = 0, $delivery = 0, $additional = 0, $advance = 0): array
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += (float) ($item['qty'] ?? 0) * (float) ($item['unit_price'] ?? 0);
        }

        $grandTotal = $subtotal - (float) $discount + (float) $delivery + (float) $additional;
        $due = $grandTotal - (float) $advance;

        return [
            'subtotal' => round($subtotal, 2),
            'grand_total' => round($grandTotal, 2),
            'due_amount' => round($due, 2),
        ];
    }
}
