@extends('layouts.admin.app')

@section('title')
    @isset($category)
        Edit Category
    @else
        Add Category
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
                    @isset($category)
                        Edit Category
                    @else
                        Add Category
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($category)
                        Update the details of this category
                    @else
                        Create a new top-level product category
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($category)
                    <x-ui.button variant="outline" :href="routeHelper('category/' . $category->id)">
                        <i class="fas fa-eye text-xs"></i> Show
                    </x-ui.button>
                @endisset
                <x-ui.button variant="outline" :href="routeHelper('category')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('category') }}" class="transition-colors hover:text-primary">Categories</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($category)
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
                action="{{ isset($category) ? routeHelper('category/' . $category->id) : routeHelper('category') }}"
                method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @isset($category)
                    @method('PUT')
                @endisset

                {{-- Category details --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Category details</h3>
                            <p class="text-xs text-slate-500">Name, position and description</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input
                                name="name"
                                label="Name"
                                type="text"
                                :value="$category->name ?? null"
                                placeholder="Write category name"
                                required
                                autocomplete="off"
                            />

                            <div>
                                <x-ui.input
                                    name="pos"
                                    label="Position"
                                    type="text"
                                    :value="$category->pos ?? null"
                                    placeholder="Position"
                                    autocomplete="off"
                                />
                                <p class="mt-1 text-xs text-slate-500">
                                    Already taken positions:
                                    @foreach (APP\Models\Category::all() as $pos)
                                        {{ $pos->pos }},
                                    @endforeach
                                </p>
                            </div>
                        </div>

                        <x-ui.textarea name="description" label="Description" :rows="5" placeholder="Write category description">{{ $category->description ?? old('description') }}</x-ui.textarea>
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
                            <p class="text-xs text-slate-500">Cover photo and display settings</p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        {{-- Cover Photo (Dropify — keep raw input, JS hooks preserved) --}}
                        <div>
                            <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full @error('cover_photo') border border-danger rounded-lg @enderror"
                                data-default-file="@isset($category) /uploads/category/{{ $category->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($category->status ?? true)>
                                <label for="status"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Is Feature toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="is_feature" id="is_feature" @checked($category->is_feature ?? false)>
                                <label for="is_feature"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Featured</span>
                            </div>
                            @error('is_feature')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Show on homepage toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="is_shown_on_homepage" id="is_shown_on_homepage" @checked($category->is_shown_on_homepage ?? false)>
                                <label for="is_shown_on_homepage"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Show on homepage</span>
                            </div>
                            @error('is_shown_on_homepage')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('category')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($category)
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
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
        });
    </script>
@endpush
