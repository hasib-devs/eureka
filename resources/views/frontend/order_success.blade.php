@extends('layouts.frontend.app')

@push('meta')
<meta name='description' content="Your order has been placed successfully — thank you for shopping with us."/>
@endpush

@section('title', 'Thank You')

@php
    $currency = setting('CURRENCY_CODE_MIN') ?? '৳';
    $items = $data['orderDetails'];
    $subtotal = (float) $items->sum('total_price');
    $shipping = (float) ($data['shipping_charge'] ?? 0);
    $total = (float) $data['total'];
    // Anything beyond items + shipping (gift wrap fee, coupon discount…).
    $adjustment = $total - $subtotal - $shipping;
@endphp

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Inter:wght@300;400;500;600;700&display=swap');

.thankyou-page {
    --ty-gold: #C9A227;
    --ty-gold-bright: #FFCC00;
    --ty-black: #0a0a0a;
    --ty-gray: #6b6b6b;
    --ty-border: #ececec;
    --ty-cream: #faf8f4;
    --ty-ease: cubic-bezier(0.23, 1, 0.32, 1);
    font-family: 'Inter', sans-serif;
    color: var(--ty-black);
    max-width: 820px;
    margin: 90px auto 70px;
    padding: 0 20px;
}

/* ── Entrance animation (staggered fade-up) ── */
.ty-reveal { opacity: 0; transform: translateY(18px); animation: tyFadeUp 0.8s var(--ty-ease) forwards; }
.ty-reveal:nth-child(2) { animation-delay: 0.12s; }
.ty-reveal:nth-child(3) { animation-delay: 0.24s; }
.ty-reveal:nth-child(4) { animation-delay: 0.36s; }
.ty-reveal:nth-child(5) { animation-delay: 0.48s; }
.ty-reveal:nth-child(6) { animation-delay: 0.60s; }
@keyframes tyFadeUp { to { opacity: 1; transform: none; } }

/* ── Hero ── */
.ty-hero { text-align: center; padding-bottom: 38px; }
.ty-check { width: 92px; height: 92px; margin: 0 auto 26px; display: block; }
.ty-check circle {
    fill: none; stroke: var(--ty-gold); stroke-width: 2;
    stroke-dasharray: 264; stroke-dashoffset: 264;
    animation: tyDraw 0.9s var(--ty-ease) 0.2s forwards;
}
.ty-check path {
    fill: none; stroke: var(--ty-black); stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
    stroke-dasharray: 60; stroke-dashoffset: 60;
    animation: tyDraw 0.5s var(--ty-ease) 0.9s forwards;
}
@keyframes tyDraw { to { stroke-dashoffset: 0; } }
.ty-eyebrow { display: block; font-size: 11px; letter-spacing: 5px; text-transform: uppercase; color: var(--ty-gray); margin-bottom: 16px; }
.ty-title {
    margin: 0 0 18px; font-family: 'Cinzel Decorative', Georgia, serif;
    font-size: 44px; font-weight: 700; line-height: 1.12; letter-spacing: 1px;
}
.ty-sub { margin: 0 auto; max-width: 480px; font-size: 14.5px; font-weight: 300; line-height: 1.8; color: var(--ty-gray); }
.ty-sub strong { color: var(--ty-black); font-weight: 600; }

/* ── Order number card ── */
.ty-invoice-card {
    background: var(--ty-cream); border: 1px solid var(--ty-border);
    padding: 26px 28px; text-align: center; margin-bottom: 22px;
}
.ty-invoice-card label { display: block; font-size: 10.5px; letter-spacing: 3px; text-transform: uppercase; color: var(--ty-gray); margin-bottom: 10px; }
.ty-invoice-row { display: inline-flex; align-items: center; gap: 14px; flex-wrap: wrap; justify-content: center; }
.ty-invoice-number { font-size: 26px; font-weight: 700; letter-spacing: 3px; }
.ty-copy-btn {
    display: inline-flex; align-items: center; gap: 7px;
    border: 1px solid var(--ty-black); background: transparent; color: var(--ty-black);
    font-size: 11.5px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase;
    padding: 8px 14px; cursor: pointer; transition: all 0.3s var(--ty-ease);
}
.ty-copy-btn:hover { background: var(--ty-black); color: var(--ty-gold-bright); }
.ty-copy-btn svg { width: 13px; height: 13px; stroke: currentColor; fill: none; }
.ty-copy-btn.copied { background: var(--ty-black); color: var(--ty-gold-bright); }
.ty-invoice-note { margin: 14px 0 0; font-size: 12px; color: var(--ty-gray); }

/* ── What happens next ── */
.ty-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
.ty-step { border: 1px solid var(--ty-border); background: #fff; padding: 22px 18px; text-align: center; }
.ty-step-icon {
    width: 42px; height: 42px; margin: 0 auto 12px; border-radius: 50%;
    background: var(--ty-cream); display: flex; align-items: center; justify-content: center;
}
.ty-step-icon svg { width: 19px; height: 19px; stroke: var(--ty-gold); fill: none; stroke-width: 1.7; }
.ty-step h4 { margin: 0 0 6px; font-size: 13px; font-weight: 600; letter-spacing: 0.4px; }
.ty-step p { margin: 0; font-size: 12px; line-height: 1.65; color: var(--ty-gray); }

/* ── Order summary ── */
.ty-summary { border: 1px solid var(--ty-border); background: #fff; padding: 28px; margin-bottom: 26px; }
.ty-summary h3 {
    margin: 0 0 20px; font-size: 12px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;
    padding-bottom: 14px; border-bottom: 1px solid var(--ty-border);
}
.ty-item { display: flex; align-items: center; gap: 16px; padding: 13px 0; border-bottom: 1px solid var(--ty-border); }
.ty-item img { width: 54px; height: 68px; object-fit: cover; background: var(--ty-cream); flex-shrink: 0; }
.ty-item-info { flex: 1; min-width: 0; }
.ty-item-info h5 { margin: 0 0 4px; font-size: 14px; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ty-item-info span { font-size: 12px; color: var(--ty-gray); text-transform: capitalize; }
.ty-item-price { font-size: 14px; font-weight: 600; white-space: nowrap; }
.ty-total-row { display: flex; justify-content: space-between; font-size: 13.5px; color: var(--ty-gray); padding-top: 14px; }
.ty-total-row.grand {
    color: var(--ty-black); font-size: 17px; font-weight: 700;
    margin-top: 12px; padding-top: 16px; border-top: 1px dashed #d9d4c7;
}
.ty-total-row.grand span:last-child { color: var(--ty-gold); }

/* ── Actions ── */
.ty-actions { display: flex; gap: 14px; margin-bottom: 30px; }
.ty-btn {
    flex: 1; display: inline-flex; align-items: center; justify-content: center;
    padding: 17px 20px; font-size: 12px; font-weight: 600; letter-spacing: 2.5px; text-transform: uppercase;
    text-decoration: none; text-align: center; cursor: pointer; transition: all 0.4s var(--ty-ease);
}
.ty-btn-dark { background: var(--ty-black); color: #fff; border: 1px solid var(--ty-black); }
.ty-btn-dark:hover { background: transparent; color: var(--ty-black); }
.ty-btn-gold { background: transparent; color: var(--ty-black); border: 1px solid var(--ty-gold); }
.ty-btn-gold:hover { background: var(--ty-gold-bright); border-color: var(--ty-gold-bright); color: var(--ty-black); }

/* ── Concierge note ── */
.ty-help { text-align: center; font-size: 12.5px; color: var(--ty-gray); line-height: 1.8; }
.ty-help a { color: var(--ty-gold); text-decoration: none; font-weight: 500; }
.ty-help a:hover { text-decoration: underline; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .thankyou-page { margin: 60px auto 50px; }
    .ty-title { font-size: 30px; }
    .ty-steps { grid-template-columns: 1fr; }
    .ty-summary { padding: 20px; }
    .ty-actions { flex-direction: column; }
    .ty-invoice-number { font-size: 21px; }
}

@media (prefers-reduced-motion: reduce) {
    .ty-reveal { opacity: 1; transform: none; animation: none; }
    .ty-check circle, .ty-check path { stroke-dashoffset: 0; animation: none; }
}
</style>

<div class="thankyou-page">

    {{-- Hero --}}
    <div class="ty-hero ty-reveal">
        <svg class="ty-check" viewBox="0 0 92 92" aria-hidden="true">
            <circle cx="46" cy="46" r="42"></circle>
            <path d="M30 47.5 L41.5 59 L62 35"></path>
        </svg>
        <span class="ty-eyebrow">Order Confirmed</span>
        <h1 class="ty-title">Thank You{{ !empty($data['name']) ? ', ' . $data['name'] : '' }}</h1>
        <p class="ty-sub">
            Your order has been placed successfully.
            @if (!empty($data['phone']))
                Our concierge will call you shortly at <strong>{{ $data['phone'] }}</strong> to confirm the details.
            @endif
        </p>
    </div>

    {{-- Order number --}}
    <div class="ty-invoice-card ty-reveal">
        <label>Your Order Number</label>
        <div class="ty-invoice-row">
            <span class="ty-invoice-number" id="tyInvoice">{{ $data['invoice'] }}</span>
            <button type="button" class="ty-copy-btn" id="tyCopyBtn" onclick="tyCopyInvoice()">
                <svg viewBox="0 0 24 24" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                <span id="tyCopyLabel">Copy</span>
            </button>
        </div>
        <p class="ty-invoice-note">Please save this number — you'll need it to track your order or for any query.</p>
    </div>

    {{-- What happens next --}}
    <div class="ty-steps ty-reveal">
        <div class="ty-step">
            <div class="ty-step-icon">
                <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.96.36 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0122 16.92z"/></svg>
            </div>
            <h4>Confirmation Call</h4>
            <p>We verify your order and delivery details over a quick phone call.</p>
        </div>
        <div class="ty-step">
            <div class="ty-step-icon">
                <svg viewBox="0 0 24 24"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
            </div>
            <h4>Carefully Packed</h4>
            <p>Each piece is inspected and wrapped with boutique-level care.</p>
        </div>
        <div class="ty-step">
            <div class="ty-step-icon">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-6 9 6v11a1 1 0 01-1 1H4a1 1 0 01-1-1V9z"/><path d="M9 21V12h6v9"/></svg>
            </div>
            <h4>Delivered To You</h4>
            <p>Shipped across {{ setting('COUNTRY_SERVE') ?? 'Bangladesh' }}, right to your doorstep.</p>
        </div>
    </div>

    {{-- Order summary --}}
    <div class="ty-summary ty-reveal">
        <h3>Order Summary</h3>

        @foreach ($items as $detail)
            @php
                $meta = collect([
                    'Qty ' . $detail->qty,
                    !in_array(strtolower((string) $detail->color), ['', 'blank']) ? $detail->color : null,
                    !in_array(strtolower((string) $detail->size), ['', 'blank']) ? $detail->size : null,
                ])->filter()->implode(' · ');
            @endphp
            <div class="ty-item">
                <img src="{{ optional($detail->product)->hero_image_url ?? asset('frontend/images/placeholder.png') }}" alt="{{ $detail->title }}" loading="lazy">
                <div class="ty-item-info">
                    <h5>{{ $detail->title }}</h5>
                    <span>{{ $meta }}</span>
                </div>
                <span class="ty-item-price">{{ $currency }} {{ number_format((float) $detail->total_price) }}</span>
            </div>
        @endforeach

        <div class="ty-total-row"><span>Subtotal</span><span>{{ $currency }} {{ number_format($subtotal) }}</span></div>
        <div class="ty-total-row"><span>Shipping</span><span>{{ $shipping > 0 ? $currency . ' ' . number_format($shipping) : 'Free' }}</span></div>
        @if ($adjustment > 0.009)
            <div class="ty-total-row"><span>Gift wrapping &amp; extras</span><span>{{ $currency }} {{ number_format($adjustment) }}</span></div>
        @elseif ($adjustment < -0.009)
            <div class="ty-total-row"><span>Discount</span><span>− {{ $currency }} {{ number_format(abs($adjustment)) }}</span></div>
        @endif
        <div class="ty-total-row grand"><span>Total</span><span>{{ $currency }} {{ number_format($total) }}</span></div>
    </div>

    {{-- Actions --}}
    <div class="ty-actions ty-reveal">
        <a href="{{ route('track') }}" class="ty-btn ty-btn-dark">Track Your Order</a>
        <a href="{{ route('home') }}" class="ty-btn ty-btn-gold">Continue Shopping</a>
    </div>

    <p class="ty-help ty-reveal">
        Questions about your order? Visit <a href="{{ route('track') }}">Order Tracking</a>
        and use your order number above — our concierge team is happy to help.
    </p>

</div>

@endsection

@push('js')
<script>
    /* Copy the order number (clipboard API with a fallback for older browsers). */
    function tyCopyInvoice() {
        var text = document.getElementById('tyInvoice').textContent.trim();
        var done = function () {
            var btn = document.getElementById('tyCopyBtn');
            document.getElementById('tyCopyLabel').textContent = 'Copied';
            btn.classList.add('copied');
            setTimeout(function () {
                document.getElementById('tyCopyLabel').textContent = 'Copy';
                btn.classList.remove('copied');
            }, 2000);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(done);
        } else {
            var ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); done(); } catch (e) {}
            document.body.removeChild(ta);
        }
    }

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        "event": "purchase",
        "ecommerce": {
            "transaction_id": "{{ $data['invoice'] }}",
            "value": {{ $data['total'] }},
            "currency": "BDT",
            "shipping": {{ $data['shipping_charge'] ?? 0 }},
            "total_quantity": {{ $data['orderDetails']->sum('qty') }},
            "items": [
                @foreach($data['orderDetails'] as $detail)
                {
                    "item_id": "{{ $detail->product_id }}",
                    "item_name": "{{ $detail->title }}",
                    "price": {{ $detail->price }},
                    "quantity": {{ $detail->qty }}
                },
                @endforeach
            ],
            "customer_info": {
                "first_name": "{{ $data['name'] }}",
                "phone": "{{ $data['phone'] }}"
            }
        }
    });
</script>
@endpush
