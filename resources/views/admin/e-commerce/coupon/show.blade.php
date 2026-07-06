@extends('layouts.admin.app')

@section('title', 'Coupon Information')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Coupon Information</h1>
                <p class="mt-1 text-sm text-slate-500">Full details of coupon "{{ $coupon->code }}"</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('coupon/' . $coupon->id . '/edit')">
                    <i class="fas fa-edit text-xs"></i> Edit
                </x-ui.button>
                <x-ui.button variant="outline" :href="routeHelper('coupon')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('coupon') }}" class="transition-colors hover:text-primary">Coupons</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Show</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $coupon->code }}</h2>
                        <p class="text-xs text-slate-500">Coupon details and usage limits</p>
                    </div>
                </div>

                <div class="px-5 py-2">
                    <dl class="divide-y divide-slate-100">
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Coupon Code</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $coupon->code }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Discount Type</dt>
                            <dd class="text-right font-medium capitalize text-slate-800">{{ $coupon->discount_type }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Discount Amount/Percent</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">
                                @if ($coupon->discount_type == 'percent')
                                    {{ $coupon->discount . ' %' }}
                                @else
                                    {{ $coupon->discount }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Use Limit Per User</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $coupon->limit_per_user }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Total Use Limit</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $coupon->total_use_limit }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Expire Date</dt>
                            <dd class="whitespace-nowrap text-right font-medium text-slate-800">{{ date('d M Y', strtotime($coupon->expire_date)) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Description</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $coupon->description }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Status</dt>
                            <dd class="text-right">
                                @if ($coupon->status)
                                    <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

@endsection
