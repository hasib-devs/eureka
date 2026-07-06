@extends('layouts.frontend.app')

@push('meta')
<meta name='description' content="All Products"/>
<meta name='keywords' content="@foreach($products as $product){{$product->title.', '}}@endforeach" />
@endpush

@section('title', 'Shop')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');

    /* ── HERO ── */
    .sp-hero {
        padding: 56px 24px 42px;
        text-align: center;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .sp-hero-eyebrow {
        margin: 0 0 12px;
        font-size: 11px;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: #888;
        font-weight: 500;
    }
    .sp-hero-title {
        margin: 0;
        font-family: 'Cinzel Decorative', Georgia, serif;
        font-size: 44px;
        font-weight: 700;
        color: #111;
        line-height: 1;
        text-transform: uppercase;
    }

    /* ── FULL-WIDTH PRODUCT GRID (same as homepage products section) ── */
    .sp-products {
        background: #fff;
        padding: 40px 0 66px;
        width: 100vw !important;
        max-width: 100vw !important;
        margin-left: calc(50% - 50vw) !important;
        margin-right: calc(50% - 50vw) !important;
    }
    .sp-products .lux-product-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
        padding: 0;
        margin: 0;
    }

    @media (min-width: 640px) {
        .sp-products .lux-product-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .sp-products .lux-product-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .sp-hero-title { font-size: 30px; }
        .sp-hero { padding: 40px 20px 32px; }
    }
</style>

{{-- ====== HERO ====== --}}
<div class="sp-hero">
    <p class="sp-hero-eyebrow">Curated Collection</p>
    <h1 class="sp-hero-title">All Products</h1>
</div>

{{-- ====== FULL-WIDTH PRODUCT GRID ====== --}}
<section class="sp-products">
    <div class="lux-product-grid">
        @forelse ($products as $product)
            <x-lux-product-card :product="$product" />
        @empty
            <x-product-empty-component />
        @endforelse
    </div>
</section>

<x-add-cart-modal />
@include('components.cart-modal-attri')

@endsection

@push('js')
    <script>
    $(document).ready(function () {

        // ── Qty buttons ──
        $('.value-plus').on('click', function () {
            var d = $(this).parent().find('.value'), v = parseInt(d.val(), 10) + 1;
            d.val(v); $('input#qty').val(v);
        });
        $('.value-minus').on('click', function () {
            var d = $(this).parent().find('.value'), v = parseInt(d.val(), 10) - 1;
            if (v >= 1) { d.val(v); $('input#qty').val(v); }
        });

        // ── Cart modal form ──
        $(document).on('submit', '#addToCart', function (e) {
            e.preventDefault();
            var url = $(this).attr('action'), type = $(this).attr('method'),
                btn = $(this), formData = $(this).serialize();
            $.ajax({
                type: type, url: url, data: formData, dataType: 'JSON',
                beforeSend: function () { $(btn).attr('disabled', true); },
                success: function (response) {
                    if (response.alert != 'Congratulations') {
                        $.toast({ heading: 'Warning', text: response.message, icon: 'warning', position: 'top-right', stack: false });
                    } else {
                        loadCartOnCanvas();
                        $('span#total-cart-amount').text(response.subtotal);
                        $.toast({ heading: 'Congratulations', text: response.message, icon: 'success', position: 'top-right', stack: false });
                        $('#cart-modal').modal('hide');
                    }
                },
                complete: function () { $(btn).attr('disabled', false); },
                error: function (xhr) {
                    $.toast({ heading: xhr.status, text: xhr.responseJSON?.message, icon: 'error', position: 'top-right', stack: false });
                }
            });
        });
    });

    // ── Lux add-to-cart AJAX ──
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ajax-lux-cart-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var form = document.getElementById(btn.getAttribute('data-form-id'));
                if (!form) return;
                btn.disabled    = true;
                btn.textContent = 'Adding…';
                fetch(form.action, {
                    method:  'POST',
                    headers: {
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(form)
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    btn.disabled    = false;
                    btn.textContent = 'Add to Cart';
                    if (typeof loadCartOnCanvas === 'function') loadCartOnCanvas();
                    $.toast({
                        heading:  data.alert === 'Congratulations' ? 'Congratulations' : 'Notice',
                        text:     data.message,
                        icon:     data.alert === 'Congratulations' ? 'success' : 'warning',
                        position: 'top-right',
                        stack:    false
                    });
                })
                .catch(function () { btn.disabled = false; btn.textContent = 'Add to Cart'; });
            });
        });
    });
    </script>
@endpush
