@extends('layouts.admin.app')

@section('title', 'Your Information')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Profile</h1>
                <p class="mt-1 text-sm text-slate-500">Your account information at a glance</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" :href="routeHelper('profile/update')">
                    <i class="fas fa-edit text-xs"></i>
                    Edit Profile
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">My Profile</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6 grid grid-cols-1 items-start gap-4 lg:grid-cols-3">

        {{-- Aside: avatar + security --}}
        <div class="space-y-4">

            {{-- Avatar card --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                <img src="{{ Auth::user()->avatar != 'default.png' ? '/uploads/admin/' . Auth::user()->avatar : '/default/user.jpg' }}"
                    alt="User Image"
                    class="mx-auto h-28 w-28 rounded-full object-cover ring-4 ring-slate-100">
                <h2 class="mt-4 text-base font-semibold text-slate-900">{{ Auth::user()->name }}</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ '@' . Auth::user()->username }}</p>
                <div class="mt-3 flex justify-center">
                    @if (Auth::user()->status)
                        <x-ui.badge variant="success" dot>Active</x-ui.badge>
                    @else
                        <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                    @endif
                </div>
            </div>

            {{-- Security card --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Security</h3>
                        <p class="text-xs text-slate-500">Password and account access</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 p-5">
                    <p class="text-sm text-slate-500">Keep your account safe with a strong password.</p>
                    <x-ui.button variant="outline" size="sm" :href="routeHelper('profile/change-password')">
                        <i class="fas fa-key text-xs"></i>
                        Change Password
                    </x-ui.button>
                </div>
            </div>

        </div>

        {{-- Main: details --}}
        <div class="space-y-4 lg:col-span-2">

            {{-- Basic information --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Basic Information</h3>
                        <p class="text-xs text-slate-500">Your personal account details</p>
                    </div>
                </div>
                <dl class="divide-y divide-slate-100 px-5 py-2">
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Username</dt>
                        <dd class="text-right font-medium text-slate-800">{{ Auth::user()->username }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Name</dt>
                        <dd class="text-right font-medium text-slate-800">{{ Auth::user()->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Phone</dt>
                        <dd class="text-right font-medium text-slate-800">{{ Auth::user()->phone }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Email</dt>
                        <dd class="text-right font-medium text-slate-800">{{ Auth::user()->email }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Status</dt>
                        <dd class="text-right">
                            @if (Auth::user()->status)
                                <x-ui.badge variant="success" dot>Active</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            @if (auth()->user()->desig == 1)
                {{-- Shop information --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Shop Information</h3>
                            <p class="text-xs text-slate-500">Your shop branding and payout details</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <p class="mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Shop Profile</p>
                                <img src="{{ '/uploads/shop/profile/' . Auth::user()->shop_info->profile }}"
                                    alt="Shop profile image" width="250"
                                    class="max-w-full rounded-lg ring-1 ring-slate-200">
                            </div>
                            <div>
                                <p class="mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Cover Photo</p>
                                <img src="{{ '/uploads/shop/cover/' . Auth::user()->shop_info->cover_photo }}"
                                    alt="Shop cover image" width="250"
                                    class="max-w-full rounded-lg ring-1 ring-slate-200">
                            </div>
                        </div>
                        <dl class="divide-y divide-slate-100">
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Shop Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ Auth::user()->shop_info->name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Shop URL</dt>
                                <dd class="text-right font-medium text-slate-800">{{ Auth::user()->shop_info->url }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Bank Account</dt>
                                <dd class="text-right font-medium tabular-nums text-slate-800">{{ Auth::user()->shop_info->bank_account }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Bank Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ Auth::user()->shop_info->bank_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Holder Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ Auth::user()->shop_info->holder_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Branch Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ Auth::user()->shop_info->branch_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 text-sm">
                                <dt class="text-slate-500">Routing</dt>
                                <dd class="text-right font-medium tabular-nums text-slate-800">{{ Auth::user()->shop_info->routing }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif

        </div>

    </section>

@endsection
