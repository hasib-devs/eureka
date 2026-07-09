@extends('layouts.frontend.app')

@section('title', $product->title)

@push('meta')
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($product->short_description ?? $product->full_description ?? $product->title), 150) }}">
@endpush

@push('css')
    {{-- Related Products slider — mirrors the homepage "Cozy Lighting" section.
         Scoped so it can never touch the rest of the product page. --}}
    <style>
        .related-products-section { width: 100%; background: #fff; padding: 0 0 66px; }
        .related-products-section .lux-shop-header { padding: 72px 20px 52px; text-align: center; background: #fff; }
        .related-products-section .lux-shop-header p { margin: 0 0 18px; color: #707070; font-size: 12px; letter-spacing: 7px; font-weight: 400; }
        .related-products-section .lux-shop-header h1 {
            margin: 0; color: #111; font-family: 'Cinzel Decorative', Georgia, serif;
            font-size: 52px; font-weight: 700; line-height: 1; letter-spacing: 1px; text-transform: uppercase;
        }
        .related-products-section .product-slider .slick-slide { padding: 0 10px; }
        .related-products-section .product-slider .slick-list { margin: 0 -10px; }
        @media (max-width: 768px) {
            .related-products-section .lux-shop-header { padding: 50px 16px 38px; }
            .related-products-section .lux-shop-header p { font-size: 11px; letter-spacing: 4px; }
            .related-products-section .lux-shop-header h1 { font-size: 34px; }
            .related-products-section { padding: 0 20px 50px; }
        }
    </style>
@endpush

@php
    use App\Http\Controllers\Frontend\ProductReviewController;

    $currency = setting('CURRENCY_CODE_MIN') ?? '৳';

    $mainImage = $product->hero_image_url;
    $galleryImages = $product->gallery_image_urls;
    $lifestyleImages = $product->lifestyle_images;

    $productVideoFile = $product->playable_video_url;
    $productVideoYoutube = !empty($product->yvideo) ? $product->yvideo : null;
    $productVideoThumb = !empty($product->video_thumb)
        ? (str_starts_with($product->video_thumb, 'http') ? $product->video_thumb : asset('uploads/product/video/' . $product->video_thumb))
        : null;

    $finalPrice = $product->discount_price ?: $product->regular_price;
    $giftWrapFee = (float) config('shop.gift_wrap_fee');

    $firstCategory = optional($product->categories->first())->name ?? 'Premium Product';
    $inStock = ($product->quantity ?? 0) > 0;

    // Colors: name + hex from color_product; glow ring uses the hex with alpha.
    // For variable products each colour also carries its own image/price/stock.
    $swatches = collect($colors_product ?? [])->map(fn ($c) => [
        'slug' => $c->slug ?? $c->name,
        'name' => $c->name,
        'code' => $c->code ?? '#dddddd',
        'img' => ! empty($c->image) ? asset('uploads/product/' . $c->image) : null,
        'price' => isset($c->price) ? (float) $c->price : null,
        'qty' => isset($c->qnty) ? (int) $c->qnty : null,
    ])->values();

    $isVariable = (bool) ($product->is_variable ?? false);
    // Variable products start with no colour selected: the stage shows the
    // product's own main image and the gallery is the normal thumbnail strip.
    // Colour images appear only when a swatch is clicked.
    $defaultSwatch = $isVariable ? null : $swatches->first();

    // Reviews shaped for the client-side list (filter/sort/load-more).
    $reviewsData = $product->reviews->sortByDesc('created_at')->map(
        fn ($review) => ProductReviewController::presentReview($review)
    )->values();

    $trustBadges = array_values(array_filter([
        setting('TRUST_BADGE_1'),
        setting('TRUST_BADGE_2'),
        setting('TRUST_BADGE_3'),
    ]));

    // Client requirement: the product name renders on two lines
    // (e.g. "Aurora" / "Swirl Lamp"). Split at the word midpoint; the
    // <br> collapses on mobile where the title sits beside the stock note.
    $titleWords = preg_split('/\s+/', trim($product->title)) ?: [];
    $titleSplitAt = count($titleWords) > 1 ? max(1, (int) floor(count($titleWords) / 2)) : count($titleWords);
    $titleLine1 = implode(' ', array_slice($titleWords, 0, $titleSplitAt));
    $titleLine2 = implode(' ', array_slice($titleWords, $titleSplitAt));

    // Per-product content wins; fall back to the global setting so existing products keep their text.
    $shippingText = filled($product->shipping_concierge) ? $product->shipping_concierge : setting('PRODUCT_SHIPPING_TEXT');
    $warrantyText = filled($product->warranty_returns) ? $product->warranty_returns : setting('PRODUCT_WARRANTY_TEXT');
    $specs = $product->specs;
@endphp

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Inter:wght@300;400;500;600;700&display=swap');

:root {
    --accent-color: #FFCC00;
    --accent-deep: #C9A227;
    --deep-black: #0a0a0a;
    --soft-gray: #707070;
    --faint-gray: #999;
    --border-color: #e8e8e6;
    --bg-light: #fafafa;
    --ease: cubic-bezier(0.23, 1, 0.32, 1);
}

/* The client's design sits on a plain white page — kill the theme's gray
   body backdrop and the layout's extra side padding so nothing reads as a
   boxed container. Scoped: this style block only ships with this page. */
body {
    background: #fff !important;
}
.site-inner {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.boutique-wrapper {
    display: flex;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 90px auto 50px;
    padding: 0 20px;
    gap: 80px;
    font-family: 'Inter', sans-serif;
    background: #fff;
}

/* ===== GALLERY ===== */
.gallery-column { flex: 1.2; display: flex; flex-direction: column; gap: 20px; min-width: 350px; }
.featured-stage {
    width: 100%; position: relative; overflow: hidden; border-radius: 2px;
    background: var(--bg-light); aspect-ratio: 3/4; cursor: zoom-in;
    transition: box-shadow 0.5s var(--ease);
}
.featured-stage img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.8s var(--ease); animation: stageBreathe 12s ease-in-out infinite; }
.featured-stage:hover img { animation: none; transform: scale(1.05); }
/* Hover-peek overlay: the next photo sits on top of the stage and cross-fades in. */
.featured-stage img.featured-peek {
    position: absolute; top: 0; left: 0; opacity: 0; pointer-events: none;
    transition: transform 0.8s var(--ease), opacity 0.5s var(--ease);
}
.featured-stage.is-peeking img.featured-peek { opacity: 1; }
@keyframes stageBreathe {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.035); }
}
@media (prefers-reduced-motion: reduce) {
    .featured-stage img { animation: none; }
    .featured-stage img.featured-peek { transition: none; }
}
.stage-badge {
    position: absolute; top: 18px; left: 18px; background: var(--deep-black); color: var(--accent-color);
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase; padding: 8px 14px; z-index: 2;
}
.thumbnail-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.thumbnail-grid .thumb-box {
    width: 100%; aspect-ratio: 3/4; background: var(--bg-light); overflow: hidden;
    cursor: pointer; opacity: 0.75; transition: var(--ease); border: 1px solid transparent;
}
.thumbnail-grid .thumb-box img { width: 100%; height: 100%; object-fit: cover; display: block; }
.thumbnail-grid .thumb-box:hover, .thumbnail-grid .thumb-box.active {
    opacity: 1; border-color: var(--accent-color); transform: translateY(-4px);
}

/* ===== INFO ===== */
.info-column { flex: 1; min-width: 350px; display: flex; flex-direction: column; }
.category-label { font-size: 11px; letter-spacing: 4px; text-transform: uppercase; color: var(--soft-gray); margin-bottom: 15px; }
.brand-title {
    font-family: 'Cinzel Decorative', cursive; font-size: 42px; line-height: 1.2;
    color: var(--deep-black); margin: 0 0 20px 0; letter-spacing: -1px;
}
.rating-box { display: flex; align-items: center; gap: 10px; margin-bottom: 25px; font-size: 13px; color: var(--soft-gray); cursor: pointer; width: fit-content; }
.rating-box:hover { color: var(--deep-black); }
.star-gold { color: var(--accent-color); font-size: 16px; letter-spacing: 2px; }
.price-tag { font-size: 28px; font-weight: 300; color: var(--deep-black); margin-bottom: 8px; }
.price-tag del { color: #999; font-size: 20px; margin-right: 10px; }
.stock-note { font-size: 12px; letter-spacing: 0.5px; margin-bottom: 30px; display: flex; align-items: center; gap: 6px; }
.stock-note.in { color: #3a7d44; }
.stock-note.out { color: #b23b3b; }
.stock-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; background: currentColor; }

.specs-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 30px;
    border-top: 1px solid var(--border-color); padding-top: 30px; margin-bottom: 40px;
}
.spec-item label { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: var(--soft-gray); margin-bottom: 5px; }
.spec-item span { font-size: 15px; color: var(--deep-black); font-weight: 500; }

.color-selection-container { margin-bottom: 35px; }
.selection-label { font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: var(--soft-gray); margin-bottom: 15px; display: block; }
.swatch-group { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
.swatch-wrapper { display: flex; flex-direction: column; align-items: center; gap: 8px; cursor: pointer; width: 38px; }
.color-swatch { width: 38px; height: 38px; border-radius: 50%; border: 1.5px solid #c2c2c0; transition: var(--ease); background-clip: content-box; padding: 2px; background-color: #fff; }
.swatch-wrapper:hover .color-swatch { border-color: #8a8a88; }
.swatch-wrapper.active .color-swatch { border: 1.5px solid var(--accent-deep); padding: 2px; transform: scale(1.12); }
.swatch-name { font-size: 11px; color: var(--soft-gray); text-transform: capitalize; opacity: 0; transition: var(--ease); white-space: nowrap; }
.swatch-wrapper:hover .swatch-name, .swatch-wrapper.active .swatch-name { opacity: 1; }
.color-live-readout { display: flex; align-items: center; gap: 8px; margin-top: 14px; font-size: 12px; color: var(--soft-gray); transition: var(--ease); }
.color-live-readout .live-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(0,0,0,0.05); transition: var(--ease); }
.color-live-readout .live-name { color: var(--deep-black); font-weight: 600; }

/* ===== GIFT WRAP ===== */
.gift-wrap-row { display: flex; align-items: center; gap: 14px; cursor: pointer; width: fit-content; }
.gift-circle-btn {
    position: relative; width: 54px; height: 54px; border-radius: 50%; background: #fff;
    border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center;
    transition: var(--ease); flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.gift-circle-btn svg { width: 26px; height: 26px; stroke: var(--soft-gray); fill: none; transition: var(--ease); }
.gift-wrap-row:hover .gift-circle-btn { border-color: var(--accent-color); }
.gift-wrap-row.active .gift-circle-btn {
    background: var(--accent-color); border-color: var(--accent-deep); transform: scale(1.08);
    box-shadow: 0 0 0 5px rgba(255,204,0,0.22), 0 6px 16px rgba(201,162,39,0.35);
}
.gift-wrap-row.active .gift-circle-btn svg { stroke: #000; }
.gift-check-badge {
    position: absolute; bottom: -2px; right: -2px; width: 18px; height: 18px; border-radius: 50%;
    background: var(--deep-black); color: var(--accent-color); font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; opacity: 0; transform: scale(0.4);
    transition: var(--ease);
}
.gift-wrap-row.active .gift-check-badge { opacity: 1; transform: scale(1); }
.gift-wrap-text { display: flex; flex-direction: column; gap: 3px; }
.gift-wrap-text .gift-title { font-size: 13px; font-weight: 600; color: var(--deep-black); }
.gift-wrap-text .gift-sub { font-size: 11px; color: var(--soft-gray); }

.order-summary-box {
    border-top: 1px solid var(--border-color); padding-top: 16px; margin-bottom: 22px;
    display: flex; flex-direction: column; gap: 8px; font-size: 13px;
}
.order-summary-box .sum-row { display: flex; justify-content: space-between; color: var(--soft-gray); }
.order-summary-box .sum-row.total { color: var(--deep-black); font-weight: 700; font-size: 16px; padding-top: 8px; border-top: 1px dashed var(--border-color); }
.order-summary-box .sum-row span.gift-line { color: var(--accent-deep); }

.qty-gift-row { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 18px; }
.qty-stepper { display: flex; align-items: center; border: 1px solid var(--deep-black); }
.qty-stepper button { width: 42px; height: 58px; background: none; border: none; font-size: 16px; cursor: pointer; }
.qty-stepper span { width: 40px; text-align: center; font-size: 14px; display: inline-block; }
.action-group { display: flex; gap: 15px; margin-bottom: 18px; }
.btn-lux {
    flex: 1; padding: 20px; border: none; cursor: pointer; font-size: 12px; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; transition: all 0.4s var(--ease); text-align: center;
}
.btn-dark { background: var(--deep-black); color: #fff; }
.btn-gold { background: var(--accent-color); color: #000; }
.btn-lux:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.12); opacity: 0.92; }
.btn-lux:disabled { opacity: 0.4; cursor: not-allowed; transform: none; box-shadow: none; }

.trust-row { display: flex; gap: 22px; margin-bottom: 45px; padding-top: 5px; flex-wrap: wrap; }
.trust-item { display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--soft-gray); letter-spacing: 0.5px; }
.trust-item svg { width: 16px; height: 16px; stroke: var(--soft-gray); flex-shrink: 0; }

.luxe-accordion { border-top: 1px solid var(--border-color); }
.luxe-accordion details { border-bottom: 1px solid var(--border-color); }
.luxe-accordion summary { list-style: none; padding: 25px 0; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; }
.luxe-accordion summary::-webkit-details-marker { display: none; }
.luxe-accordion summary::after { content: '\002B'; font-size: 18px; font-weight: 300; }
.luxe-accordion details[open] summary::after { content: '\2212'; }
.details-content { padding-bottom: 30px; font-size: 15px; color: #555; line-height: 1.8; }

/* ===== LIFESTYLE ===== */
.lifestyle-section { max-width: 1200px; margin: 20px auto 100px; padding: 0 20px; font-family: 'Inter', sans-serif; }
.section-heading { text-align: center; margin-bottom: 45px; }
.section-heading .category-label { justify-content: center; display: flex; }
.section-heading h2 { font-family: 'Cinzel Decorative', cursive; font-size: 30px; color: var(--deep-black); margin: 10px 0 0; }
.lifestyle-scroll-wrap { position: relative; }
.lifestyle-grid {
    display: flex; gap: 18px; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 10px;
    scrollbar-width: thin; scrollbar-color: var(--accent-color) var(--bg-light); -webkit-overflow-scrolling: touch;
}
.lifestyle-grid::-webkit-scrollbar { height: 6px; }
.lifestyle-grid::-webkit-scrollbar-thumb { background: var(--accent-color); }
.lifestyle-grid::-webkit-scrollbar-track { background: var(--bg-light); }
.lifestyle-tile {
    position: relative; overflow: hidden; background: var(--deep-black); cursor: pointer;
    flex: 0 0 auto; width: 380px; aspect-ratio: 3/4; scroll-snap-align: start;
}
.lifestyle-tile img, .lifestyle-tile video, .lifestyle-tile iframe { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 1s var(--ease), filter 1s var(--ease); filter: brightness(0.88); border: 0; }
.lifestyle-tile:hover img, .lifestyle-tile:hover video { transform: scale(1.08); filter: brightness(1.05); }
.lifestyle-caption {
    position: absolute; left: 0; right: 0; bottom: 0; padding: 20px;
    background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%); color: #fff; pointer-events: none;
}
.lifestyle-caption .tag { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-color); display: block; margin-bottom: 6px; }
.lifestyle-caption .cap-title { font-size: 16px; font-weight: 500; }
.video-tile .play-button {
    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: 58px; height: 58px; border-radius: 50%; background: rgba(255,204,0,0.92);
    display: flex; align-items: center; justify-content: center; transition: var(--ease); opacity: 1;
}
.video-tile:hover .play-button { transform: translate(-50%, -50%) scale(1.1); }
.video-tile .play-button svg { width: 20px; height: 20px; fill: #000; margin-left: 3px; }
.video-tile.is-playing .play-button { opacity: 0; pointer-events: none; }
.scroll-hint-arrows { display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px; }
.scroll-arrow-btn {
    width: 40px; height: 40px; border: 1px solid var(--border-color); background: #fff; cursor: pointer;
    font-size: 16px; display: flex; align-items: center; justify-content: center; transition: var(--ease);
}
.scroll-arrow-btn:hover { background: var(--deep-black); color: #fff; border-color: var(--deep-black); }
@media (min-width: 901px) {
    .lifestyle-tile { width: 560px; }
}

/* ===== REVIEWS ===== */
.reviews-section { max-width: 1200px; margin: 0 auto 120px; padding: 0 20px; font-family: 'Inter', sans-serif; }
.reviews-header {
    display: flex; gap: 70px; flex-wrap: wrap; justify-content: center;
    border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);
    padding: 55px 0; margin-bottom: 50px;
}
.reviews-summary { flex: 0 0 260px; text-align: center; }
.reviews-summary .avg-num { font-family: 'Cinzel Decorative', cursive; font-size: 56px; color: var(--deep-black); line-height: 1; }
.reviews-summary .star-gold { font-size: 20px; display: block; margin: 12px 0 8px; }
.reviews-summary .total-count { font-size: 12px; color: var(--soft-gray); letter-spacing: 1px; text-transform: uppercase; }
.btn-write-review {
    margin-top: 24px; width: 100%; padding: 16px; background: var(--deep-black); color: #fff; border: none;
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; transition: var(--ease);
}
.btn-write-review:hover { background: var(--accent-deep); color: #000; }
.reviews-breakdown { flex: 0 0 auto; width: 34%; min-width: 240px; display: flex; flex-direction: column; justify-content: center; gap: 10px; }
.bar-row { display: grid; grid-template-columns: 48px 1fr 28px; align-items: center; gap: 10px; font-size: 11px; color: var(--soft-gray); cursor: pointer; }
.bar-row:hover { color: var(--deep-black); }
.bar-track { height: 8px; border-radius: 4px; background: var(--border-color); position: relative; overflow: hidden; }
.bar-fill { position: absolute; left: 0; top: 0; bottom: 0; border-radius: 4px; background: var(--accent-color); transition: width 0.6s var(--ease); }
.reviews-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 16px; }
.reviews-toolbar h3 { font-family: 'Cinzel Decorative', cursive; font-size: 22px; margin: 0; }
.toolbar-controls { display: flex; gap: 12px; align-items: center; font-size: 12px; }
.toolbar-controls select { padding: 10px 14px; border: 1px solid var(--border-color); background: #fff; font-family: inherit; font-size: 12px; letter-spacing: 0.5px; cursor: pointer; }
.filter-chip { padding: 8px 14px; border: 1px solid var(--border-color); background: #fff; font-size: 11px; cursor: pointer; letter-spacing: 0.5px; transition: var(--ease); }
.filter-chip.active { background: var(--deep-black); color: #fff; border-color: var(--deep-black); }
.review-list { display: flex; flex-direction: column; }
.review-card { padding: 34px 0; border-bottom: 1px solid var(--border-color); display: flex; gap: 22px; }
.review-avatar {
    width: 46px; height: 46px; border-radius: 50%; background: var(--deep-black); color: var(--accent-color);
    display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 600; flex-shrink: 0;
}
.review-body { flex: 1; }
.review-top-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; margin-bottom: 6px; }
.reviewer-name-row { display: flex; align-items: center; gap: 8px; }
.reviewer-name { font-size: 14px; font-weight: 600; color: var(--deep-black); }
.verified-badge { font-size: 9px; letter-spacing: 1px; text-transform: uppercase; color: #3a7d44; background: #eaf5ec; padding: 3px 8px; display: inline-flex; align-items: center; gap: 4px; }
.review-date { font-size: 11px; color: var(--faint-gray); }
.review-stars { font-size: 13px; color: var(--accent-color); margin-bottom: 10px; letter-spacing: 1px; }
.review-title { font-size: 15px; font-weight: 600; color: var(--deep-black); margin: 0 0 8px; }
.review-text { font-size: 14px; color: #444; line-height: 1.75; margin: 0 0 16px; }
.review-photos { display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
.review-photos img { width: 72px; height: 72px; object-fit: cover; cursor: pointer; transition: var(--ease); border: 1px solid var(--border-color); }
.review-photos img:hover { transform: translateY(-3px); border-color: var(--accent-color); }
.review-actions { display: flex; align-items: center; gap: 18px; }
.helpful-btn {
    background: none; border: 1px solid var(--border-color); padding: 8px 14px; font-size: 11px;
    letter-spacing: 0.5px; color: var(--soft-gray); cursor: pointer; display: flex; align-items: center; gap: 6px; transition: var(--ease);
}
.helpful-btn:hover, .helpful-btn.voted { border-color: var(--deep-black); color: var(--deep-black); }
.helpful-btn svg { width: 13px; height: 13px; }
.load-more-wrap { text-align: center; margin-top: 40px; }
.btn-load-more {
    padding: 16px 40px; background: none; border: 1px solid var(--deep-black); font-size: 11px;
    letter-spacing: 2px; text-transform: uppercase; cursor: pointer; transition: var(--ease);
}
.btn-load-more:hover { background: var(--deep-black); color: #fff; }
.no-reviews-msg { text-align: center; padding: 60px 20px; color: var(--soft-gray); font-size: 14px; }

/* ===== REVIEW MODAL ===== */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(10,10,10,0.6); z-index: 1000;
    display: none; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(3px);
}
.modal-overlay.open { display: flex; }
.review-modal {
    background: #fff; max-width: 560px; width: 100%; max-height: 90vh; overflow-y: auto;
    padding: 45px; position: relative; animation: modalIn 0.35s var(--ease); font-family: 'Inter', sans-serif;
}
@keyframes modalIn { from { opacity: 0; transform: translateY(20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
.modal-close { position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 22px; cursor: pointer; color: var(--soft-gray); line-height: 1; }
.modal-close:hover { color: var(--deep-black); }
.review-modal h3 { font-family: 'Cinzel Decorative', cursive; font-size: 24px; margin: 0 0 6px; }
.modal-sub { font-size: 13px; color: var(--soft-gray); margin-bottom: 30px; }
.form-group { margin-bottom: 22px; }
.form-group label { display: block; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--soft-gray); margin-bottom: 10px; }
.star-input { display: flex; gap: 6px; font-size: 26px; }
.star-input span { cursor: pointer; color: #ddd; transition: var(--ease); }
.star-input span.filled { color: var(--accent-color); }
.form-group input[type="text"], .form-group textarea {
    width: 100%; border: 1px solid var(--border-color); padding: 14px; font-family: 'Inter', sans-serif; font-size: 14px; resize: vertical;
}
.form-group input[type="text"]:focus, .form-group textarea:focus { outline: none; border-color: var(--deep-black); }
.photo-upload-zone {
    border: 1.5px dashed var(--border-color); padding: 24px; text-align: center; cursor: pointer;
    font-size: 12px; color: var(--soft-gray); transition: var(--ease);
}
.photo-upload-zone:hover, .photo-upload-zone.dragover { border-color: var(--accent-color); color: var(--deep-black); background: #fffdf5; }
.photo-preview-grid { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
.photo-preview-item { position: relative; width: 64px; height: 64px; }
.photo-preview-item img { width: 100%; height: 100%; object-fit: cover; }
.photo-preview-item .remove-x {
    position: absolute; top: -7px; right: -7px; width: 18px; height: 18px; border-radius: 50%;
    background: var(--deep-black); color: #fff; font-size: 11px; display: flex; align-items: center; justify-content: center; cursor: pointer; line-height: 1;
}
.btn-submit-review {
    width: 100%; padding: 18px; background: var(--deep-black); color: #fff; border: none;
    font-size: 12px; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; transition: var(--ease);
}
.btn-submit-review:hover { background: var(--accent-deep); color: #000; }
.btn-submit-review:disabled { opacity: 0.4; cursor: not-allowed; }
.form-msg { font-size: 12px; margin-top: 12px; text-align: center; display: none; }
.form-msg.ok { color: #3a7d44; }
.form-msg.err { color: #b23b3b; }

/* ===== LIGHTBOX ===== */
.lightbox-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 1100; display: none; align-items: center; justify-content: center; }
.lightbox-overlay.open { display: flex; }
.lightbox-overlay img { max-width: 90vw; max-height: 85vh; object-fit: contain; }
.lightbox-close { position: absolute; top: 24px; right: 32px; color: #fff; font-size: 30px; cursor: pointer; background: none; border: none; z-index: 2; }
.lightbox-nav { position: absolute; top: 50%; transform: translateY(-50%); color: #fff; font-size: 34px; cursor: pointer; background: none; border: none; padding: 10px 18px; }
.lightbox-prev { left: 10px; } .lightbox-next { right: 10px; }

/* ===== BACK TO TOP ===== */
/* Scroll-to-top now lives globally in the footer partial (site-wide, right side,
   stacked above the WhatsApp button). */

/* ===== MOBILE (client-specified reorder) ===== */
@media (max-width: 900px) {
    .boutique-wrapper {
        display: grid; grid-template-columns: 1fr 1fr; column-gap: 14px; row-gap: 10px;
        padding: 32px 20px; margin: 48px auto 20px;
    }

    /* Dissolve the two columns so every element becomes a direct,
       freely reorderable grid item. */
    .gallery-column, .info-column { display: contents; }

    /* Mobile sequence: category -> [title | stock] -> [price | stars] ->
       main photo -> thumbnails (1 row of 3, swipe) -> colors -> summary ->
       [qty | gift] -> buttons -> trust -> specs -> accordion */
    .category-label { order: 1; grid-column: 1 / -1; margin-bottom: 10px; }
    .brand-title { order: 2; grid-column: 1; font-size: 19px; line-height: 1.25; margin: 0; align-self: center; }
    .brand-title br { display: none; }
    .stock-note { order: 3; grid-column: 2; margin: 0; justify-content: flex-end; text-align: right; align-self: center; }
    .stock-detail { display: none; }
    .price-tag { order: 4; grid-column: 1; font-size: 21px; margin: 0; align-self: center; }
    .price-tag del { font-size: 15px; }
    .rating-box { order: 5; grid-column: 2; margin: 0; justify-content: flex-end; text-align: right; width: auto; align-self: center; }
    .rating-box #headerRatingText { order: 1; }
    .rating-box #headerStars { order: 2; }

    .featured-stage { order: 6; grid-column: 1 / -1; }
    .thumbnail-grid { order: 7; grid-column: 1 / -1; }
    .color-selection-container { order: 8; grid-column: 1 / -1; margin-bottom: 0; }
    .order-summary-box { order: 9; grid-column: 1 / -1; }
    .qty-gift-row { order: 10; grid-column: 1 / -1; }
    .action-group { order: 11; grid-column: 1 / -1; flex-direction: column; }
    .trust-row { order: 12; grid-column: 1 / -1; }
    .specs-grid { order: 13; grid-column: 1 / -1; }
    .luxe-accordion { order: 14; grid-column: 1 / -1; }

    /* 1 row, 3 columns of thumbnails — swipe sideways for the rest */
    .thumbnail-grid {
        display: flex; overflow-x: auto; gap: 10px; scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }
    .thumbnail-grid .thumb-box { flex: 0 0 30%; scroll-snap-align: start; }

    .lifestyle-tile { width: 85vw; }
    .scroll-hint-arrows { display: none; }
    .reviews-header { padding: 40px 0; gap: 35px; }
    .reviews-summary { flex: 1 1 100%; }
    .reviews-breakdown { width: 100%; }
    .review-card { flex-direction: column; }
}
</style>

<div class="boutique-wrapper">
    <div class="gallery-column">
        <div class="featured-stage" id="featuredStage" onclick="openLightboxSrc(stageVisibleSrc())">
            <span class="stage-badge">{{ $product->is_shown_on_homepage ? 'Bestseller' : $firstCategory }}</span>
            <img id="featuredImg" src="{{ $mainImage }}" alt="{{ $product->title }}">
            <img id="featuredPeek" class="featured-peek" src="{{ $mainImage }}" alt="" aria-hidden="true">
        </div>
        <div class="thumbnail-grid" id="thumbGrid">
            <div class="thumb-box active" onclick="setFeatured(this, '{{ $mainImage }}')">
                <img src="{{ $mainImage }}" alt="{{ $product->title }}">
            </div>
            @foreach ($galleryImages as $key => $image)
                <div class="thumb-box" onclick="setFeatured(this, '{{ $image }}')">
                    <img src="{{ $image }}" alt="{{ $product->title }} photo {{ $key + 2 }}">
                </div>
            @endforeach
        </div>
    </div>

    <div class="info-column">
        <span class="category-label">{{ $firstCategory }}</span>
        <h1 class="brand-title">{{ $titleLine1 }}@if($titleLine2 !== '')<br>{{ $titleLine2 }}@endif</h1>

        <div class="rating-box" onclick="document.getElementById('reviewsSection').scrollIntoView({behavior:'smooth'})">
            <span class="star-gold" id="headerStars">★★★★★</span>
            <span id="headerRatingText">({{ $reviewsData->count() }} signatures)</span>
        </div>

        <div class="price-tag" id="priceTag">
            @if ($product->discount_price)
                <del>{{ $currency }} {{ number_format((float) $product->regular_price) }}</del>
            @endif
            {{ $currency }} <span data-price-value>{{ number_format((float) $finalPrice) }}</span>
        </div>
        <div class="stock-note {{ $inStock ? 'in' : 'out' }}" id="stockNote">
            <span class="stock-dot"></span>
            <span id="stockLabel">{{ $inStock ? 'In stock' : 'Out of stock' }}</span>@if($inStock)<span class="stock-detail"> — ready to ship across {{ setting('COUNTRY_SERVE') ?? 'Bangladesh' }}.</span>@endif
        </div>

        @if (count($specs))
            <div class="specs-grid">
                @foreach ($specs as $spec)
                    <div class="spec-item">
                        <label>{{ $spec['label'] }}</label>
                        <span>{{ $spec['value'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($swatches->isNotEmpty())
            <div class="color-selection-container">
                <span class="selection-label">Select Illumination Essence</span>
                <div class="swatch-group">
                    @foreach ($swatches as $key => $swatch)
                        <div class="swatch-wrapper {{ $defaultSwatch && $key === 0 ? 'active' : '' }}"
                            data-color="{{ $swatch['slug'] }}" data-name="{{ $swatch['name'] }}" data-code="{{ $swatch['code'] }}"
                            data-img="{{ $swatch['img'] }}" data-price="{{ $swatch['price'] }}" data-qty="{{ $swatch['qty'] }}"
                            onclick="selectSwatch(this)">
                            <div class="color-swatch" style="background: {{ $swatch['code'] }}"></div>
                            <span class="swatch-name">{{ $swatch['name'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="color-live-readout">
                    <span class="live-dot" id="liveDot" style="background: {{ $defaultSwatch['code'] ?? '#d6d6d4' }};"></span>
                    Selected essence: <span class="live-name" id="liveName">{{ $defaultSwatch['name'] ?? '—' }}</span>
                </div>
            </div>
        @endif

        <div class="order-summary-box">
            <div class="sum-row"><span>Unit price</span><span id="sumUnitPrice">{{ $currency }} <span data-unit>{{ number_format((float) $finalPrice) }}</span></span></div>
            <div class="sum-row"><span>Quantity</span><span id="sumQty">× 1</span></div>
            <div class="sum-row" id="sumGiftRow" style="display:none;"><span>Gift wrapping</span><span class="gift-line">+ {{ $currency }} {{ number_format($giftWrapFee) }}</span></div>
            <div class="sum-row total"><span>Total</span><span id="sumTotal">{{ $currency }} {{ number_format((float) $finalPrice) }}</span></div>
        </div>

        <div class="qty-gift-row">
            <div class="qty-stepper">
                <button type="button" onclick="stepQty(-1)">−</button>
                <span id="qtyVal">1</span>
                <button type="button" onclick="stepQty(1)">+</button>
            </div>
            <div class="gift-wrap-row" id="giftWrapRow" onclick="toggleGiftWrap()">
                <div class="gift-circle-btn">
                    <svg viewBox="0 0 24 24" stroke-width="1.7"><path d="M20 12v9H4v-9M2 7h20v5H2V7zm10 0V4a2 2 0 10-2 2h2zm0 0V4a2 2 0 112 2h-2zm0 5v9"/></svg>
                    <span class="gift-check-badge">✓</span>
                </div>
                <div class="gift-wrap-text">
                    <span class="gift-title">Add Gift Wrapping</span>
                    <span class="gift-sub">Premium Wrapping — {{ $currency }}{{ number_format($giftWrapFee) }}</span>
                </div>
            </div>
        </div>

        <div class="action-group">
            <button type="button" class="btn-lux btn-dark" id="addToCartBtn" onclick="addToCart()" {{ $inStock ? '' : 'disabled' }}>Add To Cart</button>
            <button type="button" class="btn-lux btn-gold" id="buyNowBtn" onclick="buyNow()" {{ $inStock ? '' : 'disabled' }}>Buy It Now</button>
        </div>

        @if (count($trustBadges))
            <div class="trust-row">
                @foreach ($trustBadges as $badge)
                    <div class="trust-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z"/></svg>
                        {{ $badge }}
                    </div>
                @endforeach
            </div>
        @endif

        <div class="luxe-accordion">
            @if (!empty($product->short_description))
                <details open>
                    <summary>The Design Story</summary>
                    <div class="details-content">{!! $product->short_description !!}</div>
                </details>
            @endif
            @if (!empty($product->full_description))
                <details {{ empty($product->short_description) ? 'open' : '' }}>
                    <summary>Technical Details</summary>
                    <div class="details-content">{!! $product->full_description !!}</div>
                </details>
            @endif
            @if (!empty($shippingText))
                <details>
                    <summary>Shipping &amp; Concierge</summary>
                    <div class="details-content">{!! $shippingText !!}</div>
                </details>
            @endif
            @if (!empty($warrantyText))
                <details>
                    <summary>Warranty &amp; Returns</summary>
                    <div class="details-content">{!! $warrantyText !!}</div>
                </details>
            @endif
        </div>
    </div>
</div>

{{-- ===== LIFESTYLE / "STYLED BY LIGHT" ===== --}}
@if ($lifestyleImages->isNotEmpty() || $productVideoFile || $productVideoYoutube)
    <div class="lifestyle-section">
        <div class="section-heading">
            <span class="category-label">In Every Room</span>
            <h2>Styled By Light</h2>
        </div>
        <div class="lifestyle-scroll-wrap">
            <div class="lifestyle-grid" id="lifestyleGrid">
                @if ($productVideoFile)
                    <div class="lifestyle-tile video-tile" onclick="openVideoLightbox()">
                        <video autoplay loop muted playsinline @if($productVideoThumb) poster="{{ $productVideoThumb }}" @endif>
                            <source src="{{ $productVideoFile }}" type="video/mp4">
                        </video>
                        <div class="play-button">
                            <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <div class="lifestyle-caption"><span class="tag">Watch</span><span class="cap-title">{{ $product->title }} In Motion</span></div>
                    </div>
                @elseif ($productVideoYoutube)
                    <div class="lifestyle-tile video-tile">
                        <iframe src="{{ $productVideoYoutube }}" title="{{ $product->title }} video" loading="lazy" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    </div>
                @endif
                @foreach ($lifestyleImages as $key => $tile)
                    <div class="lifestyle-tile" onclick="openLightbox({{ $key }})">
                        <img src="{{ $tile->url }}" alt="{{ $tile->caption ?? $product->title }}">
                        @if ($tile->tag || $tile->caption)
                            <div class="lifestyle-caption">
                                @if ($tile->tag)<span class="tag">{{ $tile->tag }}</span>@endif
                                @if ($tile->caption)<span class="cap-title">{{ $tile->caption }}</span>@endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="scroll-hint-arrows">
                <button type="button" class="scroll-arrow-btn" onclick="scrollLifestyle(-1)">‹</button>
                <button type="button" class="scroll-arrow-btn" onclick="scrollLifestyle(1)">›</button>
            </div>
        </div>
    </div>
@endif

{{-- ===== REVIEWS ===== --}}
<div class="reviews-section" id="reviewsSection">
    <div class="reviews-header">
        <div class="reviews-summary">
            <div class="avg-num" id="avgNum">0.0</div>
            <span class="star-gold" id="avgStars">★★★★★</span>
            <div class="total-count" id="totalCount"></div>
            <button type="button" class="btn-write-review" onclick="openReviewModal()">Write a Review</button>
        </div>
        <div class="reviews-breakdown" id="breakdownBars"></div>
    </div>

    <div class="reviews-toolbar">
        <h3>Customer Signatures</h3>
        <div class="toolbar-controls">
            <div id="filterChips" style="display:flex; gap:8px;"></div>
            <select id="sortSelect" onchange="renderReviews()">
                <option value="newest">Newest First</option>
                <option value="highest">Highest Rated</option>
                <option value="lowest">Lowest Rated</option>
                <option value="helpful">Most Helpful</option>
            </select>
        </div>
    </div>

    <div class="review-list" id="reviewList"></div>
    <div class="load-more-wrap" id="loadMoreWrap" style="display:none;">
        <button type="button" class="btn-load-more" onclick="loadMore()">Load More Signatures</button>
    </div>
</div>

{{-- ===== RELATED PRODUCTS ===== --}}
@if (isset($relatedProducts) && $relatedProducts->isNotEmpty())
    <section class="related-products-section">
        <div class="lux-shop-header">
            <p>You May Also Love</p>
            <h1>Related Products</h1>
        </div>
        <div class="px-4">
            <div class="product-slider related-product-slider">
                @foreach ($relatedProducts as $related)
                    <x-lux-product-card :product="$related" />
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===== WRITE REVIEW MODAL ===== --}}
<div class="modal-overlay" id="reviewModalOverlay">
    <div class="review-modal">
        <button type="button" class="modal-close" onclick="closeReviewModal()">&times;</button>
        <h3>Share Your Signature</h3>
        <p class="modal-sub">Tell fellow patrons how {{ $product->title }} fits into your space.</p>

        <div class="form-group">
            <label>Your Rating</label>
            <div class="star-input" id="starInput">
                <span data-v="1">★</span><span data-v="2">★</span><span data-v="3">★</span><span data-v="4">★</span><span data-v="5">★</span>
            </div>
        </div>
        <div class="form-group">
            <label>Your Name</label>
            <input type="text" id="reviewerNameInput" placeholder="e.g. Farhana R." value="{{ auth()->user()->name ?? '' }}">
        </div>
        <div class="form-group">
            <label>Review Title</label>
            <input type="text" id="reviewTitleInput" placeholder="Sum it up in a few words">
        </div>
        <div class="form-group">
            <label>Your Review</label>
            <textarea id="reviewTextInput" rows="4" placeholder="What did you like or dislike? How does it look in your room?"></textarea>
        </div>
        <div class="form-group">
            <label>Add Photos <span style="text-transform:none; letter-spacing:0;">(optional, up to 5)</span></label>
            <div class="photo-upload-zone" id="uploadZone" onclick="document.getElementById('photoInput').click()">
                Click or drag photos here — show us the product in its new home
            </div>
            <input type="file" id="photoInput" accept="image/*" multiple style="display:none">
            <div class="photo-preview-grid" id="photoPreviewGrid"></div>
        </div>
        <button type="button" class="btn-submit-review" id="submitReviewBtn" onclick="submitReview()">Post Signature</button>
        <div class="form-msg" id="formMsg"></div>
    </div>
</div>

{{-- ===== LIGHTBOXES ===== --}}
<div class="lightbox-overlay" id="lightboxOverlay">
    <button type="button" class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <button type="button" class="lightbox-nav lightbox-prev" onclick="navLightbox(-1)">‹</button>
    <img id="lightboxImg" src="" alt="Enlarged view">
    <button type="button" class="lightbox-nav lightbox-next" onclick="navLightbox(1)">›</button>
</div>

@if ($productVideoFile)
    <div class="lightbox-overlay" id="videoLightboxOverlay">
        <button type="button" class="lightbox-close" onclick="closeVideoLightbox()">&times;</button>
        <video id="lightboxVideo" controls loop playsinline style="max-width:90vw; max-height:85vh;"
            @if($productVideoThumb) poster="{{ $productVideoThumb }}" @endif>
            <source src="{{ $productVideoFile }}" type="video/mp4">
            Your browser does not support embedded video.
        </video>
    </div>
@endif


<script>
/* ---------- Data from the server ---------- */
const CSRF_TOKEN = '{{ csrf_token() }}';
const PRODUCT_ID = {{ (int) $product->id }};
const UNIT_PRICE = {{ (float) $finalPrice }};
const GIFT_WRAP_FEE = {{ (float) $giftWrapFee }};
const CURRENCY = @json($currency);
const MAX_QTY = {{ max(1, (int) ($product->quantity ?? 1)) }};
const IS_VARIABLE = {{ $isVariable ? 'true' : 'false' }};
// Live values — for variable products these follow the selected colour.
let unitPrice = UNIT_PRICE;
let currentMaxQty = MAX_QTY;
const ADD_CART_URL = '{{ route('add.cart') }}';
const BUY_NOW_URL = '{{ route('buy.product') }}';
const REVIEW_STORE_URL = '{{ route('product.review.store', $product->id) }}';
const HELPFUL_URL_TEMPLATE = '{{ route('product.review.helpful', ':id') }}';
let reviews = @json($reviewsData);
const lifestyleImages = @json($lifestyleImages->pluck('url'));

/* ---------- Gallery ---------- */
// The image the stage rests on (set by thumbnail clicks, and by colour
// swatches on variable products). Hover peeks the next image, then reverts here.
let currentFeaturedSrc = document.getElementById('featuredImg').src;
function setFeatured(el, src) {
    document.querySelectorAll('.thumb-box').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    currentFeaturedSrc = src;
    document.getElementById('featuredImg').src = src;
}

/* The image the visitor currently sees on the stage (peek overlay wins while hovering). */
function stageVisibleSrc() {
    const stage = document.getElementById('featuredStage');
    const peek = document.getElementById('featuredPeek');
    if (stage && peek && stage.classList.contains('is-peeking')) return peek.src;
    return document.getElementById('featuredImg').src;
}

/* Hover the main image to cross-fade in the next photo; leave to fade back. */
(function () {
    const stage = document.getElementById('featuredStage');
    const peek = document.getElementById('featuredPeek');
    if (!stage || !peek) return;
    const srcList = Array.from(document.querySelectorAll('#thumbGrid .thumb-box img')).map(i => i.src);
    if (srcList.length < 2) return;
    stage.addEventListener('mouseenter', function () {
        const idx = srcList.indexOf(currentFeaturedSrc);
        peek.src = idx === -1 ? srcList[0] : srcList[(idx + 1) % srcList.length];
        stage.classList.add('is-peeking');
    });
    stage.addEventListener('mouseleave', function () {
        stage.classList.remove('is-peeking');
    });
})();

/* ---------- Color swatches + glow ---------- */
// Variable products start unselected ('blank') — the stage rests on the main image.
let selectedColor = @json($defaultSwatch['slug'] ?? 'blank');
function hexToGlow(hex) {
    const h = (hex || '').replace('#', '');
    if (h.length !== 6) return 'rgba(255,204,0,0.45)';
    const r = parseInt(h.slice(0, 2), 16), g = parseInt(h.slice(2, 4), 16), b = parseInt(h.slice(4, 6), 16);
    return `rgba(${r},${g},${b},0.5)`;
}
function selectSwatch(el) {
    document.querySelectorAll('.swatch-wrapper').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
    selectedColor = el.dataset.color || 'blank';
    const dot = document.getElementById('liveDot');
    const name = document.getElementById('liveName');
    if (dot) dot.style.background = el.dataset.code;
    if (name) name.textContent = el.dataset.name;
    const stage = document.getElementById('featuredStage');
    if (stage) stage.style.boxShadow = '0 0 0 4px #fff, 0 0 30px 6px ' + hexToGlow(el.dataset.code);

    // Variable products: the colour also drives the image, price and stock.
    if (IS_VARIABLE) applyVariation(el);
}

// Switch the main image / price / stock to the chosen colour (variable only).
function applyVariation(el) {
    const img = el.dataset.img;
    if (img) {
        currentFeaturedSrc = img;
        const f = document.getElementById('featuredImg');
        if (f) f.src = img;
        // The colour photo isn't part of the gallery strip — clear thumb highlights.
        document.querySelectorAll('#thumbGrid .thumb-box').forEach(t => t.classList.remove('active'));
    }

    const price = parseFloat(el.dataset.price);
    if (!isNaN(price)) {
        unitPrice = price;
        document.querySelectorAll('[data-unit], [data-price-value]').forEach(s => s.textContent = formatMoney(price));
    }

    const qty = parseInt(el.dataset.qty, 10);
    if (!isNaN(qty)) {
        currentMaxQty = qty;
        const qtyEl = document.getElementById('qtyVal');
        let cur = parseInt(qtyEl.textContent, 10) || 1;
        if (cur > qty) { qtyEl.textContent = Math.max(1, qty); }
        updateStockUi(qty);
    }

    updateOrderSummary();
}

function updateStockUi(qty) {
    const inStock = qty > 0;
    const note = document.getElementById('stockNote');
    if (note) {
        note.classList.toggle('in', inStock);
        note.classList.toggle('out', !inStock);
        const label = document.getElementById('stockLabel');
        if (label) label.textContent = inStock ? 'In stock' : 'Out of stock';
        const detail = note.querySelector('.stock-detail');
        if (detail) detail.style.display = inStock ? '' : 'none';
    }
    const addBtn = document.getElementById('addToCartBtn');
    const buyBtn = document.getElementById('buyNowBtn');
    if (addBtn) addBtn.disabled = !inStock;
    if (buyBtn) buyBtn.disabled = !inStock;
}

/* ---------- Qty + gift wrap + live summary ---------- */
let giftWrapOn = false;
function stepQty(delta) {
    const el = document.getElementById('qtyVal');
    let v = parseInt(el.textContent) + delta;
    if (v > currentMaxQty) v = currentMaxQty;
    if (v < 1) v = 1;
    el.textContent = v;
    updateOrderSummary();
}
function toggleGiftWrap() {
    giftWrapOn = !giftWrapOn;
    document.getElementById('giftWrapRow').classList.toggle('active', giftWrapOn);
    updateOrderSummary();
}
function formatMoney(n) { return n.toLocaleString('en-IN'); }
function updateOrderSummary() {
    const qty = parseInt(document.getElementById('qtyVal').textContent) || 1;
    const total = unitPrice * qty + (giftWrapOn ? GIFT_WRAP_FEE : 0);
    document.getElementById('sumQty').textContent = '× ' + qty;
    document.getElementById('sumGiftRow').style.display = giftWrapOn ? 'flex' : 'none';
    document.getElementById('sumTotal').textContent = CURRENCY + ' ' + formatMoney(total);
}

/* ---------- Add to cart / Buy now (real cart endpoints) ---------- */
function cartPayload() {
    const qty = parseInt(document.getElementById('qtyVal').textContent) || 1;
    return { id: PRODUCT_ID, qty: qty, color: selectedColor, size: 'blank', gift_wrap: giftWrapOn ? 1 : 0 };
}
function addToCart() {
    const btn = document.getElementById('addToCartBtn');
    btn.disabled = true;
    const body = new FormData();
    body.append('_token', CSRF_TOKEN);
    Object.entries(cartPayload()).forEach(([k, v]) => body.append(k, v));
    fetch(ADD_CART_URL, { method: 'POST', body })
        .then(r => r.json())
        .then(() => {
            btn.disabled = false;
            if (typeof loadCartOnCanvas === 'function') loadCartOnCanvas(true);
        })
        .catch(() => { btn.disabled = false; alert('Could not add to cart. Please try again.'); });
}
function buyNow() {
    const params = new URLSearchParams(cartPayload());
    window.location.href = BUY_NOW_URL + '?' + params.toString();
}

/* ---------- Lifestyle carousel ---------- */
function scrollLifestyle(dir) {
    const grid = document.getElementById('lifestyleGrid');
    if (grid) grid.scrollBy({ left: dir * 400, behavior: 'smooth' });
}

/* Scroll hint: when the carousel first scrolls into view, glide it a little
   to the left and back so visitors instantly know it swipes. Runs once. */
(function () {
    const grid = document.getElementById('lifestyleGrid');
    if (!grid || !('IntersectionObserver' in window)) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            obs.disconnect();
            setTimeout(() => {
                grid.scrollBy({ left: 150, behavior: 'smooth' });
                setTimeout(() => grid.scrollTo({ left: 0, behavior: 'smooth' }), 800);
            }, 350);
        });
    }, { threshold: 0.45 });
    observer.observe(grid);
})();

/* ---------- Lightboxes ---------- */
let lbImages = [], lbIndex = 0;
function openLightbox(i) {
    lbImages = lifestyleImages; lbIndex = i;
    showLightbox(lbImages[i]);
}
function openLightboxSrc(src) {
    lbImages = []; lbIndex = 0;
    showLightbox(src);
}
function openPhotoLightbox(photos, i) {
    lbImages = photos; lbIndex = i;
    showLightbox(photos[i]);
}
function showLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxOverlay').classList.add('open');
}
function navLightbox(dir) {
    if (lbImages.length < 2) return;
    lbIndex = (lbIndex + dir + lbImages.length) % lbImages.length;
    document.getElementById('lightboxImg').src = lbImages[lbIndex];
}
function closeLightbox() { document.getElementById('lightboxOverlay').classList.remove('open'); }
document.getElementById('lightboxOverlay').addEventListener('click', e => { if (e.target.id === 'lightboxOverlay') closeLightbox(); });

@if ($productVideoFile)
function openVideoLightbox() {
    document.getElementById('videoLightboxOverlay').classList.add('open');
    const vid = document.getElementById('lightboxVideo');
    vid.currentTime = 0;
    vid.play().catch(() => {});
}
function closeVideoLightbox() {
    document.getElementById('lightboxVideo').pause();
    document.getElementById('videoLightboxOverlay').classList.remove('open');
}
document.getElementById('videoLightboxOverlay').addEventListener('click', e => { if (e.target.id === 'videoLightboxOverlay') closeVideoLightbox(); });
@endif


/* ---------- Reviews (server data, client-side filter/sort) ---------- */
let activeFilter = 0; // 0 = all, 'photo', or 1..5
let visibleCount = 4;
const votedReviews = new Set();

function starsHtml(n) { return '★★★★★'.slice(0, n) + '☆☆☆☆☆'.slice(0, 5 - n); }
function initials(name) { return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase(); }
function formatDate(d) { return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }); }
function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

function computeSummary() {
    const total = reviews.length;
    const avg = total ? reviews.reduce((a, r) => a + r.rating, 0) / total : 0;
    const counts = [0, 0, 0, 0, 0];
    reviews.forEach(r => counts[r.rating - 1]++);
    return { total, avg, counts };
}

function renderSummary() {
    const { total, avg, counts } = computeSummary();
    document.getElementById('avgNum').textContent = total ? avg.toFixed(1) : '0.0';
    document.getElementById('avgStars').textContent = starsHtml(Math.round(avg));
    document.getElementById('totalCount').textContent = 'Based on ' + total + ' signature' + (total === 1 ? '' : 's');
    document.getElementById('headerRatingText').textContent = '(' + total + ' signature' + (total === 1 ? '' : 's') + ')';
    document.getElementById('headerStars').textContent = starsHtml(total ? Math.round(avg) : 5);

    const bars = document.getElementById('breakdownBars');
    bars.innerHTML = '';
    for (let star = 5; star >= 1; star--) {
        const c = counts[star - 1];
        const pct = total ? Math.round((c / total) * 100) : 0;
        const row = document.createElement('div');
        row.className = 'bar-row';
        row.onclick = () => { activeFilter = (activeFilter === star) ? 0 : star; visibleCount = 4; renderReviews(); };
        row.innerHTML = `<span>${star} star</span><div class="bar-track"><div class="bar-fill" style="width:${pct}%"></div></div><span>${c}</span>`;
        bars.appendChild(row);
    }

    const chips = document.getElementById('filterChips');
    chips.innerHTML = '';
    [['All', 0], ['With Photos', 'photo']].forEach(([labelText, key]) => {
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'filter-chip' + (activeFilter === key ? ' active' : '');
        chip.textContent = labelText;
        chip.onclick = () => { activeFilter = (activeFilter === key) ? 0 : key; visibleCount = 4; renderReviews(); };
        chips.appendChild(chip);
    });
}

function getFiltered() {
    let list = reviews.slice();
    if (activeFilter === 'photo') list = list.filter(r => r.photos && r.photos.length);
    else if (activeFilter) list = list.filter(r => r.rating === activeFilter);

    const sortBy = document.getElementById('sortSelect').value;
    if (sortBy === 'newest') list.sort((a, b) => new Date(b.date) - new Date(a.date));
    else if (sortBy === 'highest') list.sort((a, b) => b.rating - a.rating);
    else if (sortBy === 'lowest') list.sort((a, b) => a.rating - b.rating);
    else if (sortBy === 'helpful') list.sort((a, b) => b.helpful - a.helpful);
    return list;
}

function renderReviews() {
    renderSummary();
    const list = getFiltered();
    const container = document.getElementById('reviewList');
    container.innerHTML = '';

    if (!list.length) {
        container.innerHTML = '<div class="no-reviews-msg">' +
            (reviews.length ? 'No signatures match this filter yet.' : 'No signatures yet — be the first to share yours.') + '</div>';
        document.getElementById('loadMoreWrap').style.display = 'none';
        return;
    }

    list.slice(0, visibleCount).forEach(r => {
        const card = document.createElement('div');
        card.className = 'review-card';
        const photosHtml = (r.photos && r.photos.length)
            ? '<div class="review-photos">' + r.photos.map((p, i) =>
                `<img src="${p}" alt="Customer photo" data-review="${r.id}" data-index="${i}">`).join('') + '</div>'
            : '';
        card.innerHTML = `
            <div class="review-avatar">${escapeHtml(initials(r.name))}</div>
            <div class="review-body">
                <div class="review-top-row">
                    <div class="reviewer-name-row">
                        <span class="reviewer-name">${escapeHtml(r.name)}</span>
                        ${r.verified ? '<span class="verified-badge">✓ Verified Buyer</span>' : ''}
                    </div>
                    <span class="review-date">${formatDate(r.date)}</span>
                </div>
                <div class="review-stars">${starsHtml(r.rating)}</div>
                ${r.title ? `<div class="review-title">${escapeHtml(r.title)}</div>` : ''}
                <p class="review-text">${escapeHtml(r.text)}</p>
                ${photosHtml}
                <div class="review-actions">
                    <button type="button" class="helpful-btn ${votedReviews.has(r.id) ? 'voted' : ''}" data-helpful="${r.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 22V11m0 11H4a1 1 0 01-1-1v-9a1 1 0 011-1h3m3 11h8.5a2 2 0 002-1.6L22 12a2 2 0 00-2-2.4h-5V5a2 2 0 00-2-2l-1 1-1.5 5.5V22"/></svg>
                        Helpful (${r.helpful})
                    </button>
                </div>
            </div>`;
        container.appendChild(card);
    });

    container.querySelectorAll('[data-helpful]').forEach(btn => {
        btn.addEventListener('click', () => markHelpful(parseInt(btn.dataset.helpful), btn));
    });
    container.querySelectorAll('.review-photos img').forEach(img => {
        img.addEventListener('click', () => {
            const r = reviews.find(x => x.id === parseInt(img.dataset.review));
            if (r) openPhotoLightbox(r.photos, parseInt(img.dataset.index));
        });
    });

    document.getElementById('loadMoreWrap').style.display = (visibleCount < list.length) ? 'block' : 'none';
}

function loadMore() { visibleCount += 4; renderReviews(); }

function markHelpful(id, btn) {
    if (votedReviews.has(id)) return;
    votedReviews.add(id);
    fetch(HELPFUL_URL_TEMPLATE.replace(':id', id), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
    })
        .then(r => r.json())
        .then(data => {
            const review = reviews.find(x => x.id === id);
            if (review && typeof data.helpful_count === 'number') review.helpful = data.helpful_count;
            renderReviews();
        })
        .catch(() => votedReviews.delete(id));
}

/* ---------- Write review modal ---------- */
let selectedRating = 0;
let pendingPhotos = [];

function openReviewModal() { document.getElementById('reviewModalOverlay').classList.add('open'); }
function closeReviewModal() { document.getElementById('reviewModalOverlay').classList.remove('open'); resetReviewForm(); }
document.getElementById('reviewModalOverlay').addEventListener('click', e => { if (e.target.id === 'reviewModalOverlay') closeReviewModal(); });

document.querySelectorAll('#starInput span').forEach(star => {
    star.addEventListener('click', () => {
        selectedRating = parseInt(star.dataset.v);
        document.querySelectorAll('#starInput span').forEach(s => {
            s.classList.toggle('filled', parseInt(s.dataset.v) <= selectedRating);
        });
    });
});

const photoInput = document.getElementById('photoInput');
const uploadZone = document.getElementById('uploadZone');
photoInput.addEventListener('change', e => handleFiles(e.target.files));
['dragover', 'dragenter'].forEach(evt => uploadZone.addEventListener(evt, e => { e.preventDefault(); uploadZone.classList.add('dragover'); }));
['dragleave', 'drop'].forEach(evt => uploadZone.addEventListener(evt, e => { e.preventDefault(); uploadZone.classList.remove('dragover'); }));
uploadZone.addEventListener('drop', e => handleFiles(e.dataTransfer.files));

function handleFiles(files) {
    Array.from(files).slice(0, 5 - pendingPhotos.length).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            pendingPhotos.push({ file, preview: e.target.result });
            renderPhotoPreview();
        };
        reader.readAsDataURL(file);
    });
}
function renderPhotoPreview() {
    const grid = document.getElementById('photoPreviewGrid');
    grid.innerHTML = '';
    pendingPhotos.forEach((p, i) => {
        const item = document.createElement('div');
        item.className = 'photo-preview-item';
        item.innerHTML = `<img src="${p.preview}" alt=""><span class="remove-x" data-i="${i}">&times;</span>`;
        item.querySelector('.remove-x').addEventListener('click', () => { pendingPhotos.splice(i, 1); renderPhotoPreview(); });
        grid.appendChild(item);
    });
}

function resetReviewForm() {
    selectedRating = 0;
    pendingPhotos = [];
    document.querySelectorAll('#starInput span').forEach(s => s.classList.remove('filled'));
    document.getElementById('reviewTitleInput').value = '';
    document.getElementById('reviewTextInput').value = '';
    renderPhotoPreview();
    const msg = document.getElementById('formMsg');
    msg.style.display = 'none';
    msg.className = 'form-msg';
}

function submitReview() {
    const name = document.getElementById('reviewerNameInput').value.trim();
    const title = document.getElementById('reviewTitleInput').value.trim();
    const text = document.getElementById('reviewTextInput').value.trim();
    const msg = document.getElementById('formMsg');

    if (!selectedRating) { alert('Please select a star rating.'); return; }
    if (!name) { alert('Please enter your name.'); return; }
    if (!text) { alert('Please write a short review.'); return; }

    const btn = document.getElementById('submitReviewBtn');
    btn.disabled = true;

    const body = new FormData();
    body.append('_token', CSRF_TOKEN);
    body.append('rating', selectedRating);
    body.append('reviewer_name', name);
    body.append('title', title);
    body.append('review', text);
    pendingPhotos.forEach(p => body.append('photos[]', p.file));

    fetch(REVIEW_STORE_URL, { method: 'POST', body, headers: { 'Accept': 'application/json' } })
        .then(async r => {
            if (!r.ok) {
                const err = await r.json().catch(() => ({}));
                throw new Error(err.message || 'Could not post your review.');
            }
            return r.json();
        })
        .then(data => {
            reviews.unshift(data.review);
            activeFilter = 0;
            visibleCount = 4;
            msg.textContent = data.message;
            msg.className = 'form-msg ok';
            msg.style.display = 'block';
            setTimeout(() => {
                btn.disabled = false;
                closeReviewModal();
                renderReviews();
                document.getElementById('reviewsSection').scrollIntoView({ behavior: 'smooth' });
            }, 400);
        })
        .catch(err => {
            btn.disabled = false;
            msg.textContent = err.message;
            msg.className = 'form-msg err';
            msg.style.display = 'block';
        });
}

/* ---------- Inline lifestyle video: hide play button while playing ---------- */
const inlineVideoTile = document.querySelector('.video-tile');
const inlineVideo = inlineVideoTile ? inlineVideoTile.querySelector('video') : null;
if (inlineVideo && inlineVideoTile) {
    ['play', 'playing'].forEach(evt => inlineVideo.addEventListener(evt, () => inlineVideoTile.classList.add('is-playing')));
    ['pause', 'waiting'].forEach(evt => inlineVideo.addEventListener(evt, () => inlineVideoTile.classList.remove('is-playing')));
    if (!inlineVideo.paused) inlineVideoTile.classList.add('is-playing');
}

/* ---------- Init ---------- */
renderReviews();
updateOrderSummary();
const defaultSwatch = document.querySelector('.swatch-wrapper.active');
if (defaultSwatch) selectSwatch(defaultSwatch);
</script>

@endsection

@push('js')
    {{-- Related Products carousel — same Slick config as the homepage "Cozy Lighting" row.
         Runs from the 'js' stack, which loads after jQuery + slick.js. --}}
    <script>
        $(document).ready(function () {
            if ($('.related-product-slider').length) {
                $('.related-product-slider').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    rows: 1,
                    autoplay: true,
                    autoplaySpeed: 2500,
                    arrows: true,
                    dots: false,
                    speed: 600,
                    infinite: true,
                    cssEase: 'ease-in-out',
                    responsive: [
                        { breakpoint: 1200, settings: { slidesToShow: 4, rows: 1 } },
                        { breakpoint: 992, settings: { slidesToShow: 3, rows: 1 } },
                        { breakpoint: 768, settings: { slidesToShow: 1, rows: 1 } }
                    ]
                });
            }
        });
    </script>
@endpush
