@extends('layouts.admin.app')

@section('title', 'Invoice '.$vm['number'])

@push('css')
    <style>
        .invoice-preview{background:#fff;color:#1f2937;border-radius:14px;border:1px solid #e5e7eb;padding:0;max-width:800px;margin:0 auto;overflow:hidden;position:relative;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;}
        .invoice-preview *{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
        .inv-head{background:var(--header-bg,#1c1c22);color:#fff;padding:24px 32px;position:relative;overflow:hidden;}
        .inv-head-row{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;position:relative;z-index:2;}
        .inv-curve{position:absolute;left:0;right:0;bottom:-1px;width:100%;z-index:1;height:46px;display:block;}
        .inv-head-left{display:flex;flex-direction:column;align-items:center;gap:10px;flex:1;}
        .inv-logo{height:62px;width:auto;max-width:160px;object-fit:contain;display:block;}
        .inv-logo-fallback{width:54px;height:54px;border-radius:8px;background:var(--accent,#f5b400);display:flex;align-items:center;justify-content:center;color:#1c1c22;font-weight:800;font-size:20px;}
        .inv-biz-name{font-weight:700;font-size:18px;letter-spacing:.03em;color:#fff;line-height:1;text-align:center;margin:0;}
        .inv-tag{font-size:10px;color:#9ca3af;font-style:italic;text-align:center;line-height:1;margin:0;}
        .inv-head-meta{display:flex;gap:26px;font-size:11px;color:#fff;padding-top:4px;}
        .inv-head-meta-col{max-width:170px;}
        .inv-head-meta-col div:first-child{color:var(--accent,#f5b400);font-weight:700;font-size:9.5px;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;}
        .inv-head-meta-col div:last-child{line-height:1.5;color:#d1d5db;}
        .inv-body{padding:30px 32px;position:relative;z-index:2;background:#fff;}
        .inv-tobox-row{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:22px;}
        .inv-to-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;margin-bottom:5px;}
        .inv-to-name{font-weight:700;font-size:14.5px;color:#1c1c22;text-transform:uppercase;}
        .inv-title{font-size:32px;font-weight:800;letter-spacing:.02em;text-align:right;color:#1c1c22;}
        .inv-details{font-size:12.5px;color:#6b7280;text-align:right;margin-top:8px;line-height:1.7;}
        .inv-details b{color:#1c1c22;font-weight:600;}
        .inv-status-badge{display:inline-block;padding:4px 11px;border-radius:999px;font-size:11px;font-weight:700;}
        .inv-label{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#b8860b;margin-bottom:6px;}
        .inv-table{width:100%;font-size:13.5px;border-collapse:collapse;}
        .inv-table thead tr{background:var(--accent,#f5b400);}
        .inv-table th{padding:11px 14px;font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:#1c1c22;text-align:left;font-weight:700;}
        .inv-table th.th-num{background:#3a3a42;color:#fff;text-align:right;}
        .inv-table td{padding:13px 14px;border-bottom:1px solid #ececec;color:#374151;vertical-align:top;}
        .inv-table tbody tr:nth-child(even){background:#f3f4f5;}
        .inv-bottom-row{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-top:18px;flex-wrap:wrap;}
        .inv-bottom-left{flex:1 1 240px;min-width:0;}
        .inv-bottom-right{flex:1 1 240px;min-width:0;}
        .inv-summary{display:flex;flex-direction:column;gap:2px;}
        .inv-summary-row{display:flex;justify-content:space-between;font-size:13px;color:#6b7280;padding:2px 0;}
        .inv-summary-row.due{font-weight:700;color:#dc2626;}
        .inv-total-bar{display:flex;justify-content:space-between;align-items:center;background:var(--accent,#f5b400);padding:14px 20px;border-radius:4px;margin-top:10px;}
        .inv-total-bar span:first-child{font-weight:800;font-size:13.5px;color:#1c1c22;letter-spacing:.04em;text-transform:uppercase;}
        .inv-total-bar span:last-child{font-weight:800;font-size:18px;color:#1c1c22;}
        .inv-pay-box{margin-bottom:14px;color:#374151;}
        .inv-qr{width:74px;height:74px;object-fit:contain;border:1px solid #e5e7eb;border-radius:8px;background:#fff;}
        .inv-footer{display:flex;align-items:flex-end;justify-content:space-between;margin-top:30px;padding-top:18px;}
        .inv-sig{height:42px;object-fit:contain;}
        .inv-thankyou{font-weight:700;color:#1c1c22;font-size:14.5px;}
        .inv-terms{font-size:11px;color:#9ca3af;margin-top:4px;max-width:320px;}

        @media print{
            body *{visibility:hidden;}
            .invoice-preview, .invoice-preview *{visibility:visible;}
            .invoice-preview{position:absolute;top:0;left:0;width:100%;border:none;border-radius:0;}
            .no-print{display:none !important;}
        }
        @page{size:A4;margin:0;}
    </style>
@endpush

@section('content')

    <div class="no-print mb-5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.invoices.index') }}" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-lg font-bold tracking-tight text-slate-900">Invoice {{ $vm['number'] }}</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if ($vm['source'] === 'manual')
                <x-ui.button variant="outline" size="sm" :href="route('admin.invoices.edit', $vm['id'])">
                    <i class="fas fa-pen"></i> Edit
                </x-ui.button>
                <x-ui.button variant="outline" size="sm" :href="route('admin.invoices.duplicate', $vm['id'])">
                    <i class="fas fa-copy"></i> Duplicate
                </x-ui.button>
            @endif
            <x-ui.button variant="outline" size="sm" onclick="invPrint()">
                <i class="fas fa-print"></i> Print
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" onclick="invPrint()">
                <i class="fas fa-download"></i> Download PDF
            </x-ui.button>
            @if ($vm['source'] === 'manual')
                <form method="POST" action="{{ route('admin.invoices.destroy', $vm['id']) }}"
                    onsubmit="return confirm('Delete invoice {{ $vm['number'] }}? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <x-ui.button variant="danger" size="sm" type="submit">
                        <i class="fas fa-trash"></i>
                    </x-ui.button>
                </form>
            @endif
        </div>
    </div>

    <div class="print-scale-wrap">
        @include('admin.e-commerce.invoice._template', ['vm' => $vm])
    </div>

@endsection

@push('js')
    <script>
        function invPrint() {
            var existing = document.getElementById('inv-print-page');
            if (existing) existing.remove();
            var el = document.querySelector('.invoice-preview');
            var heightMm = 297;
            if (el) {
                var pxPerMm = 96 / 25.4;
                var neededMm = Math.ceil(el.scrollHeight / pxPerMm) + 4;
                heightMm = Math.max(297, neededMm);
            }
            var style = document.createElement('style');
            style.id = 'inv-print-page';
            style.innerHTML = '@page{ size: 210mm ' + heightMm + 'mm; margin:0; }';
            document.head.appendChild(style);
            setTimeout(function () { window.print(); }, 30);
        }
    </script>
@endpush
