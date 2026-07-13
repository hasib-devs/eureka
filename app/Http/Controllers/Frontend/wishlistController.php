<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\wishlist;
use Illuminate\Http\Request;

class wishlistController extends Controller
{
    public function index()
    {
        if (setting('wishlist_status', '1') === '0') {
            return redirect()->route('home');
        }

        $wishlist = wishlist::where('user_id', auth()->id())
            ->with(['product.brand', 'product.sizes', 'product.colors', 'product.reviews'])
            ->latest('id')
            ->get();

        return view('frontend.wishlist', compact('wishlist'));
    }

    public function store(Request $request)
    {
        if (setting('wishlist_status', '1') === '0') {
            return redirect()->route('home');
        }

        $p = Product::where('slug', $request->product_id)->first();

        if (! $p) {
            if ($request->expectsJson()) {
                return response()->json(['alert' => 'Error', 'message' => 'Product not found'], 404);
            }
            notify()->error('Product not found', 'Error');

            return back();
        }

        $already = wishlist::where('user_id', auth()->id())->where('product_id', $p->id)->count();

        if ($already == 0) {
            wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $p->id,
            ]);
            $message = 'Added to your wishlist';
        } else {
            $message = 'Already in your wishlist';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'alert' => 'Success',
                'message' => $message,
                'count' => wishlist::where('user_id', auth()->id())->count(),
            ]);
        }

        notify()->success($message, 'Wishlist');

        return back();
    }

    public function delete($id)
    {
        wishlist::where('id', $id)->where('user_id', auth()->id())->firstOrFail()->delete();
        notify()->success('Successfully Remove An Wishlist Product', 'Success');

        return back();

    }
}
