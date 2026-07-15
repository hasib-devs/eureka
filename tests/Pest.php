<?php

use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function seedRoles(): void
{
    test()->seed(RoleSeeder::class);
}

function adminUser(): User
{
    seedRoles();

    return User::factory()->create(['role_id' => 1]);
}

function makeProduct(): Product
{
    seedRoles();

    $user = User::factory()->create();
    $brand = Brand::create(['name' => 'Test Brand', 'slug' => 'brand-'.Str::random(10)]);

    return Product::factory()->create(['user_id' => $user->id, 'brand_id' => $brand->id]);
}

/**
 * An order with one line item, for tracking tests.
 *
 * order_details.product_id is a real foreign key and color/size are NOT NULL,
 * so the row has to be complete rather than minimal.
 *
 * @param  array<string, mixed>  $overrides
 */
function makeOrder(array $overrides = []): Order
{
    $product = makeProduct();

    $order = Order::create(array_merge([
        'user_id' => null,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'John_Smith@gmail.com',
        'phone' => '01712345678',
        'district' => 'Dhaka',
        'town' => 'Dhaka',
        'post_code' => '1207',
        'country' => 'Bangladesh',
        'payment_method' => 'Cash on Delivery',
        'subtotal' => 1000,
        'discount' => 0,
        'shipping_charge' => 60,
        'total' => 1060,
        'status' => 0,
        'pay_staus' => 0,
        'order_id' => 'ORD-TEST1234',
        'invoice' => 'INV-TEST-1',
    ], $overrides));

    OrderDetails::create([
        'order_id' => $order->id,
        'seller_id' => $product->user_id,
        'product_id' => $product->id,
        'title' => 'Test Product',
        'color' => '',
        'size' => '',
        'qty' => 2,
        'price' => 500,
        'total_price' => 1000,
        'g_total' => 1060,
    ]);

    return $order;
}
