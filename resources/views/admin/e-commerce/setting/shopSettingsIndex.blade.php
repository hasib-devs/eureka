@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Shop Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Checkout, currency, commission and point-system configuration</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    @endphp

    <section class="mb-6">
        <form id="shopSettingsForm" action="{{ routeHelper('setting') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="10">

            <div class="mx-auto w-full max-w-3xl space-y-4">

                {{-- Checkout --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Checkout</h2>
                            <p class="text-xs text-slate-500">Guest checkout, checkout flow and phone validation</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-x-4 p-5 md:grid-cols-2">
                        <x-ui.select name="GUEST_CHECKOUT" label="Guest Checkout" id="GUEST_CHECKOUT">
                            <option value="{{ $GUEST_CHECKOUT->value }}">
                                {{ $GUEST_CHECKOUT->value == 1 ? 'On' : 'Off' }}
                            </option>
                            <option value="1">On</option>
                            <option value="0">Off</option>
                        </x-ui.select>

                        <x-ui.select name="CHECKOUT_TYPE" label="Checkout Type" id="CHECKOUT_TYPE">
                            <option value="{{ setting('CHECKOUT_TYPE') ?? 0 }}">
                                {{ setting('CHECKOUT_TYPE') == 1 ? 'Complex' : 'Minimal' }}
                            </option>
                            <option value="1">Complex</option>
                            <option value="0">Minimal</option>
                        </x-ui.select>

                        <div class="mb-4">
                            <x-ui.input name="phone_min_dgt" label="Phone Number Minimum Digit" type="number"
                                :value="setting('phone_min_dgt') ?? 11" />
                        </div>

                        <div class="mb-4">
                            <x-ui.input name="phone_max_dgt" label="Phone Number Maximum Digit" type="number"
                                :value="setting('phone_max_dgt') ?? 11" />
                        </div>
                    </div>
                </div>

                {{-- Withdraw --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Wallet &amp; Withdraw</h2>
                            <p class="text-xs text-slate-500">Minimum recharge and withdrawal amounts</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-x-4 p-5 md:grid-cols-2">
                        <div class="mb-4">
                            <x-ui.input name="min_rec" label="Minimum Recharge" type="text"
                                :value="setting('min_rec') ?? 50" />
                        </div>

                        <div class="mb-4">
                            <x-ui.input name="min_with" label="Minimum Withdraw" type="text"
                                :value="setting('min_with') ?? 500" />
                        </div>
                    </div>
                </div>

                {{-- Shipping & currency --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Shipping &amp; Currency</h2>
                            <p class="text-xs text-slate-500">Serving region and currency display</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="COUNTRY_SERVE" class="mb-1 block text-sm font-medium text-slate-700">
                                Country of Serve <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" type="text" name="COUNTRY_SERVE" id="COUNTRY_SERVE"
                                placeholder="Bangladesh"
                                value="{{ setting('COUNTRY_SERVE') ?? 'Bangladesh' }}" required>
                        </div>

                        <div>
                            <label for="shipping_range_inside" class="mb-1 block text-sm font-medium text-slate-700">
                                Text - Shipping in Range <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" name="shipping_range_inside" id="shipping_range_inside"
                                type="text" value="{{ setting('shipping_range_inside') ?? 'Dhaka' }}" required>
                        </div>

                        <div>
                            <label for="CURRENCY_CODE" class="mb-1 block text-sm font-medium text-slate-700">
                                Currency Code <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" type="text" name="CURRENCY_CODE" id="CURRENCY_CODE"
                                placeholder="Currency code"
                                value="{{ setting('CURRENCY_CODE') ?? 'BDT' }}" required>
                        </div>

                        <div>
                            <label for="CURRENCY_CODE_MIN" class="mb-1 block text-sm font-medium text-slate-700">
                                Currency Code Small <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" type="text" name="CURRENCY_CODE_MIN" id="CURRENCY_CODE_MIN"
                                placeholder="Currency code"
                                value="{{ setting('CURRENCY_CODE_MIN') ?? 'Tk' }}" required>
                        </div>

                        <div>
                            <label for="CURRENCY_ICON" class="mb-1 block text-sm font-medium text-slate-700">
                                Currency Icon <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" type="text" name="CURRENCY_ICON" id="CURRENCY_ICON"
                                placeholder="Currency icon"
                                value="{{ setting('CURRENCY_ICON') ?? '৳' }}" required>
                        </div>
                    </div>
                </div>

                {{-- Calculation --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Calculation</h2>
                            <p class="text-xs text-slate-500">Vendor commission and reward points</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-x-4 gap-y-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="shop_commission" class="mb-1 block text-sm font-medium text-slate-700">
                                Vendor Commission <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" type="number" name="shop_commission" id="shop_commission"
                                placeholder="Bangladesh"
                                value="{{ setting('shop_commission') ?? 0 }}" required>
                        </div>

                        <x-ui.select name="is_point" label="Point System Status" id="is_point">
                            <option value="{{ setting('is_point') ?? 0 }}">
                                {{ setting('is_point') == 1 ? 'Active' : 'Deactive' }}
                            </option>
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
                        </x-ui.select>

                        <div>
                            <label for="Default_Point" class="mb-1 block text-sm font-medium text-slate-700">
                                Default Point <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" type="text" name="Default_Point" id="Default_Point"
                                placeholder="Bangladesh"
                                value="{{ setting('Default_Point') ?? 0 }}" required>
                        </div>

                        <div>
                            <label for="Point_rate" class="mb-1 block text-sm font-medium text-slate-700">
                                Point Rate <span class="text-danger">*</span>
                            </label>
                            <input class="{{ $control }}" type="text" name="Point_rate" id="Point_rate"
                                placeholder="Bangladesh"
                                value="{{ setting('Point_rate') ?? 0 }}" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
