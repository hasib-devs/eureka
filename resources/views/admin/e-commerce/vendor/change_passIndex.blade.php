@extends('layouts.admin.app')

@section('title', 'Change Password')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Change Password</h1>
                <p class="mt-1 text-sm text-slate-500">Set a new login password for this vendor</p>
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
                    <li class="font-medium text-slate-600">Change Password</li>
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

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            {{-- Vendor summary --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Vendor</h3>
                </div>
                <div class="px-5 py-2">
                    <dl class="divide-y divide-slate-100">
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Name</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $vendor->name ?? old('name') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Username</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $vendor->username ?? old('username') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Shop Name</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $vendor->shop_info->name ?? old('shop_name') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">E-mail</dt>
                            <dd class="break-all text-right font-medium text-slate-800">{{ $vendor->email ?? old('email') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Phone</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $vendor->phone ?? old('phone') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Password form --}}
            <div class="lg:col-span-2">
                <form action="{{ route('admin.vendor.change_pass', ['id' => $vendor->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @isset($vendor)
                        @method('PUT')
                    @endisset

                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">New password</h3>
                                <p class="text-xs text-slate-500">The vendor will use this password on their next login</p>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <x-ui.input name="password" label="Password:" type="password"
                                    placeholder="********" required />

                                <div class="space-y-1">
                                    <label for="password-confirm" class="block text-sm font-medium text-slate-700">Confirm Password:</label>
                                    <input type="password" name="password_confirmation" id="password-confirm"
                                        placeholder="********" required
                                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                            <a href="{{ routeHelper('vendor') }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                                <i class="fas fa-check text-xs"></i>
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>

@endsection
