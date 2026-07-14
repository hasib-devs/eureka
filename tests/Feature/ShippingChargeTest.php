<?php

use App\Models\Setting;
use App\Services\ShippingCharge;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleSeeder::class);

    Setting::updateOrCreate(['name' => 'shipping_charge'], ['value' => '60']);
    Setting::updateOrCreate(['name' => 'shipping_charge_out_of_range'], ['value' => '120']);
    Setting::updateOrCreate(['name' => 'shipping_free_above'], ['value' => '5000']);
});

it('charges the inside rate for the Dhaka district', function () {
    expect(ShippingCharge::single('Dhaka'))->toBe(60.0);
});

it('charges the outside rate for any other district', function () {
    expect(ShippingCharge::single('Cumilla'))->toBe(120.0)
        ->and(ShippingCharge::single('Gazipur'))->toBe(120.0);
});

it('matches the inside district case-insensitively', function () {
    expect(ShippingCharge::single('dhaka'))->toBe(60.0);
});

it('falls back to the legacy city value when no district is given', function () {
    expect(ShippingCharge::single(null, 'Dhaka'))->toBe(60.0)
        ->and(ShippingCharge::single('', 'Dhaka'))->toBe(60.0);
});

it('treats a missing district and city as outside', function () {
    expect(ShippingCharge::single(null, null))->toBe(120.0);
});

it('respects a custom inside area from settings', function () {
    Setting::updateOrCreate(['name' => 'shipping_range_inside'], ['value' => 'Chattogram']);

    expect(ShippingCharge::single('Chattogram'))->toBe(60.0)
        ->and(ShippingCharge::single('Dhaka'))->toBe(120.0);
});

it('multiplies the single charge by the seller count for an order', function () {
    $charge = ShippingCharge::forOrder(1000, 3, 'Dhaka');

    expect($charge['single'])->toBe(60.0)
        ->and($charge['total'])->toBe(180.0);
});

it('ships free once the subtotal reaches the free-above threshold', function () {
    $charge = ShippingCharge::forOrder(5000, 2, 'Cumilla');

    expect($charge['single'])->toBe(0.0)
        ->and($charge['total'])->toBe(0.0);
});

it('does not ship free when the threshold is unset or zero', function () {
    Setting::updateOrCreate(['name' => 'shipping_free_above'], ['value' => '0']);

    expect(ShippingCharge::forOrder(999999, 1, 'Dhaka')['total'])->toBe(60.0);
});

it('rejects a minimal checkout order without a district', function () {
    $response = $this->post(route('order.store_minimal'), [
        'first_name' => 'Test Customer',
        'phone' => '01700000000',
    ]);

    $response->assertSessionHasErrors('district');
});

it('rejects a minimal buy-now order without a district', function () {
    $response = $this->post(route('order.buy.store_minimal'), [
        'first_name' => 'Test Customer',
        'phone' => '01700000000',
    ]);

    $response->assertSessionHasErrors('district');
});
