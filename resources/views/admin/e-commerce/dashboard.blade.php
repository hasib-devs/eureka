@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Page header --}}
    <section class="mb-6">
        <?php

use App\Models\Product;

        $low_products = Product::where('quantity', '<', '6')->where('user_id', auth()->id())->count();
        if ($low_products > 0) {
            ?>
        <a href="{{ route('admin.low.product') }}"
           class="mb-4 flex w-full items-center gap-2 rounded-xl bg-danger px-5 py-3 text-sm font-medium text-white shadow-sm transition-all hover:opacity-90 hover:shadow">
            <i class="fas fa-exclamation-triangle"></i>
            You have {{ $low_products }} low quantity product.
        </a>
        <?php }?>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Overview of your store's performance</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Dashboard</li>
                </ol>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">
        <h2 class="mb-3 text-base font-semibold text-slate-900">Store Overview</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Live Active Visitors --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="p-4">
                    <p class="mb-1 flex items-center gap-1.5 text-xs text-slate-500">
                        <span class="h-1.5 w-1.5 shrink-0 animate-pulse rounded-full bg-success" aria-hidden="true"></span>
                        Live Active Visitors
                    </p>
                    <h3 class="mb-0 text-3xl font-bold tabular-nums text-slate-800" id="live-active-visitors">0</h3>
                    <small class="text-sm text-success">Realtime website users</small>
                </div>
            </div>

            <x-ui.stat-tile variant="info" class="rounded-xl"
                :value="$products"
                label="Total Products"
                icon="fas fa-procedures"
                :href="routeHelper('product')" />

            <x-ui.stat-tile variant="warning" class="rounded-xl"
                :value="$quantity"
                label="Product Qty"
                icon="fas fa-sort-numeric-down-alt"
                :href="routeHelper('product')" />

            <x-ui.stat-tile variant="success" class="rounded-xl"
                :value="$orders"
                label="Total Orders"
                icon="fas fa-shopping-cart"
                :href="routeHelper('order')" />

            <x-ui.stat-tile variant="info" class="rounded-xl"
                :value="$pending_orders"
                label="Pending Orders"
                icon="fas fa-hourglass-start"
                :href="routeHelper('order/pending')" />

            <x-ui.stat-tile variant="primary" class="rounded-xl"
                :value="$processing_orders"
                label="Processing Orders"
                icon="fas fa-running"
                :href="routeHelper('order/processing')" />

            <x-ui.stat-tile variant="danger" class="rounded-xl"
                :value="$cancel_orders"
                label="Cancel Orders"
                icon="fas fa-window-close"
                :href="routeHelper('order/cancel')" />

            <x-ui.stat-tile variant="success" class="rounded-xl"
                :value="$delivered_orders"
                label="Delivered Orders"
                icon="fas fa-thumbs-up"
                :href="routeHelper('order/delivered')" />

            <x-ui.stat-tile variant="primary" class="rounded-xl"
                :value="$vendor_amount"
                label="Vendor Amount"
                icon="fas fa-money-check-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="primary" class="rounded-xl"
                :value="$vendor_pamount"
                label="Vendor Pending Amount"
                icon="fas fa-money-check-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="info" class="rounded-xl"
                :value="$admin_amount"
                label="Self Amount"
                icon="fas fa-money-bill-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="info" class="rounded-xl"
                :value="$pending_amount"
                label="Self Pending"
                icon="fas fa-money-bill-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="primary" class="rounded-xl"
                :value="$vendors"
                label="Total Vendor"
                icon="fas fa-users-cog"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="warning" class="rounded-xl"
                :value="$customers"
                label="Total Customer"
                icon="fas fa-users"
                :href="routeHelper('customer')" />

            <x-ui.stat-tile variant="success" class="rounded-xl"
                :value="$commission"
                label="Total Commission"
                icon="fas fa-money-bill"
                :href="route('admin.comission')" />

        </div>
    </section>

@endsection

@push('js')
<script>
    (function() {
        const url = "{{ route('visitor.count') }}";

        function updateCount() {
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        const el = document.getElementById('live-active-visitors');
                        if (el) el.innerText = data.active_count;
                    }
                })
                .catch(() => {});
        }

        updateCount();
        setInterval(updateCount, 5000);
    })();
</script>
@endpush
