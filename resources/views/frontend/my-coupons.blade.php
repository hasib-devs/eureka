@extends('layouts.frontend.app')

@section('title', 'My Coupons')

@section('content')
<div class="customar-dashboard">
    <div class="container">
        <div class="customar-access row">
            <div class="customar-menu col-md-3">
                @include('layouts.frontend.partials.userside')
            </div>
            <div class="col-md-9 products mt-5">
                <div class="customer-right">
                    <style>
                        .mycpn-title { font-size: 22px; font-weight: 700; color: #1a1a1a; margin: 0 0 18px; }
                        .mycpn-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
                        @media (max-width: 767px) { .mycpn-grid { grid-template-columns: 1fr; } }
                        .mycpn-card {
                            display: flex; align-items: stretch; border-radius: 12px; overflow: hidden;
                            border: 1px solid #eee; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.05);
                        }
                        .mycpn-left {
                            flex: 0 0 40%; padding: 16px; color: #fff; display: flex; flex-direction: column;
                            justify-content: center; background: var(--primary_color, #f85606);
                        }
                        .mycpn-value { font-size: 22px; font-weight: 800; line-height: 1.1; }
                        .mycpn-src { margin-top: 4px; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; opacity: .9; }
                        .mycpn-right { flex: 1; padding: 16px; display: flex; flex-direction: column; justify-content: center; gap: 6px; }
                        .mycpn-code {
                            font-family: monospace; font-size: 16px; font-weight: 700; color: #1a1a1a;
                            letter-spacing: 1px; border: 1px dashed #ccc; border-radius: 6px; padding: 6px 10px; display: inline-block;
                        }
                        .mycpn-meta { font-size: 12px; color: #777; }
                        .mycpn-copy {
                            align-self: flex-start; margin-top: 4px; border: none; background: #111; color: #fff;
                            font-size: 12px; padding: 6px 14px; border-radius: 6px; cursor: pointer;
                        }
                        .mycpn-copy:hover { background: #333; }
                        .mycpn-card.is-inactive { opacity: .55; }
                        .mycpn-card.is-inactive .mycpn-left { background: #9ca3af; }
                        .mycpn-empty { text-align: center; padding: 48px 20px; color: #777; }
                        .mycpn-empty p { margin: 4px 0; }
                    </style>

                    <h3 class="mycpn-title">My Coupons</h3>

                    @if ($coupons->isEmpty())
                        <div class="mycpn-empty">
                            <p>You haven&rsquo;t earned any coupons yet.</p>
                            <p>Leave a product review or place an order to earn reward coupons.</p>
                        </div>
                    @else
                        <div class="mycpn-grid">
                            @foreach ($coupons as $c)
                                @php
                                    $expired = \Carbon\Carbon::parse($c->expire_date)->isPast();
                                    $used = $c->available_limit <= 0;
                                    $inactive = $expired || $used || ! $c->status;
                                    $amount = rtrim(rtrim(number_format((float) $c->discount, 2, '.', ''), '0'), '.');
                                    $label = $c->discount_type == 'percent' ? $amount.'% OFF' : '৳'.$amount.' OFF';
                                @endphp
                                <div class="mycpn-card {{ $inactive ? 'is-inactive' : '' }}">
                                    <div class="mycpn-left">
                                        <div class="mycpn-value">{{ $label }}</div>
                                        <div class="mycpn-src">{{ ucfirst($c->source ?? 'Reward') }} reward</div>
                                    </div>
                                    <div class="mycpn-right">
                                        <div class="mycpn-code">{{ $c->code }}</div>
                                        <div class="mycpn-meta">
                                            @if ($used)
                                                Already used
                                            @elseif ($expired)
                                                Expired
                                            @else
                                                Valid till {{ \Carbon\Carbon::parse($c->expire_date)->format('d M Y') }}
                                            @endif
                                        </div>
                                        @unless ($inactive)
                                            <button type="button" class="mycpn-copy" onclick="mycpnCopy(this, '{{ $c->code }}')">Copy code</button>
                                        @endunless
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mycpnCopy(btn, code) {
    if (navigator.clipboard) { navigator.clipboard.writeText(code).catch(function () {}); }
    var original = btn.textContent;
    btn.textContent = 'Copied!';
    setTimeout(function () { btn.textContent = original; }, 1500);
}
</script>
@endsection
