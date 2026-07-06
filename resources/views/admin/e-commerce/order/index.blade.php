@extends('layouts.admin.app')

@section('title', 'All Order List')

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
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-slate-800">Orders</h1>
                <p class="mt-1 text-sm text-slate-500">Manage and track all customer orders</p>
            </div>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-slate-700">Orders</li>
            </ol>
        </div>
    </section>

    <section class="mb-6 space-y-4">

        {{-- Filters --}}
        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            @php
                $control = 'h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary';
            @endphp

            <form action="{{ route('admin.order.index') }}" method="GET" class="grid grid-cols-1 items-end gap-3 md:grid-cols-12">
                <div class="md:col-span-5">
                    <label for="keyword" class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                    <div class="relative">
                        <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                        <input
                            id="keyword"
                            name="keyword"
                            type="text"
                            value="{{ request('keyword') }}"
                            placeholder="Invoice number or phone…"
                            class="{{ $control }} pl-9"
                        />
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                    <select id="status" name="status" class="{{ $control }}">
                        <option value="">All statuses</option>
                        @foreach ($statusMap as $value => $status)
                            <option value="{{ $value }}" {{ request('status') === (string) $value ? 'selected' : '' }}>
                                {{ $status['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="is_pre" class="mb-1 block text-sm font-medium text-slate-700">Pre Order</label>
                    <select id="is_pre" name="is_pre" class="{{ $control }}">
                        <option value="">All</option>
                        <option value="1" {{ request('is_pre') === '1' ? 'selected' : '' }}>Pre Order</option>
                        <option value="0" {{ request('is_pre') === '0' ? 'selected' : '' }}>Not Pre Order</option>
                    </select>
                </div>

                <div class="flex gap-2 md:col-span-2">
                    <button type="submit" class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-md bg-primary px-4 text-sm font-medium text-white transition-colors hover:bg-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    <a href="{{ route('admin.order.index') }}" title="Reset filters" class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-300 bg-white text-slate-500 transition-colors hover:border-slate-400 hover:text-slate-700">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Order list --}}
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                <h2 class="font-medium text-slate-900">All Orders</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                    {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[960px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <th class="px-4 py-3 font-semibold">SL</th>
                            <th class="px-4 py-3 font-semibold">Invoice</th>
                            <th class="px-4 py-3 font-semibold">Customer</th>
                            <th class="px-4 py-3 font-semibold">Payment</th>
                            <th class="px-4 py-3 text-right font-semibold">Subtotal</th>
                            <th class="px-4 py-3 text-right font-semibold">Discount</th>
                            <th class="px-4 py-3 text-right font-semibold">Total</th>
                            <th class="px-4 py-3 font-semibold">Date</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($orders as $key => $data)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500">{{ $orders->firstItem() + $key }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ routeHelper('order/' . $data->id) }}" class="font-semibold text-primary hover:underline">
                                        {{ $data->invoice }}
                                    </a>
                                    @if ($data->is_pre == 1)
                                        <span class="ml-1 inline-flex items-center rounded-full bg-info/10 px-2 py-0.5 text-[11px] font-medium text-info">Pre</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $data->first_name }}</p>
                                    <div class="mt-0.5 flex items-center gap-2">
                                        <span class="text-slate-500">{{ $data->phone }}</span>
                                        <button
                                            class="fraud_checker inline-flex items-center gap-1 rounded-full bg-danger/10 px-2 py-0.5 text-[11px] font-medium text-danger transition-colors hover:bg-danger hover:text-white"
                                            data-id="{{ $data->id }}"
                                            title="Check fraud history for this customer">
                                            <i class="fas fa-shield-alt"></i> Fraud
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $data->payment_method }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-slate-600">{{ number_format($data->subtotal, 2) }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-slate-600">{{ number_format($data->discount, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold tabular-nums text-slate-800">{{ number_format($data->total, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600">{{ date('d M Y', strtotime($data->created_at)) }}</td>
                                <td class="px-4 py-3">
                                    @php $status = $statusMap[$data->status] ?? null; @endphp
                                    @if ($status)
                                        <x-ui.badge variant="{{ $status['variant'] }}" class="whitespace-nowrap">{{ $status['label'] }}</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="neutral">Unknown</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ routeHelper('order/' . $data->id) }}"
                                            title="View order"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-500 transition-colors hover:border-primary hover:bg-primary-50 hover:text-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.order.invoice', $data->id) }}" target="_blank"
                                            title="Print invoice"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-500 transition-colors hover:border-primary hover:bg-primary-50 hover:text-primary">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-16 text-center">
                                    <i class="fas fa-box-open mb-3 text-4xl text-slate-300"></i>
                                    <p class="font-medium text-slate-600">No orders found</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Try adjusting your search or
                                        <a href="{{ route('admin.order.index') }}" class="text-primary hover:underline">reset the filters</a>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="border-t border-slate-200 px-4 py-3">
                    {{ $orders->appends(request()->query())->links('admin.partials.pagination') }}
                </div>
            @endif
        </div>

        {{-- Fraud checker modal --}}
        <div class="fixed inset-0 z-50 hidden items-center justify-center p-4" id="fraud_checker_modal" tabindex="-1" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/50" data-dismiss="modal"></div>
            <div class="relative z-10 flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-lg bg-white shadow-xl" role="document">
                <div id="fraud_checker_details" class="overflow-y-auto">
                    <!-- ajax content -->
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        $(document).on("click", ".fraud_checker", function() {
            let id = $(this).data('id');

            $("#fraud_checker_details").html(
                '<div class="flex items-center justify-center gap-2 p-10 text-slate-500">' +
                '<i class="fas fa-circle-notch fa-spin"></i> Loading report…</div>'
            );

            $("#fraud_checker_modal").css("display", "flex");

            $.ajax({
                type: "POST",
                url: "{{ route('admin.order.fraud_checker') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    $("#fraud_checker_details").html(response);
                },
                error: function() {
                    $("#fraud_checker_details").html(
                        '<div class="p-6 text-center text-danger">Something went wrong. Please try again.</div>'
                    );
                }
            });
        });

        $(document).on("click", "[data-dismiss='modal']", function() {
            $("#fraud_checker_modal").css("display", "none");
        });

        $(document).on("keyup", function(e) {
            if (e.key === "Escape") $("#fraud_checker_modal").css("display", "none");
        });
    </script>
@endpush
