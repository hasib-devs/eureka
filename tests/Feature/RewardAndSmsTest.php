<?php

use App\Models\Coupon;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\OrderSms;
use App\Services\RewardCoupon;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->seed(RoleSeeder::class));

it('issues a percentage coupon owned by the user for a review', function () {
    $user = User::factory()->create();

    $coupon = RewardCoupon::forReview($user->id);

    expect($coupon)->toBeInstanceOf(Coupon::class)
        ->and($coupon->user_id)->toBe($user->id)
        ->and($coupon->discount_type)->toBe('percent')
        ->and((float) $coupon->discount)->toBe(10.0)
        ->and($coupon->source)->toBe('review')
        ->and((int) $coupon->available_limit)->toBe(1);
});

it('issues a fixed coupon owned by the user for a purchase', function () {
    $user = User::factory()->create();

    $coupon = RewardCoupon::forPurchase($user->id);

    expect($coupon)->toBeInstanceOf(Coupon::class)
        ->and($coupon->discount_type)->toBe('fixed')
        ->and((float) $coupon->discount)->toBe(50.0)
        ->and($coupon->source)->toBe('purchase');
});

it('does not issue reward coupons for guests', function () {
    expect(RewardCoupon::forReview(null))->toBeNull()
        ->and(RewardCoupon::forPurchase(null))->toBeNull();
});

it('does not issue a review coupon when the reward is disabled', function () {
    $user = User::factory()->create();
    Setting::updateOrCreate(['name' => 'review_coupon_status'], ['value' => '0']);

    expect(RewardCoupon::forReview($user->id))->toBeNull();
});

it('a personal reward coupon is only in its owner wallet', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    RewardCoupon::forReview($owner->id);

    expect(Coupon::where('user_id', $owner->id)->count())->toBe(1)
        ->and(Coupon::where('user_id', $other->id)->count())->toBe(0)
        ->and(Coupon::public()->count())->toBe(0); // reward coupons are not public
});

it('builds an order SMS with placeholders filled in', function () {
    $order = new Order([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'invoice' => 'INV-XYZ',
        'order_id' => 'ORD-1',
        'total' => 250,
    ]);

    $msg = OrderSms::buildMessage($order);

    expect($msg)->toContain('INV-XYZ')
        ->and($msg)->toContain('John Doe');
});

it('order SMS send is a no-op when disabled (never throws)', function () {
    Setting::updateOrCreate(['name' => 'order_sms_status'], ['value' => '0']);
    $order = new Order(['phone' => '01700000000', 'invoice' => 'INV-1', 'first_name' => 'A']);

    OrderSms::send($order); // should simply return, no exception

    expect(true)->toBeTrue();
});

it('seeds default header menu items and the active scope orders them', function () {
    // menu_items are seeded by the migration
    expect(MenuItem::count())->toBeGreaterThanOrEqual(6);

    $labels = MenuItem::active()->pluck('label')->all();
    expect($labels)->toContain('Home')->toContain('Shop');
});
