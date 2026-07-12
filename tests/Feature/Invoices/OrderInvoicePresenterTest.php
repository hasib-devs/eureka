<?php

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\PartialPayment;
use App\Support\Invoices\OrderInvoicePresenter;

it('maps an order into the invoice view-model with a partial payment', function () {
    $order = Order::factory()->create([
        'total' => 500,
        'subtotal' => 420,
        'discount' => 0,
        'shipping_charge' => 80,
        'pay_staus' => 0,
    ]);

    OrderDetails::create([
        'order_id' => $order->id,
        'seller_id' => 1,
        'product_id' => makeProduct()->id,
        'title' => 'Aurora Swirl Lamp',
        'color' => 'Default',
        'size' => '0',
        'qty' => 1,
        'price' => 420,
        'total_price' => 420,
        'g_total' => 420,
    ]);

    PartialPayment::create([
        'order_id' => $order->id,
        'payment_method' => 'bKash',
        'amount' => 200,
        'status' => 1,
    ]);

    $vm = (new OrderInvoicePresenter($order->fresh()))->toArray();

    expect($vm['source'])->toBe('order');
    expect($vm['grand_total'])->toBe(500.0);
    expect($vm['advance_paid'])->toBe(200.0);
    expect($vm['due_amount'])->toBe(300.0);
    expect($vm['status'])->toBe('Partially Paid');
    expect($vm['delivery_charge'])->toBe(80.0);
    expect($vm['items'][0]['line_total'])->toBe(420.0);
    expect($vm['items'][0]['description'])->toBe('Aurora Swirl Lamp');
});

it('marks a fully paid order as Paid', function () {
    $order = Order::factory()->create(['total' => 300, 'pay_staus' => 1]);

    $vm = (new OrderInvoicePresenter($order->fresh()))->toArray();

    expect($vm['status'])->toBe('Paid');
    expect($vm['due_amount'])->toBe(300.0);
});
