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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .note-editor {
            box-shadow: none !important;
        }
    </style>
@endpush

@section('content')
    {{-- Content Header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($product)
                    Edit Product
                @else
                    Add Product
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($product)
                        Edit Product
                    @else
                        Add Product
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        @if ($errors->any())
            {!! implode('', $errors->all('<div class="mb-2 rounded-md bg-red-50 border border-red-300 px-4 py-2 text-sm text-red-700">:message</div>')) !!}
        @endif
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h3 class="font-medium text-slate-900">
                        @isset($product)
                            Edit Product
                        @else
                            Add New Product
                        @endisset
                    </h3>
                    <div class="flex gap-2">
                        @isset($product)
                            <x-ui.button :href="routeHelper('product/' . $product->id)" variant="info">
                                <i class="fas fa-eye"></i>
                                Show
                            </x-ui.button>
                        @endisset
                        <x-ui.button :href="routeHelper('product')" variant="danger">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            Back to List
                        </x-ui.button>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap">
                <form class="w-full lg:w-2/3"
                    action="{{ isset($product) ? routeHelper('product/' . $product->id) : routeHelper('product') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($product)
                        <input type="hidden" value="{{ $product->id }}" id="id">
                        @method('PUT')
                    @endisset
                    <input type="hidden" value="{{ $type ?? '' }}" name="ptypen">

                    <div class="p-4">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Product name <span class="text-danger">(*)</span>:</label>
                            <input type="text" name="title" id="title" placeholder="Write product title"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('title') border-danger @else border-slate-300 @enderror"
                                value="{{ $product->title ?? old('title') }}">
                            @error('title')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="sku" class="block text-sm font-medium text-slate-700 mb-1">Product Code (SKU):</label>
                            <input type="text" name="sku" id="sku" placeholder="Product Code/SKU"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('sku') border-danger @else border-slate-300 @enderror"
                                value="{{ $product->sku ?? old('sku') }}">
                            @error('sku')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="short_description" class="block text-sm font-medium text-slate-700 mb-1">Short Description:</label>
                            <textarea name="short_description" id="short_description" rows="3" placeholder="Write product short description"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('short_description') border-danger @else border-slate-300 @enderror">{{ $product->short_description ?? old('short_description') }}</textarea>
                            @error('short_description')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="full_description" class="block text-sm font-medium text-slate-700 mb-1">Full Description:</label>
                            <textarea name="full_description" id="full_description" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ $product->full_description ?? old('full_description') }}</textarea>
                            @error('full_description')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap -mx-2">
                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="buying_price" class="block text-sm font-medium text-slate-700 mb-1">Buying Price:</label>
                                <input step="0.01" type="number" name="buying_price" id="buying_price"
                                    placeholder="Enter product buying price"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('buying_price') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->buying_price ?? old('buying_price') }}">
                                @error('buying_price')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="regular_price" class="block text-sm font-medium text-slate-700 mb-1">Regular Price <span class="text-danger">(*)</span>:</label>
                                <input step="0.01" type="number" name="regular_price" id="regular_price"
                                    placeholder="Enter product regular price"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('regular_price') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->regular_price ?? old('regular_price') }}" required>
                                @error('regular_price')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="dis_type" class="block text-sm font-medium text-slate-700 mb-1">Discount Type:</label>
                                <select name="dis_type" id="dis_type"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('dis_type') border-danger @else border-slate-300 @enderror">
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
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
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
                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="discount_price" class="block text-sm font-medium text-slate-700 mb-1">Discount:</label>
                                <input step="0.01" type="number" name="discount_price" id="discount_price"
                                    placeholder="Enter product discount price"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('discount_price') border-danger @else border-slate-300 @enderror"
                                    value="{{ $discount_price ?? old('discount_price') }}">
                                @error('discount_price')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1">Quantity <span class="text-danger">(*)</span>:</label>
                                <input type="number" name="quantity" id="quantity"
                                    placeholder="Enter product quantity"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('quantity') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->quantity ?? old('quantity') }}" required>
                                @error('quantity')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="brand" class="block text-sm font-medium text-slate-700 mb-1">Select Brand <span class="text-danger">(*)</span>:</label>
                                <select name="brand" id="brand" data-placeholder="Select Brand"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary select2 @error('brand') border-danger @else border-slate-300 @enderror">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            @isset($product) {{ $brand->id == $product->brand_id ? 'selected' : '' }} @endisset>
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Select Category <span class="text-danger">(*)</span>:</label>
                                <select name="categories[]" id="category" multiple data-placeholder="Select Category"
                                    class="category block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary select2 @error('categories') border-danger @else border-slate-300 @enderror"
                                    required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @isset($product) @foreach ($product->categories as $pro_category) {{ $category->id == $pro_category->id ? 'selected' : '' }} @endforeach @endisset>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="sub_category" class="block text-sm font-medium text-slate-700 mb-1">Select Sub Category:</label>
                                <select name="sub_categories[]" id="sub_category" data-placeholder="Select Sub Category"
                                    class="sub_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary {{ isset($product) ? 'select2' : '' }} @error('sub_categories') border-danger @enderror"
                                    {{ isset($product) ? 'multiple' : '' }}>
                                    @isset($product)
                                        @foreach ($product->sub_categories as $sub_category)
                                            <option value="{{ $sub_category->id }}" selected>{{ $sub_category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('sub_categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="mini_category" class="block text-sm font-medium text-slate-700 mb-1">Select Mini Category:</label>
                                <select name="mini_categories[]" id="mini_category"
                                    data-placeholder="Select Mini Category"
                                    class="mini_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary {{ isset($product) ? 'select2' : '' }} @error('mini_categories') border-danger @enderror"
                                    {{ isset($product) ? 'multiple' : '' }}>
                                    @isset($product)
                                        @foreach ($product->mini_categories as $mini_category)
                                            <option value="{{ $mini_category->id }}" selected>{{ $mini_category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('mini_categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="extra_category" class="block text-sm font-medium text-slate-700 mb-1">Select Extra Category:</label>
                                <select name="extra_categories[]" id="extra_category"
                                    data-placeholder="Select Extra Category"
                                    class="extra_categories block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary {{ isset($product) ? 'select2' : '' }} @error('extra_categories') border-danger @enderror"
                                    {{ isset($product) ? 'multiple' : '' }}>
                                    @isset($product)
                                        @foreach ($product->extra_categories as $extra_category)
                                            <option value="{{ $extra_category->id }}" selected>{{ $extra_category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('extra_categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="tag" class="block text-sm font-medium text-slate-700 mb-1">Select Tag:</label>
                                <select name="tags[]" id="tag" multiple data-placeholder="Select Tag"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary select2 @error('tags') border-danger @enderror">
                                    <option value="">Select Tag</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            @isset($product) @foreach ($product->tags as $pro_tag) {{ $tag->id == $pro_tag->id ? 'selected' : '' }} @endforeach @endisset>
                                            {{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                @error('tags')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <input type='hidden' name="shipping_charge" value="1">

                            <div class="mb-4 w-full px-2">
                                <div class="rounded-md bg-slate-100 p-3">
                                    <div>
                                        <div class="mb-1 border border-slate-300 rounded">
                                            <label class="block" for="color">
                                                <button class="w-full text-left px-3 py-2 font-medium" type="button"
                                                    data-toggle="collapse" data-target="#collapseExampleColor"
                                                    aria-expanded="false" aria-controls="collapseExampleColor">
                                                    Select Color:<i class="fas fa-arrow-down float-right mt-2"></i>
                                                </button>
                                            </label>
                                            <div class="collapse" id="collapseExampleColor">
                                                <div class="flex input-group px-2 pb-2">
                                                    <select id="select_color" data-placeholder="Select Color"
                                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('colors') border-danger @enderror">
                                                        <option value="">Select Color</option>
                                                        @foreach ($colors as $color)
                                                            <option style="color:white;background: {{ $color->code }}"
                                                                value="{{ $color->slug . ',' . $color->id }}">
                                                                {{ $color->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('colors')
                                                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div id="increment_color">
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
                                                                <div class="input-group-append" id="remove"
                                                                    style="cursor:context-menu">
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
                                    </div>
                                    <div id="sho_attributes" class="flex flex-wrap">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 w-full px-2">
                                @isset($product)
                                    <div class="mb-2">
                                        <a target="_blank"
                                            href="{{ asset('uploads/product/video/' . $product->video) }}"
                                            class="text-primary hover:underline">Click View Video</a>
                                        <br>
                                        <a target="_blank"
                                            href="{{ asset('uploads/product/video/' . $product->video_thumb) }}"
                                            class="text-primary hover:underline">Click View Video Thumbnail</a>
                                    </div>
                                @endisset
                                <label for="video" class="block text-sm font-medium text-slate-700 mb-1">Product Video:</label>
                                <input type="file" name="video"
                                    class="block w-full text-sm text-slate-700 mb-2 @error('video') border-danger @enderror">
                                <label for="yvideo" class="block text-sm font-medium text-slate-700 mb-1">OR Youtbe Video:</label>
                                <input value="{{ $product->yvideo ?? old('yvideo') }}" type="text" name="yvideo"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-2 @error('yvideo') border-danger @enderror">
                                <label for="video_thumb" class="block text-sm font-medium text-slate-700 mb-1">Product Video Thumbnail:</label>
                                <input type="file" name="video_thumb"
                                    class="block w-full text-sm text-slate-700 @error('video_thumb') border-danger @enderror">
                                @error('video')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4 w-full md:w-1/2 px-2">
                                <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Product Thumbnail Image <span class="text-danger">(*)</span>: <a
                                        target="_blank" href="https://youtu.be/JsZc-I_Wygk" class="text-primary hover:underline">How to Optimize
                                        Image</a></label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="dropify @error('image') is-invalid @enderror"
                                    data-default-file="@isset($product) /uploads/product/{{ $product->image }}@endisset">
                            @error('image')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 w-full md:w-1/2 px-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Product Gallery Image: <a target="_blank" href="https://youtu.be/JsZc-I_Wygk" class="text-primary hover:underline">How to Optimize Image</a></label>
                            <div class="input-group" id="increment">
                                <input type="file" class="form-control" accept="image/*" id="images" name="images[]">
                                <select name="imagesc[]" id="imagesc">
                                    <option value="">Select Color</option>
                                    @foreach ($colors as $color)
                                        <option  style="color:white;background: {{ $color->code }}" value="{{ $color->slug }}" >{{ $color->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append" id="add" style="cursor:context-menu">
                                    <span class="input-group-text">Add More</span>
                                </div>
                            </div>
                            @error('images')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror

                            <style type="text/css">
                                .d {
                                    display: flex;
                                    align-items: center;
                                    padding: 10px;
                                    margin: 10px 0px;
                                    border-radius: 5px;
                                }
                            </style>

                            @isset($product)
                                @foreach ($product->images as $image)
                                    <div class="d">
                                        <img src="{{ asset('uploads/product/' . $image->name) }}" style="width: 100px;height: 70px;object-fit: cover;">
                                        <div class="flex-1 text-right">
                                            <x-ui.button :href="route('admin.idelte', $image->id)" variant="danger" size="sm">Delete</x-ui.button>
                                        </div>
                                    </div>
                                @endforeach
                            @endisset

                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 px-2 mb-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary" name="status"
                                id="status"
                                @isset($product) {{ $product->status ? 'checked' : '' }} @else checked @endisset>
                            <label class="text-sm font-medium text-slate-700" for="status">Status</label>
                            @error('status')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                        </div>
                        <div class="border-t border-slate-200 px-4 py-3">
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
                </form>
                <div class="w-full lg:w-1/3 p-4">
                    @include('components.product-sidebar')
                </div>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js">
    </script>
    <script>
        $('#ncolor').colorpicker();

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
