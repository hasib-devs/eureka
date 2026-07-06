@extends('layouts.admin.app')

@section('title', 'Withdraw List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Withdrawals</h1>
                <p class="mt-1 text-sm text-slate-500">Review, approve or cancel vendor withdrawal requests</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Withdrawals</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Withdrawals</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $withdraws->count() }} {{ Str::plural('request', $withdraws->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Name</th>
                            <th class="px-4 py-3.5">Method</th>
                            <th class="px-4 py-3.5 text-right">Amount</th>
                            <th class="px-4 py-3.5">Account</th>
                            <th class="px-4 py-3.5">Date</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($withdraws as $data)
                            @php
                                if ($data->payment_method == 1) {
                                    $methodLabel = 'Bkash';
                                    $method = 'bkash';
                                } elseif ($data->payment_method == 2) {
                                    $methodLabel = 'Nagad';
                                    $method = 'nogod';
                                } elseif ($data->payment_method == 3) {
                                    $methodLabel = 'Rocket';
                                    $method = 'rocket';
                                } elseif ($data->payment_method == 4) {
                                    $methodLabel = 'Mobile Recharge';
                                } else {
                                    $methodLabel = 'Bank';
                                    $method = 'bank';
                                }
                            @endphp
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $data->user->name }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $methodLabel }}</td>
                                <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">{{ $data->amount }}</td>
                                <td class="px-4 py-4 tabular-nums text-slate-600">{{ $data->number != null ? $data->number : $data->user->shop_info->$method }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-slate-500">{{ date('d M Y', strtotime($data->created_at)) }}</td>
                                <td class="px-4 py-4">
                                    @if ($data->status == 0)
                                        <x-ui.badge variant="warning" dot>Pending</x-ui.badge>
                                    @elseif ($data->status == 1)
                                        <x-ui.badge variant="primary" dot>Complete</x-ui.badge>
                                    @elseif ($data->status == 2)
                                        <x-ui.badge variant="danger" dot>Canceled</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="neutral" dot>Unknown</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if ($data->status == 0)
                                            <a href="{{ route('admin.vendor.withdraw.aprove', ['id' => $data->id]) }}"
                                                title="Approve withdrawal"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-success hover:bg-success/10 hover:text-success hover:shadow">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="{{ route('admin.vendor.withdraw.cancel', ['id' => $data->id]) }}"
                                                title="Cancel withdrawal"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-warning hover:bg-warning/10 hover:text-warning hover:shadow">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.vendor.withdraw.delete', ['id' => $data->id]) }}"
                                            title="Delete withdrawal"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-wallet text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No withdrawal requests found</p>
                                    <p class="mt-1 text-sm text-slate-500">Vendor withdrawal requests will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>

@endsection
