@extends('layouts.admin.app')

@section('title', 'Courier — Pathao')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Courier — Pathao</h1>
                <p class="mt-1 text-sm text-slate-500">Pathao merchant API credentials and delivery configuration</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Courier</li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Pathao</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    @endphp

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl space-y-4">

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Pathao Courier</h2>
                            <p class="text-xs text-slate-500">Send orders to the Pathao courier network</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if (setting('PATHAO_CLIENT_ID'))
                            @if ($connected)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                    <i class="fas fa-check-circle"></i> Connected ({{ setting('PATHAO_MODE') === 'live' ? 'Live' : 'Sandbox' }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-600">
                                    <i class="fas fa-times-circle"></i> Not connected
                                </span>
                            @endif
                        @endif
                        <a href="https://merchant.pathao.com/" target="_blank" rel="noopener noreferrer"
                            class="text-xs font-medium text-primary hover:underline">
                            merchant.pathao.com <i class="fas fa-external-link-alt text-[9px]"></i>
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.setting.pathao.save') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4 p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                                <div>
                                    <label for="PATHAO_STATUS" class="block text-sm font-medium text-slate-700">Status</label>
                                    <p class="mt-0.5 text-xs text-slate-500">Enable Pathao for orders</p>
                                </div>
                                <select name="PATHAO_STATUS" id="PATHAO_STATUS"
                                    class="h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm">
                                    <option value="1" @if (setting('PATHAO_STATUS') == 1) selected @endif>ON</option>
                                    <option value="0" @if (setting('PATHAO_STATUS') != 1) selected @endif>OFF</option>
                                </select>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                                <div>
                                    <label for="PATHAO_MODE" class="block text-sm font-medium text-slate-700">Environment</label>
                                    <p class="mt-0.5 text-xs text-slate-500">Sandbox for testing, Live for production</p>
                                </div>
                                <select name="PATHAO_MODE" id="PATHAO_MODE"
                                    class="h-10 w-32 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm">
                                    <option value="sandbox" @if (setting('PATHAO_MODE') !== 'live') selected @endif>Sandbox</option>
                                    <option value="live" @if (setting('PATHAO_MODE') === 'live') selected @endif>Live</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="PATHAO_CLIENT_ID" class="mb-1 block text-sm font-medium text-slate-700">Client ID</label>
                                <input type="text" name="PATHAO_CLIENT_ID" id="PATHAO_CLIENT_ID"
                                    value="{{ setting('PATHAO_CLIENT_ID') ?? '7N1aMJQbWm' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="PATHAO_CLIENT_SECRET" class="mb-1 block text-sm font-medium text-slate-700">Client Secret</label>
                                <input type="text" name="PATHAO_CLIENT_SECRET" id="PATHAO_CLIENT_SECRET"
                                    value="{{ setting('PATHAO_CLIENT_SECRET') ?? 'wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="PATHAO_USERNAME" class="mb-1 block text-sm font-medium text-slate-700">Merchant Email</label>
                                <input type="text" name="PATHAO_USERNAME" id="PATHAO_USERNAME"
                                    value="{{ setting('PATHAO_USERNAME') ?? 'test@pathao.com' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="PATHAO_PASSWORD" class="mb-1 block text-sm font-medium text-slate-700">Merchant Password</label>
                                <input type="password" name="PATHAO_PASSWORD" id="PATHAO_PASSWORD"
                                    value="{{ setting('PATHAO_PASSWORD') ?? 'lovePathao' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="PATHAO_STORE_ID" class="mb-1 block text-sm font-medium text-slate-700">Store</label>
                                @if (! empty($stores))
                                    <select name="PATHAO_STORE_ID" id="PATHAO_STORE_ID" class="{{ $control }}">
                                        <option value="">Select store</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store['store_id'] }}"
                                                @if (setting('PATHAO_STORE_ID') == $store['store_id']) selected @endif>
                                                {{ $store['store_name'] }} (#{{ $store['store_id'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="number" name="PATHAO_STORE_ID" id="PATHAO_STORE_ID"
                                        value="{{ setting('PATHAO_STORE_ID') }}" placeholder="Store ID"
                                        class="{{ $control }}">
                                    <p class="mt-1 text-xs text-slate-400">Save valid credentials first — the store list loads automatically once connected.</p>
                                @endif
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

            <div class="rounded-xl border border-slate-200 bg-white p-5 text-sm text-slate-500 shadow-sm">
                <p class="font-medium text-slate-700">How it works</p>
                <ul class="mt-2 list-inside list-disc space-y-1 text-xs">
                    <li>Turn Status ON with valid credentials and a store selected.</li>
                    <li>Open any order and press <strong>Send Pathao</strong> — pick the delivery city/zone and confirm the COD amount.</li>
                    <li>Sandbox mode uses Pathao's public test environment; switch to Live with your merchant credentials from merchant.pathao.com.</li>
                </ul>
            </div>

        </div>
    </section>

@endsection
