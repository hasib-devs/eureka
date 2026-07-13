@extends('layouts.admin.app')

@section('title', 'Invoices')

@php
    $cur = setting('CURRENCY_CODE_MIN') ?? 'TK';
    $statusVariant = [
        'Draft' => 'neutral',
        'Unpaid' => 'danger',
        'Partially Paid' => 'warning',
        'Paid' => 'success',
    ];
    $inputClass = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary';
@endphp

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Invoices</h1>
                <p class="mt-1 text-sm text-slate-500">Every order and manual invoice, in one place.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.invoices.settings')">
                    <i class="fas fa-sliders-h"></i>
                    Settings
                </x-ui.button>
                <x-ui.button variant="primary" :href="route('admin.invoices.create')">
                    <i class="fas fa-plus"></i>
                    New Invoice
                </x-ui.button>
            </div>
        </div>
    </section>

    <section class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.invoices.index') }}"
            class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6">
            <div class="lg:col-span-2">
                <input type="text" name="search" value="{{ $filters['search'] }}"
                    placeholder="Search invoice # or customer..." class="{{ $inputClass }}">
            </div>
            <select name="source" class="{{ $inputClass }}">
                <option value="all" @selected($filters['source'] === 'all')>All Sources</option>
                <option value="order" @selected($filters['source'] === 'order')>From Orders</option>
                <option value="manual" @selected($filters['source'] === 'manual')>Manual</option>
            </select>
            <select name="status" class="{{ $inputClass }}">
                <option value="All">All Statuses</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" @selected($filters['status'] === $s)>{{ $s }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="{{ $inputClass }}">
            <div class="flex gap-2">
                <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="{{ $inputClass }}">
            </div>
            <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-6">
                <x-ui.button type="submit" variant="primary" size="sm">
                    <i class="fas fa-filter"></i> Filter
                </x-ui.button>
                <x-ui.button variant="ghost" size="sm" :href="route('admin.invoices.index')">Reset</x-ui.button>
            </div>
        </form>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3 font-semibold">Invoice #</th>
                        <th class="px-4 py-3 font-semibold">Customer</th>
                        <th class="px-4 py-3 font-semibold">Date</th>
                        <th class="px-4 py-3 text-right font-semibold">Total</th>
                        <th class="px-4 py-3 text-right font-semibold">Due</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Source</th>
                        <th class="px-4 py-3 text-right font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($invoices as $inv)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <a href="{{ $inv['url'] }}" class="font-semibold text-slate-900 hover:text-primary">{{ $inv['number'] }}</a>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $inv['customer'] ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ \Illuminate\Support\Carbon::parse($inv['date'])->format('d M, Y') }}</td>
                            <td class="px-4 py-3 text-right font-semibold tabular-nums text-slate-900">{{ number_format($inv['total'], 2) }} {{ $cur }}</td>
                            <td class="px-4 py-3 text-right font-semibold tabular-nums text-rose-600">{{ number_format($inv['due'], 2) }} {{ $cur }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$statusVariant[$inv['status']] ?? 'neutral'" dot>{{ $inv['status'] }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                @if ($inv['source'] === 'manual')
                                    <span class="inline-flex items-center rounded-md bg-primary/10 px-2 py-0.5 text-[11px] font-semibold text-primary">Manual</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-500">Order</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ $inv['url'] }}" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-100 hover:text-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <div class="mx-auto grid h-12 w-12 place-items-center rounded-xl bg-slate-100 text-slate-400">
                                    <i class="fas fa-file-invoice text-lg"></i>
                                </div>
                                <p class="mt-3 font-semibold text-slate-700">No invoices found</p>
                                <p class="text-sm text-slate-400">Orders will appear here automatically, or create a manual invoice.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $invoices->appends(request()->query())->links('admin.partials.pagination') }}
            </div>
        @endif
    </section>

@endsection
