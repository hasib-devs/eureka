<?php

use App\Models\Order;
use App\Services\OrderSms;

beforeEach(function () {
    seedRoles();
    $this->withoutVite();
});

it('lets a guest track a new INV- order by its exact invoice', function () {
    Order::factory()->create(['invoice' => 'INV-AB12CD-1699999999', 'status' => 1]);

    $this->post('/order/tracking', ['invoice' => 'INV-AB12CD-1699999999'])
        ->assertOk()
        ->assertSee('Order has left our supply center.');
});

it('matches a legacy hash-prefixed invoice pasted without the hash', function () {
    Order::factory()->create(['invoice' => '#556677', 'status' => 0]);

    $this->post('/order/tracking', ['invoice' => '556677'])
        ->assertOk()
        ->assertSee('Order has left our supply center.');
});

it('tracks an order from a one-click deep link', function () {
    Order::factory()->create(['invoice' => 'INV-XY99ZZ-1700000000', 'status' => 1]);

    $this->get('/order/track?invoice=INV-XY99ZZ-1700000000')
        ->assertOk()
        ->assertSee('Order has left our supply center.');
});

it('shows a not-found notice for an unknown invoice', function () {
    $this->post('/order/tracking', ['invoice' => 'INV-NONE00-1700000000'])
        ->assertOk()
        ->assertSee('অর্ডার পাওয়া যায়নি');
});

it('shows the bare tracking form when no invoice is given', function () {
    $this->get('/order/track')
        ->assertOk()
        ->assertDontSee('Order has left our supply center.');
});

it('builds an order SMS with a one-click tracking link that carries the invoice', function () {
    $order = new Order(['first_name' => 'Rin', 'invoice' => 'INV-AB12CD-1700000000', 'total' => 250]);

    expect(OrderSms::buildMessage($order))->toContain('order/track?invoice=INV-AB12CD-1700000000');
});
