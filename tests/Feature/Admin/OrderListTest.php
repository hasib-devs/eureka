<?php

use App\Models\Order;

// adminUser() is provided globally by tests/Pest.php.

it('renders the admin order list with orders and their status badges', function () {
    $this->withoutVite();
    $admin = adminUser();

    Order::factory()->create(['invoice' => '#911111', 'status' => 0]);
    Order::factory()->create(['invoice' => '#922222', 'status' => 4]);

    $this->actingAs($admin)->get('/admin/order')
        ->assertOk()
        ->assertSee('#911111')
        ->assertSee('#922222')
        ->assertSee('Pending')
        ->assertSee('Shipping');
});

it('filters the admin order list by keyword and status', function () {
    $this->withoutVite();
    $admin = adminUser();

    Order::factory()->create(['invoice' => '#911111', 'status' => 0]);
    Order::factory()->create(['invoice' => '#922222', 'status' => 3]);

    $this->actingAs($admin)->get('/admin/order?keyword=911111')
        ->assertOk()
        ->assertSee('#911111')
        ->assertDontSee('#922222');

    $this->actingAs($admin)->get('/admin/order?status=3')
        ->assertOk()
        ->assertSee('#922222')
        ->assertDontSee('#911111');
});

it('shows an empty state when no orders match the filters', function () {
    $this->withoutVite();
    $admin = adminUser();

    $this->actingAs($admin)->get('/admin/order?keyword=no-such-order')
        ->assertOk()
        ->assertSee('No orders found');
});
