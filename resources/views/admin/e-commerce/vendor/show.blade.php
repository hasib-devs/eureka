@extends('layouts.admin.app')

@section('title', 'Vendor Information')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Vendor Information</h1>
                <p class="mt-1 text-sm text-slate-500">Account, shop and payout details for {{ $vendor->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ routeHelper('vendor/' . $vendor->id . '/edit') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                    <i class="fas fa-edit text-xs"></i>
                    Edit
                </a>
                <a href="{{ routeHelper('vendor') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i>
                    Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Vendor Information</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            {{-- Main column --}}
            <div class="space-y-4 lg:col-span-2">

                {{-- Shop details --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-store"></i>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">Shop details</h3>
                    </div>
                    <div class="px-5 py-2">
                        <dl class="divide-y divide-slate-100">
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Shop Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->shop_info->name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Shop Address</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->shop_info->address }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Shop Url</dt>
                                <dd class="break-all text-right font-medium text-slate-800">{{ $vendor->shop_info->url }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Description</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->shop_info->description }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Bank details --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">Bank details</h3>
                    </div>
                    <div class="px-5 py-2">
                        <dl class="divide-y divide-slate-100">
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Bank Account</dt>
                                <dd class="text-right font-medium tabular-nums text-slate-800">{{ $vendor->shop_info->bank_account }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Bank Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->shop_info->bank_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Holder Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->shop_info->holder_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Branch Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->shop_info->branch_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Routing</dt>
                                <dd class="text-right font-medium tabular-nums text-slate-800">{{ $vendor->shop_info->routing }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-images"></i>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">Media &amp; documents</h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Profile</p>
                                <img src="/uploads/shop/profile/{{ $vendor->shop_info->profile }}" alt="Profile"
                                    class="h-24 w-full rounded-lg object-cover ring-1 ring-slate-200">
                            </div>
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Cover Photo</p>
                                <img src="/uploads/shop/cover/{{ $vendor->shop_info->cover_photo }}" alt="Cover photo"
                                    class="h-24 w-full rounded-lg object-cover ring-1 ring-slate-200">
                            </div>
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Nid Photo</p>
                                <img src="/uploads/shop/nid/{{ $vendor->shop_info->nid }}" alt="Nid photo"
                                    class="h-24 w-full rounded-lg object-cover ring-1 ring-slate-200">
                            </div>
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Trade Photo</p>
                                <img src="/uploads/shop/trade/{{ $vendor->shop_info->trade }}" alt="Trade photo"
                                    class="h-24 w-full rounded-lg object-cover ring-1 ring-slate-200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aside --}}
            <div class="space-y-4">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">Account</h3>
                    </div>
                    <div class="px-5 py-2">
                        <dl class="divide-y divide-slate-100">
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Username</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $vendor->username }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Email</dt>
                                <dd class="break-all text-right font-medium text-slate-800">{{ $vendor->email }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Phone</dt>
                                <dd class="text-right font-medium tabular-nums text-slate-800">{{ $vendor->phone }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
