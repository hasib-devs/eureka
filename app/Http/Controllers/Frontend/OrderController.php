<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Core\ShoppingCart\Facades\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function buyProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'qty' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->id);
        $qty = $request->qty ?? 1;

        $price = $request->dynamic_price
            ?? $product->discount_price
            ?? $product->regular_price;

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
}

/**
* Note: This file may contain artifacts of previous malicious infection.
* <?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\DownloadProduct;
use App\Models\DownloadUserProduct;
use App\Models\Order;
use App\Models\CartInfo;
use App\Models\PartialPayment;
use App\Models\Product;
use App\Models\Review;
use App\Models\Attribute;
use App\Models\User;
use App\Models\IpBlock;
use App\Models\VendorAccount;
// use Gloudemans\Shoppingcart\Facades\Cart;
use App\Core\ShoppingCart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Library\UddoktaPay;
use ZipArchive;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
* However, the dangerous code has been removed, and the file is now safe to use.
*/
