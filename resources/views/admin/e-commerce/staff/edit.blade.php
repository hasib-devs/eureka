@extends('layouts.admin.app')

@section('title')
    @isset($customer)
        Edit Information
    @else
        Add Customer
    @endisset
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($customer)
                        Edit Information
                    @else
                        Add Customer
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($customer)
                        Update this staff member's account information
                    @else
                        Create a new customer account
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($product)
                    <a href="{{ routeHelper('customer/' . $product->id) }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50 hover:shadow">
                        <i class="fas fa-eye text-xs"></i>
                        Show
                    </a>
                @endisset
                <a href="{{ route('admin.staff.list') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i>
                    Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($customer)
                            Edit Information
                        @else
                            Add Customer
                        @endisset
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        @if ($errors->any())
            <div class="mb-4 space-y-2">
                @foreach ($errors->all() as $error)
                    <x-ui.alert variant="danger">{{ $error }}</x-ui.alert>
                @endforeach
            </div>
        @endif

        <form action="{{ isset($customer) ? routeHelper('customer/' . $customer->id) : routeHelper('customer') }}"
            method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @isset($customer)
                @method('PUT')
            @endisset

            {{-- Account details --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Account details</h3>
                        <p class="text-xs text-slate-500">Identity and contact information</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-ui.input name="name" label="Name:" type="text"
                            :value="$customer->name ?? old('name')"
                            placeholder="Write customer name" required />

                        <x-ui.input name="username" label="Username (unique):" type="text"
                            :value="$customer->username ?? old('username')"
                            placeholder="Write customer username" required />

                        <x-ui.input name="email" label="Email:" type="email"
                            :value="$customer->email ?? old('email')"
                            placeholder="example@gmail.com" required />

                        <x-ui.input name="phone" label="Phone:" type="text"
                            :value="$customer->phone ?? old('phone')"
                            placeholder="write customer phone number" required />
                    </div>
                </div>
            </div>

            {{-- Security (create only) --}}
            @isset($customer)
            @else
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Security</h3>
                            <p class="text-xs text-slate-500">Set a login password for this account</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input name="password" label="Password:" type="password"
                                placeholder="********" required />

                            <x-ui.input name="password_confirmation" label="Confirm Password:" type="password"
                                placeholder="********" required />
                        </div>
                    </div>
                </div>
            @endisset

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <a href="{{ route('admin.staff.list') }}"
                    class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                    @isset($customer)
                        <i class="fas fa-check text-xs"></i>
                        Update
                    @else
                        <i class="fas fa-plus text-xs"></i>
                        Submit
                    @endisset
                </button>
            </div>
        </form>
    </section>

@endsection
