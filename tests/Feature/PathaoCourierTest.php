<?php

use App\Models\Order;
use App\Models\Setting;
use App\Services\PathaoCourierService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

// adminUser() is provided globally by tests/Pest.php.

beforeEach(function () {
    foreach ([
        'PATHAO_STATUS' => '1',
        'PATHAO_MODE' => 'sandbox',
        'PATHAO_CLIENT_ID' => 'test-client',
        'PATHAO_CLIENT_SECRET' => 'test-secret',
        'PATHAO_USERNAME' => 'test@pathao.com',
        'PATHAO_PASSWORD' => 'secret',
        'PATHAO_STORE_ID' => '123',
    ] as $name => $value) {
        Setting::updateOrCreate(['name' => $name], ['value' => $value]);
    }

    Cache::flush();
});

it('issues a token once and caches it', function () {
    Http::fake([
        'https://courier-api-sandbox.pathao.com/aladdin/api/v1/issue-token' => Http::response([
            'access_token' => 'tok_abc',
            'expires_in' => 3600,
        ]),
    ]);

    expect(PathaoCourierService::token())->toBe('tok_abc')
        ->and(PathaoCourierService::token())->toBe('tok_abc');

    Http::assertSentCount(1);
});

it('returns null when authentication fails', function () {
    Http::fake([
        'https://courier-api-sandbox.pathao.com/*' => Http::response(['message' => 'invalid'], 401),
    ]);

    expect(PathaoCourierService::token())->toBeNull();
});

it('uses the live base url when mode is live', function () {
    Setting::updateOrCreate(['name' => 'PATHAO_MODE'], ['value' => 'live']);

    expect(PathaoCourierService::baseUrl())->toBe('https://api-hermes.pathao.com');
});

it('creates an order with the bearer token and reports the consignment id', function () {
    Http::fake([
        'https://courier-api-sandbox.pathao.com/aladdin/api/v1/issue-token' => Http::response([
            'access_token' => 'tok_abc',
            'expires_in' => 3600,
        ]),
        'https://courier-api-sandbox.pathao.com/aladdin/api/v1/orders' => Http::response([
            'message' => 'Order Created Successfully',
            'data' => ['consignment_id' => 'DX12345678'],
        ]),
    ]);

    $result = PathaoCourierService::createOrder(['merchant_order_id' => 'ORD-1']);

    expect($result['ok'])->toBeTrue()
        ->and($result['consignment_id'])->toBe('DX12345678');

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/aladdin/api/v1/orders')
            && $request->header('Authorization')[0] === 'Bearer tok_abc';
    });
});

it('surfaces pathao error messages when order creation fails', function () {
    Http::fake([
        'https://courier-api-sandbox.pathao.com/aladdin/api/v1/issue-token' => Http::response([
            'access_token' => 'tok_abc',
            'expires_in' => 3600,
        ]),
        'https://courier-api-sandbox.pathao.com/aladdin/api/v1/orders' => Http::response([
            'message' => 'The given data was invalid.',
            'errors' => ['recipient_phone' => ['The recipient phone is invalid.']],
        ], 422),
    ]);

    $result = PathaoCourierService::createOrder([]);

    expect($result['ok'])->toBeFalse()
        ->and($result['message'])->toContain('recipient phone');
});

it('lets an admin save pathao settings and clears the cached token', function () {
    Cache::put('pathao_token_'.md5(PathaoCourierService::baseUrl().'test-client'.'test@pathao.com'), 'stale');

    $admin = adminUser();

    $this->actingAs($admin)->put('/admin/setting/pathao', [
        'PATHAO_STATUS' => '1',
        'PATHAO_MODE' => 'sandbox',
        'PATHAO_CLIENT_ID' => 'new-client',
        'PATHAO_CLIENT_SECRET' => 'new-secret',
        'PATHAO_USERNAME' => 'merchant@example.com',
        'PATHAO_PASSWORD' => 'newpass',
        'PATHAO_STORE_ID' => '42',
    ])->assertRedirect();

    expect(setting('PATHAO_CLIENT_ID'))->toBe('new-client')
        ->and(setting('PATHAO_STORE_ID'))->toBe('42');
});

it('sends an order to pathao and marks it couriered', function () {
    Http::fake([
        'https://courier-api-sandbox.pathao.com/aladdin/api/v1/issue-token' => Http::response([
            'access_token' => 'tok_abc',
            'expires_in' => 3600,
        ]),
        'https://courier-api-sandbox.pathao.com/aladdin/api/v1/orders' => Http::response([
            'message' => 'Order Created Successfully',
            'data' => ['consignment_id' => 'DX99'],
        ]),
    ]);

    $admin = adminUser();
    $order = Order::factory()->create(['status' => 0]);

    $this->actingAs($admin)->post('/admin/setting/pathao/send/'.$order->id, [
        'city_id' => 1,
        'zone_id' => 25,
        'area_id' => 3,
        'item_weight' => '0.5',
        'amount_to_collect' => '1500',
    ])->assertRedirect(route('admin.order.show', $order->id));

    expect((int) $order->fresh()->status)->toBe(9);

    Http::assertSent(function ($request) {
        if (! str_contains($request->url(), '/aladdin/api/v1/orders')) {
            return false;
        }

        return $request['store_id'] === 123
            && $request['recipient_city'] === 1
            && $request['recipient_zone'] === 25
            && $request['amount_to_collect'] === 1500;
    });
});

it('refuses to send an already-couriered order again', function () {
    Http::fake();

    $admin = adminUser();
    $order = Order::factory()->create(['status' => 9]);

    $this->actingAs($admin)->post('/admin/setting/pathao/send/'.$order->id, [
        'city_id' => 1,
        'zone_id' => 25,
        'item_weight' => '0.5',
        'amount_to_collect' => '100',
    ])->assertRedirect(route('admin.order.show', $order->id));

    Http::assertNothingSent();
});
