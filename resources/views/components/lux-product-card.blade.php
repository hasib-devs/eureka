@props(['product', 'category' => null])

@php
    // Resolve the primary product image (image column is a JSON array of filenames).
    $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
    if (! is_array($images)) {
        $images = ! empty($product->image) ? [$product->image] : [];
    }
    $mainImage = ! empty($images[0])
        ? asset('uploads/product/' . $images[0])
        : asset('frontend/images/placeholder.png');

    $price = $product->discount_price ?? $product->regular_price;

    // Section name wins (per-category sections), else the product's own category.
    $categoryName = $category
        ?? optional($product->categories->first())->name
        ?? 'Cozy Lighting';

    // Dynamic rating from the product's reviews.
    $reviewCount = $product->reviews->count();
    $avgRating = $reviewCount > 0 ? $product->reviews->sum('rating') / $reviewCount : 0;
    $fullStars = (int) round($avgRating);

    // Dynamic colour variants from the color_product pivot.
    $colors = $product->colors;
    $maxSwatches = 4;
@endphp

<div class="lux-product-card">
    <div class="lux-product-thumb">
        <a href="{{ url('product/' . $product->slug) }}">
            <img src="{{ $mainImage }}" alt="{{ $product->title }}">
        </a>
    </div>

    <div class="lux-product-info">
        <div class="lux-info-top-row">
            <div>
                <span class="lux-category-label">{{ $categoryName }}</span>
                <h2 class="lux-product-name">
                    <a href="{{ url('product/' . $product->slug) }}">{{ $product->title }}</a>
                </h2>
            </div>

            <button type="button" class="lux-wishlist-btn">
                Save to Wishlist ♡
            </button>
        </div>

        <div class="lux-variants-row">
            <div>
                <span class="lux-rating">{{ str_repeat('★', $fullStars) }}{{ str_repeat('☆', 5 - $fullStars) }}</span>
                <span class="lux-rating-count">
                    {{ $reviewCount > 0 ? $reviewCount . ' Review' . ($reviewCount > 1 ? 's' : '') : 'No reviews' }}
                </span>
            </div>

            @if ($colors->count() > 0)
                <div class="lux-color-variants">
                    @foreach ($colors->take($maxSwatches) as $color)
                        <span class="lux-color" style="background: {{ $color->code ?? '#ddd' }}"
                            title="{{ $color->name }}"></span>
                    @endforeach
                    @if ($colors->count() > $maxSwatches)
                        <span class="lux-rating-count">+{{ $colors->count() - $maxSwatches }}</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="lux-purchase-row">
            <p class="lux-product-price">৳ {{ number_format($price) }}</p>

            <form id="lux-cart-form-{{ $product->id }}" action="{{ route('add.cart') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="qty" value="1">

                <button type="button" class="lux-add-to-cart ajax-lux-cart-btn"
                    data-form-id="lux-cart-form-{{ $product->id }}">
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>
