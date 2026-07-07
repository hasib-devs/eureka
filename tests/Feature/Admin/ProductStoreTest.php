<?php

use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

function storeAdmin(): User
{
    test()->seed(RoleSeeder::class);

    return User::factory()->create([
        'role_id' => 1,
        'is_approved' => true,
        'status' => true,
    ]);
}

it('creates a product from the rebuilt admin form and redirects to the list', function () {
    $admin = storeAdmin();
    $categoryId = DB::table('categories')->insertGetId([
        'name' => 'Cozy Lighting', 'slug' => 'cozy-lighting', 'status' => 1,
        'created_at' => now(), 'updated_at' => now(),
    ]);
    $colorId = DB::table('colors')->insertGetId([
        'name' => 'Warm Glow', 'slug' => 'warm-glow', 'code' => '#f9d976',
        'created_at' => now(), 'updated_at' => now(),
    ]);

    $response = $this->actingAs($admin)->post('/admin/product', [
        'ptypen' => 'normal',
        'shipping_charge' => 1,
        'title' => 'Aurora Swirl Lamp',
        'sku' => 'ASL-1',
        'categories' => [$categoryId],
        'regular_price' => 2290,
        'quantity' => 10,
        'status' => 1,
        'short_description' => '<p>The design story</p>',
        'full_description' => '<p>Technical details</p>',
        'spec_labels' => ['Light Source', 'Origin'],
        'spec_values' => ['ST64 Vintage LED', 'Bangladesh'],
        'colors' => [$colorId],
        'color_prices' => [0],
        'color_quantits' => [5],
        'image' => UploadedFile::fake()->image('main.jpg', 600, 800),
        'images' => [UploadedFile::fake()->image('g1.jpg', 600, 800)],
        'lifestyle_images' => [UploadedFile::fake()->image('l1.jpg', 600, 800)],
        'lifestyle_tags' => ['Evening Glow'],
        'lifestyle_captions' => ['The Living Room Edit'],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $product = Product::where('title', 'Aurora Swirl Lamp')->firstOrFail();

    expect($product->specs)->toBe([
        ['label' => 'Light Source', 'value' => 'ST64 Vintage LED'],
        ['label' => 'Origin', 'value' => 'Bangladesh'],
    ]);
    expect($product->images()->where('section', 'gallery')->count())->toBe(1)
        ->and($product->images()->where('section', 'lifestyle')->count())->toBe(1);

    $lifestyle = $product->images()->where('section', 'lifestyle')->first();
    expect($lifestyle->tag)->toBe('Evening Glow')
        ->and($lifestyle->caption)->toBe('The Living Room Edit');

    expect(DB::table('color_product')->where('product_id', $product->id)->where('color_id', $colorId)->count())->toBe(1);
});

it('returns validation errors instead of saving when required fields are missing', function () {
    $admin = storeAdmin();

    $this->actingAs($admin)
        ->from('/admin/product/create')
        ->post('/admin/product', ['ptypen' => 'normal', 'shipping_charge' => 1])
        ->assertRedirect('/admin/product/create')
        ->assertSessionHasErrors(['title', 'regular_price', 'quantity', 'categories', 'image']);

    expect(Product::count())->toBe(0);
});
