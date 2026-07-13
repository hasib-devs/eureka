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
    $avgRating   = $reviewCount > 0 ? $product->reviews->sum('rating') / $reviewCount : 0;
    $fullStars   = (int) round($avgRating);

    // Dynamic colour variants from the color_product pivot.
    $colors      = $product->colors;
    $maxSwatches = 4;

    // Hover-peek target: the next gallery image (same source the product page's
    // thumbnail strip uses). Null when the product has only its hero image.
    $peekImage = $product->gallery_image_urls->first();
@endphp

{{-- ─────────────────────────────────────────────────────────────────────────
     Self-contained styles — pushed once per page regardless of card count.
     This makes the component work on any page without relying on homepage CSS.
──────────────────────────────────────────────────────────────────────────── --}}
@pushOnce('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');

    /* ── CARD WRAPPER ── */
    .lux-product-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
    }

    /* ── THUMBNAIL ── */
    .lux-product-thumb {
        position: relative;
        height: 540px;
        overflow: hidden;
        border-radius: 7px;
        background: #f5f5f5;
    }
    .lux-product-thumb a {
        display: block;
        width: 100%;
        height: 100%;
    }
    .lux-product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
        animation: lux-card-breathe 9s ease-in-out infinite; /* gentle always-on movement */
    }
    @keyframes lux-card-breathe {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.05); }
    }
    .lux-product-card:hover .lux-product-thumb img {
        animation: none; /* pause the drift; give a clean hover zoom instead */
        transform: scale(1.05);
    }
    /* Hover-peek overlay: the next gallery image sits on top and cross-fades in.
       Desktop: the swap waits ~2s after hover before it reveals (premium feel);
       leaving hides it immediately. Mobile uses .lux-scroll-peek (see JS) so the
       swap is driven by scroll position with no delay. */
    .lux-product-thumb .lux-peek-img {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        pointer-events: none;
        transition: transform 0.45s ease, opacity 0.6s ease;
    }
    .lux-product-thumb:hover .lux-peek-img {
        opacity: 1;
        transition-delay: 0s, 2s; /* transform immediate, opacity waits ~2s */
    }
    /* Mobile scroll-driven swap: JS toggles this as the card scrolls up the viewport. */
    .lux-product-thumb.lux-scroll-peek .lux-peek-img {
        opacity: 1;
    }
    @media (prefers-reduced-motion: reduce) {
        .lux-product-thumb img { animation: none; }
        .lux-product-thumb .lux-peek-img { transition: none; }
    }

    /* ── INFO AREA ── */
    .lux-product-info {
        padding: 13px 0 0;
    }

    .lux-info-top-row,
    .lux-variants-row,
    .lux-purchase-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .lux-info-top-row {
        align-items: flex-start;
    }

    /* ── CATEGORY LABEL ── */
    .lux-category-label {
        display: block;
        margin-bottom: 4px;
        font-size: 12px;
        color: #707070;
        text-transform: capitalize;
    }

    /* ── PRODUCT NAME ── */
    .lux-product-name {
        max-width: 210px;
        margin: 0;
        font-size: 19px;
        font-weight: 500;
        line-height: 1.16;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .lux-product-name a {
        color: #111;
        text-decoration: none;
    }
    .lux-product-name a:hover {
        color: #333;
    }

    /* ── WISHLIST BUTTON ── */
    .lux-wishlist-btn {
        margin-top: 3px;
        padding: 0;
        border: 0;
        background: transparent;
        color: #000;
        font-size: 12px;
        line-height: 1.2;
        white-space: nowrap;
        cursor: pointer;
        flex-shrink: 0;
    }
    .lux-wishlist-btn:hover {
        color: #e74c3c;
    }

    /* ── VARIANTS ROW ── */
    .lux-variants-row {
        align-items: center;
        margin-top: 8px;
    }

    /* ── RATING ── */
    .lux-rating {
        color: #fbc831;
        font-size: 17px;
        line-height: 1;
        letter-spacing: -1px;
    }
    .lux-rating-count {
        margin-left: 4px;
        color: #707070;
        font-size: 12px;
    }

    /* ── COLOR SWATCHES ── */
    .lux-color-variants {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .lux-color {
        width: 19px;
        height: 19px;
        display: inline-block;
        border-radius: 50%;
        border: 2px solid #eee;
        padding: 2px;                 /* thin white ring between border and colour */
        background-clip: content-box; /* colour fills only the inner circle */
        box-sizing: border-box;       /* keep the swatch a fixed 19px */
        flex-shrink: 0;
    }
    /* Colours that carry a variation image are clickable and switch the card photo. */
    .lux-color.is-clickable {
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .lux-color.is-clickable:hover {
        transform: scale(1.15);
    }
    .lux-color.is-active {
        border-color: #C9A227;
    }

    /* ── PURCHASE ROW ── */
    .lux-purchase-row {
        align-items: center;
        margin-top: 12px;
    }

    /* ── PRICE ── */
    .lux-product-price {
        margin: 0;
        color: #111;
        font-size: 19px;
        font-weight: 500;
    }

    /* ── ADD TO CART BUTTON ── */
    @keyframes lux-rainbow-border {
        0%     { border-color: #ff0000; box-shadow: 0 0 8px rgba(255,0,0,.45); }
        16.67% { border-color: #ff7f00; box-shadow: 0 0 8px rgba(255,127,0,.45); }
        33.33% { border-color: #e6c200; box-shadow: 0 0 8px rgba(230,194,0,.45); }
        50%    { border-color: #00cc44; box-shadow: 0 0 8px rgba(0,204,68,.45); }
        66.67% { border-color: #0055ff; box-shadow: 0 0 8px rgba(0,85,255,.45); }
        83.33% { border-color: #6600cc; box-shadow: 0 0 8px rgba(102,0,204,.45); }
        100%   { border-color: #ff0000; box-shadow: 0 0 8px rgba(255,0,0,.45); }
    }
    .lux-add-to-cart {
        padding: 9px 18px;
        border: 1.5px solid #000;
        border-radius: 7px;
        background: #000;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.25s ease;
        animation: lux-rainbow-border 3s linear infinite;
        white-space: nowrap;
    }
    .lux-add-to-cart:hover {
        background: #222;
    }
    .lux-add-to-cart:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        animation: none;
    }

    /* ── RESPONSIVE — mirrors the homepage card exactly, so the card looks
       identical on every page (shop, category, filter, search…) ── */
    @media (max-width: 900px)  { .lux-product-thumb { height: 360px; } }
    @media (max-width: 768px)  {
        .lux-product-thumb { height: 520px; }
        .lux-product-name { max-width: 220px; font-size: 24px; }
        .lux-rating { font-size: 18px; }
        .lux-color { width: 22px; height: 22px; }
        .lux-product-price { font-size: 22px; }
        .lux-add-to-cart { padding: 11px 22px; font-size: 14px; }
    }
</style>
@endPushOnce

<div class="lux-product-card">
    <div class="lux-product-thumb">
        <a href="{{ url('product/' . $product->slug) }}">
            <img src="{{ $mainImage }}" alt="{{ $product->title }}" loading="lazy"
                 data-main="{{ $mainImage }}" data-current="{{ $mainImage }}">
            @if ($peekImage && $peekImage !== $mainImage)
                <img class="lux-peek-img" src="{{ $peekImage }}" alt="" aria-hidden="true" loading="lazy">
            @endif
        </a>
    </div>

    <div class="lux-product-info">
        <div class="lux-info-top-row">
            <div style="min-width:0;flex:1;">
                <span class="lux-category-label">{{ $categoryName }}</span>
                <h2 class="lux-product-name">
                    <a href="{{ url('product/' . $product->slug) }}">{{ $product->title }}</a>
                </h2>
            </div>

            @if (setting('wishlist_status', '1') !== '0')
                <form action="{{ route('wishlist.add') }}" method="POST" class="lux-wishlist-form" style="margin:0;"
                      data-login="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->slug }}">
                    <button type="submit" class="lux-wishlist-btn" aria-label="Save to Wishlist">
                        Save to Wishlist ♡
                    </button>
                </form>
            @endif
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
                        @php
                            $swatchImg = ! empty($color->pivot->image)
                                ? asset('uploads/product/' . $color->pivot->image)
                                : null;
                        @endphp
                        <span class="lux-color{{ $swatchImg ? ' is-clickable' : '' }}"
                              style="background:{{ $color->code ?? '#ddd' }}"
                              title="{{ $color->name }}"
                              @if ($swatchImg) data-img="{{ $swatchImg }}" role="button" tabindex="0" aria-label="Show {{ $color->name }}" @endif></span>
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
                <input type="hidden" name="id"  value="{{ $product->id }}">
                <input type="hidden" name="qty" value="1">
                <button type="button" class="lux-add-to-cart ajax-lux-cart-btn"
                        data-form-id="lux-cart-form-{{ $product->id }}">
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ─────────────────────────────────────────────────────────────────────────
     Card interactions (pushed once, works for every card on the page):
       • Click a colour → switch the card photo to that colour's variation.
     (Hover-peek is pure CSS: the .lux-peek-img overlay cross-fades in, so it
     also covers Slick's cloned slider slides with no JS.)
──────────────────────────────────────────────────────────────────────────── --}}
@pushOnce('js')
<script>
(function () {
    // Colour swatch → switch the card image to that colour's variation photo.
    function selectSwatch(sw) {
        var card = sw.closest('.lux-product-card');
        if (!card) return;
        var img = card.querySelector('.lux-product-thumb img');
        var src = sw.getAttribute('data-img');
        if (img && src) {
            img.src = src;
            img.setAttribute('data-current', src); // becomes the new resting image
        }
        card.querySelectorAll('.lux-color').forEach(function (s) { s.classList.remove('is-active'); });
        sw.classList.add('is-active');
    }

    document.addEventListener('click', function (e) {
        var sw = e.target.closest && e.target.closest('.lux-color[data-img]');
        if (!sw) return;
        e.preventDefault();
        selectSwatch(sw);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter' && e.key !== ' ') return;
        var sw = e.target.closest && e.target.closest('.lux-color[data-img]');
        if (!sw) return;
        e.preventDefault();
        selectSwatch(sw);
    });

    // ── Mobile scroll-driven image swap ──
    // On phones the 2nd (peek) image cross-fades in based on scroll position:
    // a card shows its 1st image while it sits low in the viewport, then swaps
    // to the 2nd image as it scrolls up past the middle. Auto-adjusts to the
    // screen height (uses window.innerHeight) and is disabled on desktop.
    var mq = window.matchMedia('(max-width: 768px)');
    var ticking = false;

    function updateScrollPeek() {
        ticking = false;
        if (!mq.matches) return;
        var mid = window.innerHeight * 0.5;
        document.querySelectorAll('.lux-product-thumb').forEach(function (t) {
            if (!t.querySelector('.lux-peek-img')) return;
            var r = t.getBoundingClientRect();
            if (r.bottom < 0 || r.top > window.innerHeight) return; // off-screen
            var center = r.top + r.height / 2;
            if (center < mid) t.classList.add('lux-scroll-peek');
            else t.classList.remove('lux-scroll-peek');
        });
    }

    function clearScrollPeek() {
        document.querySelectorAll('.lux-product-thumb.lux-scroll-peek')
            .forEach(function (t) { t.classList.remove('lux-scroll-peek'); });
    }

    function onScroll() {
        if (!ticking) { ticking = true; requestAnimationFrame(updateScrollPeek); }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function () { mq.matches ? updateScrollPeek() : clearScrollPeek(); });
    if (mq.addEventListener) {
        mq.addEventListener('change', function () { mq.matches ? updateScrollPeek() : clearScrollPeek(); });
    }
    updateScrollPeek();

    // ── Wishlist add (AJAX; works on every card, prompts guests to log in) ──
    function luxWishToast(msg, type) {
        if (window.jQuery && jQuery.toast) {
            jQuery.toast({
                heading: type === 'success' ? 'Wishlist' : 'Notice',
                text: msg,
                icon: type === 'success' ? 'success' : 'warning',
                position: 'top-right',
                stack: false
            });
        } else {
            alert(msg);
        }
    }
    document.addEventListener('submit', function (e) {
        var form = e.target.closest && e.target.closest('.lux-wishlist-form');
        if (!form) return;
        e.preventDefault();
        var btn = form.querySelector('button');
        if (btn && btn.disabled) return;
        if (btn) btn.disabled = true;
        var token = document.querySelector('meta[name="csrf-token"]');
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token ? token.getAttribute('content') : '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(function (r) {
            if (r.status === 401 || r.status === 419) {
                luxWishToast('Please log in to save items to your wishlist.', 'warning');
                setTimeout(function () { window.location = form.getAttribute('data-login') || '/login'; }, 900);
                return null;
            }
            return r.json().catch(function () { return {}; });
        })
        .then(function (data) {
            if (btn) btn.disabled = false;
            if (!data) return;
            luxWishToast(data.message || 'Added to your wishlist', 'success');
        })
        .catch(function () { if (btn) btn.disabled = false; });
    });
})();
</script>
@endPushOnce
