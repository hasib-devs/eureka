@extends('layouts.admin.app')

@section('title')
    @isset($product)
        Edit Product
    @else
        Add Product
    @endisset
@endsection

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .note-editor {
            box-shadow: none !important;
        }
    </style>
@endpush

@php
    $control = 'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    $label = 'mb-1 block text-sm font-medium text-slate-700';
@endphp

@section('content')
    {{-- Page header --}}
    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($product)
                        Edit Product
                    @else
                        Add Product
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($product)
                        Update details, pricing, media and variations for this product
                    @else
                        Create a new product with pricing, media and variations
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($product)
                    <a href="{{ routeHelper('product/' . $product->id) }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-primary hover:text-primary hover:shadow">
                        <i class="fas fa-eye text-xs"></i>
                        Show
                    </a>
                @endisset
                <a href="{{ routeHelper('product') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-primary hover:text-primary hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i>
                    Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($product)
                            Edit Product
                        @else
                            Add Product
                        @endisset
                    </li>
                </ol>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">
        @if ($errors->any())
            <div class="mb-4">
                {!! implode('', $errors->all('<div class="mb-2 rounded-lg border border-danger/30 bg-danger/5 px-4 py-2 text-sm text-danger">:message</div>')) !!}
            </div>
        @endif

        <div>
            <form class="space-y-4"
                action="{{ isset($product) ? routeHelper('product/' . $product->id) : routeHelper('product') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @isset($product)
                    <input type="hidden" value="{{ $product->id }}" id="id">
                    @method('PUT')
                @endisset
                <input type="hidden" value="{{ $type ?? '' }}" name="ptypen">
                <input type='hidden' name="shipping_charge" value="1">

                {{-- Basic information --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Basic Information</h3>
                            <p class="text-xs text-slate-500">Name, code and descriptions shown to customers</p>
                        </div>
                    </div>
                    <div class="space-y-4 p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="title" class="{{ $label }}">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" placeholder="Write product title"
                                    class="{{ $control }} @error('title') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->title ?? old('title') }}">
                                @error('title')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="sku" class="{{ $label }}">Product Code (SKU)</label>
                                <input type="text" name="sku" id="sku" placeholder="Product Code/SKU"
                                    class="{{ $control }} @error('sku') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->sku ?? old('sku') }}">
                                @error('sku')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="short_description" class="{{ $label }}">Short Description</label>
                            <textarea name="short_description" id="short_description" rows="3" placeholder="Write product short description"
                                class="{{ $control }} @error('short_description') border-danger @else border-slate-300 @enderror">{{ $product->short_description ?? old('short_description') }}</textarea>
                            @error('short_description')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="full_description" class="{{ $label }}">Full Description</label>
                            <textarea name="full_description" id="full_description"
                                class="{{ $control }} border-slate-300">{{ $product->full_description ?? old('full_description') }}</textarea>
                            @error('full_description')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Pricing & inventory --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-coins"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Pricing &amp; Inventory</h3>
                            <p class="text-xs text-slate-500">Prices, discount and available stock</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="buying_price" class="{{ $label }}">Buying Price</label>
                            <input step="0.01" type="number" name="buying_price" id="buying_price"
                                placeholder="Enter product buying price"
                                class="{{ $control }} @error('buying_price') border-danger @else border-slate-300 @enderror"
                                value="{{ $product->buying_price ?? old('buying_price') }}">
                            @error('buying_price')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="regular_price" class="{{ $label }}">Regular Price <span class="text-danger">*</span></label>
                            <input step="0.01" type="number" name="regular_price" id="regular_price"
                                placeholder="Enter product regular price"
                                class="{{ $control }} @error('regular_price') border-danger @else border-slate-300 @enderror"
                                value="{{ $product->regular_price ?? old('regular_price') }}" required>
                            @error('regular_price')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dis_type" class="{{ $label }}">Discount Type</label>
                            <select name="dis_type" id="dis_type"
                                class="{{ $control }} @error('dis_type') border-danger @else border-slate-300 @enderror">
                                <option value="0"
                                    @isset($product) {{ $product->dis_type == '0' ? 'selected' : '' }} @endisset>
                                    None</option>
                                <option value="1"
                                    @isset($product) {{ $product->dis_type == '1' ? 'selected' : '' }} @endisset>
                                    Flat</option>
                                <option value="2"
                                    @isset($product) {{ $product->dis_type == '2' ? 'selected' : '' }} @endisset>
                                    Parcent %</option>
                            </select>
                            @error('dis_type')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @isset($product)
                            @if ($product->dis_type == '2')
                                @php($discount_price = (($product->regular_price - $product->discount_price) / $product->regular_price) * 100)
                            @else
                                @if ($product->discount_price < 1)
                                    @php($discount_price = '')
                                @else
                                    @php($discount_price = $product->regular_price - $product->discount_price)
                                @endif
                            @endif
                        @endisset
                        <div>
                            <label for="discount_price" class="{{ $label }}">Discount</label>
                            <input step="0.01" type="number" name="discount_price" id="discount_price"
                                placeholder="Enter product discount price"
                                class="{{ $control }} @error('discount_price') border-danger @else border-slate-300 @enderror"
                                value="{{ $discount_price ?? old('discount_price') }}">
                            @error('discount_price')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quantity" class="{{ $label }}">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity"
                                placeholder="Enter product quantity"
                                class="{{ $control }} @error('quantity') border-danger @else border-slate-300 @enderror"
                                value="{{ $product->quantity ?? old('quantity') }}" required>
                            @error('quantity')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Organization --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-sitemap"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Organization</h3>
                            <p class="text-xs text-slate-500">Brand, categories and tags used for browsing and search</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="brand" class="{{ $label }}">Select Brand <span class="text-danger">*</span></label>
                            <select name="brand" id="brand" data-placeholder="Select Brand"
                                class="{{ $control }} select2 @error('brand') border-danger @else border-slate-300 @enderror">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        @isset($product) {{ $brand->id == $product->brand_id ? 'selected' : '' }} @endisset>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="{{ $label }}">Select Category <span class="text-danger">*</span></label>
                            <select name="categories[]" id="category" multiple data-placeholder="Select Category"
                                class="category {{ $control }} select2 @error('categories') border-danger @else border-slate-300 @enderror"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @isset($product) @foreach ($product->categories as $pro_category) {{ $category->id == $pro_category->id ? 'selected' : '' }} @endforeach @endisset>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('categories')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sub_category" class="{{ $label }}">Select Sub Category</label>
                            <select name="sub_categories[]" id="sub_category" data-placeholder="Select Sub Category"
                                class="sub_category {{ $control }} border-slate-300 {{ isset($product) ? 'select2' : '' }} @error('sub_categories') border-danger @enderror"
                                {{ isset($product) ? 'multiple' : '' }}>
                                @isset($product)
                                    @foreach ($product->sub_categories as $sub_category)
                                        <option value="{{ $sub_category->id }}" selected>{{ $sub_category->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('sub_categories')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mini_category" class="{{ $label }}">Select Mini Category</label>
                            <select name="mini_categories[]" id="mini_category"
                                data-placeholder="Select Mini Category"
                                class="mini_category {{ $control }} border-slate-300 {{ isset($product) ? 'select2' : '' }} @error('mini_categories') border-danger @enderror"
                                {{ isset($product) ? 'multiple' : '' }}>
                                @isset($product)
                                    @foreach ($product->mini_categories as $mini_category)
                                        <option value="{{ $mini_category->id }}" selected>{{ $mini_category->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('mini_categories')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="extra_category" class="{{ $label }}">Select Extra Category</label>
                            <select name="extra_categories[]" id="extra_category"
                                data-placeholder="Select Extra Category"
                                class="extra_categories {{ $control }} border-slate-300 {{ isset($product) ? 'select2' : '' }} @error('extra_categories') border-danger @enderror"
                                {{ isset($product) ? 'multiple' : '' }}>
                                @isset($product)
                                    @foreach ($product->extra_categories as $extra_category)
                                        <option value="{{ $extra_category->id }}" selected>{{ $extra_category->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('extra_categories')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tag" class="{{ $label }}">Select Tag</label>
                            <select name="tags[]" id="tag" multiple data-placeholder="Select Tag"
                                class="{{ $control }} border-slate-300 select2 @error('tags') border-danger @enderror">
                                <option value="">Select Tag</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                        @isset($product) @foreach ($product->tags as $pro_tag) {{ $tag->id == $pro_tag->id ? 'selected' : '' }} @endforeach @endisset>
                                        {{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Variations --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-swatchbook"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Variations</h3>
                            <p class="text-xs text-slate-500">Colors and attribute-based options with extra price and stock</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="rounded-lg border border-slate-200">
                            <label class="block" for="color">
                                <button class="flex w-full items-center justify-between px-4 py-3 text-left text-sm font-medium text-slate-700" type="button"
                                    data-toggle="collapse" data-target="#collapseExampleColor"
                                    aria-expanded="false" aria-controls="collapseExampleColor">
                                    Select Color
                                    <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                                </button>
                            </label>
                            <div class="collapse" id="collapseExampleColor">
                                <div class="input-group flex px-3 pb-3">
                                    <select id="select_color" data-placeholder="Select Color"
                                        class="{{ $control }} @error('colors') border-danger @else border-slate-300 @enderror">
                                        <option value="">Select Color</option>
                                        @foreach ($colors as $color)
                                            <option style="color:white;background: {{ $color->code }}"
                                                value="{{ $color->slug . ',' . $color->id }}">
                                                {{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('colors')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="increment_color" class="px-3 pb-3">
                                    @isset($product)
                                        @foreach ($colors_product as $pro_color)
                                            <div class="input-group mt-2">
                                                <input class="form-control" type="hidden" readonly=""
                                                    name="colors[]" value="{{ $pro_color->id }}">
                                                <input class="form-control" type="text" readonly=""
                                                    value="{{ $pro_color->name }}">
                                                <input class="form-control" type="number"
                                                    placeholder="extra price" name="color_prices[]"
                                                    value="{{ $pro_color->price }}">
                                                <input class="form-control" type="number"
                                                    placeholder="extra quantity" name="color_quantits[]"
                                                    value="{{ $pro_color->qnty }}">
                                                <div class="input-group-append cursor-pointer" id="remove">
                                                    <a
                                                        href="{{ route('admin.color.delete.n2', ['cc' => $pro_color->id, 'pp' => $product->id]) }}">
                                                        <span class="input-group-text">Remove</span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endisset
                                </div>
                            </div>
                        </div>
                        <div id="sho_attributes" class="mt-3 flex flex-wrap">
                        </div>
                    </div>
                </div>

                {{-- Media --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-photo-video"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Media</h3>
                            <p class="text-xs text-slate-500">Thumbnail, gallery images and product video</p>
                        </div>
                    </div>
                    <div class="space-y-5 p-5">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="image" class="{{ $label }}">Product Thumbnail Image <span class="text-danger">*</span>
                                    <a target="_blank" href="https://youtu.be/JsZc-I_Wygk" class="font-normal text-primary hover:underline">How to Optimize Image</a>
                                </label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="dropify @error('image') is-invalid @enderror"
                                    data-default-file="@isset($product) /uploads/product/{{ $product->image }}@endisset">
                                @error('image')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="{{ $label }}">Product Gallery Image
                                    <a target="_blank" href="https://youtu.be/JsZc-I_Wygk" class="font-normal text-primary hover:underline">How to Optimize Image</a>
                                </label>
                                <div class="input-group" id="increment">
                                    <input type="file" class="form-control" accept="image/*" id="images" name="images[]">
                                    <select name="imagesc[]" id="imagesc">
                                        <option value="">Select Color</option>
                                        @foreach ($colors as $color)
                                            <option style="color:white;background: {{ $color->code }}" value="{{ $color->slug }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append cursor-pointer" id="add">
                                        <span class="input-group-text">Add More</span>
                                    </div>
                                </div>
                                @error('images')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror

                                @isset($product)
                                    <div class="mt-3 space-y-2">
                                        @foreach ($product->images as $image)
                                            <div class="flex items-center gap-3 rounded-lg border border-slate-200 p-2">
                                                <img src="{{ asset('uploads/product/' . $image->name) }}"
                                                    class="h-16 w-24 rounded-lg object-cover ring-1 ring-slate-200" alt="Gallery image">
                                                <div class="flex-1 text-right">
                                                    <x-ui.button :href="route('admin.idelte', $image->id)" variant="danger" size="sm">Delete</x-ui.button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endisset
                            </div>
                        </div>

                        <div class="rounded-xl border-2 border-dashed border-slate-200 px-4 py-5 transition-colors hover:border-primary/50 hover:bg-primary-50/30">
                            @isset($product)
                                <div class="mb-3 flex flex-wrap gap-4 text-sm">
                                    <a target="_blank"
                                        href="{{ asset('uploads/product/video/' . $product->video) }}"
                                        class="text-primary hover:underline"><i class="fas fa-play-circle mr-1"></i>Click View Video</a>
                                    <a target="_blank"
                                        href="{{ asset('uploads/product/video/' . $product->video_thumb) }}"
                                        class="text-primary hover:underline"><i class="fas fa-image mr-1"></i>Click View Video Thumbnail</a>
                                </div>
                            @endisset
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label for="video" class="{{ $label }}">Product Video</label>
                                    <input type="file" name="video"
                                        class="block w-full text-sm text-slate-600 @error('video') border-danger @enderror">
                                </div>
                                <div>
                                    <label for="yvideo" class="{{ $label }}">OR Youtube Video</label>
                                    <input value="{{ $product->yvideo ?? old('yvideo') }}" type="text" name="yvideo"
                                        class="{{ $control }} @error('yvideo') border-danger @else border-slate-300 @enderror">
                                </div>
                                <div>
                                    <label for="video_thumb" class="{{ $label }}">Product Video Thumbnail</label>
                                    <input type="file" name="video_thumb"
                                        class="block w-full text-sm text-slate-600 @error('video_thumb') border-danger @enderror">
                                </div>
                            </div>
                            @error('video')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Publish --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 p-5">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="status"
                                    id="status"
                                    @isset($product) {{ $product->status ? 'checked' : '' }} @else checked @endisset>
                                <label class="text-sm font-medium text-slate-700" for="status">Status</label>
                                @error('status')
                                    <p class="text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="is_shown_on_homepage"
                                    id="is_shown_on_homepage"
                                    @isset($product) {{ $product->is_shown_on_homepage ? 'checked' : '' }} @endisset>
                                <label class="text-sm font-medium text-slate-700" for="is_shown_on_homepage">Show on homepage</label>
                                @error('is_shown_on_homepage')
                                    <p class="text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ routeHelper('product') }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-slate-400 hover:text-slate-800 hover:shadow">
                                Cancel
                            </a>
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
                </div>
            </form>

        </div>
    </section>

@endsection

@push('js')
    <!-- Select2 -->
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/dist/extra.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.dropify').dropify();
            $('#full_description').summernote();
            $('#short_description').summernote();

            // increment gallery image row
            $(document).on('click', '#add', function(e) {
                let htmlData = '<div class="input-group mt-2">';
                htmlData +=
                    '<input type="file" class="form-control" accept="image/*" name="images[]" required>';
                htmlData += '<select name="imagesc[]">';
                htmlData += $('#imagesc').html();
                htmlData += '</select>';
                htmlData += '<div class="input-group-append" id="remove" style="cursor:context-menu">';
                htmlData += '<span class="input-group-text">Remove</span>';
                htmlData += '</div>';
                htmlData += '</div>';
                $('#increment').append(htmlData);
            });

            // increment color row
            $(document).on('change', '#select_color', function(e) {
                let colors = $(this).val();
                var color = colors.split(',');

                let htmlData = '<div class="input-group mt-2">';
                htmlData += ' <input class="form-control" type="hidden" name="colors[]"  readonly value="' +
                    color[1] + '">';
                htmlData += ' <input class="form-control" type="text"    readonly value="' + color[0] +
                    '">';
                htmlData +=
                    ' <input class="form-control" type="number" placeholder="extra price" name="color_prices[]" value="">';
                htmlData +=
                    ' <input class="form-control" type="number" placeholder="extra quantity" name="color_quantits[]" value="">';

                htmlData += '<div class="input-group-append" id="remove" style="cursor:context-menu">';
                htmlData += '<span class="input-group-text">Remove</span>';
                htmlData += '</div>';
                htmlData += '</div>';
                $('#increment_color').append(htmlData);
            });

            // remove a dynamically added row
            $(document).on('click', '#remove', function(e) {
                $(this).parent().remove();
            });

            $(document).on('change', '#category', function() {

                var options = document.getElementById('category').selectedOptions;
                var values = Array.from(options).map(({
                    value
                }) => value);

                $.ajax({
                    type: 'POST',
                    url: '/admin/get/sub-categories',
                    data: {
                        'ids': values,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: "JSON",
                    success: function(response) {

                        let data = '<option value="">Select Sub Category</option>';
                        $.each(response, function(key, val) {
                            data += '<option value="' + val.id + '">' + val.name +
                                '</option>';

                        });
                        $('#sub_category').html(data).attr('multiple', true).select2();
                    }
                });
            });

            $(document).on('change', '#sub_category', function() {

                var options = document.getElementById('sub_category').selectedOptions;
                var values = Array.from(options).map(({
                    value
                }) => value);

                $.ajax({
                    type: 'POST',
                    url: '/admin/get/mini-categories',
                    data: {
                        'ids': values,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: "JSON",
                    success: function(response) {

                        let data = '<option value="">Select Mini Category</option>';
                        $.each(response, function(key, val) {
                            data += '<option value="' + val.id + '">' + val.name +
                                '</option>';

                        });
                        $('#mini_category').html(data).attr('multiple', true).select2();
                    }
                });
            });
            $(document).on('change', '#mini_category', function() {

                var options = document.getElementById('mini_category').selectedOptions;
                var values = Array.from(options).map(({
                    value
                }) => value);

                $.ajax({
                    type: 'POST',
                    url: '/admin/get/extra-categories',
                    data: {
                        'ids': values,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: "JSON",
                    success: function(response) {

                        let data = '<option value="">Select Extra Category</option>';
                        $.each(response, function(key, val) {
                            data += '<option value="' + val.id + '">' + val.name +
                                '</option>';

                        });
                        $('#extra_category').html(data).attr('multiple', true).select2();
                    }
                });
            });
        });
    </script>
    @isset($product)
        <script>
            function attributes() {
                var options = document.getElementById('category').selectedOptions;
                var values = Array.from(options).map(({
                    value
                }) => value);
                var product_id = $('#id').val();

                $.ajax({
                    type: 'POST',
                    url: '/admin/get/attributes',
                    data: {
                        'ids': values,
                        'product_id': product_id,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: "JSON",
                    success: function(response) {
                        $('#sho_attributes').html(response);
                    }
                });
            }
            attributes();
            $(document).on('change', '#category', function() {

                var options = document.getElementById('category').selectedOptions;
                var values = Array.from(options).map(({
                    value
                }) => value);
                var product_id = $('#id').val();
                $.ajax({
                    type: 'POST',
                    url: '/admin/get/attributes',
                    data: {
                        'ids': values,
                        'product_id': product_id,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: "JSON",
                    success: function(response) {
                        $('#sho_attributes').html(response);
                    }
                });
            });
        </script>
    @else
        <script>
            $(document).on('change', '#category', function() {

                var options = document.getElementById('category').selectedOptions;
                var values = Array.from(options).map(({
                    value
                }) => value);

                $.ajax({
                    type: 'POST',
                    url: '/admin/get/attributes',
                    data: {
                        'ids': values,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: "JSON",
                    success: function(response) {
                        $('#sho_attributes').html(response);
                    }
                });
            });
        </script>
    @endisset
    <script>

        // Discount required while discount type changes
        $(document).on('change', '#dis_type', function(e) {
            if ($(this).val() != "0") {
                $('#discount_price').prop('required', true);
            } else {
                $('#discount_price').prop('required', false);
            }
        });
    </script>
@endpush
