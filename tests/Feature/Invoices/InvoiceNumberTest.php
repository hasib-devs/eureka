<?php

use App\Models\Invoice;
use App\Support\Invoices\InvoiceNumber;

it('starts at 0001 when no invoices exist for the year', function () {
    expect(InvoiceNumber::next())->toBe('INV-'.now()->year.'-0001');
});

it('increments from the latest invoice number for the year', function () {
    Invoice::create([
        'invoice_no' => 'INV-'.now()->year.'-0007',
        'invoice_date' => now()->toDateString(),
        'customer_name' => 'X',
    ]);

    expect(InvoiceNumber::next())->toBe('INV-'.now()->year.'-0008');
});
