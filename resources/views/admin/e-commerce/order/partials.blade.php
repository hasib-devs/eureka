@extends('layouts.admin.app')

@section('title', 'Partials Pay List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Partial Payments</h1>
                <p class="mt-1 text-sm text-slate-500">Review and approve partial payments submitted by customers</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Partials Pay List</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-4">
                <h2 class="text-base font-semibold text-slate-900">Partials Pay List</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $partials->count() }} {{ Str::plural('payment', $partials->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="px-4 py-3.5">No.</th>
                            <th class="px-4 py-3.5">Invoice</th>
                            <th class="px-4 py-3.5 text-right">Amount</th>
                            <th class="px-4 py-3.5">Transaction ID</th>
                            <th class="px-4 py-3.5">Method</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($partials as $order)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $order->order->invoice ?? '' }}</td>
                                <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">{{ number_format($order->amount, 2) }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $order->transaction_id }}</td>
                                <td class="px-4 py-4 text-slate-600">
                                    @if ($order->payment_method == 'bk')
                                        Bkash
                                    @elseif($order->payment_method == 'ng')
                                        Nagad
                                    @elseif($order->payment_method == 'rk')
                                        Rocket
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if ($order->status == 1)
                                        <x-ui.badge variant="success" dot>Approved</x-ui.badge>
                                    @elseif($order->status == 2)
                                        <x-ui.badge variant="danger" dot>Canceled</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="warning" dot>Pending</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if ($order->status == 0)
                                            <a href="{{ route('admin.order.partials.status', ['id' => $order->id, 'st' => '1']) }}"
                                                title="Approve payment"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                                <i class="fas fa-thumbs-up"></i>
                                            </a>
                                            <a href="{{ route('admin.order.partials.status', ['id' => $order->id, 'st' => '2']) }}"
                                                title="Cancel payment"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @elseif($order->status == 2)
                                            <a href="{{ route('admin.order.partials.status', ['id' => $order->id, 'st' => '1']) }}"
                                                title="Approve payment"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                                <i class="fas fa-thumbs-up"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-money-check-alt text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No partial payments found</p>
                                    <p class="mt-1 text-sm text-slate-500">Partial payments submitted by customers will show up here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
