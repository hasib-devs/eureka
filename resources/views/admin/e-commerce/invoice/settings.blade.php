@extends('layouts.admin.app')

@section('title', 'Invoice Settings')

@php
    $inp = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary';
    $lbl = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-wide text-slate-500';
    $accent = setting('invoice_accent', '#f5b400');
    $headerBg = setting('invoice_header_bg', '#1c1c22');
    $mobiles = [
        ['key' => 'bkash', 'label' => 'bKash'],
        ['key' => 'nagad', 'label' => 'Nagad'],
        ['key' => 'rocket', 'label' => 'Rocket'],
    ];
@endphp

@section('content')

    <form method="POST" action="{{ route('admin.invoices.settings.update') }}" enctype="multipart/form-data"
        class="mx-auto max-w-3xl space-y-4">
        @csrf

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.invoices.index') }}" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">Invoice Settings</h1>
            </div>
            <x-ui.button variant="primary" type="submit"><i class="fas fa-check"></i> Save Settings</x-ui.button>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Appearance --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-palette"></i></div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Appearance</h3>
                    <p class="text-xs text-slate-500">Colors used on the printed invoice.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                <div>
                    <label class="{{ $lbl }}">Accent Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="invoice_accent" value="{{ old('invoice_accent', $accent) }}" class="h-10 w-14 cursor-pointer rounded-lg border border-slate-300">
                        <input type="text" value="{{ old('invoice_accent', $accent) }}" readonly class="{{ $inp }}" onclick="this.previousElementSibling.click()">
                    </div>
                </div>
                <div>
                    <label class="{{ $lbl }}">Header Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="invoice_header_bg" value="{{ old('invoice_header_bg', $headerBg) }}" class="h-10 w-14 cursor-pointer rounded-lg border border-slate-300">
                        <input type="text" value="{{ old('invoice_header_bg', $headerBg) }}" readonly class="{{ $inp }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Signature --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-signature"></i></div>
                <h3 class="text-base font-semibold text-slate-900">Signature</h3>
            </div>
            <div class="flex items-center gap-4 p-5">
                <div class="grid h-16 w-28 place-items-center overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                    @if (setting('invoice_signature'))
                        <img src="{{ asset('uploads/invoice/'.setting('invoice_signature')) }}" class="max-h-full max-w-full object-contain" alt="signature">
                    @else
                        <i class="fas fa-signature text-slate-300"></i>
                    @endif
                </div>
                <label class="cursor-pointer rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                    Upload<input type="file" name="invoice_signature" accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        {{-- Mobile payments --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-mobile-alt"></i></div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Mobile Payments</h3>
                    <p class="text-xs text-slate-500">Shown on invoices when that payment method is selected.</p>
                </div>
            </div>
            <div class="space-y-5 p-5">
                @foreach ($mobiles as $m)
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2">
                        <div>
                            <label class="{{ $lbl }}">{{ $m['label'] }} Number</label>
                            <input type="text" name="invoice_{{ $m['key'] }}_number" value="{{ old('invoice_'.$m['key'].'_number', setting('invoice_'.$m['key'].'_number')) }}" placeholder="01XXXXXXXXX" class="{{ $inp }}">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="grid h-16 w-16 place-items-center overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                                @if (setting('invoice_'.$m['key'].'_qr'))
                                    <img src="{{ asset('uploads/invoice/'.setting('invoice_'.$m['key'].'_qr')) }}" class="max-h-full max-w-full object-contain" alt="qr">
                                @else
                                    <i class="fas fa-qrcode text-slate-300"></i>
                                @endif
                            </div>
                            <label class="cursor-pointer rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                                QR<input type="file" name="invoice_{{ $m['key'] }}_qr" accept="image/*" class="hidden">
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end pb-8">
            <x-ui.button variant="primary" type="submit"><i class="fas fa-check"></i> Save Settings</x-ui.button>
        </div>
    </form>

@endsection

@push('js')
    <script>
        document.querySelectorAll('input[type=color]').forEach(function (picker) {
            picker.addEventListener('input', function () {
                var text = picker.parentElement.querySelector('input[type=text]');
                if (text) text.value = picker.value;
            });
        });
    </script>
@endpush
