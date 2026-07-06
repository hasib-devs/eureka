@extends('layouts.admin.app')

@section('title', 'Coupon List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Coupons</h1>
                <p class="mt-1 text-sm text-slate-500">Create and manage discount coupons for your store</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ routeHelper('coupon/create') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
                    <i class="fas fa-plus text-xs"></i> Add Coupon
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Coupons</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Coupons</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ count($coupons) }} {{ Str::plural('coupon', count($coupons)) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Coupon Code</th>
                            <th class="px-4 py-3.5">Discount Type</th>
                            <th class="px-4 py-3.5 text-right">Charge</th>
                            <th class="px-4 py-3.5 text-right">User Limit</th>
                            <th class="px-4 py-3.5 text-right">Total Limit</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($coupons as $key => $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4">
                                    <a href="{{ routeHelper('coupon/' . $data->id) }}" class="font-semibold text-primary hover:underline">{{ $data->code }}</a>
                                </td>
                                <td class="px-4 py-4 capitalize text-slate-600">{{ $data->discount_type }}</td>
                                <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">
                                    @if ($data->discount_type == 'percent')
                                        {{ $data->discount . ' %' }}
                                    @else
                                        {{ $data->discount }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">{{ $data->limit_per_user }}</td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">{{ $data->total_use_limit }}</td>
                                <td class="px-4 py-4">
                                    @if ($data->status)
                                        <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ routeHelper('coupon/' . $data->id) }}"
                                            title="View coupon"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ routeHelper('coupon/' . $data->id . '/edit') }}"
                                            title="Edit coupon"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" id="deleteData" data-id="{{ $data->id }}"
                                            title="Delete coupon"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <form id="delete-data-form-{{ $data->id }}"
                                            action="{{ routeHelper('coupon/' . $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-ticket-alt text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No coupons found</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Get started by
                                        <a href="{{ routeHelper('coupon/create') }}" class="text-primary hover:underline">adding your first coupon</a>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
