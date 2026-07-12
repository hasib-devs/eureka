<?php

use App\Support\Invoices\InvoiceTotals;

it('computes subtotal, grand total and due amount', function () {
    $result = InvoiceTotals::compute(
        [['qty' => 2, 'unit_price' => 100]],
        discount: 10,
        delivery: 80,
        additional: 0,
        advance: 50,
    );

    expect($result['subtotal'])->toBe(200.0);
    expect($result['grand_total'])->toBe(270.0);
    expect($result['due_amount'])->toBe(220.0);
});

it('sums multiple line items', function () {
    $result = InvoiceTotals::compute([
        ['qty' => 1, 'unit_price' => 150],
        ['qty' => 3, 'unit_price' => 50],
    ]);

    expect($result['subtotal'])->toBe(300.0);
    expect($result['grand_total'])->toBe(300.0);
    expect($result['due_amount'])->toBe(300.0);
});
