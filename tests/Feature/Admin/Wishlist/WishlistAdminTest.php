<?php

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\wishlist;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

beforeEach(fn () => Cache::flush());

function wishlistAdminProduct(): Product
{
    seedRoles();
    $vendor = User::factory()->create();
    $brandId = DB::table('brands')->insertGetId([
        'name' => 'Brand '.uniqid(),
        'slug' => 'brand-'.uniqid(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Product::factory()->create(['user_id' => $vendor->id, 'brand_id' => $brandId]);
}

it('shows the wishlist admin page with the most-wishlisted products', function () {
    $product = wishlistAdminProduct();
    $customer = User::factory()->create();
    wishlist::create(['user_id' => $customer->id, 'product_id' => $product->id]);

    $this->actingAs(adminUser())->get(route('admin.wishlist.index'))
        ->assertOk()
        ->assertSee('Wishlists')
        ->assertSee('Total saves')
        ->assertSee($product->title);
});

it('redirects guests from the wishlist admin page', function () {
    $this->get(route('admin.wishlist.index'))->assertRedirect(route('login'));
});

it('saves the wishlist enable/disable toggle', function () {
    $this->actingAs(adminUser())->post(route('admin.wishlist.update'), ['wishlist_status' => '1'])
        ->assertRedirect();
    expect(Setting::where('name', 'wishlist_status')->value('value'))->toBe('1');

    // Checkbox absent => disabled.
    $this->actingAs(adminUser())->post(route('admin.wishlist.update'), [])
        ->assertRedirect();
    expect(Setting::where('name', 'wishlist_status')->value('value'))->toBe('0');
});

it('blocks the storefront wishlist page when the feature is disabled', function () {
    Setting::updateOrCreate(['name' => 'wishlist_status'], ['value' => '0']);
    Cache::flush();

    seedRoles();
    $customer = User::factory()->create();
    $this->actingAs($customer)->get('/wishlist')->assertRedirect(route('home'));
});
