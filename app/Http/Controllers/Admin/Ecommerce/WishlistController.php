<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index()
    {
        $topProducts = wishlist::query()
            ->select('product_id', DB::raw('COUNT(*) as saves'))
            ->groupBy('product_id')
            ->orderByDesc('saves')
            ->with('product:id,title,slug,image')
            ->take(20)
            ->get();

        return view('admin.e-commerce.wishlist.index', [
            'enabled' => setting('wishlist_status', '1') !== '0',
            'totalSaves' => wishlist::count(),
            'customers' => wishlist::distinct()->count('user_id'),
            'productsWishlisted' => wishlist::distinct()->count('product_id'),
            'topProducts' => $topProducts,
        ]);
    }

    public function update(Request $request)
    {
        Setting::updateOrCreate(
            ['name' => 'wishlist_status'],
            ['value' => $request->boolean('wishlist_status') ? '1' : '0']
        );

        notify()->success('Wishlist settings saved');

        return back();
    }
}
