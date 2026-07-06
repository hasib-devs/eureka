@extends('layouts.admin.app')

@section('title', 'Settings')

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Manage your application branding and images</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            {{-- Application images --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Application Images</h2>
                        <p class="text-xs text-slate-500">Logo, meta image and favicon shown across the storefront</p>
                    </div>
                </div>

                <form action="{{ routeHelper('setting/logo') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6 p-5">
                        <div>
                            <label for="logo" class="mb-1 block text-sm font-medium text-slate-700">Logo</label>
                            <input type="file" name="logo" id="logo"
                                class="w-full @error('logo') border-danger @enderror"
                                data-default-file="{{ '/uploads/setting/' . setting('logo') }}">
                            @error('logo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="meta_img_wrap">
                            <label for="auth_logo" class="mb-1 block text-sm font-medium text-slate-700">Meta Image <span class="font-normal text-slate-400">(1200&times;627px)</span></label>
                            <input type="file" name="auth_logo" id="auth_logo"
                                class="w-full @error('auth_logo') border-danger @enderror"
                                data-default-file="{{ '/uploads/setting/' . setting('auth_logo') }}">
                            @error('auth_logo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                            <a href="{{ route('admin.setting.seo') }}" class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary hover:underline">
                                Meta Data <i class="fas fa-caret-right"></i>
                            </a>
                        </div>

                        <div>
                            <label for="favicon" class="mb-1 block text-sm font-medium text-slate-700">Favicon</label>
                            <input type="file" name="favicon" id="favicon"
                                class="w-full @error('favicon') border-danger @enderror"
                                data-default-file="{{ '/uploads/setting/' . setting('favicon') }}">
                            @error('favicon')
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
                </form>
            </div>

            {{-- Update notice --}}
            <div class="rounded-xl border border-slate-200 bg-gradient-to-br from-slate-800 to-slate-900 text-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-white/10 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-white/10 text-white">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h2 class="text-base font-semibold text-white">Update Notice</h2>
                </div>
                <div class="p-5">
                    <h5 class="text-sm font-semibold text-white">We are always there for support.</h5>
                    <p class="mt-2 text-sm text-white/70">We are coming with new innovation @Finvasoft</p>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input[type="file"]').dropify();
        });
    </script>
@endpush
