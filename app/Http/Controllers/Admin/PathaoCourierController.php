<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\PathaoCourierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PathaoCourierController extends Controller
{
    /** Settings page under the new Courier menu. */
    public function index()
    {
        $stores = [];
        $connected = false;

        if (setting('PATHAO_CLIENT_ID') && setting('PATHAO_CLIENT_SECRET')) {
            try {
                $connected = (bool) PathaoCourierService::token();
                $stores = $connected ? PathaoCourierService::stores() : [];
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return view('admin.e-commerce.setting.pathaoIndex', compact('stores', 'connected'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'PATHAO_STATUS' => 'required|in:0,1',
            'PATHAO_MODE' => 'required|in:sandbox,live',
            'PATHAO_CLIENT_ID' => 'nullable|string|max:255',
            'PATHAO_CLIENT_SECRET' => 'nullable|string|max:255',
            'PATHAO_USERNAME' => 'nullable|string|max:255',
            'PATHAO_PASSWORD' => 'nullable|string|max:255',
            'PATHAO_STORE_ID' => 'nullable|integer',
        ]);

        foreach ([
            'PATHAO_STATUS', 'PATHAO_MODE', 'PATHAO_CLIENT_ID', 'PATHAO_CLIENT_SECRET',
            'PATHAO_USERNAME', 'PATHAO_PASSWORD', 'PATHAO_STORE_ID',
        ] as $name) {
            Setting::updateOrCreate(['name' => $name], ['value' => $request->get($name)]);
        }

        PathaoCourierService::forgetToken();

        notify()->success('Pathao settings updated', 'Success');

        return back();
    }

    /** Small standalone form: pick city/zone/area, confirm COD amount, send. */
    public function sendForm(Order $order)
    {
        $cities = [];

        try {
            $cities = PathaoCourierService::cities();
        } catch (\Throwable $e) {
            report($e);
        }

        if (empty($cities)) {
            notify()->warning('Could not load Pathao city list — check the Pathao credentials.', 'Pathao');

            return redirect()->route('admin.setting.pathao');
        }

        return view('admin.e-commerce.setting.pathaoSend', compact('order', 'cities'));
    }

    public function sendOrder(Request $request, Order $order)
    {
        $request->validate([
            'city_id' => 'required|integer',
            'zone_id' => 'required|integer',
            'area_id' => 'nullable|integer',
            'item_weight' => 'required|numeric|min:0.1|max:100',
            'amount_to_collect' => 'required|numeric|min:0',
            'special_instruction' => 'nullable|string|max:500',
        ]);

        if ($order->status == 9) {
            notify()->warning('This order was already sent to a courier.', 'Pathao');

            return redirect()->route('admin.order.show', $order->id);
        }

        $payload = [
            'store_id' => (int) setting('PATHAO_STORE_ID'),
            'merchant_order_id' => $order->order_id,
            'recipient_name' => trim($order->first_name.' '.$order->last_name),
            'recipient_phone' => $order->phone,
            'recipient_address' => trim($order->address.', '.$order->thana.', '.$order->district, ', '),
            'recipient_city' => (int) $request->city_id,
            'recipient_zone' => (int) $request->zone_id,
            'recipient_area' => (int) ($request->area_id ?? 0) ?: null,
            'delivery_type' => 48,
            'item_type' => 2,
            'special_instruction' => $request->special_instruction ?? '',
            'item_quantity' => max(1, (int) $order->orderDetails()->sum('qty')),
            'item_weight' => (float) $request->item_weight,
            'amount_to_collect' => (int) round($request->amount_to_collect),
            'item_description' => $order->orderDetails()->pluck('title')->take(3)->implode(', '),
        ];

        $result = PathaoCourierService::createOrder($payload);

        if (! $result['ok']) {
            notify()->warning($result['message'], 'Pathao Failed');

            return back()->withInput();
        }

        $order->status = 9; // sent to courier — same status Steadfast uses
        $order->save();
        DB::table('multi_order')->where('order_id', $order->id)->update(['status' => 9]);

        notify()->success('Sent to Pathao. Consignment: '.($result['consignment_id'] ?? 'N/A'), 'Success');

        return redirect()->route('admin.order.show', $order->id);
    }

    public function zones(int $city)
    {
        return response()->json(PathaoCourierService::zones($city));
    }

    public function areas(int $zone)
    {
        return response()->json(PathaoCourierService::areas($zone));
    }
}
