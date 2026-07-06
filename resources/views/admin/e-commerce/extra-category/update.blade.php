@extends('layouts.admin.app')

@section('title', 'Edit Extra Category')

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Extra Category</h1>
                <p class="mt-1 text-sm text-slate-500">Update the details of this extra category</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.extracategory.list')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.extracategory.list') }}" class="transition-colors hover:text-primary">Extra Categories</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Edit</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            @if (!empty(Session::get('massage2')))
                <x-ui.alert variant="success" class="mb-4 text-center">
                    {{ Session::get('massage2') }}
                </x-ui.alert>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Edit Extra Category Details</h3>
                        <p class="text-xs text-slate-500">Parent categories, name, cover photo and visibility</p>
                    </div>
                </div>

                <form action="{{ route('admin.edit.extra') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="{{ $extra->id }}" name="ddddd">
                    @csrf

                    <div class="space-y-4 p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label for="mainCategory" class="mb-1 block text-sm font-medium text-slate-700">Category name</label>
                                <select name="main" id="mainCategory" required
                                    class="category block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option {{ $hascategories->id == $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="nsubc" class="mb-1 block text-sm font-medium text-slate-700">Select Sub Category</label>
                                <select name="nsubc" id="nsubc"
                                    class="sub_category block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="{{ $hsasub->id }}">{{ $hsasub->name }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="mini" class="mb-1 block text-sm font-medium text-slate-700">Select mini Category</label>
                                <select name="mini" id="mini"
                                    class="sub_category block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="{{ $hsaMini->id }}">{{ $hsaMini->name }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Name --}}
                        <x-ui.input
                            name="name"
                            label="Name"
                            type="text"
                            :value="$extra->name ?? null"
                            placeholder="Write category name"
                            required
                            autocomplete="off"
                        />

                        {{-- Cover Photo (Dropify — keep raw input, JS hooks preserved) --}}
                        <div>
                            <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full @error('cover_photo') border border-danger rounded-lg @enderror"
                                data-default-file="@isset($extra)/uploads/extra-category/{{ $extra->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input type="checkbox" name="status" id="status"
                                    class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" {{ $extra->status ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Featured --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input type="checkbox" name="is_feature" id="is_feature"
                                    class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" {{ $extra->is_feature ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-700">Featured</span>
                            </label>
                            @error('is_feature')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="route('admin.extracategory.list')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script src="/assets/dist/extra.js"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
        });
    </script>
@endpush
