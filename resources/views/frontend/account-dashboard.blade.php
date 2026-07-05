@extends('layouts.frontend.app')

@section('title', 'Account')

@section('content')
@php
    use App\Models\Product;

    $low_products = Product::where('quantity', '<', 6)->where('user_id', auth()->id())->count();

    $stats = [
        ['label' => 'Total Blogs',      'value' => $blog,       'icon' => 'fa-newspaper',        'tone' => 'slate'],
        ['label' => 'Total Orders',     'value' => $order,      'icon' => 'fa-box',              'tone' => 'gold'],
        ['label' => 'Pending Orders',   'value' => $pending,    'icon' => 'fa-clock',            'tone' => 'amber'],
        ['label' => 'Processing',       'value' => $processing, 'icon' => 'fa-hourglass-half',   'tone' => 'blue'],
        ['label' => 'Shipping',         'value' => $shipping,   'icon' => 'fa-truck',            'tone' => 'indigo'],
        ['label' => 'Delivered',        'value' => $delevery,   'icon' => 'fa-check-circle',     'tone' => 'green'],
        ['label' => 'Cancelled',        'value' => $cancel,     'icon' => 'fa-times-circle',     'tone' => 'red'],
    ];
@endphp

<style>
    .ad-wrap { font-family: var(--font-store, "Muli", sans-serif); padding: 28px 0 48px; }
    .ad-wrap .ad-grid-main { display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-start; }
    .ad-side { flex: 0 0 300px; max-width: 300px; }
    .ad-main { flex: 1 1 480px; min-width: 0; }

    .ad-alert {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 18px; margin-bottom: 20px; border-radius: 14px;
        background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;
        font-size: 14px; font-weight: 600; text-decoration: none;
    }
    .ad-alert:hover { background: #fee2e2; color: #991b1b; }
    .ad-alert i { font-size: 16px; }

    .ad-header { margin-bottom: 22px; }
    .ad-header h2 { font-size: 22px; font-weight: 800; color: #17130a; margin: 0; letter-spacing: -.2px; }
    .ad-header p { font-size: 14px; color: #8a8577; margin: 4px 0 0; }
    .ad-header .ad-gold { color: #b8964a; }

    .ad-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
    .ad-card {
        position: relative; background: #fff; border: 1px solid #ececec; border-radius: 16px;
        padding: 18px; box-shadow: 0 1px 2px rgba(16,24,40,.04);
        transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
    }
    .ad-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(16,24,40,.08); border-color: #e2d9bf; }
    .ad-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; font-size: 18px; margin-bottom: 14px; }
    .ad-label { font-size: 12px; font-weight: 600; letter-spacing: .5px; text-transform: uppercase; color: #98918263; color: #928a76; margin: 0; }
    .ad-value { font-size: 30px; font-weight: 800; color: #17130a; line-height: 1.1; margin: 4px 0 0; }

    /* tones */
    .ad-t-gold   { background: #f7efd8; color: #a67f16; }
    .ad-t-slate  { background: #f1f5f9; color: #475569; }
    .ad-t-amber  { background: #fef3c7; color: #b45309; }
    .ad-t-blue   { background: #e0f2fe; color: #0369a1; }
    .ad-t-indigo { background: #e0e7ff; color: #4338ca; }
    .ad-t-green  { background: #dcfce7; color: #15803d; }
    .ad-t-red    { background: #fee2e2; color: #b91c1c; }

    @media (max-width: 991px) {
        .ad-side { flex-basis: 100%; max-width: 100%; }
        .ad-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 520px) {
        .ad-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        .ad-value { font-size: 25px; }
    }
</style>

<div class="ad-wrap">
    <div class="container">
        <div class="ad-grid-main">
            <aside class="ad-side">
                @include('layouts.frontend.partials.userside')
            </aside>

            <section class="ad-main">
                @if ($low_products > 0)
                    <a class="ad-alert" href="{{ route('vendor.low.product') }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        You have {{ $low_products }} low quantity {{ Str::plural('product', $low_products) }} — restock soon.
                    </a>
                @endif

                <div class="ad-header">
                    <h2>Welcome back, <span class="ad-gold">{{ auth()->user()?->name }}</span></h2>
                    <p>Here’s an overview of your account activity.</p>
                </div>

                <div class="ad-grid">
                    @foreach ($stats as $s)
                        <div class="ad-card">
                            <div class="ad-icon ad-t-{{ $s['tone'] }}"><i class="fas {{ $s['icon'] }}"></i></div>
                            <p class="ad-label">{{ $s['label'] }}</p>
                            <p class="ad-value">{{ $s['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
