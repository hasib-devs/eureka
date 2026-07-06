@extends('layouts.admin.app')

@section('title')
    @isset($collection)
        Edit Collection
    @else
        Add Collection
    @endisset
@endsection

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
                    @isset($collection)
                        Edit Collection
                    @else
                        Add Collection
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($collection)
                        Update the details of this collection
                    @else
                        Create a new collection of categories
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($collection)
                    <x-ui.button variant="outline" :href="routeHelper('collection/' . $collection->id)">
                        <i class="fas fa-eye text-xs"></i> Show
                    </x-ui.button>
                @endisset
                <x-ui.button variant="outline" :href="routeHelper('collection')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('collection') }}" class="transition-colors hover:text-primary">Collections</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($collection)
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
            <form
                action="{{ isset($collection) ? routeHelper('collection/' . $collection->id) : routeHelper('collection') }}"
                method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @isset($collection)
                    @method('PUT')
                @endisset

                {{-- Collection details --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Collection details</h3>
                            <p class="text-xs text-slate-500">Name and included categories</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                            <input type="text" name="name" id="name" placeholder="Write category name"
                                class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $collection->name ?? old('name') }}" required autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Categories (select2 hooks .select2) --}}
                        <div>
                            <label for="category" class="mb-1 block text-sm font-medium text-slate-700">Select Category</label>
                            <select name="categories[]" id="category" multiple data-placeholder="Select Category"
                                class="select2 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('categories') border-danger @else border-slate-300 @enderror"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @isset($collection) @foreach ($collection->categories as $pro_category) {{ $category->id == $pro_category->id ? 'selected' : '' }} @endforeach @endisset>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('categories')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Media & visibility --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-image"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Media &amp; visibility</h3>
                            <p class="text-xs text-slate-500">Cover photo and status</p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        {{-- Cover Photo (Dropify hooks #cover_photo — keep raw input) --}}
                        <div>
                            <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full @error('cover_photo') border border-danger rounded-lg @enderror"
                                data-default-file="@isset($collection) /uploads/collection/{{ $collection->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($collection->status ?? true)>
                                <label for="status"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('collection')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($collection)
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

@push('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
            $('.select2').select2();
        });
    </script>
@endpush
