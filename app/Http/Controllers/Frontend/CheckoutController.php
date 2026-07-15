<?php

namespace App\Http\Controllers\Frontend;

use App\Core\ShoppingCart\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Services\Tracking\TrackingEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 2 || Auth::user()->role_id == 3) {
                if (Cart::count() > 0) {
                    return $this->checkoutView($request);
                }
                notify()->warning('Your cart is empty.', 'Empty');
                return back();
            }

            notify()->warning('You are not authorized for this action.', 'Unauthorized');
            return back();
        }

        if (setting('GUEST_CHECKOUT') == 0) {
            return redirect()->route('login');
        }

        if (Cart::count() > 0) {
            return $this->checkoutView($request);
        }

        notify()->warning('Your cart is empty.', 'Empty');
        return back();
    }

    /**
     * Render checkout and record InitiateCheckout.
     *
     * Both the logged-in and guest paths land here, so the event fires once per
     * path with no duplication. Tracking must never block checkout, so a failure
     * is reported and swallowed.
     */
    private function checkoutView(Request $request)
    {
        try {
            app(TrackingEvents::class)->initiateCheckout($request, $this->cartCustomData());
        } catch (\Throwable $e) {
            report($e);
        }

        return view('frontend.checkout');
    }

    /**
     * Meta-shaped custom data built from the real cart contents.
     *
     * @return array<string, mixed>
     */
    private function cartCustomData(): array
    {
        $contents = [];
        $contentIds = [];
        $numItems = 0;
        $value = 0.0;

        foreach (Cart::content() as $item) {
            $contentIds[] = (string) $item->id;
            $contents[] = [
                'id' => (string) $item->id,
                'item_name' => $item->name,
                'quantity' => (int) $item->qty,
                'item_price' => round((float) $item->price, 2),
            ];
            $numItems += (int) $item->qty;
            $value += (float) $item->subtotal;
        }

        return [
            'currency' => app(TrackingEvents::class)->currency(),
            'value' => round($value, 2),
            'content_type' => 'product',
            'content_ids' => $contentIds,
            'contents' => $contents,
            'num_items' => $numItems,
        ];
    }
}
