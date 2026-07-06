@extends('layouts.admin.app')

@section('title')
    @isset($coupon)
        Edit Coupon
    @else
        Add Coupon
    @endisset
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($coupon)
                        Edit Coupon
                    @else
                        Add Coupon
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($coupon)
                        Update the details of this coupon
                    @else
                        Create a new discount coupon
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($coupon)
                    <x-ui.button variant="outline" :href="routeHelper('coupon/' . $coupon->id)">
                        <i class="fas fa-eye text-xs"></i> Show
                    </x-ui.button>
                @endisset
                <x-ui.button variant="outline" :href="routeHelper('coupon')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('coupon') }}" class="transition-colors hover:text-primary">Coupons</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($coupon)
                            Edit
                        @else
                            Add
                        @endisset
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form action="{{ isset($coupon) ? routeHelper('coupon/' . $coupon->id) : routeHelper('coupon') }}"
                method="POST">
                @csrf
                @isset($coupon)
                    @method('PUT')
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                @isset($coupon)
                                    Edit Coupon Details
                                @else
                                    Add New Coupon
                                @endisset
                            </h3>
                            <p class="text-xs text-slate-500">Code, discount, usage limits and validity</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            {{-- Coupon Code --}}
                            <x-ui.input
                                name="code"
                                label="Coupon Code"
                                type="text"
                                placeholder="Write coupon code"
                                :value="$coupon->code ?? old('code')"
                                required
                                autocomplete="off"
                            />

                            {{-- Discount Type --}}
                            <x-ui.select name="discount_type" label="Discount Type">
                                <option value="percent" @selected(isset($coupon) && $coupon->discount_type == 'percent')>Percent</option>
                                <option value="amount" @selected(isset($coupon) && $coupon->discount_type == 'amount')>Fixed Amount</option>
                            </x-ui.select>

                            {{-- Discount Amount/Percent --}}
                            <x-ui.input
                                name="discount"
                                label="Discount Amount/Percent"
                                type="text"
                                placeholder="Write discount amount/percent"
                                :value="$coupon->discount ?? old('discount')"
                                required
                            />

                            {{-- Use Limit Per User --}}
                            <x-ui.input
                                name="limit_per_user"
                                label="Use Limit Per User"
                                type="number"
                                placeholder="Enter use limit per user"
                                :value="$coupon->limit_per_user ?? old('limit_per_user')"
                                required
                            />

                            {{-- Total Use Limit --}}
                            <x-ui.input
                                name="total_use_limit"
                                label="Total Use Limit"
                                type="number"
                                placeholder="Enter total use limit"
                                :value="$coupon->total_use_limit ?? old('total_use_limit')"
                                required
                            />

                            {{-- Expire Date --}}
                            <x-ui.input
                                name="expire_date"
                                label="Expire Date"
                                type="date"
                                :value="$coupon->expire_date ?? old('expire_date')"
                                required
                            />
                        </div>

                        {{-- Description --}}
                        <div class="mt-4">
                            <x-ui.textarea
                                name="description"
                                label="Description"
                                rows="2"
                                placeholder="Write coupon description"
                            >{{ $coupon->description ?? old('description') }}</x-ui.textarea>
                        </div>

                        {{-- Status toggle --}}
                        <div class="mt-2">
                            <label for="status" class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($coupon->status ?? true)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('coupon')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($coupon)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Submit
                            @endisset
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
