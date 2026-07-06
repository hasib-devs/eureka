@extends('layouts.frontend.app')

@section('title', $product->title)

@push('meta')
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($product->short_description ?? $product->full_description ?? $product->title), 150) }}">
@endpush

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Inter:wght@300;400;500;600&display=swap');

:root {
    --accent-color: #FFCC00;
    --deep-black: #000000;
    --soft-gray: #707070;
    --border-color: #e5e5e5;
    --bg-light: #fafafa;
    --ease: cubic-bezier(0.23, 1, 0.32, 1);
}

.boutique-wrapper {
    display: flex;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
    gap: 80px;
    font-family: 'Inter', sans-serif;
    background: #fff;
}

.gallery-column {
    flex: 1.2;
    display: flex;
    flex-direction: column;
    gap: 20px;
    min-width: 350px;
}

.featured-stage {
    width: 100%;
    position: relative;
    overflow: hidden;
    border-radius: 2px;
    background: #fafafa;
    aspect-ratio: 1 / 1;
}

.featured-stage img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.8s var(--ease);
}

.featured-stage:hover img {
    transform: scale(1.03);
}

.thumbnail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
.thumbnail-grid img,
.thumbnail-grid video,
.thumbnail-grid iframe {
    width: 100%;
    height: 280px;
    object-fit: cover;
    cursor: pointer;
    opacity: 0.8;
    transition: var(--ease);
    border: 1px solid transparent;
    border-radius: 4px;
}

/* Video/YouTube tile: same-size frame as the image thumbnails. */
.thumbnail-grid .thumb-video {
    background: #000;
    opacity: 1;
    cursor: default;
    border: none;
    outline: none;
}

.thumbnail-grid img:hover,
.thumbnail-grid img.active {
    opacity: 1;
    border-color: var(--accent-color);
    transform: translateY(-5px);
}

.info-column {
    flex: 1;
    min-width: 350px;
    display: flex;
    flex-direction: column;
}

.category-label {
    font-size: 11px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--soft-gray);
    margin-bottom: 15px;
}

.brand-title {
    font-family: 'Cinzel Decorative', cursive;
    font-size: 42px;
    line-height: 1.2;
    color: var(--deep-black);
    margin: 0 0 20px 0;
    letter-spacing: -1px;
}

.rating-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
    font-size: 13px;
    color: var(--soft-gray);
}

.star-gold {
    color: var(--accent-color);
    font-size: 16px;
}

.price-tag {
    font-size: 28px;
    font-weight: 300;
    color: var(--deep-black);
    margin-bottom: 35px;
}

.price-tag del {
    color: #999;
    font-size: 20px;
    margin-right: 10px;
}

.specs-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    border-top: 1px solid var(--border-color);
    padding-top: 30px;
    margin-bottom: 40px;
}

.spec-item label {
    display: block;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--soft-gray);
    margin-bottom: 5px;
}

.spec-item span {
    font-size: 15px;
    color: var(--deep-black);
    font-weight: 500;
}

.color-selection-container {
    margin: 30px 0;
    border-top: 1px solid var(--border-color);
    padding-top: 25px;
}

.selection-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--soft-gray);
    margin-bottom: 15px;
    display: block;
}

.swatch-group {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.swatch-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.color-swatch {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 1px solid #e0e0e0;
    position: relative;
    transition: var(--ease);
    background-clip: content-box;
    padding: 2px;
}

.swatch-wrapper.active .color-swatch {
    border-color: var(--deep-black);
    padding: 3px;
    transform: scale(1.1);
}

.swatch-name {
    font-size: 11px;
    color: var(--soft-gray);
    text-transform: capitalize;
    opacity: 0;
    transition: var(--ease);
}

.swatch-wrapper:hover .swatch-name,
.swatch-wrapper.active .swatch-name {
    opacity: 1;
}

.action-group {
    display: flex;
    gap: 15px;
    margin-bottom: 50px;
}

.btn-lux {
    flex: 1;
    padding: 20px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    transition: all 0.4s var(--ease);
    text-align: center;
}

.btn-dark {
    background: var(--deep-black);
    color: #fff;
}

.btn-gold {
    background: var(--accent-color);
    color: #000;
}

.btn-lux:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    opacity: 0.9;
}

.luxe-accordion {
    border-top: 1px solid var(--border-color);
}

details {
    border-bottom: 1px solid var(--border-color);
}

summary {
    list-style: none;
    padding: 25px 0;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

summary::-webkit-details-marker {
    display: none;
}

summary::after {
    content: '\002B';
    font-size: 18px;
    font-weight: 300;
}

details[open] summary::after {
    content: '\2212';
}

.details-content {
    padding-bottom: 30px;
    font-size: 15px;
    color: #555;
    line-height: 1.8;
}

/* ===== Tablet ===== */
@media (max-width: 991px) {
    .boutique-wrapper {
        gap: 40px;
        padding: 24px 18px;
        margin: 10px auto;
    }

    .gallery-column,
    .info-column {
        min-width: 100%;
        flex: 1 1 100%;
    }

    .brand-title {
        font-size: 32px;
    }

    .thumbnail-grid img,
    .thumbnail-grid video {
        height: 200px;
    }
}

/* ===== Mobile ===== */
@media (max-width: 600px) {
    .boutique-wrapper {
        gap: 26px;
        padding: 16px 14px;
        margin: 0 auto;
    }

    .brand-title {
        font-size: 24px;
        letter-spacing: -0.5px;
    }

    .category-label {
        letter-spacing: 3px;
        margin-bottom: 10px;
    }

    .price-tag {
        font-size: 22px;
        margin-bottom: 24px;
    }

    .price-tag del {
        font-size: 16px;
    }

    .rating-box {
        margin-bottom: 18px;
    }

    .thumbnail-grid {
        gap: 10px;
    }

    .thumbnail-grid img,
    .thumbnail-grid video,
    .thumbnail-grid iframe {
        height: 150px;
    }

    .specs-grid {
        gap: 18px;
        padding-top: 22px;
        margin-bottom: 28px;
    }

    .spec-item span {
        font-size: 14px;
    }

    .color-selection-container {
        margin: 22px 0;
        padding-top: 20px;
    }

    .swatch-name {
        opacity: 1;
    }

    .action-group {
        flex-direction: column;
        gap: 12px;
        margin-bottom: 34px;
    }

    .btn-lux {
        padding: 16px;
    }

    summary {
        padding: 20px 0;
        font-size: 12px;
    }

    .details-content {
        font-size: 14px;
        line-height: 1.7;
    }
}
</style>

@php
    $galleryImages = collect();

    // Main image is stored as a single filename in products.image
    // (older records may hold a JSON array, so handle both).
    $productImages = is_array($product->image) ? $product->image : json_decode($product->image, true);
    if (!is_array($productImages)) {
        $productImages = !empty($product->image) ? [$product->image] : [];
    }
    foreach ($productImages as $img) {
        if (!empty($img)) {
            $galleryImages->push(asset('uploads/product/' . $img));
        }
    }

    // Extra gallery images live in the product_images table (images() relation).
    foreach ($product->images as $img) {
        if (!empty($img->name)) {
            $galleryImages->push(asset('uploads/product/' . $img->name));
        }
    }

    $galleryImages = $galleryImages->unique()->values();
    $mainImage = $galleryImages->first() ?? asset('frontend/images/placeholder.png');

    // A product video can be either an uploaded MP4 (products.video) or a
    // YouTube embed URL (products.yvideo). Handle both so the gallery shows
    // whichever the admin added.
    $productVideoFile = !empty($product->video)
        ? (str_starts_with($product->video, 'http') ? $product->video : asset('uploads/product/video/' . $product->video))
        : null;
    $productVideoYoutube = !empty($product->yvideo) ? $product->yvideo : null;
    $productVideoThumb = !empty($product->video_thumb)
        ? (str_starts_with($product->video_thumb, 'http') ? $product->video_thumb : asset('uploads/product/video/' . $product->video_thumb))
        : null;

    $finalPrice = $product->discount_price ?: $product->regular_price;
    $reviewCount = $product->reviews ? $product->reviews->count() : 0;
    $firstCategory = optional($product->categories->first())->name ?? 'Premium Product';
@endphp

<div class="boutique-wrapper">
    <div class="gallery-column">
        <div class="featured-stage">
            <img id="featuredProductImage" src="{{ $mainImage }}" alt="{{ $product->title }}">
        </div>

        <div class="thumbnail-grid">
            @forelse($galleryImages as $key => $image)
                <img
                    src="{{ $image }}"
                    alt="{{ $product->title }} image {{ $key + 1 }}"
                    class="{{ $key === 0 ? 'active' : '' }}"
                    onclick="changeProductImage(this)"
                >
            @empty
                <img src="{{ $mainImage }}" alt="{{ $product->title }}" class="active" onclick="changeProductImage(this)">
            @endforelse
            @if(!empty($productVideoFile))
                <video class="thumb-video" controls muted loop playsinline
                    @if(!empty($productVideoThumb)) poster="{{ $productVideoThumb }}" @endif>
                    <source src="{{ $productVideoFile }}" type="video/mp4">
                </video>
            @endif
            @if(!empty($productVideoYoutube))
                <iframe class="thumb-video" src="{{ $productVideoYoutube }}"
                    title="{{ $product->title }} video"
                    loading="lazy" allowfullscreen
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            @endif
        </div>
    </div>

    <div class="info-column">
        <span class="category-label">{{ $firstCategory }}</span>

        <h1 class="brand-title">{{ $product->title }}</h1>

        <div class="rating-box">
            <span class="star-gold">★★★★★</span>
            <span>({{ $reviewCount }} VERIFIED REVIEWS)</span>
        </div>

        <div class="price-tag">
            @if($product->discount_price)
                <del>{{ setting('CURRENCY_CODE_MIN') ?? '৳' }} {{ $product->regular_price }}</del>
                {{ setting('CURRENCY_CODE_MIN') ?? '৳' }} {{ $product->discount_price }}
            @else
                {{ setting('CURRENCY_CODE_MIN') ?? '৳' }} {{ $product->regular_price }}
            @endif
        </div>

        <div class="specs-grid">
            <div class="spec-item">
                <label>Product Code</label>
                <span>{{ $product->sku ?? $product->id }}</span>
            </div>
            <div class="spec-item">
                <label>Stock</label>
                <span>{{ ($product->quantity ?? 0) > 0 ? 'Available' : 'Out of Stock' }}</span>
            </div>
            <div class="spec-item">
                <label>Delivery</label>
                <span>Cash on Delivery</span>
            </div>
            <div class="spec-item">
                <label>Origin</label>
                <span>Bangladesh</span>
            </div>
        </div>

        @if(isset($colors_product) && $colors_product->count() > 0)
            <div class="color-selection-container">
                <span class="selection-label">Select Color</span>

                <div class="swatch-group">
                    @foreach($colors_product as $key => $color)
                        <div class="swatch-wrapper {{ $key === 0 ? 'active' : '' }}" data-color="{{ $color->slug ?? $color->name }}">
                            <div class="color-swatch" style="background: {{ $color->code ?? $color->color_code ?? '#ddd' }}"></div>
                            <span class="swatch-name">{{ $color->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form id="luxProductForm" action="{{ route('add.cart') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
            <input type="hidden" name="qty" value="1">
            <input type="hidden" name="color" id="selectedColor" value="{{ isset($colors_product) && $colors_product->count() > 0 ? ($colors_product->first()->slug ?? $colors_product->first()->name) : 'blank' }}">
            <input type="hidden" name="size" value="blank">
            <input type="hidden" name="dynamic_prices" value="{{ $finalPrice }}">

            @if(isset($attributeGroups) && $attributeGroups->count() > 0)
                @foreach($attributeGroups as $attributeId => $values)
                    @php $attribute = optional($values->first())->attributes; @endphp
                    @if($attribute)
                        <input type="hidden" name="{{ $attribute->slug }}" value="{{ optional($values->first())->id ?? 'blank' }}">
                    @endif
                @endforeach
            @endif

            <div class="action-group">
                <button type="submit" class="btn-lux btn-dark" onclick="setAddToCartMode()">Add To Cart</button>
                <button type="button" class="btn-lux btn-gold" onclick="buyNowProduct()">Buy It Now</button>
            </div>
        </form>

        <div class="luxe-accordion">
            <details open>
                <summary>The Design Story</summary>
                <div class="details-content">
                    {!! $product->short_description ?? $product->full_description ?? 'Premium quality product with modern finishing and reliable performance.' !!}
                </div>
            </details>

            <details>
                <summary>Technical Details</summary>
                <div class="details-content">
                    {!! $product->full_description ?? $product->short_description ?? 'Product details will be updated soon.' !!}
                </div>
            </details>

            <details>
                <summary>Shipping & Concierge</summary>
                <div class="details-content">
                    Delivered via courier. Cash on Delivery available across Bangladesh.
                </div>
            </details>

            <details>
                <summary>Warranty & Returns</summary>
                <div class="details-content">
                    Warranty and return policy depends on product condition and seller policy.
                </div>
            </details>
        </div>
    </div>
</div>

<script>
function changeProductImage(el) {
    const featured = document.getElementById('featuredProductImage');
    featured.src = el.src;

    document.querySelectorAll('.thumbnail-grid img').forEach(img => img.classList.remove('active'));
    el.classList.add('active');
}

document.querySelectorAll('.swatch-wrapper').forEach(function (swatch) {
    swatch.addEventListener('click', function () {
        document.querySelectorAll('.swatch-wrapper').forEach(item => item.classList.remove('active'));
        this.classList.add('active');

        const selectedColor = document.getElementById('selectedColor');
        if (selectedColor) {
            selectedColor.value = this.dataset.color || 'blank';
        }
    });
});

function setAddToCartMode() {
    setTimeout(function () {
        window.location.href = "{{ route('cart') }}";
    }, 500);
}

function buyNowProduct() {
    const form = document.getElementById('luxProductForm');
    form.action = "{{ route('buy.product') }}";
    form.method = "GET";
    form.submit();
}
</script>

@endsection
