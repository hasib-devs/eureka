<?php

use App\Models\Invoice;
use App\Models\Order;

function makeManualInvoice(array $overrides = []): Invoice
{
    return Invoice::create(array_merge([
        'invoice_no' => 'INV-'.now()->year.'-0001',
        'invoice_date' => now()->toDateString(),
        'customer_name' => 'Manual Customer',
        'grand_total' => 500,
        'due_amount' => 500,
        'status' => 'Unpaid',
    ], $overrides));
}

it('lists both order-derived and manual invoices for an admin', function () {
    $admin = adminUser();
    Order::factory()->create(['invoice' => '#00042', 'first_name' => 'Rahim', 'last_name' => 'Khan', 'total' => 1000]);
    makeManualInvoice(['customer_name' => 'Karim Manual']);

    $this->actingAs($admin)->get(route('admin.invoices.index'))
        ->assertOk()
        ->assertSee('#00042')
        ->assertSee('INV-'.now()->year.'-0001')
        ->assertSee('Karim Manual');
});

it('filters to only manual invoices', function () {
    $admin = adminUser();
    Order::factory()->create(['invoice' => '#00099', 'total' => 100]);
    makeManualInvoice(['customer_name' => 'OnlyManual']);

    $this->actingAs($admin)->get(route('admin.invoices.index', ['source' => 'manual']))
        ->assertOk()
        ->assertSee('OnlyManual')
        ->assertDontSee('#00099');
});

it('redirects guests away from the invoices page', function () {
    $this->get(route('admin.invoices.index'))->assertRedirect(route('login'));
});
