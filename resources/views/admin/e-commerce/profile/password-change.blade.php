@extends('layouts.admin.app')

@section('title', 'Change Password')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Change Password</h1>
                <p class="mt-1 text-sm text-slate-500">Update your account password to keep it secure</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Change Password</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mx-auto mb-6 w-full max-w-xl">

        {{-- Security card --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Password &amp; Security</h2>
                    <p class="text-xs text-slate-500">Choose a strong password you don't use elsewhere</p>
                </div>
            </div>

            <form action="{{ routeHelper('profile/password/update') }}" method="POST" class="p-5">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <x-ui.input
                        name="current_password"
                        label="Current Password"
                        type="password"
                        placeholder="Current Password"
                    />

                    <x-ui.input
                        name="password"
                        label="New Password"
                        type="password"
                        placeholder="New Password"
                    />

                    <x-ui.input
                        name="password_confirmation"
                        label="Confirm Password"
                        type="password"
                        placeholder="Confirm Password"
                    />
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    <x-ui.button variant="outline" :href="routeHelper('profile')">Cancel</x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-key text-xs"></i>
                        Change Password
                    </x-ui.button>
                </div>

            </form>
        </div>

    </section>

@endsection
