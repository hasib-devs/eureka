<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\CampaingProduct;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductPriceCalculator
{
    /**
     * Authoritative server-side unit price for a product, given the selected
     * attributes / colour / campaign carried on the request.
     *
     * This is the single source of truth for pricing: the AJAX price preview
     * (ProductController@getAttrPrice) and the Buy-Now flow
     * (OrderController@buyProduct) both call it, so the price shown always
     * equals the price charged. Never trust a client-supplied price — always
     * recompute here.
     */
    public function unitPrice(Request $request, Product $product): float
    {
        $price = 0;

        foreach (Attribute::all() as $attribute) {
            $hasAttribute = DB::table('attribute_product')
                ->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')
                ->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')
                ->where('attribute_product.product_id', $product->id)
                ->where('attributes.id', $attribute->id)
                ->exists();

            if ($hasAttribute) {
                $selectedValueId = $request->{$attribute->slug};
                if ($selectedValueId > 0) {
                    $attr = DB::table('attribute_product')
                        ->where('product_id', $product->id)
                        ->where('attribute_value_id', $selectedValueId)
                        ->first();
                    if ($attr) {
                        $price += $attr->price ?? 0;
                    }
                }
            }
        }

        $colorRow = null;
        $color = Color::where('slug', $request->color)->first();
        if (! empty($color)) {
            $colorRow = DB::table('color_product')
                ->where('product_id', $product->id)
                ->where('color_id', $color->id)
                ->first();
        }

        // Variable products: the chosen colour carries the FULL price (not a
        // surcharge on top of a base). Fall back to regular_price, which is the
        // first colour's price, when no colour is resolved.
        if ($product->is_variable) {
            return (float) (! empty($colorRow) && $colorRow->price > 0
                ? $colorRow->price
                : $product->regular_price);
        }

        if (isset($request->camp) && ($camp = CampaingProduct::find($request->camp))) {
            $base = $camp->price;
        } elseif (empty($product->discount_price)) {
            $base = $product->regular_price;
        } else {
            $base = $product->discount_price;
        }

        $price += $base + (! empty($colorRow) ? ($colorRow->price ?? 0) : 0);

        return (float) $price;
    }
}
