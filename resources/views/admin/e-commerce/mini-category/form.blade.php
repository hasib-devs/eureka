@extends('layouts.admin.app')

@section('title')
    @isset($mini)
        Edit Mini Category
    @else
        Add Mini Category
    @endisset
@endsection

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($mini)
                        Edit Mini Category
                    @else
                        Add Mini Category
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($mini)
                        Update the details of this mini category
                    @else
                        Create a new mini category under a sub category
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.minicategory.list')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.minicategory.list') }}" class="transition-colors hover:text-primary">Mini Categories</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($mini)
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
            @if (!empty(Session::get('massage2')))
                <x-ui.alert variant="success" class="mb-4 text-center">
                    {{ Session::get('massage2') }}
                </x-ui.alert>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">
                            @isset($mini)
                                Edit Mini Category Details
                            @else
                                Add New Mini Category
                            @endisset
                        </h3>
                        <p class="text-xs text-slate-500">Sub category, name, cover photo and visibility</p>
                    </div>
                </div>

                @isset($mini)
                    <form action="{{ route('admin.edit.mini') }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" value="{{ $mini->id }}" name="ddddd">
                    @else
                        <form action="{{ route('admin.create.mini') }}" method="POST" enctype="multipart/form-data">
                        @endisset
                        @csrf

                        <div class="space-y-4 p-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                {{-- Sub category select --}}
                                <div>
                                    <label for="category" class="mb-1 block text-sm font-medium text-slate-700">Select Sub Category</label>
                                    <select name="category" id="category"
                                        class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('category') border-danger @else border-slate-300 @enderror">
                                        <option value="">Select Category</option>
                                        @foreach ($sub_categories as $category)
                                            <option value="{{ $category->id }}"
                                                @isset($mini) {{ $mini->category_id == $category->id ? 'selected' : '' }} @endisset>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Name --}}
                                <x-ui.input
                                    name="name"
                                    label="Name"
                                    type="text"
                                    :value="$mini->name ?? null"
                                    placeholder="Write category name"
                                    required
                                    autocomplete="off"
                                />
                            </div>

                            {{-- Cover Photo (Dropify — keep raw input, JS hooks preserved) --}}
                            <div>
                                <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo</label>
                                <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                    class="block w-full @error('cover_photo') border border-danger rounded-lg @enderror"
                                    data-default-file="@isset($mini)/uploads/mini-category/{{ $mini->cover_photo }}@endisset">
                                @error('cover_photo')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="inline-flex cursor-pointer items-center gap-2">
                                    <input type="checkbox" name="status" id="status"
                                        class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" @checked($mini->status ?? true)>
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
                                        class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" @checked($mini->is_feature ?? false)>
                                    <span class="text-sm font-medium text-slate-700">Featured</span>
                                </label>
                                @error('is_feature')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                            <x-ui.button variant="outline" :href="route('admin.minicategory.list')">
                                Cancel
                            </x-ui.button>
                            <x-ui.button type="submit" variant="primary">
                                @isset($mini)
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                @else
                                    <i class="fas fa-plus-circle"></i>
                                    Submit
                                @endisset
                            </x-ui.button>
                        </div>
                    </form>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
        });
    </script>
@endpush
