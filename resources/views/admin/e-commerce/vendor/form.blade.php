@extends('layouts.admin.app')

@section('title')
    @isset($vendor)
        Edit Vendor
    @else
        Add Vendor
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($vendor)
                        Edit Vendor
                    @else
                        Add Vendor
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($vendor)
                        Update this vendor's account, shop and bank details
                    @else
                        Create a new vendor account with shop and bank details
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($vendor)
                    <a href="{{ routeHelper('vendor/' . $vendor->id) }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50 hover:shadow">
                        <i class="fas fa-eye text-xs"></i>
                        Show
                    </a>
                @endisset
                <a href="{{ routeHelper('vendor') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i>
                    Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($vendor)
                            Edit Vendor
                        @else
                            Add Vendor
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

        <form action="{{ isset($vendor) ? routeHelper('vendor/' . $vendor->id) : routeHelper('vendor') }}"
            method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @isset($vendor)
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
                        <p class="text-xs text-slate-500">Vendor identity and contact information</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-ui.input name="name" label="Name:*" type="text"
                            placeholder="Write name"
                            :value="$vendor->name ?? old('name')"
                            required />

                        <x-ui.input name="username" label="Username (unique):*" type="text"
                            placeholder="Write username"
                            :value="$vendor->username ?? old('username')"
                            required />

                        <x-ui.input name="email" label="Email:*" type="email"
                            placeholder="example@gmail.com"
                            :value="$vendor->email ?? old('email')"
                            required />

                        <x-ui.input name="phone" label="Phone:*" type="text"
                            placeholder="write phone number"
                            :value="$vendor->phone ?? old('phone')"
                            required />
                    </div>
                </div>
            </div>

            {{-- Shop details --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Shop details</h3>
                        <p class="text-xs text-slate-500">Storefront name, address and commission</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-ui.input name="shop_name" label="Shop Name:*" type="text"
                            placeholder="write shop name"
                            :value="$vendor->shop_info->name ?? old('shop_name')"
                            required />

                        <x-ui.input name="url" label="Fb page Url:" type="text"
                            placeholder="write shop url"
                            :value="$vendor->shop_info->url ?? old('url')" />

                        <x-ui.input name="address" label="Address:*" type="text"
                            placeholder="write shop address"
                            :value="$vendor->shop_info->address ?? old('address')"
                            required />

                        <x-ui.input name="commission" label="Commission(Optional):" type="number"
                            placeholder="write commission"
                            :value="$vendor->shop_info->commission ?? old('commission')" />

                        <div class="md:col-span-2">
                            <x-ui.textarea name="description" label="Description:*" rows="4"
                                placeholder="write shop description">{{ $vendor->shop_info->description ?? old('description') }}</x-ui.textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bank details --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Bank details</h3>
                        <p class="text-xs text-slate-500">Payout account used for withdrawals</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <x-ui.input name="bank_account" label="Bank Account:*" type="text"
                            placeholder="write bank account"
                            :value="$vendor->shop_info->bank_account ?? old('bank_account')"
                            required />

                        <x-ui.input name="bank_name" label="Bank Name:*" type="text"
                            placeholder="write bank name"
                            :value="$vendor->shop_info->bank_name ?? old('bank_name')"
                            required />

                        <x-ui.input name="holder_name" label="Holder Name:*" type="text"
                            placeholder="write holder name"
                            :value="$vendor->shop_info->holder_name ?? old('holder_name')"
                            required />

                        <x-ui.input name="branch_name" label="Branch Name:*" type="text"
                            placeholder="write bank branch name"
                            :value="$vendor->shop_info->branch_name ?? old('branch_name')"
                            required />

                        <x-ui.input name="routing" label="Routing:*" type="text"
                            placeholder="write routing"
                            :value="$vendor->shop_info->routing ?? old('routing')"
                            required />
                    </div>
                </div>
            </div>

            {{-- Security (create only) --}}
            @isset($vendor)
            @else
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Security</h3>
                            <p class="text-xs text-slate-500">Set a login password for this vendor</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input name="password" label="Password:*" type="password"
                                placeholder="********" required />

                            <div class="space-y-1">
                                <label for="password-confirm" class="block text-sm font-medium text-slate-700">Confirm Password:*</label>
                                <input type="password" name="password_confirmation" id="password-confirm"
                                    placeholder="********" required
                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            </div>
                        </div>
                    </div>
                </div>
            @endisset

            {{-- Media & documents --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Media &amp; documents</h3>
                        <p class="text-xs text-slate-500">Shop images and verification documents</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-1">
                            <label for="profile" class="block text-sm font-medium text-slate-700">Shop Profile:</label>
                            <input type="file" name="profile" id="profile" accept="image/*"
                                class="dropify @error('profile') border-danger @enderror"
                                data-default-file="@isset($vendor)/uploads/shop/profile/{{ $vendor->shop_info->profile }}@endisset">
                            @error('profile')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="cover_photo" class="block text-sm font-medium text-slate-700">Shop Cover Photo:</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="dropify"
                                data-default-file="@isset($vendor)/uploads/shop/cover/{{ $vendor->shop_info->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="nid" class="block text-sm font-medium text-slate-700">Nid Card:</label>
                            <input type="file" name="nid" id="nid" accept="image/*"
                                class="dropify"
                                data-default-file="@isset($vendor)/uploads/shop/nid/{{ $vendor->shop_info->nid }}@endisset">
                            @error('nid')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="trade" class="block text-sm font-medium text-slate-700">Trade license:</label>
                            <input type="file" name="trade" id="trade" accept="image/*"
                                class="dropify"
                                data-default-file="@isset($vendor)/uploads/shop/trade/{{ $vendor->shop_info->trade }}@endisset">
                            @error('trade')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <a href="{{ routeHelper('vendor') }}"
                    class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                    @isset($vendor)
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

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
