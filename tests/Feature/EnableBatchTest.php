<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\wishlist;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Runtime verification for the "enable audited features" batch.
 * See docs/superpowers/specs/2026-07-13-enable-audited-features-design.md
 */
function enableBatchProduct(array $attributes = []): Product
{
    test()->seed(RoleSeeder::class);
    $vendor = User::factory()->create();
    $brandId = DB::table('brands')->insertGetId([
        'name' => 'Brand '.uniqid(),
        'slug' => 'brand-'.uniqid(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Product::factory()->create(array_merge([
        'user_id' => $vendor->id,
        'brand_id' => $brandId,
    ], $attributes));
}

it('resolves the wishlist -> product relationship (E3)', function () {
    $product = enableBatchProduct();
    $customer = User::factory()->create();
    $row = wishlist::create(['user_id' => $customer->id, 'product_id' => $product->id]);

    expect($row->product)->not->toBeNull()
        ->and($row->product->id)->toBe($product->id);
});

it('loads the wishlist page for a customer with a saved product without crashing (E3)', function () {
    $this->withoutVite();
    $product = enableBatchProduct();
    $customer = User::factory()->create(); // role_id 3 -> passes AccountMiddleware

    wishlist::create(['user_id' => $customer->id, 'product_id' => $product->id]);

    $this->actingAs($customer)
        ->get('/wishlist')
        ->assertOk()
        ->assertSee($product->title);
});

it('populates the brand filter on the category page (E5)', function () {
    $this->withoutVite();
    $product = enableBatchProduct();
    $brandName = DB::table('brands')->where('id', $product->brand_id)->value('name');

    $category = Category::create([
        'name' => 'Table Lamps',
        'slug' => 'table-lamps-e5',
        'status' => true,
    ]);
    $category->products()->attach($product->id);

    $this->get('/category/table-lamps-e5')
        ->assertOk()
        ->assertViewHas('brands', fn ($brands) => $brands->contains('id', $product->brand_id))
        ->assertSee('name="brands[]"', false)
        ->assertSee($brandName);
});

it('shows COD, bKash and Bank payment options at checkout (E1, E7)', function () {
    $this->withoutVite();
    test()->seed(SettingSeeder::class); // g_cod / g_bkash / g_bank => 'true'
    $product = enableBatchProduct();

    $this->post('/add/cart', [
        'id' => $product->id,
        'qty' => 1,
        'color' => 'blank',
        'size' => 'blank',
    ])->assertOk();

    $this->get('/checkout')
        ->assertOk()
        ->assertSee('value="Cash on Delivery"', false)
        ->assertSee('value="Bkash"', false)
        ->assertSee('value="Bank"', false);
});

it('drives the storefront theme primary color from the admin Color setting (global color)', function () {
    $this->withoutVite();
    $product = enableBatchProduct();
    Setting::updateOrCreate(['name' => 'PRIMARY_COLOR'], ['value' => '#123456']);

    $this->get('/product/'.$product->slug)
        ->assertOk()
        ->assertSee('--color-primary: #123456', false)      // bridged onto the Tailwind theme
        ->assertSee('--color-primary-600: #102f4d', false); // derived hover shade
});

it('shows the Google sign-in button on the login page only when configured AND enabled (E8)', function () {
    $this->withoutVite();

    // Credentials present but the admin toggle is off => hidden.
    config(['services.google.client_id' => 'test-client-id']);
    Setting::updateOrCreate(['name' => 'google_login_status'], ['value' => '0']);
    Cache::flush();
    $this->get('/login')->assertOk()->assertDontSee('Continue with Google');

    // Toggle on but no credentials => still hidden.
    config(['services.google.client_id' => null]);
    Setting::updateOrCreate(['name' => 'google_login_status'], ['value' => '1']);
    Cache::flush();
    $this->get('/login')->assertOk()->assertDontSee('Continue with Google');

    // Both present => button shows, wired to the redirect route.
    config(['services.google.client_id' => 'test-client-id']);
    Setting::updateOrCreate(['name' => 'google_login_status'], ['value' => '1']);
    Cache::flush();
    $this->get('/login')
        ->assertOk()
        ->assertSee('Continue with Google')
        ->assertSee('/auth/google/redirect');
});

it('saves the Google login toggle from the registration settings form (E8 admin)', function () {
    $this->actingAs(adminUser())->put(route('admin.setting.update'), [
        'type' => 7,
        'regVerify' => 'email',
        'recovrAC' => 'email',
        'google_login_status' => '1',
    ])->assertRedirect();

    expect(Setting::where('name', 'google_login_status')->value('value'))->toBe('1');
});
