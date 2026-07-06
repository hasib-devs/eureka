@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Courier Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Configure courier and fraud-checker API credentials</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Courier</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    @endphp

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl space-y-4">

            {{-- Steadfast --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Steadfast Courier</h2>
                            <p class="text-xs text-slate-500">Send orders directly to the Steadfast courier network</p>
                        </div>
                    </div>
                    <a href="https://www.steadfast.com.bd/" target="_blank" rel="noopener noreferrer"
                        class="text-xs font-medium text-primary hover:underline">
                        Get API from steadfast.com.bd <i class="fas fa-external-link-alt text-[9px]"></i>
                    </a>
                </div>

                <form id="sms_config" action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="12">

                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                            <div>
                                <label for="STEEDFAST_STATUS" class="block text-sm font-medium text-slate-700">API V1 Status</label>
                                <p class="mt-0.5 text-xs text-slate-500">Enable or disable the Steadfast integration</p>
                            </div>
                            <select name="STEEDFAST_STATUS" id="STEEDFAST_STATUS"
                                class="h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                @if (setting('STEEDFAST_STATUS') ?? 0 == 1)
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                @else
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                @endif
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="STEEDFAST_API_KEY" class="mb-1 block text-sm font-medium text-slate-700">API Key</label>
                                <input type="text" name="STEEDFAST_API_KEY" id="STEEDFAST_API_KEY"
                                    value="{{ setting('STEEDFAST_API_KEY') ?? '' }}"
                                    class="{{ $control }}">
                            </div>
                            <div>
                                <label for="STEEDFAST_API_SECRET_KEY" class="mb-1 block text-sm font-medium text-slate-700">API Secret</label>
                                <input type="text" name="STEEDFAST_API_SECRET_KEY" id="STEEDFAST_API_SECRET_KEY"
                                    value="{{ setting('STEEDFAST_API_SECRET_KEY') ?? '' }}"
                                    class="{{ $control }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>

            {{-- BD Courier --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">BD Courier (Fraud Checker)</h2>
                            <p class="text-xs text-slate-500">Check customer delivery history before confirming orders</p>
                        </div>
                    </div>
                    <a href="https://bdcourier.com/" target="_blank" rel="noopener noreferrer"
                        class="text-xs font-medium text-primary hover:underline">
                        Get API from bdcourier.com <i class="fas fa-external-link-alt text-[9px]"></i>
                    </a>
                </div>

                <form id="bdcourier_config" action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="12">

                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                            <div>
                                <label for="BDCOURIER_STATUS" class="block text-sm font-medium text-slate-700">Status</label>
                                <p class="mt-0.5 text-xs text-slate-500">Enable or disable the fraud checker</p>
                            </div>
                            <select name="BDCOURIER_STATUS" id="BDCOURIER_STATUS"
                                class="h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                @if (setting('BDCOURIER_STATUS') ?? 0 == 1)
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                @else
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label for="BDCOURIER_API_KEY" class="mb-1 block text-sm font-medium text-slate-700">API Key <span class="font-normal text-slate-400">(Bearer Token)</span></label>
                            <input type="text" name="BDCOURIER_API_KEY" id="BDCOURIER_API_KEY"
                                value="{{ setting('BDCOURIER_API_KEY') ?? 'ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2' }}"
                                class="{{ $control }}">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>

        </div>
    </section>

@endsection
