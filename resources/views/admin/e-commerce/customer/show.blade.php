@extends('layouts.admin.app')

@section('title', 'Information')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ $customer->desig != '' ? 'Staff' : 'Customer' }} Information
                </h1>
                <p class="mt-1 text-sm text-slate-500">Profile and address details for {{ $customer->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ routeHelper('customer/' . $customer->id . '/edit') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                    <i class="fas fa-edit text-xs"></i> Edit
                </a>
                <a href="{{ routeHelper('customer') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:-translate-y-px hover:border-slate-400 hover:text-slate-800 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Details</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            {{-- Profile --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </span>
                    <h3 class="text-base font-semibold text-slate-900">
                        {{ $customer->desig != '' ? 'Staff' : 'Customer' }} Details
                    </h3>
                </div>
                <dl class="divide-y divide-slate-100 px-5 py-2">
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Name</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Username</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->username }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Email</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->email }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Phone</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->phone }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Address --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <h3 class="text-base font-semibold text-slate-900">Address</h3>
                </div>
                <dl class="divide-y divide-slate-100 px-5 py-2">
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Country</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->customer_info->country }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">City</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->customer_info->city }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Street</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->customer_info->street }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Post Code</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $customer->customer_info->post_code }}</dd>
                    </div>
                </dl>
            </div>

        </div>
    </section>

@endsection
