<?php

use App\Models\Invoice;

it('shows the create invoice form to an admin', function () {
    $this->actingAs(adminUser())->get(route('admin.invoices.create'))
        ->assertOk()
        ->assertSee('Create Invoice');
});

it('stores a manual invoice with server-computed totals and a generated number', function () {
    $admin = adminUser();

    $response = $this->actingAs($admin)->post(route('admin.invoices.store'), [
        'invoice_date' => now()->toDateString(),
        'customer_name' => 'Jane Doe',
        'items' => [
            ['description' => 'Aurora Lamp', 'qty' => 2, 'unit_price' => 100],
            ['description' => 'Shipping box', 'qty' => 1, 'unit_price' => 20],
        ],
        'discount' => 10,
        'delivery_charge' => 80,
        'additional_charges' => 0,
        'advance_paid' => 50,
        'status' => 'Partially Paid',
        'payment_method' => 'bKash',
    ]);

    $invoice = Invoice::first();
    expect($invoice)->not->toBeNull();
    $response->assertRedirect(route('admin.invoices.show', $invoice->id));

    expect($invoice->invoice_no)->toBe('INV-'.now()->year.'-0001');
    expect((float) $invoice->subtotal)->toBe(220.0);
    expect((float) $invoice->grand_total)->toBe(290.0);
    expect((float) $invoice->due_amount)->toBe(240.0);
    expect($invoice->items)->toHaveCount(2);
    expect($invoice->created_by)->toBe($admin->id);
});

it('rejects a manual invoice without a customer name', function () {
    $this->actingAs(adminUser())->post(route('admin.invoices.store'), [
        'invoice_date' => now()->toDateString(),
        'items' => [['description' => 'X', 'qty' => 1, 'unit_price' => 10]],
        'status' => 'Draft',
    ])->assertSessionHasErrors('customer_name');

    expect(Invoice::count())->toBe(0);
});
