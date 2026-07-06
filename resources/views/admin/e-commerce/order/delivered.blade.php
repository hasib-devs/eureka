@extends('layouts.admin.app')

@section('title', 'Delivered Order List')

@php
    $statusMap = [
        0 => ['label' => 'Pending', 'variant' => 'warning'],
        1 => ['label' => 'Confirmed', 'variant' => 'primary'],
        2 => ['label' => 'Canceled', 'variant' => 'danger'],
        3 => ['label' => 'Delivered', 'variant' => 'success'],
        4 => ['label' => 'Shipping', 'variant' => 'info'],
        5 => ['label' => 'Refund', 'variant' => 'danger'],
        6 => ['label' => 'Return Requested', 'variant' => 'warning'],
        7 => ['label' => 'Returning by Customer', 'variant' => 'warning'],
        8 => ['label' => 'Returned', 'variant' => 'danger'],
        9 => ['label' => 'Sent to Courier', 'variant' => 'info'],
    ];
@endphp

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Delivered Orders</h1>
                <p class="mt-1 text-sm text-slate-500">Orders successfully delivered to customers</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Delivered Order List</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-4">
                <h2 class="text-base font-semibold text-slate-900">Delivered Order List</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $orders->count() }} {{ Str::plural('order', $orders->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[960px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Invoice</th>
                            <th class="px-4 py-3.5">Customer</th>
                            <th class="px-4 py-3.5">Payment</th>
                            <th class="px-4 py-3.5 text-right">Subtotal</th>
                            <th class="px-4 py-3.5 text-right">Discount</th>
                            <th class="px-4 py-3.5 text-right">Total</th>
                            <th class="px-4 py-3.5">Date</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($orders as $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4">
                                    <a href="{{ routeHelper('order/' . $data->id) }}" class="font-semibold text-primary hover:underline">
                                        {{ $data->invoice }}
                                    </a>
                                    @if ($data->is_pre == 1)
                                        <span class="ml-1 inline-flex items-center rounded-full bg-info/10 px-2 py-0.5 text-[11px] font-medium text-info ring-1 ring-inset ring-info/20">Pre</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-800">{{ $data->first_name }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ $data->phone }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $data->payment_method }}</td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">{{ number_format($data->subtotal, 2) }}</td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">{{ number_format($data->discount, 2) }}</td>
                                <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">{{ number_format($data->total, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-slate-500">{{ date('d M Y', strtotime($data->created_at)) }}</td>
                                <td class="px-4 py-4">
                                    @php $status = $statusMap[$data->status] ?? null; @endphp
                                    @if ($status)
                                        <x-ui.badge variant="{{ $status['variant'] }}" dot class="whitespace-nowrap">{{ $status['label'] }}</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="neutral" dot>Unknown</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ routeHelper('order/' . $data->id) }}"
                                            title="Show Information"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.order.invoice', $data->id) }}" target="_blank"
                                            title="Invoice"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-box-open text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No delivered orders found</p>
                                    <p class="mt-1 text-sm text-slate-500">Delivered orders will show up here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
