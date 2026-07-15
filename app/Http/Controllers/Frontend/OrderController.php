<?php

namespace App\Http\Controllers\Frontend;

use App\Core\ShoppingCart\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Library\UddoktaPay;
use App\Models\DownloadProduct;
use App\Models\DownloadUserProduct;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Review;
use App\Services\OrderSms;
use App\Services\ProductPriceCalculator;
use App\Services\RewardCoupon;
use App\Services\ShippingCharge;
use App\Services\Tracking\TrackingEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // ─── Direct Buy ────────────────────────────────────────────────────────

    public function buyProduct(Request $request, ProductPriceCalculator $pricing)
    {
        $request->validate([
            'id' => 'required|integer',
            'qty' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->id);
        $qty = $request->qty ?? 1;

        // Per-order premium gift wrapping opt-in from the product page.
        if ($request->boolean('gift_wrap')) {
            Session::put('gift_wrap', true);
        }

        // Recompute the price server-side from the selected attributes/colour.
        // Never trust a client-supplied price (was $request->dynamic_price).
        $price = $pricing->unitPrice($request, $product);

        Cart::add([
            'id' => $product->id,
            'name' => $product->title,
            'qty' => $qty,
            'price' => $price,
            'weight' => 0,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image,
                'color' => $request->color ?? 'blank',
            ],
        ]);

        return redirect()->route('checkout');
    }

    // ─── Order Store Methods ────────────────────────────────────────────────

    public function orderStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
            'payment_method' => 'required|string',
        ]);

        $order = $this->createOrder($request, Auth::id(), 'cart');
        Cart::destroy();
        Session::forget('coupon');

        return view('frontend.order_success', ['data' => $this->successData($order)]);
    }

    public function orderBuyNowStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
            'payment_method' => 'required|string',
        ]);

        $order = $this->createOrder($request, Auth::id(), 'buy_now');
        Cart::destroy();
        Session::forget('coupon');

        return view('frontend.order_success', ['data' => $this->successData($order)]);
    }

    public function orderStore_guest(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
        ]);

        $order = $this->createOrder($request, null, 'cart');
        Cart::destroy();
        Session::forget('coupon');

        return view('frontend.order_success', ['data' => $this->successData($order)]);
    }

    public function orderStore_minimal(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
            'district' => 'required|string|max:100',
            'thana' => 'nullable|string|max:100',
        ]);

        $order = $this->createOrder($request, Auth::id() ?: null, 'cart');
        Cart::destroy();
        Session::forget('coupon');

        return view('frontend.order_success', ['data' => $this->successData($order)]);
    }

    public function orderBuyNowStore_minimal(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required',
            'district' => 'required|string|max:100',
            'thana' => 'nullable|string|max:100',
        ]);

        $order = $this->createOrder($request, Auth::id() ?: null, 'buy_now');
        Cart::destroy();
        Session::forget('coupon');

        return view('frontend.order_success', ['data' => $this->successData($order)]);
    }

    // ─── Order List & Invoice ───────────────────────────────────────────────

    public function order()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderDetails')
            ->latest()
            ->get();

        return view('frontend.order', compact('orders'));
    }

    public function orderInvoice($id)
    {
        $order = Order::with('orderDetails.product')->findOrFail($id);

        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('frontend.invoice', compact('order'));
    }

    // ─── Order Actions ──────────────────────────────────────────────────────

    public function cancel($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status == 0) {
            $order->update(['status' => 2]);
            notify()->success('Order cancelled.', 'Cancelled');
        }

        return back();
    }

    public function return_req($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $order->update(['status' => 6]);
        notify()->success('Return request submitted.', 'Return');

        return back();
    }

    public function returns()
    {
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', [6, 7, 8])
            ->with('orderDetails')
            ->latest()
            ->get();

        return view('frontend.returns_order', compact('orders'));
    }

    // ─── Review ─────────────────────────────────────────────────────────────

    public function review($order_id)
    {
        $order = Order::with('orderDetails.product')
            ->where('order_id', $order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('frontend.review', compact('order'));
    }

    public function storeReview(Request $request, $product_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        $files = [];
        foreach (['report', 'report2', 'report3', 'report4', 'report5'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $name = time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/reviews'), $name);
                $files[] = $name;
            } else {
                $files[] = null;
            }
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'body' => $request->review,
            'file' => $files[0] ?? '',
            'file2' => $files[1],
            'file3' => $files[2],
            'file4' => $files[3],
            'file5' => $files[4],
        ]);

        $avg = Review::where('product_id', $product_id)->avg('rating');
        Product::where('id', $product_id)->update(['review' => round($avg, 1)]);

        RewardCoupon::forReview(Auth::id());

        notify()->success('Review submitted.', 'Thanks');

        return back();
    }

    // ─── Downloads ──────────────────────────────────────────────────────────

    public function download()
    {
        $items = OrderDetails::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())->where('pay_staus', 1);
        })->with('product.downloads')->get();

        return view('frontend.download', compact('items'));
    }

    public function downloadProductFile($pro_id, $id)
    {
        $product = Product::with('downloads')->findOrFail($pro_id);
        $download = DownloadProduct::findOrFail($id);

        $purchased = OrderDetails::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())->where('pay_staus', 1);
        })->where('product_id', $pro_id)->exists();

        abort_unless($purchased, 403, 'You have not purchased this product.');

        if ($product->download_expire && now()->gt($product->download_expire)) {
            notify()->error('Download link has expired.', 'Expired');

            return back();
        }

        $used = DownloadUserProduct::where('user_id', Auth::id())
            ->where('product_id', $pro_id)
            ->count();
        $limit = ($product->download_limit ?? 0) * $product->downloads->count();

        if ($limit > 0 && $used >= $limit) {
            notify()->error('Download limit reached.', 'Limit Reached');

            return back();
        }

        DownloadUserProduct::create([
            'user_id' => Auth::id(),
            'product_id' => $pro_id,
            'download_id' => $id,
        ]);

        if ($download->url) {
            return redirect($download->url);
        }

        return response()->download(public_path('uploads/downloads/'.$download->file));
    }

    // ─── Payment ────────────────────────────────────────────────────────────

    public function payform($slug)
    {
        $order = Order::with('orderDetails')
            ->where('order_id', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('frontend.payform', compact('order'));
    }

    public function payCreate(Request $request, $slug)
    {
        $order = Order::where('order_id', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $paymentUrl = UddoktaPay::init_payment([
                'full_name' => $order->first_name,
                'email' => $order->email ?: (Auth::user()->email ?? 'noreply@'.parse_url(config('app.url'), PHP_URL_HOST)),
                'amount' => (string) $order->total,
                'metadata' => [
                    'order_id' => (string) $order->order_id,
                    'invoice' => (string) $order->invoice,
                ],
                'redirect_url' => route('uddoktapay.success'),
                'cancel_url' => route('uddoktapay.cancel'),
                'webhook_url' => url('/api/webhook'),
                'return_type' => 'GET',
            ]);

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            notify()->error($e->getMessage(), 'Payment Error');

            return back();
        }
    }

    // ─── UddoktaPay Return / Confirmation ───────────────────────────────────

    /**
     * User-facing return after paying (GET redirect_url?invoice_id=...).
     */
    public function success2(Request $request)
    {
        return $this->handlePaymentReturn($request->input('invoice_id'));
    }

    /**
     * Same as success2, for a POST return_type configuration.
     */
    public function success(Request $request)
    {
        return $this->handlePaymentReturn($request->input('invoice_id'));
    }

    /**
     * User-facing cancel / failure return.
     */
    public function fail(Request $request)
    {
        notify()->error('Payment was cancelled or did not complete.', 'Payment');

        return redirect()->route('order');
    }

    /**
     * Server-to-server webhook from UddoktaPay. Authenticated by the shared
     * API key header; re-verifies the payment before crediting the order.
     */
    public function webhook(Request $request)
    {
        $configuredKey = setting('uapi');
        if (empty($configuredKey) || $request->header('RT-UDDOKTAPAY-API-KEY') !== $configuredKey) {
            return response()->json(['status' => false], 401);
        }

        $invoiceId = $request->input('invoice_id');
        if (! $invoiceId) {
            return response()->json(['status' => false], 400);
        }

        try {
            $payment = UddoktaPay::verify_payment($invoiceId);
        } catch (\Exception $e) {
            return response()->json(['status' => false], 502);
        }

        $this->markOrderPaidFromPayment($payment);

        return response()->json(['status' => true], 200);
    }

    /**
     * Verify the invoice with UddoktaPay, credit the order if completed, and
     * redirect the customer back to their order list with a status message.
     */
    private function handlePaymentReturn(?string $invoiceId)
    {
        if (! $invoiceId) {
            notify()->error('Missing payment reference.', 'Payment');

            return redirect()->route('order');
        }

        try {
            $payment = UddoktaPay::verify_payment($invoiceId);
        } catch (\Exception $e) {
            notify()->warning('We could not verify your payment yet. If money was deducted it will be confirmed shortly.', 'Payment');

            return redirect()->route('order');
        }

        $order = $this->markOrderPaidFromPayment($payment);

        if ($order && $order->pay_staus == 1) {
            notify()->success('Payment successful. Your order is confirmed.', 'Paid');
        } else {
            notify()->warning('Payment is not completed yet. Please try again or contact support.', 'Payment');
        }

        return redirect()->route('order');
    }

    /**
     * Idempotently mark an order paid from a verified UddoktaPay payload.
     * Only a COMPLETED payment whose amount covers the order total flips an
     * as-yet-unpaid order — safe to call from both the redirect and the webhook.
     */
    private function markOrderPaidFromPayment(?array $payment): ?Order
    {
        if (! is_array($payment) || strtoupper($payment['status'] ?? '') !== 'COMPLETED') {
            return null;
        }

        $orderId = $payment['metadata']['order_id'] ?? null;
        if (! $orderId) {
            return null;
        }

        $order = Order::where('order_id', $orderId)->first();
        if (! $order) {
            return null;
        }

        // Amount reconciliation: the paid amount must cover the order total
        // (small epsilon for float/rounding). Underpaid → do not mark paid.
        if ((float) ($payment['amount'] ?? 0) + 0.5 < (float) $order->total) {
            return $order;
        }

        if ($order->pay_staus != 1) {
            $order->pay_staus = 1;
            $order->pay_date = now();
            $order->transaction_id = $payment['transaction_id'] ?? $payment['invoice_id'] ?? $order->transaction_id;
            $order->save();
        }

        return $order;
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function ensurePhoneVerified(?string $phone): void
    {
        if (setting('sms_config_status') != 1) {
            return;
        }

        if (! $phone || ! Cache::pull('checkout_otp_verified_'.trim($phone))) {
            throw ValidationException::withMessages([
                'phone' => 'Please verify your phone number with the OTP before placing the order.',
            ]);
        }
    }

    private function createOrder(Request $request, ?int $userId, string $cartType): Order
    {
        $this->ensurePhoneVerified($request->phone);

        $cartItems = Cart::content();
        $stotal = 0;
        $sellerIds = [];

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);

            if ($product && ! in_array($product->user_id, $sellerIds)) {
                $sellerIds[] = $product->user_id;
            }

            if ($item->qty >= 6 && $product && $product->whole_price > 0) {
                $stotal += $item->qty * $product->whole_price;
            } else {
                $stotal += $item->subtotal;
            }
        }

        $sellerCount = count($sellerIds);
        $city = $request->city ?? '';

        $shipping = ShippingCharge::forOrder((float) $stotal, $sellerCount, $request->district, $city);
        $singleCharge = $shipping['single'];
        $shippingCharge = $shipping['total'];

        $discount = 0.0;
        $couponCode = null;

        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            $discount = (float) ($coupon['discount'] ?? 0);
            $couponCode = $coupon['code'] ?? null;
        }

        $giftWrap = (bool) Session::get('gift_wrap', false);
        $giftWrapFee = $giftWrap ? (float) config('shop.gift_wrap_fee') : 0.0;

        $total = max(0.0, $stotal + $shippingCharge + $giftWrapFee - $discount);
        $orderId = 'ORD-'.strtoupper(Str::random(8));
        $invoice = 'INV-'.strtoupper(Str::random(6)).'-'.time();

        $order = Order::create([
            'user_id' => $userId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name ?? '',
            'company_name' => $request->company ?? '',
            'country' => $request->country ?? setting('COUNTRY_SERVE') ?? 'Bangladesh',
            'address' => $request->address ?? '',
            'town' => $city,
            'district' => $request->district ?? '',
            'thana' => $request->thana ?? '',
            'post_code' => $request->post_code ?? '',
            'phone' => $request->phone,
            'email' => $request->email ?? ($userId ? Auth::user()->email : ''),
            'payment_method' => $request->payment_method ?? 'Cash on Delivery',
            'mobile_number' => $request->mobile_number ?? '',
            'transaction_id' => $request->transaction_id ?? '',
            'coupon_code' => $couponCode,
            'subtotal' => $stotal,
            'discount' => $discount,
            'shipping_charge' => $shippingCharge,
            'single_charge' => $singleCharge,
            'gift_wrap' => $giftWrap,
            'gift_wrap_fee' => $giftWrapFee,
            'total' => $total,
            'cart_type' => $cartType,
            'status' => 0,
            'pay_staus' => 0,
            'order_id' => $orderId,
            'invoice' => $invoice,
        ]);

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            $lineTotal = ($item->qty >= 6 && $product && $product->whole_price > 0)
                ? $item->qty * $product->whole_price
                : $item->subtotal;

            OrderDetails::create([
                'order_id' => $order->id,
                'seller_id' => $product ? $product->user_id : null,
                'product_id' => $item->id,
                'title' => $item->name,
                'color' => $item->options->color ?? '',
                'size' => $item->options->size ?? '',
                'qty' => $item->qty,
                'price' => $item->price,
                'total_price' => $lineTotal,
                'g_total' => $total,
            ]);
        }

        Session::forget('gift_wrap');

        OrderSms::send($order);
        RewardCoupon::forPurchase($userId);

        // Every order path funnels through here, so this is the one place
        // Purchase needs firing. The browser and server legs share one event_id,
        // which is what lets Meta collapse them into a single event.
        // Failing to track must never fail an order that is already placed.
        try {
            app(TrackingEvents::class)->purchase($request, $order);
        } catch (\Throwable $e) {
            report($e);
        }

        return $order->load('orderDetails');
    }

    private function successData(Order $order): array
    {
        return [
            'invoice' => $order->invoice,
            'total' => $order->total,
            'shipping_charge' => $order->shipping_charge,
            'orderDetails' => $order->orderDetails,
            'name' => $order->first_name,
            'phone' => $order->phone,
        ];
    }
}
