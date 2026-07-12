<?php

namespace App\Support\Invoices;

use App\Models\Invoice;

class InvoiceNumber
{
    /**
     * Generate the next sequential invoice number for the current year,
     * formatted as INV-YYYY-NNNN.
     */
    public static function next(): string
    {
        $prefix = 'INV-'.now()->year.'-';

        $last = Invoice::where('invoice_no', 'like', $prefix.'%')
            ->orderByDesc('invoice_no')
            ->value('invoice_no');

        $seq = 0;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $seq = (int) $m[1];
        }

        return $prefix.str_pad((string) ($seq + 1), 4, '0', STR_PAD_LEFT);
    }
}
