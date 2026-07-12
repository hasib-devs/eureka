<?php

use App\Models\Invoice;
use App\Models\Order;

it('renders an existing order as an invoice', function () {
    $admin = adminUser();
    $order = Order::factory()->create([
        'first_name' => 'Rahim',
        'last_name' => 'Khan',
        'total' => 1000,
        'subtotal' => 920,
        'shipping_charge' => 80,
    ]);

    $this->actingAs($admin)->get(route('admin.invoices.order', $order->id))
        ->assertOk()
        ->assertSee('Rahim Khan')
        ->assertSee('INVOICE')
        ->assertSee('1,000.00');
});

it('renders a manual invoice with its items', function () {
    $admin = adminUser();
    $invoice = Invoice::create([
        'invoice_no' => 'INV-'.now()->year.'-0005',
        'invoice_date' => now()->toDateString(),
        'customer_name' => 'Karim Manual',
        'subtotal' => 300,
        'grand_total' => 300,
        'due_amount' => 300,
        'status' => 'Unpaid',
    ]);
    $invoice->items()->create([
        'description' => 'Wavy Lamp',
        'qty' => 1,
        'unit_price' => 300,
        'line_total' => 300,
    ]);

    $this->actingAs($admin)->get(route('admin.invoices.show', $invoice->id))
        ->assertOk()
        ->assertSee('INV-'.now()->year.'-0005')
        ->assertSee('Karim Manual')
        ->assertSee('Wavy Lamp');
});
