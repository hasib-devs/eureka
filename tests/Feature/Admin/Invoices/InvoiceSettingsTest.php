<?php

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('shows the invoice settings page', function () {
    $this->actingAs(adminUser())->get(route('admin.invoices.settings'))
        ->assertOk()
        ->assertSee('Invoice Settings');
});

it('saves the accent and header color settings', function () {
    $this->actingAs(adminUser())->post(route('admin.invoices.settings.update'), [
        'invoice_accent' => '#123456',
        'invoice_header_bg' => '#222222',
        'invoice_bkash_number' => '01700000000',
    ])->assertRedirect();

    expect(Setting::where('name', 'invoice_accent')->value('value'))->toBe('#123456');
    expect(Setting::where('name', 'invoice_header_bg')->value('value'))->toBe('#222222');
    expect(Setting::where('name', 'invoice_bkash_number')->value('value'))->toBe('01700000000');
});

it('rejects a non-hex accent color', function () {
    $this->actingAs(adminUser())->post(route('admin.invoices.settings.update'), [
        'invoice_accent' => 'red',
        'invoice_header_bg' => '#222222',
    ])->assertSessionHasErrors('invoice_accent');
});

it('renders the saved accent color in the invoice template', function () {
    $admin = adminUser();
    Setting::updateOrCreate(['name' => 'invoice_accent'], ['value' => '#123456']);
    Cache::flush();

    $invoice = Invoice::create([
        'invoice_no' => 'INV-'.now()->year.'-0009',
        'invoice_date' => now()->toDateString(),
        'customer_name' => 'X',
        'subtotal' => 0,
        'grand_total' => 0,
        'due_amount' => 0,
        'status' => 'Draft',
    ]);

    $this->actingAs($admin)->get(route('admin.invoices.show', $invoice->id))
        ->assertOk()
        ->assertSee('--accent: #123456', false);
});
