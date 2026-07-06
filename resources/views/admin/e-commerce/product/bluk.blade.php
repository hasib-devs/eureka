@extends('layouts.admin.app')

@section('title', 'Bulk Product Import/Export')

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Bulk Import / Export</h1>
                <p class="mt-1 text-sm text-slate-500">Import products from a spreadsheet or export the full catalog</p>
            </div>
            <ol class="flex items-center gap-1 text-sm text-slate-400">
                <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                <li class="font-medium text-slate-600">Bulk Import / Export</li>
            </ol>
        </div>
    </section>

    <section class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-2">

        {{-- Import --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fas fa-file-import"></i>
                </span>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Import Products</h3>
                    <p class="text-xs text-slate-500">Upload an Excel file to create products in bulk</p>
                </div>
            </div>
            <form method="post" action="{{ route('admin.product.import') }}" enctype="multipart/form-data" class="p-5">
                @csrf
                <input class="dropify" type="file" name="product">
                <div class="mt-4 flex justify-end">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-file-import text-xs"></i>
                        Import
                    </x-ui.button>
                </div>
            </form>
        </div>

        {{-- Export --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fas fa-file-export"></i>
                </span>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Export Products</h3>
                    <p class="text-xs text-slate-500">Download the entire product catalog as an Excel file</p>
                </div>
            </div>
            <div class="flex flex-col items-center gap-3 p-5 py-10 text-center">
                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                    <i class="fas fa-file-excel text-xl text-slate-300"></i>
                </div>
                <p class="text-sm text-slate-500">The export includes every product with its categories, colors and attributes.</p>
                <x-ui.button variant="primary" :href="route('admin.product.export')">
                    <i class="fas fa-file-export text-xs"></i>
                    Export
                </x-ui.button>
            </div>
        </div>

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
