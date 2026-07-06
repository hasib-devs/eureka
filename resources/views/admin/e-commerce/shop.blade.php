@extends('layouts.admin.app')

@section('title', 'Shop Details')

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Shop Details</h1>
                <p class="mt-1 text-sm text-slate-500">Main shop profile, bank details and media</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.setting.site_info')">
                    Update Shop Information <i class="fas fa-caret-right"></i>
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Shop Details</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <form action="{{ routeHelper('shop/update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mx-auto w-full max-w-4xl space-y-4">

                {{-- Shop identity --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Shop Identity</h2>
                            <p class="text-xs text-slate-500">Name, URL, address and description of the main shop</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <x-ui.input
                            name="shop_name"
                            label="Shop Name:"
                            type="text"
                            :value="$shop_info->name ?? old('shop_name')"
                            placeholder="write shop name"
                            required
                        />

                        <x-ui.input
                            name="url"
                            label="Shop Url:"
                            type="text"
                            :value="$shop_info->url ?? old('url')"
                            placeholder="write shop url"
                            required
                        />

                        <div class="md:col-span-2">
                            <x-ui.input
                                name="address"
                                label="Address:"
                                type="text"
                                :value="$shop_info->address ?? old('address')"
                                placeholder="write shop address"
                                required
                            />
                        </div>

                        <div class="md:col-span-2">
                            <x-ui.textarea
                                name="description"
                                label="Description:"
                                rows="4"
                                placeholder="write shop description"
                            >{{ $shop_info->description ?? old('description') }}</x-ui.textarea>
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
                            <h2 class="text-base font-semibold text-slate-900">Bank Details</h2>
                            <p class="text-xs text-slate-500">Payout account for the main shop</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <x-ui.input
                            name="bank_account"
                            label="Bank Account:"
                            type="text"
                            :value="$shop_info->bank_account ?? old('bank_account')"
                            placeholder="write bank account"
                            required
                        />

                        <x-ui.input
                            name="bank_name"
                            label="Bank Name:"
                            type="text"
                            :value="$shop_info->bank_name ?? old('bank_name')"
                            placeholder="write bank name"
                            required
                        />

                        <x-ui.input
                            name="holder_name"
                            label="Holder Name:"
                            type="text"
                            :value="$shop_info->holder_name ?? old('holder_name')"
                            placeholder="write holder name"
                            required
                        />

                        <x-ui.input
                            name="branch_name"
                            label="Branch Name:"
                            type="text"
                            :value="$shop_info->branch_name ?? old('branch_name')"
                            placeholder="write bank branch name"
                            required
                        />

                        <x-ui.input
                            name="routing"
                            label="Routing:"
                            type="text"
                            :value="$shop_info->routing ?? old('routing')"
                            placeholder="write routing"
                            required
                        />
                    </div>
                </div>

                {{-- Media --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-image"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Media</h2>
                            <p class="text-xs text-slate-500">Shop profile image and cover photo</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="profile" class="mb-1 block text-sm font-medium text-slate-700">Profile:</label>
                            <input type="file" name="profile" id="profile" accept="image/*"
                                class="dropify @error('profile') is-invalid @enderror"
                                data-default-file="/uploads/shop/profile/{{ $shop_info->profile }}">
                            @error('profile')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo:</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="dropify @error('cover_photo') is-invalid @enderror"
                                data-default-file="/uploads/shop/cover/{{ $shop_info->cover_photo }}">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </div>

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
