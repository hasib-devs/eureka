@extends('layouts.admin.app')

@section('title', 'Settings')

@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Payment Gateways</h1>
                <p class="mt-1 text-sm text-slate-500">Enable payment methods and configure their credentials</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Gateways</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
        $checkbox = 'h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30';
    @endphp

    <section class="mb-6">
        <form action="{{ route('admin.setting_g') }}" method="POST">
            @csrf

            <div class="space-y-4">

                {{-- Manual / offline payments --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Manual / Offline Pay</h2>
                            <p class="text-xs text-slate-500">Mobile banking numbers customers can send money to</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                <input type="checkbox" name="bkash" class="{{ $checkbox }}"
                                    @if (setting('g_bkash') == 'true') checked @endif>
                                Bkash
                            </label>
                            <input class="{{ $control }} mt-2" type="text" name="bkash" id="bkash"
                                value="{{ setting('bkash') ?? '01721*****' }}">
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                <input type="checkbox" name="nagad" class="{{ $checkbox }}"
                                    @if (setting('g_nagad') == 'true') checked @endif>
                                Nagad
                            </label>
                            <input class="{{ $control }} mt-2" type="text" name="nagad" id="nagad"
                                value="{{ setting('nagad') ?? '01721*****' }}">
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                <input type="checkbox" name="rocket" class="{{ $checkbox }}"
                                    @if (setting('g_rocket') == 'true') checked @endif>
                                Rocket
                            </label>
                            <input class="{{ $control }} mt-2" type="text" name="rocket" id="rocket"
                                value="{{ setting('rocket') ?? '01721*****' }}">
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                <input type="checkbox" name="wallate" class="{{ $checkbox }}"
                                    @if (setting('g_wallate') == 'true') checked @endif>
                                Wallate
                            </label>
                            <p class="mt-2 text-xs text-slate-500">Wallet means the store point withdrawn amount is used for purchasing products.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                    {{-- Bank --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-900">Bank Transfer</h2>
                                    <p class="text-xs text-slate-500">Bank account shown to customers</p>
                                </div>
                            </div>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <input type="checkbox" name="bank" class="{{ $checkbox }}"
                                    @if (setting('g_bank') == 'true') checked @endif>
                                Enabled
                            </label>
                        </div>

                        <div class="space-y-4 p-5">
                            <div>
                                <label for="bank_name" class="mb-1 block text-sm font-medium text-slate-700">Bank Name</label>
                                <input class="{{ $control }}" type="text" name="bank_name" id="bank_name"
                                    value="{{ setting('bank_name') ?? 'Bangladesh Bank' }}">
                            </div>
                            <div>
                                <label for="bank_account" class="mb-1 block text-sm font-medium text-slate-700">Bank A/C Number</label>
                                <input class="{{ $control }}" type="number" name="bank_account" id="bank_account"
                                    value="{{ setting('bank_account') ?? '000000000' }}">
                            </div>
                            <div>
                                <label for="branch_name" class="mb-1 block text-sm font-medium text-slate-700">Branch Name</label>
                                <input class="{{ $control }}" type="text" name="branch_name" id="branch_name"
                                    value="{{ setting('branch_name') ?? 'Kishoreganj Sadar' }}">
                            </div>
                            <div>
                                <label for="holder_name" class="mb-1 block text-sm font-medium text-slate-700">Account Holder Name <span class="font-normal text-slate-400">(Capital Letter)</span></label>
                                <input class="{{ $control }}" type="text" name="holder_name" id="holder_name"
                                    value="{{ setting('holder_name') ?? 'Kishoreganj Sadar' }}">
                            </div>
                            <div>
                                <label for="routing" class="mb-1 block text-sm font-medium text-slate-700">Routing</label>
                                <input class="{{ $control }}" type="text" name="routing" id="routing"
                                    value="{{ setting('routing') ?? 'BDBL000' }}">
                            </div>
                        </div>
                    </div>

                    {{-- COD & shipping charges --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-900">COD &amp; Shipping Charges</h2>
                                    <p class="text-xs text-slate-500">Cash on delivery and shipping fees</p>
                                </div>
                            </div>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <input type="checkbox" name="cod" class="{{ $checkbox }}"
                                    @if (setting('g_cod') == 'true') checked @endif>
                                Enabled
                            </label>
                        </div>

                        <div class="space-y-4 p-5">
                            <p class="rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700 ring-1 ring-inset ring-amber-200">
                                <i class="fas fa-exclamation-triangle"></i>
                                All charges are not related to COD only &mdash; they also affect every payment gateway.
                            </p>
                            <div>
                                <label for="shipping_free_above" class="mb-1 block text-sm font-medium text-slate-700">Shipping Charge Free Above Amount</label>
                                <input name="shipping_free_above" id="shipping_free_above" class="{{ $control }}"
                                    type="text" value="{{ setting('shipping_free_above') ?? 10000 }}">
                            </div>
                            <div>
                                <label for="shipping_range_inside" class="mb-1 block text-sm font-medium text-slate-700">Text - Shipping in Range</label>
                                <input name="shipping_range_inside" id="shipping_range_inside" class="{{ $control }}"
                                    type="text" value="{{ setting('shipping_range_inside') ?? 'Dhaka' }}">
                            </div>
                            <div>
                                <label for="shipping_charge" class="mb-1 block text-sm font-medium text-slate-700">Shipping Charge Inside Range</label>
                                <input name="shipping_charge" id="shipping_charge" class="{{ $control }}"
                                    type="text" value="{{ setting('shipping_charge') ?? 80 }}">
                            </div>
                            <div>
                                <label for="shipping_charge_out_of_range" class="mb-1 block text-sm font-medium text-slate-700">Shipping Charge Out Of Range</label>
                                <input name="shipping_charge_out_of_range" id="shipping_charge_out_of_range"
                                    class="{{ $control }}" type="text"
                                    value="{{ setting('shipping_charge_out_of_range') ?? 130 }}">
                            </div>
                        </div>
                    </div>

                    {{-- aamarPay --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-900">Online Pay &mdash; aamarPay</h2>
                                    <p class="text-xs text-slate-500">Automatic online payment gateway</p>
                                </div>
                            </div>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <input type="checkbox" name="aamar" class="{{ $checkbox }}"
                                    @if (setting('g_aamar') == 'true') checked @endif>
                                Active
                            </label>
                        </div>

                        <div class="space-y-4 p-5">
                            <div>
                                <label for="astore" class="mb-1 block text-sm font-medium text-slate-700">Store ID</label>
                                <input class="{{ $control }}" name="astore" id="astore" value="{{ setting('astore') }}">
                            </div>
                            <div>
                                <label for="akey" class="mb-1 block text-sm font-medium text-slate-700">Signature Key</label>
                                <input class="{{ $control }}" name="akey" id="akey" value="{{ setting('akey') }}">
                            </div>
                            <div>
                                <label for="amode" class="mb-1 block text-sm font-medium text-slate-700">Mode</label>
                                <select name="amode" id="amode" class="{{ $control }} cursor-pointer">
                                    <option @if (setting('amode') == '1') selected @endif value="1">Sandbox</option>
                                    <option @if (setting('amode') == '2') selected @endif value="2">Live</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- UddoktaPay --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-900">Online Pay &mdash; UddoktaPay</h2>
                                    <p class="text-xs text-slate-500">Automatic online payment gateway</p>
                                </div>
                            </div>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <input type="checkbox" name="uddok" class="{{ $checkbox }}"
                                    @if (setting('g_uddok') == 'true') checked @endif>
                                Active
                            </label>
                        </div>

                        <div class="space-y-4 p-5">
                            <div>
                                <label for="uapi" class="mb-1 block text-sm font-medium text-slate-700">API Key</label>
                                <input class="{{ $control }}" name="uapi" id="uapi" value="{{ setting('uapi') }}">
                            </div>
                            <div>
                                <label for="ubase" class="mb-1 block text-sm font-medium text-slate-700">Base URL</label>
                                <input class="{{ $control }}" name="ubase" id="ubase" value="{{ setting('ubase') }}">
                            </div>
                            <div>
                                <label for="umode" class="mb-1 block text-sm font-medium text-slate-700">Mode</label>
                                <select name="umode" id="umode" class="{{ $control }} cursor-pointer">
                                    <option @if (setting('umode') == '1') selected @endif value="1">Sandbox</option>
                                    <option @if (setting('umode') == '2') selected @endif value="2">Live</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-end gap-2 rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-check text-xs"></i>
                        Save Changes
                    </x-ui.button>
                </div>

            </div>
        </form>
    </section>

@endsection

@push('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
            $('.select2').select2();
        });
    </script>
@endpush
