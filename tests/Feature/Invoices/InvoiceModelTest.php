<?php

use App\Models\Invoice;

it('creates an invoice with items and round-trips decimals', function () {
    $invoice = Invoice::create([
        'invoice_no' => 'INV-2026-0001',
        'invoice_date' => '2026-07-12',
        'customer_name' => 'Test Customer',
        'subtotal' => 200,
        'grand_total' => 270.50,
        'due_amount' => 220.50,
        'status' => 'Unpaid',
    ]);

    $invoice->items()->createMany([
        ['description' => 'Item A', 'qty' => 1, 'unit_price' => 100, 'line_total' => 100],
        ['description' => 'Item B', 'qty' => 1, 'unit_price' => 100, 'line_total' => 100],
    ]);

    expect($invoice->items()->count())->toBe(2);
    expect((float) $invoice->fresh()->grand_total)->toBe(270.50);
    expect($invoice->items->first()->invoice->id)->toBe($invoice->id);
});
