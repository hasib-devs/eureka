@extends('layouts.admin.app')

@section('title')
    @isset($blog)
        Edit blog
    @else
        Add blog
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
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
                    @isset($blog)
                        Edit Blog
                    @else
                        Add Blog
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($blog)
                        Update the details of this blog post
                    @else
                        Write a new blog post for your store
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('blogs')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('blogs') }}" class="transition-colors hover:text-primary">Blogs</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($blog)
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
            <form action="{{ isset($blog) ? route('admin.update_exit_blog') : route('admin.create_blog') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($blog))
                    <input type="hidden" value="{{ $blog->id }}" name="power">
                @endif

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                @isset($blog)
                                    Edit Blog Details
                                @else
                                    Add New Blog
                                @endisset
                            </h3>
                            <p class="text-xs text-slate-500">Title, thumbnail, category and content</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        {{-- Title --}}
                        <x-ui.input
                            name="title"
                            label="Title"
                            type="text"
                            placeholder="Write blog Title"
                            :value="$blog->title ?? old('title')"
                            required
                            autocomplete="off"
                        />

                        {{-- Thumbnail (Dropify — keep raw input, JS hooks preserved) --}}
                        <div>
                            <label for="thumbnail" class="mb-1 block text-sm font-medium text-slate-700">Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                                class="block w-full @error('thumbnail') border border-danger rounded-lg @enderror"
                                @isset($blog) data-default-file="{{ asset('uploads/blogs/' . $blog->thumbnail) }}" @endisset>
                            @error('thumbnail')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <x-ui.select name="category" label="Category">
                            <option>Select One</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(isset($blog) && $blog->category_id == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </x-ui.select>

                        {{-- Description (Summernote — keep raw textarea, JS hooks preserved) --}}
                        <div>
                            <label for="descripiton" class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                            <textarea name="descripiton" id="descripiton" rows="5"
                                placeholder="Write blog description"
                                class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('descripiton') border-danger @else border-slate-300 @enderror"
                                required>{{ $blog->description ?? old('descripiton') }}</textarea>
                            @error('descripiton')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($blog->status ?? true)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('blogs')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($blog)
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
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            $('#thumbnail').dropify();
            $('#descripiton').summernote();
        });
    </script>
@endpush
