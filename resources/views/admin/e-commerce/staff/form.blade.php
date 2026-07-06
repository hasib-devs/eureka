@extends('layouts.admin.app')

@section('title')
    @isset($staff)
        Edit staff
    @else
        Add staff
    @endisset
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($staff)
                        Edit Staff
                    @else
                        Add Staff
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($staff)
                        Update this staff member's details and role
                    @else
                        Create a new staff account with a role
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.staff.list') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i>
                    Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($staff)
                            Edit Staff
                        @else
                            Add Staff
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

        <form action="{{ isset($staff) ? routeHelper('staff/' . $staff->id) : route('admin.staff.store') }}"
            method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Staff details --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Staff details</h3>
                        <p class="text-xs text-slate-500">Basic identity and contact information</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-ui.input name="name" label="Name:" type="text"
                            placeholder="Write staff name"
                            :value="$staff->name ?? old('name')"
                            required />

                        <x-ui.input name="username" label="Username (unique):" type="text"
                            placeholder="Write staff username"
                            :value="$staff->username ?? old('username')"
                            required />

                        <x-ui.input name="email" label="Email:" type="email"
                            placeholder="example@gmail.com"
                            :value="$staff->email ?? old('email')"
                            required />

                        <x-ui.input name="phone" label="Phone:" type="text"
                            placeholder="write staff phone number"
                            :value="$staff->phone ?? old('phone')"
                            required />
                    </div>
                </div>
            </div>

            {{-- Role & profile --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Role &amp; profile</h3>
                        <p class="text-xs text-slate-500">Assign a role and upload a profile photo</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-ui.select name="position" label="Select Role:" required>
                            <option value="1">Admin</option>
                            <option value="2">Manager</option>
                            <option value="3">Product Manager</option>
                            <option value="4">Delivery Manager</option>
                        </x-ui.select>

                        <div class="space-y-1">
                            <label for="profile" class="block text-sm font-medium text-slate-700">Profile:</label>
                            <label class="block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-6 text-center transition-colors hover:border-primary/50 hover:bg-primary-50/30">
                                <i class="fas fa-cloud-upload-alt mb-2 text-xl text-slate-400"></i>
                                <p class="text-sm font-medium text-slate-700">Click to upload a profile photo</p>
                                <input type="file" name="profile" id="profile"
                                    class="mx-auto mt-3 block w-full max-w-xs text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-primary @error('profile') text-danger @enderror"
                                    required>
                            </label>
                            @error('profile')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Security (create only) --}}
            @isset($staff)
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
                                placeholder="********"
                                required />

                            <x-ui.input name="password_confirmation" label="Confirm Password:" type="password"
                                placeholder="********"
                                required />
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
                    @isset($staff)
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
