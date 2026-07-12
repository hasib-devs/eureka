@php
    $cur = setting('CURRENCY_CODE_MIN') ?? 'TK';
    $money = fn ($n) => number_format((float) $n, 2).' '.$cur;
    $statusColors = [
        'Draft' => ['bg' => '#f3f4f6', 'text' => '#6b7280'],
        'Unpaid' => ['bg' => '#fff1f2', 'text' => '#e11d48'],
        'Partially Paid' => ['bg' => '#fffbeb', 'text' => '#d97706'],
        'Paid' => ['bg' => '#ecfdf5', 'text' => '#059669'],
    ];
    $sc = $statusColors[$vm['status']] ?? $statusColors['Draft'];
    $mobile = $vm['payment_details']['mobile'] ?? null;
    $bank = $vm['payment_details']['bank'] ?? [];
    $showBank = ($vm['payment_method'] ?? null) === 'Bank Transfer'
        && (($bank['bank_name'] ?? null) || ($bank['account_number'] ?? null));
@endphp

<div class="invoice-preview" style="--accent: {{ $vm['accent'] }}; --header-bg: {{ $vm['header_bg'] }};">
    <div class="inv-head">
        <div class="inv-head-row">
            <div class="inv-head-left">
                @if (! empty($vm['business']['logo']))
                    <img src="{{ $vm['business']['logo'] }}" class="inv-logo" alt="logo">
                @else
                    <div class="inv-logo-fallback">{{ \Illuminate\Support\Str::substr($vm['business']['name'] ?? 'A', 0, 1) }}</div>
                @endif
                <div>
                    <div class="inv-biz-name">{{ $vm['business']['name'] }}</div>
                    @if (! empty($vm['business']['tagline']))
                        <div class="inv-tag">{{ $vm['business']['tagline'] }}</div>
                    @endif
                </div>
            </div>
            <div class="inv-head-meta">
                @if (! empty($vm['business']['phone']))
                    <div class="inv-head-meta-col"><div>Phone:</div><div>{{ $vm['business']['phone'] }}</div></div>
                @endif
                @if (! empty($vm['business']['email']))
                    <div class="inv-head-meta-col"><div>Email:</div><div>{{ $vm['business']['email'] }}</div></div>
                @endif
                @if (! empty($vm['business']['address']))
                    <div class="inv-head-meta-col"><div>Area:</div><div>{{ $vm['business']['address'] }}</div></div>
                @endif
            </div>
        </div>
        <svg class="inv-curve" viewBox="0 0 800 46" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,18 C260,50 560,0 800,20 L800,46 L0,46 Z" fill="#bcbfc3" />
            <path d="M0,28 C300,55 540,9 800,29 L800,46 L0,46 Z" fill="#ffffff" />
        </svg>
    </div>

    <div class="inv-body">
        <div class="inv-tobox-row">
            <div>
                <div class="inv-to-label">To:</div>
                <div class="inv-to-name">{{ $vm['customer']['name'] ?: '—' }}</div>
                @if (! empty($vm['customer']['address']))
                    <div style="font-size:12.5px;color:#6b7280;margin-top:3px;">{{ $vm['customer']['address'] }}</div>
                @endif
                @if (! empty($vm['customer']['phone']))
                    <div style="font-size:12.5px;color:#6b7280;">Phone: {{ $vm['customer']['phone'] }}</div>
                @endif
            </div>
            <div>
                <div class="inv-title">INVOICE</div>
                <div class="inv-details">
                    Invoice No: <b>{{ $vm['number'] }}</b><br>
                    Date: <b>{{ $vm['date'] ? \Illuminate\Support\Carbon::parse($vm['date'])->format('d M, Y') : '—' }}</b><br>
                    Due Date: <b>{{ $vm['due_date'] ? \Illuminate\Support\Carbon::parse($vm['due_date'])->format('d M, Y') : '—' }}</b>
                </div>
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <span class="inv-status-badge" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }};">{{ $vm['status'] }}</span>
        </div>

        <table class="inv-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="th-num" style="text-align:center;">Qty</th>
                    <th class="th-num">Price</th>
                    <th class="th-num">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vm['items'] as $it)
                    <tr>
                        <td>{{ $it['description'] ?: '—' }}</td>
                        <td style="text-align:center;">{{ rtrim(rtrim(number_format($it['qty'], 2), '0'), '.') }}</td>
                        <td style="text-align:right;">{{ $money($it['unit_price']) }}</td>
                        <td style="text-align:right;font-weight:600;color:#1c1c22;">{{ $money($it['line_total']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:#9ca3af;">No items</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="inv-bottom-row">
            <div class="inv-bottom-left">
                @if ($mobile && (! empty($mobile['number']) || ! empty($mobile['qr'])))
                    <div class="inv-pay-box">
                        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                            @if (! empty($mobile['qr']))
                                <img src="{{ $mobile['qr'] }}" class="inv-qr" alt="qr">
                            @endif
                            <div>
                                <div class="inv-label" style="margin-bottom:4px;">Pay via {{ $mobile['label'] }}</div>
                                @if (! empty($mobile['number']))
                                    <div style="font-size:15px;font-weight:800;color:#1c1c22;">{{ $mobile['number'] }}</div>
                                @endif
                                <div style="font-size:12px;color:#6b7280;margin-top:2px;">Scan the QR code or send to the number above</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showBank)
                    <div class="inv-pay-box">
                        <div class="inv-label">Bank Payment Details</div>
                        <div style="font-size:12.5px;color:#374151;display:flex;flex-direction:column;gap:3px;">
                            @if (! empty($bank['bank_name']))<div>Bank: <span style="font-weight:600;color:#1c1c22;">{{ $bank['bank_name'] }}</span></div>@endif
                            @if (! empty($bank['account_name']))<div>Account Name: <span style="font-weight:600;color:#1c1c22;">{{ $bank['account_name'] }}</span></div>@endif
                            @if (! empty($bank['account_number']))<div>Account Number: <span style="font-weight:600;color:#1c1c22;">{{ $bank['account_number'] }}</span></div>@endif
                            @if (! empty($bank['branch_name']))<div>Branch: <span style="font-weight:600;color:#1c1c22;">{{ $bank['branch_name'] }}</span></div>@endif
                            @if (! empty($bank['routing_number']))<div>Routing Number: <span style="font-weight:600;color:#1c1c22;">{{ $bank['routing_number'] }}</span></div>@endif
                        </div>
                    </div>
                @endif

                @if (! empty($vm['notes']))
                    <div style="margin-top:16px;">
                        <div class="inv-label">Notes</div>
                        <div style="font-size:13px;color:#374151;">{{ $vm['notes'] }}</div>
                    </div>
                @endif
            </div>

            <div class="inv-bottom-right">
                <div class="inv-summary">
                    <div class="inv-summary-row"><span>Sub Total</span><span style="color:#1c1c22;">{{ $money($vm['subtotal']) }}</span></div>
                    @if ((float) $vm['discount'] > 0)
                        <div class="inv-summary-row"><span>Discount</span><span style="color:#1c1c22;">- {{ $money($vm['discount']) }}</span></div>
                    @endif
                    <div class="inv-summary-row"><span>Delivery Charge</span><span style="color:#1c1c22;">{{ $money($vm['delivery_charge']) }}</span></div>
                    @if ((float) $vm['additional_charges'] > 0)
                        <div class="inv-summary-row"><span>Additional Charges</span><span style="color:#1c1c22;">{{ $money($vm['additional_charges']) }}</span></div>
                    @endif
                    <div class="inv-summary-row"><span>Advance Paid</span><span style="color:#1c1c22;">{{ $money($vm['advance_paid']) }}</span></div>
                    <div class="inv-summary-row due"><span>Due Amount</span><span>{{ $money($vm['due_amount']) }}</span></div>
                </div>
                <div class="inv-total-bar">
                    <span>Grand Total</span>
                    <span>{{ $money($vm['grand_total']) }}</span>
                </div>
            </div>
        </div>

        <div class="inv-footer">
            <div>
                <div class="inv-thankyou">Thank you for your business!</div>
                @if (! empty($vm['business']['tagline']))
                    <div class="inv-terms">{{ $vm['business']['name'] }} — {{ $vm['business']['tagline'] }}</div>
                @endif
            </div>
            <div style="text-align:center;">
                @if (! empty($vm['business']['signature']))
                    <img src="{{ $vm['business']['signature'] }}" class="inv-sig" alt="signature">
                @else
                    <div style="height:42px;"></div>
                @endif
                <div style="font-size:11px;color:#9ca3af;border-top:1px solid #c7c9cc;padding-top:4px;padding-left:16px;padding-right:16px;margin-top:4px;">{{ $vm['business']['owner'] ?: $vm['business']['name'] }}</div>
            </div>
        </div>
    </div>
</div>
