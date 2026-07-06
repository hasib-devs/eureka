@extends('layouts.admin.app')

@section('title')
    @isset($product)
        Edit Classified Product
    @else
        Add Classified Product
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        /* Third-party plugin overrides — cannot be expressed as Tailwind utilities */
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .note-editor.note-frame {
            border: 1px solid rgba(0, 0, 0, .2) !important;
        }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($product)
                        Edit Classified Product
                    @else
                        Add Classified Product
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($product)
                        Update the details of this classified ad
                    @else
                        Post a new classified ad product
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.classic.index')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.classic.index') }}" class="transition-colors hover:text-primary">Classified Products</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($product)
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
        <div class="mx-auto w-full max-w-4xl">
            <form
                action="{{ isset($product) ? route('admin.product.clasified.update') : route('admin.product.clasified.create') }}"
                method="POST" enctype="multipart/form-data" class="space-y-4">

                @csrf
                @if (isset($product))
                    <input type="hidden" value="{{ $product->id }}" name="power" id="id">
                @endif

                {{-- Product details --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Product details</h3>
                            <p class="text-xs text-slate-500">Title, location, contact and price</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <x-ui.input
                                name="title"
                                label="Title"
                                type="text"
                                :value="$product->title ?? old('title')"
                                placeholder="Write blog Title"
                                required
                                autocomplete="off"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <x-ui.input
                                name="location"
                                label="Location"
                                type="text"
                                :value="$product->location ?? old('location')"
                                placeholder="Write location"
                                required
                                autocomplete="off"
                            />
                        </div>

                        <x-ui.input
                            name="contact"
                            label="Contact Number"
                            type="tel"
                            :value="$product->contact ?? old('contact')"
                            placeholder="Write contact Number"
                            required
                            autocomplete="off"
                        />

                        <x-ui.input
                            name="price"
                            label="Price"
                            type="number"
                            :value="$product->price ?? old('price')"
                            placeholder="Write price"
                            required
                            autocomplete="off"
                        />

                        {{-- Description (summernote hooks #description) --}}
                        <div class="md:col-span-2">
                            <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                            <textarea name="description" id="description" rows="5"
                                placeholder="Write product short description"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('description') border-danger @enderror"
                                required>{{ $product->description ?? old('description') }}</textarea>
                            @error('description')
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
                            <p class="text-xs text-slate-500">Thumbnail image and publish state</p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        {{-- Thumbnail (Dropify hooks #image — keep raw input) --}}
                        <div>
                            <label for="image" class="mb-1 block text-sm font-medium text-slate-700">Thumbnail</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="block w-full text-sm text-slate-700 @error('image') border border-danger rounded-lg @enderror"
                                data-default-file="@isset($product){{ asset('/') }}/uploads/product/{{ $product->thumbnail }}@endisset">
                            @error('image')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="status" id="status" class="peer sr-only"
                                    @checked($product->status ?? false)>
                                <label for="status"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Published</span>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="route('admin.classic.index')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($product)
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
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            $('#image').dropify();
            $('#description').summernote();
        });
    </script>
@endpush
