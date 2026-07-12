<?php

use App\Models\Invoice;
use App\Models\InvoiceItem;

function seedManualInvoice(): Invoice
{
    $inv = Invoice::create([
        'invoice_no' => 'INV-'.now()->year.'-0001',
        'invoice_date' => now()->toDateString(),
        'customer_name' => 'Original Customer',
        'subtotal' => 100,
        'grand_total' => 100,
        'due_amount' => 100,
        'status' => 'Unpaid',
    ]);
    $inv->items()->create(['description' => 'Item A', 'qty' => 1, 'unit_price' => 100, 'line_total' => 100]);

    return $inv;
}

it('updates a manual invoice, recomputing totals and keeping its number', function () {
    $invoice = seedManualInvoice();

    $this->actingAs(adminUser())->put(route('admin.invoices.update', $invoice->id), [
        'invoice_date' => now()->toDateString(),
        'customer_name' => 'Updated Customer',
        'items' => [['description' => 'New Item', 'qty' => 3, 'unit_price' => 50]],
        'discount' => 0,
        'delivery_charge' => 25,
        'additional_charges' => 0,
        'advance_paid' => 0,
        'status' => 'Paid',
    ])->assertRedirect(route('admin.invoices.show', $invoice->id));

    $invoice->refresh();
    expect($invoice->invoice_no)->toBe('INV-'.now()->year.'-0001');
    expect($invoice->customer_name)->toBe('Updated Customer');
    expect((float) $invoice->grand_total)->toBe(175.0);
    expect($invoice->items)->toHaveCount(1);
    expect($invoice->items->first()->description)->toBe('New Item');
});

it('deletes a manual invoice and its items', function () {
    $invoice = seedManualInvoice();

    $this->actingAs(adminUser())->delete(route('admin.invoices.destroy', $invoice->id))
        ->assertRedirect(route('admin.invoices.index'));

    expect(Invoice::count())->toBe(0);
    expect(InvoiceItem::count())->toBe(0);
});

it('shows a prefilled duplicate form', function () {
    $invoice = seedManualInvoice();

    $this->actingAs(adminUser())->get(route('admin.invoices.duplicate', $invoice->id))
        ->assertOk()
        ->assertSee('Duplicate Invoice')
        ->assertSee('Original Customer');
});
