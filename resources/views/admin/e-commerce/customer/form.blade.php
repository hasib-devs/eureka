@extends('layouts.admin.app')

@section('title')
    @isset($customer)
        Edit Customer
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
                        Edit Customer
                    @else
                        Add Customer
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($customer)
                        Update the details of this customer account
                    @else
                        Create a new customer account
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ routeHelper('customer') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:-translate-y-px hover:border-slate-400 hover:text-slate-800 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($customer)
                            Edit Customer
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
            @foreach ($errors->all() as $error)
                <x-ui.alert variant="danger" class="mb-2">{{ $error }}</x-ui.alert>
            @endforeach
        @endif

        <form action="{{ isset($customer) ? routeHelper('customer/' . $customer->id) : routeHelper('customer') }}"
            method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @isset($customer)
                @method('PUT')
            @endisset

            @php
                $number = '';
                if (isset($customer)) {
                    if ($customer->phone != 'null') {
                        $number = $customer->phone;
                    }
                }
            @endphp

            {{-- Customer details --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Customer Details</h3>
                        <p class="text-xs text-slate-500">Personal information and address</p>
                    </div>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <x-ui.input name="name" label="Name:*" type="text"
                                placeholder="Write customer name"
                                :value="$customer->name ?? old('name')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="username" label="Username (unique):*" type="text"
                                placeholder="Write customer username"
                                :value="$customer->username ?? old('username')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="email" label="Email:*" type="email"
                                placeholder="example@gmail.com"
                                :value="$customer->email ?? old('email')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="phone" label="Phone:*" type="text"
                                placeholder="Enter Your Phone Number"
                                :value="$number"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="country" label="Country:*" type="text"
                                placeholder="write customer country name"
                                :value="$customer->customer_info->country ?? old('country')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="city" label="City:*" type="text"
                                placeholder="write customer city name"
                                :value="$customer->customer_info->city ?? old('city')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="street" label="Street:*" type="text"
                                placeholder="write customer street"
                                :value="$customer->customer_info->street ?? old('street')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="post_code" label="Post Code:*" type="text"
                                placeholder="write customer post code"
                                :value="$customer->customer_info->post_code ?? old('post_code')"
                                required />
                        </div>
                    </div>
                </div>
            </div>

            @isset($customer)
            @else
                {{-- Account security (create only) --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-lock"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Account Security</h3>
                            <p class="text-xs text-slate-500">Set a password for the new account</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <x-ui.input name="password" label="Password:*" type="password"
                                    placeholder="********"
                                    required />
                            </div>
                            <div>
                                <x-ui.input name="password_confirmation" label="Confirm Password:*" type="password"
                                    placeholder="********"
                                    required />
                            </div>
                        </div>
                    </div>
                </div>
            @endisset

            {{-- Submit row --}}
            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <a href="{{ routeHelper('customer') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-slate-400 hover:text-slate-800">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                    @isset($customer)
                        <i class="fas fa-arrow-circle-up"></i> Update
                    @else
                        <i class="fas fa-plus-circle"></i> Submit
                    @endisset
                </button>
            </div>
        </form>
    </section>

@endsection
