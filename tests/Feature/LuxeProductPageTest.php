<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\DB;

function luxeProduct(array $attributes = []): Product
{
    test()->seed(RoleSeeder::class);
    $vendor = User::factory()->create();
    $brandId = DB::table('brands')->insertGetId([
        'name' => 'Luxe '.uniqid(),
        'slug' => 'luxe-'.uniqid(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Product::factory()->create(array_merge([
        'user_id' => $vendor->id,
        'brand_id' => $brandId,
        'regular_price' => '2290',
        'discount_price' => null,
        'quantity' => 10,
    ], $attributes));
}

it('renders the product page', function () {
    $this->withoutVite();
    $product = luxeProduct();

    $this->get('/product/'.$product->slug)
        ->assertOk()
        ->assertSee($product->title);
});

it('lets a guest post a review from the product page and recaches the average', function () {
    $product = luxeProduct();

    $this->postJson('/product/'.$product->id.'/review', [
        'rating' => 4,
        'reviewer_name' => 'Farhana R.',
        'title' => 'Feels premium',
        'review' => 'Better than expected for the price.',
    ])
        ->assertOk()
        ->assertJsonPath('review.name', 'Farhana R.')
        ->assertJsonPath('review.rating', 4)
        ->assertJsonPath('review.verified', false);

    $this->assertDatabaseHas('reviews', [
        'product_id' => $product->id,
        'reviewer_name' => 'Farhana R.',
        'rating' => 4,
        'user_id' => null,
    ]);

    expect((float) $product->fresh()->review)->toBe(4.0);
});

it('rejects a review without rating or text', function () {
    $product = luxeProduct();

    $this->postJson('/product/'.$product->id.'/review', [
        'reviewer_name' => 'Farhana R.',
    ])->assertStatus(422);
});

it('marks a reviewer as a verified buyer when they ordered the product', function () {
    $product = luxeProduct();
    $buyer = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $buyer->id, 'status' => 3]);
    DB::table('order_details')->insert([
        'order_id' => $order->id,
        'seller_id' => $product->user_id,
        'product_id' => $product->id,
        'title' => $product->title,
        'color' => 'blank',
        'size' => 'blank',
        'qty' => 1,
        'price' => 2290,
        'total_price' => 2290,
        'g_total' => 2290,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($buyer)->postJson('/product/'.$product->id.'/review', [
        'rating' => 5,
        'reviewer_name' => $buyer->name,
        'review' => 'Verified purchase review.',
    ])->assertOk()->assertJsonPath('review.verified', true);
});

it('counts a helpful vote only once per session', function () {
    $product = luxeProduct();
    $review = Review::create([
        'product_id' => $product->id,
        'reviewer_name' => 'Tanvir',
        'rating' => 5,
        'body' => 'Nice lamp.',
    ]);

    $this->postJson('/product-review/'.$review->id.'/helpful')
        ->assertOk()
        ->assertJsonPath('helpful_count', 1);

    $this->postJson('/product-review/'.$review->id.'/helpful')
        ->assertOk()
        ->assertJsonPath('helpful_count', 1);

    expect($review->fresh()->helpful_count)->toBe(1);
});

it('carries gift wrapping from add-to-cart into the order with its fee', function () {
    $product = luxeProduct();

    $this->post('/add/cart', [
        'id' => $product->id,
        'qty' => 2,
        'color' => 'blank',
        'size' => 'blank',
        'gift_wrap' => 1,
    ])->assertOk();

    $this->post('/order_minimal', [
        'first_name' => 'Rahim',
        'phone' => '01700000000',
    ])->assertOk();

    $order = Order::firstOrFail();
    $fee = (float) config('shop.gift_wrap_fee');

    expect($order->gift_wrap)->toBeTrue()
        ->and((float) $order->gift_wrap_fee)->toBe($fee)
        ->and((float) $order->total)->toBe(2290.0 * 2 + $fee);
});

it('places an order without gift wrapping when not selected', function () {
    $product = luxeProduct();

    $this->post('/add/cart', [
        'id' => $product->id,
        'qty' => 1,
        'color' => 'blank',
        'size' => 'blank',
    ])->assertOk();

    $this->post('/order_minimal', [
        'first_name' => 'Karim',
        'phone' => '01700000001',
    ])->assertOk();

    $order = Order::firstOrFail();

    expect($order->gift_wrap)->toBeFalse()
        ->and((float) $order->gift_wrap_fee)->toBe(0.0)
        ->and((float) $order->total)->toBe(2290.0);
});

it('duplicates a product with specs, colors and lifestyle captions, starting disabled', function () {
    test()->seed(RoleSeeder::class);
    $admin = User::factory()->create(['role_id' => 1, 'is_approved' => true, 'status' => true]);

    $product = luxeProduct([
        'spec' => json_encode([['label' => 'Light Source', 'value' => 'ST64 Vintage LED']]),
        'status' => 1,
    ]);
    $colorId = DB::table('colors')->insertGetId(['name' => 'Warm Glow', 'slug' => 'warm-glow', 'code' => '#f9d976', 'created_at' => now(), 'updated_at' => now()]);
    DB::table('color_product')->insert(['color_id' => $colorId, 'product_id' => $product->id, 'qnty' => 5, 'price' => 0]);
    $product->images()->create(['name' => 'no-such-file.jpg', 'section' => 'lifestyle', 'tag' => 'Evening Glow', 'caption' => 'The Living Room Edit']);

    $this->actingAs($admin)->get('/admin/product/duplicate/'.$product->id)
        ->assertRedirect();

    $copy = Product::where('title', $product->title.' (Copy)')->firstOrFail();

    expect((int) $copy->status)->toBe(0)
        ->and($copy->specs)->toBe([['label' => 'Light Source', 'value' => 'ST64 Vintage LED']])
        ->and($copy->slug)->not->toBe($product->slug);

    expect(DB::table('color_product')->where('product_id', $copy->id)->count())->toBe(1);

    $lifestyle = $copy->images->firstWhere('section', 'lifestyle');
    expect($lifestyle->tag)->toBe('Evening Glow')
        ->and($lifestyle->caption)->toBe('The Living Room Edit');
});
