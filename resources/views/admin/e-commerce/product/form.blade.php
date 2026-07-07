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
    $existingSpecs = isset($product) ? $product->specs : [];
    $existingGallery = isset($product) ? $product->images->where('section', 'gallery')->values() : collect();
    $existingLifestyle = isset($product) ? $product->images->where('section', 'lifestyle')->values() : collect();
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
                    Everything on the product page — gallery, lifestyle banner, specs, colors and video — is controlled from here
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

        <form class="space-y-4"
            action="{{ isset($product) ? routeHelper('product/' . $product->id) : routeHelper('product') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @isset($product)
                <input type="hidden" value="{{ $product->id }}" id="id">
                @method('PUT')
            @endisset
            <input type="hidden" value="{{ $type ?? 'normal' }}" name="ptypen">
            <input type="hidden" name="shipping_charge" value="1">

            {{-- 1. Basic Information --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Basic Information</h3>
                        <p class="text-xs text-slate-500">Name, code, category and visibility</p>
                    </div>
                </div>
                <div class="space-y-4 p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="title" class="{{ $label }}">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" placeholder="Write product title"
                                class="{{ $control }} @error('title') border-danger @else border-slate-300 @enderror"
                                value="{{ old('title', $product->title ?? '') }}">
                            @error('title')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="sku" class="{{ $label }}">Product Code (SKU)</label>
                            <input type="text" name="sku" id="sku" placeholder="Product Code/SKU"
                                class="{{ $control }} @error('sku') border-danger @else border-slate-300 @enderror"
                                value="{{ old('sku', $product->sku ?? '') }}">
                            @error('sku')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="category" class="{{ $label }}">Category <span class="text-danger">*</span></label>
                            <select name="categories[]" id="category" multiple
                                class="{{ $control }} @error('categories') border-danger @else border-slate-300 @enderror">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ isset($product) && $product->categories->contains('id', $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="sub_category" class="{{ $label }}">Sub Category</label>
                            <select name="sub_categories[]" id="sub_category" multiple
                                class="{{ $control }} border-slate-300">
                                @if (isset($product))
                                    @foreach ($product->sub_categories as $sub)
                                        <option value="{{ $sub->id }}" selected>{{ $sub->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 pt-1">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="status" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30"
                                {{ old('status', $product->status ?? true) ? 'checked' : '' }}>
                            Active (visible in the store)
                        </label>
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="is_shown_on_homepage" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30"
                                {{ old('is_shown_on_homepage', $product->is_shown_on_homepage ?? false) ? 'checked' : '' }}>
                            Show on homepage
                        </label>
                    </div>
                </div>
            </div>

            {{-- 2. Pricing & Stock --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-coins"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Pricing &amp; Stock</h3>
                        <p class="text-xs text-slate-500">Selling price, discount and available quantity</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-4">
                    <div>
                        <label for="regular_price" class="{{ $label }}">Regular Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="regular_price" id="regular_price" placeholder="0.00"
                            class="{{ $control }} @error('regular_price') border-danger @else border-slate-300 @enderror"
                            value="{{ old('regular_price', $product->regular_price ?? '') }}">
                        @error('regular_price')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="dis_type" class="{{ $label }}">Discount Type</label>
                        <select name="dis_type" id="dis_type" class="{{ $control }} border-slate-300">
                            <option value="">No Discount</option>
                            <option value="1" {{ old('dis_type', $product->dis_type ?? '') == 1 ? 'selected' : '' }}>Flat Amount</option>
                            <option value="2" {{ old('dis_type', $product->dis_type ?? '') == 2 ? 'selected' : '' }}>Percentage (%)</option>
                        </select>
                    </div>
                    <div>
                        <label for="discount_price" class="{{ $label }}">Discount Value</label>
                        <input type="number" step="0.01" name="discount_price" id="discount_price" placeholder="0.00"
                            class="{{ $control }} @error('discount_price') border-danger @else border-slate-300 @enderror"
                            value="{{ old('discount_price') }}">
                        <p class="mt-1 text-xs text-slate-400">Leave empty for no discount</p>
                    </div>
                    <div>
                        <label for="quantity" class="{{ $label }}">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" placeholder="0"
                            class="{{ $control }} @error('quantity') border-danger @else border-slate-300 @enderror"
                            value="{{ old('quantity', $product->quantity ?? '') }}">
                        @error('quantity')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 3. Content --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-align-left"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Content</h3>
                        <p class="text-xs text-slate-500">Shown inside the product page accordion</p>
                    </div>
                </div>
                <div class="space-y-4 p-5">
                    <div>
                        <label for="short_description" class="{{ $label }}">The Design Story</label>
                        <textarea name="short_description" id="short_description" rows="3"
                            placeholder="The story shown first on the product page"
                            class="{{ $control }} border-slate-300 summernote">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="full_description" class="{{ $label }}">Technical Details</label>
                        <textarea name="full_description" id="full_description" rows="5"
                            placeholder="Bulb, voltage, frame, cord — the technical accordion content"
                            class="{{ $control }} border-slate-300 summernote">{{ old('full_description', $product->full_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="shipping_concierge" class="{{ $label }}">Shipping &amp; Concierge</label>
                        <textarea name="shipping_concierge" id="shipping_concierge" rows="4"
                            placeholder="Delivery, packaging and concierge details — leave blank to use the global default"
                            class="{{ $control }} border-slate-300 summernote">{{ old('shipping_concierge', $product->shipping_concierge ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="warranty_returns" class="{{ $label }}">Warranty &amp; Returns</label>
                        <textarea name="warranty_returns" id="warranty_returns" rows="4"
                            placeholder="Warranty terms and return policy — leave blank to use the global default"
                            class="{{ $control }} border-slate-300 summernote">{{ old('warranty_returns', $product->warranty_returns ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 4. Specifications --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-list-ul"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Specifications</h3>
                            <p class="text-xs text-slate-500">Label/value pairs shown in the specs grid (e.g. Light Source → ST64 Vintage LED)</p>
                        </div>
                    </div>
                    <button type="button" id="addSpecRow"
                        class="inline-flex h-9 items-center gap-2 rounded-lg border border-primary/40 bg-primary/5 px-3 text-sm font-medium text-primary transition-colors hover:bg-primary/10">
                        <i class="fas fa-plus text-xs"></i> Add Row
                    </button>
                </div>
                <div class="space-y-3 p-5" id="specRows">
                    @forelse ($existingSpecs as $spec)
                        <div class="spec-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto]">
                            <input type="text" name="spec_labels[]" placeholder="Label (e.g. Dimensions)"
                                class="{{ $control }} border-slate-300" value="{{ $spec['label'] }}">
                            <input type="text" name="spec_values[]" placeholder="Value (e.g. 7.9 x 10.4 Inches)"
                                class="{{ $control }} border-slate-300" value="{{ $spec['value'] }}">
                            <button type="button"
                                class="remove-spec-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">
                                Remove
                            </button>
                        </div>
                    @empty
                        <div class="spec-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto]">
                            <input type="text" name="spec_labels[]" placeholder="Label (e.g. Light Source)"
                                class="{{ $control }} border-slate-300">
                            <input type="text" name="spec_values[]" placeholder="Value (e.g. ST64 Vintage LED)"
                                class="{{ $control }} border-slate-300">
                            <button type="button"
                                class="remove-spec-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">
                                Remove
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 5. Colors --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-palette"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Colors</h3>
                        <p class="text-xs text-slate-500">
                            Shown as the color/essence swatches on the product page — glow uses each color's hex code.
                            Missing a color (e.g. Warm Glow)? Create it first in
                            <a href="{{ routeHelper('color') }}" target="_blank" class="font-medium text-primary hover:underline">Colors</a>
                            with its name and hex code, then it appears in this list.
                        </p>
                    </div>
                </div>
                <div class="space-y-4 p-5">
                    <div>
                        <label for="select_color" class="{{ $label }}">Add a Color</label>
                        <select id="select_color" class="{{ $control }} border-slate-300">
                            <option value="">Select color to add</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}" data-name="{{ $color->name }}" data-code="{{ $color->code ?? '' }}">
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2" id="colorRows">
                        @isset($colors_product)
                            @foreach ($colors_product as $cp)
                                <div class="color-row grid grid-cols-1 items-center gap-3 md:grid-cols-[auto_1fr_1fr_1fr_auto]">
                                    <span class="inline-block h-8 w-8 rounded-full border border-slate-200"
                                        style="background: {{ $cp->code ?? '#ddd' }}"></span>
                                    <input type="hidden" name="colors[]" value="{{ $cp->color_id ?? $cp->id }}">
                                    <input type="text" readonly value="{{ $cp->name }}" class="{{ $control }} border-slate-300 bg-slate-50">
                                    <input type="number" step="0.01" name="color_prices[]" placeholder="Extra price"
                                        class="{{ $control }} border-slate-300" value="{{ $cp->price ?? 0 }}">
                                    <input type="number" name="color_quantits[]" placeholder="Stock for this color"
                                        class="{{ $control }} border-slate-300" value="{{ $cp->qnty ?? 0 }}">
                                    <button type="button"
                                        class="remove-color-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>

            {{-- 6. Media --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-photo-video"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Media</h3>
                        <p class="text-xs text-slate-500">All images look best in 3:4 portrait — 1200×1600px recommended</p>
                    </div>
                </div>
                <div class="space-y-6 p-5">
                    {{-- Main image --}}
                    <div>
                        <label class="{{ $label }}">Main Image @empty($product)<span class="text-danger">*</span>@endempty</label>
                        <p class="mb-2 text-xs text-slate-400">The big featured photo. 3:4 portrait, 1200×1600px recommended.</p>
                        <input type="file" name="image" class="dropify" accept="image/*"
                            @isset($product) data-default-file="{{ asset('uploads/product/' . $product->image) }}" @endisset>
                        @error('image')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gallery images --}}
                    <div class="border-t border-slate-100 pt-5">
                        <label class="{{ $label }}">Gallery Images (thumbnails)</label>
                        <p class="mb-2 text-xs text-slate-400">Shown as the thumbnail strip under the main photo. Select multiple at once.</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="{{ $control }} border-slate-300">
                        @error('images.*')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                        @if ($existingGallery->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-3">
                                @foreach ($existingGallery as $img)
                                    <div class="relative">
                                        <img src="{{ asset('uploads/product/' . $img->name) }}" alt=""
                                            class="h-24 w-[72px] rounded-lg border border-slate-200 object-cover">
                                        <a href="{{ routeHelper('idelte/' . $img->id) }}"
                                            onclick="return confirm('Delete this gallery image?')"
                                            class="absolute -right-2 -top-2 grid h-6 w-6 place-items-center rounded-full bg-danger text-xs text-white shadow">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Lifestyle images --}}
                    <div class="border-t border-slate-100 pt-5">
                        <div class="mb-2 flex items-center justify-between">
                            <div>
                                <label class="{{ $label }}">Lifestyle Images ("Styled By Light" carousel)</label>
                                <p class="text-xs text-slate-400">Each tile gets a small Tag (e.g. Evening Glow) and a Caption (e.g. The Living Room Edit).</p>
                            </div>
                            <button type="button" id="addLifestyleRow"
                                class="inline-flex h-9 items-center gap-2 rounded-lg border border-primary/40 bg-primary/5 px-3 text-sm font-medium text-primary transition-colors hover:bg-primary/10">
                                <i class="fas fa-plus text-xs"></i> Add Image
                            </button>
                        </div>
                        <div class="space-y-3" id="lifestyleRows"></div>
                        @if ($existingLifestyle->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                @foreach ($existingLifestyle as $img)
                                    <div class="grid grid-cols-1 items-center gap-3 md:grid-cols-[auto_1fr_1fr_auto]">
                                        <img src="{{ asset('uploads/product/' . $img->name) }}" alt=""
                                            class="h-24 w-[72px] rounded-lg border border-slate-200 object-cover">
                                        <input type="text" name="existing_lifestyle[{{ $img->id }}][tag]" placeholder="Tag (e.g. Evening Glow)"
                                            class="{{ $control }} border-slate-300" value="{{ $img->tag }}">
                                        <input type="text" name="existing_lifestyle[{{ $img->id }}][caption]" placeholder="Caption (e.g. The Living Room Edit)"
                                            class="{{ $control }} border-slate-300" value="{{ $img->caption }}">
                                        <a href="{{ routeHelper('idelte/' . $img->id) }}"
                                            onclick="return confirm('Delete this lifestyle image?')"
                                            class="inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">
                                            Delete
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Video --}}
                    <div class="border-t border-slate-100 pt-5">
                        <label class="{{ $label }}">Product Video</label>
                        <p class="mb-2 text-xs text-slate-400">First tile of the carousel — plays automatically (muted), full playback with sound on click. MP4, 3:4 portrait. Any size works; smaller files simply load faster for visitors.</p>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <input type="file" name="video" accept="video/*" class="{{ $control }} border-slate-300">
                                @error('video')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                                @if (isset($product) && $product->video)
                                    <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                                        <input type="checkbox" name="remove_video" value="1"
                                            class="h-4 w-4 rounded border-slate-300 text-danger focus:ring-danger/30">
                                        Remove current video ({{ $product->video }})
                                    </label>
                                @endif
                            </div>
                            <div>
                                <input type="file" name="video_thumb" accept="image/*" class="{{ $control }} border-slate-300">
                                <p class="mt-1 text-xs text-slate-400">Poster image shown before the video plays (3:4, matches the tiles)</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="yvideo" class="{{ $label }}">YouTube URL (optional)</label>
                            <input type="text" name="yvideo" id="yvideo" placeholder="https://www.youtube.com/watch?v=..."
                                class="{{ $control }} border-slate-300" value="{{ old('yvideo', $product->yvideo ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-8 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90 hover:shadow">
                    <i class="fas fa-save text-xs"></i>
                    @isset($product) Update Product @else Save Product @endisset
                </button>
            </div>
        </form>
    </section>
@endsection

@push('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWd1V2WnKi0boCFxwEzOEWIXidAhbCKTgd1UfBAyIvIUAc1Q71vmwFTUAJ86UgeYvvsq+i3eMmw=="
        crossorigin="anonymous"></script>
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            // ── Specifications repeater ──────────────────────────────────
            // Repeater/row handlers are registered FIRST and enhancements
            // (dropify/select2/summernote) afterwards, each isolated — a
            // broken or blocked plugin must never kill the form's buttons.
            $('#addSpecRow').on('click', function() {
                $('#specRows').append(`
                    <div class="spec-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto]">
                        <input type="text" name="spec_labels[]" placeholder="Label" class="{{ $control }} border-slate-300">
                        <input type="text" name="spec_values[]" placeholder="Value" class="{{ $control }} border-slate-300">
                        <button type="button" class="remove-spec-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                    </div>`);
            });
            $(document).on('click', '.remove-spec-row', function() {
                $(this).closest('.spec-row').remove();
            });

            // ── Color rows ───────────────────────────────────────────────
            $('#select_color').on('change', function() {
                const opt = this.selectedOptions[0];
                if (!opt || !opt.value) return;
                if ($('#colorRows input[name="colors[]"][value="' + opt.value + '"]').length) {
                    this.value = '';
                    return;
                }
                $('#colorRows').append(`
                    <div class="color-row grid grid-cols-1 items-center gap-3 md:grid-cols-[auto_1fr_1fr_1fr_auto]">
                        <span class="inline-block h-8 w-8 rounded-full border border-slate-200" style="background: ${opt.dataset.code || '#ddd'}"></span>
                        <input type="hidden" name="colors[]" value="${opt.value}">
                        <input type="text" readonly value="${opt.dataset.name}" class="{{ $control }} border-slate-300 bg-slate-50">
                        <input type="number" step="0.01" name="color_prices[]" placeholder="Extra price" value="0" class="{{ $control }} border-slate-300">
                        <input type="number" name="color_quantits[]" placeholder="Stock for this color" value="0" class="{{ $control }} border-slate-300">
                        <button type="button" class="remove-color-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                    </div>`);
                this.value = '';
            });
            $(document).on('click', '.remove-color-row', function() {
                $(this).closest('.color-row').remove();
            });

            // ── Lifestyle image repeater ─────────────────────────────────
            $('#addLifestyleRow').on('click', function() {
                $('#lifestyleRows').append(`
                    <div class="lifestyle-row grid grid-cols-1 items-center gap-3 md:grid-cols-[1fr_1fr_1fr_auto]">
                        <input type="file" name="lifestyle_images[]" accept="image/*" class="{{ $control }} border-slate-300">
                        <input type="text" name="lifestyle_tags[]" placeholder="Tag (e.g. Evening Glow)" class="{{ $control }} border-slate-300">
                        <input type="text" name="lifestyle_captions[]" placeholder="Caption (e.g. The Living Room Edit)" class="{{ $control }} border-slate-300">
                        <button type="button" class="remove-lifestyle-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                    </div>`);
            });
            $(document).on('click', '.remove-lifestyle-row', function() {
                $(this).closest('.lifestyle-row').remove();
            });

            // ── Sub-categories load on category change ──────────────────
            $(document).on('change', '#category', function() {
                const values = Array.from(document.getElementById('category').selectedOptions).map(o => o.value);
                $.ajax({
                    type: 'POST',
                    url: '/admin/get/sub-categories',
                    data: { ids: values, _token: $('meta[name="csrf-token"]').attr('content') },
                    dataType: 'JSON',
                    success: function(response) {
                        let data = '';
                        $.each(response, function(key, val) {
                            data += '<option value="' + val.id + '">' + val.name + '</option>';
                        });
                        $('#sub_category').html(data);
                        try { $('#sub_category').select2(); } catch (e) { console.warn('select2 unavailable', e); }
                    }
                });
            });

            // ── UI enhancements — each isolated ──────────────────────────
            try { $('.dropify').dropify(); } catch (e) { console.warn('dropify unavailable', e); }
            try { $('#category, #sub_category').select2(); } catch (e) { console.warn('select2 unavailable', e); }
            try {
                $('.summernote').summernote({
                    height: 160,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['codeview']],
                    ],
                });
            } catch (e) { console.warn('summernote unavailable', e); }
        });
    </script>
@endpush
