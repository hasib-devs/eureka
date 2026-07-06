@extends('layouts.admin.app')

@section('title', 'Update Profile')

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Update Profile</h1>
                <p class="mt-1 text-sm text-slate-500">Edit your account and shop information</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('profile') }}" class="transition-colors hover:text-primary">My Profile</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Update</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">

        @if (auth()->user()->desig == 1)
            <form action="{{ routeHelper('profile/info') }}" method="POST" enctype="multipart/form-data">
        @else
            <form action="{{ routeHelper('profile/info2') }}" method="POST" enctype="multipart/form-data">
        @endif
            @csrf
            @method('PUT')

            <div class="space-y-4">

                {{-- Images card --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-image"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Images</h3>
                            <p class="text-xs text-slate-500">Profile picture and shop imagery</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label for="avatar" class="mb-1 block text-sm font-medium text-slate-700">Profile Image</label>
                                <input type="file" name="avatar"
                                    id="avatar" class="dropify @error('avatar') is-invalid @enderror"
                                    data-default-file="{{ '/uploads/admin/' . $admin->avatar }}">
                                @error('avatar')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            @if (auth()->user()->desig == 1)
                                <div>
                                    <label for="profile" class="mb-1 block text-sm font-medium text-slate-700">Shop Profile</label>
                                    <input type="file" name="profile"
                                        id="profile" class="dropify @error('profile') is-invalid @enderror"
                                        data-default-file="{{ '/uploads/shop/profile/' . $admin->shop_info->profile }}">
                                    @error('profile')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Shop Cover Photo</label>
                                    <input type="file" name="cover_photo"
                                        id="cover_photo" class="dropify @error('cover_photo') is-invalid @enderror"
                                        data-default-file="{{ '/uploads/shop/cover/' . $admin->shop_info->cover_photo }}">
                                    @error('cover_photo')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Basic information card --}}
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
                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            <x-ui.input name="name" label="Name" type="text"
                                :value="$admin->name ?? old('name')" placeholder="Name" />

                            <x-ui.input name="username" label="Username" type="text"
                                :value="$admin->username ?? old('username')" placeholder="Username" />

                            <x-ui.input name="email" label="Email" type="email"
                                :value="$admin->email ?? old('email')" placeholder="example@gmail.com" />

                            <x-ui.input name="phone" label="Phone" type="text" maxlength="25"
                                :value="$admin->phone ?? old('phone')" placeholder="Phone Number" />

                        </div>
                    </div>
                </div>

                @if (auth()->user()->desig == 1)
                    {{-- Shop information card --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                <i class="fas fa-store"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Shop Information</h3>
                                <p class="text-xs text-slate-500">Shop identity and payout details</p>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <x-ui.input name="shop_name" label="Shop Name" type="text"
                                    :value="$admin->shop_info->name ?? old('shop_name')"
                                    placeholder="Write shop name" required />

                                <x-ui.input name="url" label="Shop Url" type="text"
                                    :value="$admin->shop_info->url ?? old('url')"
                                    placeholder="write shop url" required />

                                <x-ui.input name="bank_account" label="Bank Account" type="text"
                                    :value="$admin->shop_info->bank_account ?? old('bank_account')"
                                    placeholder="write bank account" required />

                                <x-ui.input name="bank_name" label="Bank Name" type="text"
                                    :value="$admin->shop_info->bank_name ?? old('bank_name')"
                                    placeholder="write bank name" required />

                                <x-ui.input name="holder_name" label="Holder Name" type="text"
                                    :value="$admin->shop_info->holder_name ?? old('holder_name')"
                                    placeholder="write holder name" required />

                                <x-ui.input name="branch_name" label="Branch Name" type="text"
                                    :value="$admin->shop_info->branch_name ?? old('branch_name')"
                                    placeholder="write bank branch name" required />

                                <x-ui.input name="routing" label="Routing" type="text"
                                    :value="$admin->shop_info->routing ?? old('routing')"
                                    placeholder="write routing" required />

                                <x-ui.input name="address" label="Address" type="text"
                                    :value="$admin->shop_info->address ?? old('address')"
                                    placeholder="write shop address" required />

                                <div class="md:col-span-2">
                                    <x-ui.textarea name="description" label="Description" rows="4"
                                        placeholder="write shop description">{{ $admin->shop_info->description ?? old('description') }}</x-ui.textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    <x-ui.button variant="outline" :href="routeHelper('profile')">Cancel</x-ui.button>
                    <x-ui.button variant="primary" type="submit">
                        <i class="fas fa-arrow-circle-up"></i>
                        Update
                    </x-ui.button>
                </div>

            </div>

        </form>

    </section>

@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
