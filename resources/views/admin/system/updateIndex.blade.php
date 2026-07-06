@extends('layouts.admin.app')

@section('title', 'System Update')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">System Update</h1>
                <p class="mt-1 text-sm text-slate-500">Manage your license key and run application updates</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">System Update</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mx-auto mb-6 w-full max-w-2xl space-y-4">

        {{-- License card --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">License</h2>
                    <p class="text-xs text-slate-500">Your application license key</p>
                </div>
            </div>
            <form id="email_config" action="{{ routeHelper('setting') }}" method="POST" class="p-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="14">

                <x-ui.textarea name="license_ssh_key" label="License Key (*)" rows="4" placeholder="Enter License Key..." required>{{ setting('license_ssh_key') ?? '' }}</x-ui.textarea>

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    <x-ui.button type="submit" variant="success">
                        <i class="fas fa-arrow-circle-up"></i>
                        Update
                    </x-ui.button>
                </div>
            </form>
        </div>

        {{-- System update card --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Application Update</h2>
                        <p class="text-xs text-slate-500">Confirm your password to run the updater</p>
                    </div>
                </div>
                <x-ui.button variant="info" size="sm" :href="route('admin.info')">
                    <i class="fas fa-info-circle text-xs"></i>
                    PHP Info
                </x-ui.button>
            </div>
            <div class="p-5">
                @php
                    function isEnabled($func)
                    {
                        return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
                    }
                @endphp
                @if (!isEnabled('shell_exec'))
                    <div class="mb-4 flex items-start gap-2 rounded-lg bg-danger/10 px-4 py-3 text-sm text-danger ring-1 ring-inset ring-danger/20">
                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                        <span>You have to enable your hosting shell_exec() before the update</span>
                    </div>
                @endif

                <form action="{{ route('admin.update') }}" method="POST">
                    @csrf

                    <x-ui.input name="password" label="Enter your password to update" type="password" placeholder="***" required />

                    <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                        <x-ui.button type="submit" variant="success">
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>

    </section>

@endsection
