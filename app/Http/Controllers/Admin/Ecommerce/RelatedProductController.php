<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class RelatedProductController extends Controller
{
    /**
     * List every product with a checkbox marking whether it appears in the
     * storefront "Related Products" section.
     */
    public function index()
    {
        $products = Product::query()
            ->select('id', 'title', 'image', 'regular_price', 'discount_price', 'is_related', 'status')
            ->latest('id')
            ->get();

        return view('admin.e-commerce.related-product.index', compact('products'));
    }

    /**
     * Save the selection: the submitted ids become the related set; everything
     * else is unmarked.
     */
    public function update(Request $request)
    {
        $request->validate([
            'related_ids' => 'nullable|array',
            'related_ids.*' => 'integer',
        ]);

        $ids = array_map('intval', $request->input('related_ids', []));

        // Reset all, then flag the chosen ones — keeps the set exactly in sync
        // with the checkboxes, including when everything is unchecked.
        Product::query()->update(['is_related' => false]);

        if (! empty($ids)) {
            Product::whereIn('id', $ids)->update(['is_related' => true]);
        }

        notify()->success('Related products updated.');

        return back();
    }
}
