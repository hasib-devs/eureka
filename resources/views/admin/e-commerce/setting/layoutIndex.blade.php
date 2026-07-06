@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Layout Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Control the storefront header, home components and section visibility</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Layout</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
        $toggleSelect = 'h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    @endphp

    <section class="mb-6">
        <form id="layoutForm" action="{{ routeHelper('setting') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="9">

            <div class="mx-auto w-full max-w-4xl space-y-4">

                {{-- Global layout --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-columns"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Global Layout</h2>
                            <p class="text-xs text-slate-500">Header style, main menu and search placeholders</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="TOP_HEADER_STYLE" class="mb-1 block text-sm font-medium text-slate-700">Top Header Style</label>
                            <select class="{{ $control }} cursor-pointer" name="TOP_HEADER_STYLE" id="TOP_HEADER_STYLE">
                                <option value="{{ $TOP_HEADER_STYLE->value }}">Style {{ $TOP_HEADER_STYLE->value }}</option>
                                <option value="1">Style 1</option>
                                <option value="2">Style 2</option>
                                <option value="3">Style 3</option>
                            </select>
                        </div>

                        <div>
                            <label for="MAIN_MENU_STYLE" class="mb-1 block text-sm font-medium text-slate-700">Main Menu Style</label>
                            <select class="{{ $control }} cursor-pointer" name="MAIN_MENU_STYLE" id="MAIN_MENU_STYLE">
                                <option value="{{ $MAIN_MENU_STYLE->value }}">Style {{ $MAIN_MENU_STYLE->value }}</option>
                                <option value="1">Style 1</option>
                                <option value="2">Style 2</option>
                                <option value="3">Style 3</option>
                            </select>
                        </div>

                        {{-- Style 3 options (visible only when Top Header Style 3 is saved) --}}
                        <div class="rounded-xl border border-primary/20 bg-primary-50/40 p-4 md:col-span-2 {{ $TOP_HEADER_STYLE->value == 3 ? 'block' : 'hidden' }}">
                            <h3 class="mb-3 text-sm font-semibold text-slate-800">Style 3 Options</h3>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label for="STYLE_3_TOP_MENU" class="mb-1 block text-sm font-medium text-slate-700">Style 3 Top Menu</label>
                                    <textarea class="{{ $control }} font-mono text-xs leading-relaxed" name="STYLE_3_TOP_MENU"
                                        id="STYLE_3_TOP_MENU" cols="30" rows="3">{{ $STYLE_3_TOP_MENU->value }}</textarea>
                                    <p class="mt-1 text-xs text-slate-500">Copy the menu code below, paste it in the box above and customize it as per
                                        your requirements (multi-menu: add multiple li and a tags):</p>
                                    <script src="https://gist.github.com/finvasoft/c380eaf18b41491d650c28f152ce79a4.js"></script>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700" for="STYLE_3_TOP_MENU_BG_COLOR">Top Menu Background Color</label>
                                    <div class="flex items-center gap-2">
                                        <input class="h-10 w-14 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 shadow-sm" type="color"
                                            id="STYLE_3_TOP_MENU_BG_COLOR_CHOOSER"
                                            value="{{ $STYLE_3_TOP_MENU_BG_COLOR->value }}">
                                        <input class="{{ $control }}" type="text"
                                            id="STYLE_3_TOP_MENU_BG_COLOR" name="STYLE_3_TOP_MENU_BG_COLOR"
                                            value="{{ $STYLE_3_TOP_MENU_BG_COLOR->value }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700" for="STYLE_3_TOP_MENU_LINK_COLOR">Top Menu Link Color</label>
                                    <div class="flex items-center gap-2">
                                        <input class="h-10 w-14 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 shadow-sm" type="color"
                                            id="STYLE_3_TOP_MENU_LINK_COLOR_CHOOSER"
                                            value="{{ $STYLE_3_TOP_MENU_LINK_COLOR->value }}">
                                        <input class="{{ $control }}" type="text"
                                            id="STYLE_3_TOP_MENU_LINK_COLOR" name="STYLE_3_TOP_MENU_LINK_COLOR"
                                            value="{{ $STYLE_3_TOP_MENU_LINK_COLOR->value }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700" for="STYLE_3_TOP_MENU_LINK_HOVER_COLOR">Top Menu Link Hover Color</label>
                                    <div class="flex items-center gap-2">
                                        <input class="h-10 w-14 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 shadow-sm" type="color"
                                            id="STYLE_3_TOP_MENU_LINK_HOVER_COLOR_CHOOSER"
                                            value="{{ $STYLE_3_TOP_MENU_LINK_HOVER_COLOR->value }}">
                                        <input class="{{ $control }}" type="text"
                                            id="STYLE_3_TOP_MENU_LINK_HOVER_COLOR" name="STYLE_3_TOP_MENU_LINK_HOVER_COLOR"
                                            value="{{ $STYLE_3_TOP_MENU_LINK_HOVER_COLOR->value }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700" for="STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT">Search Input Width</label>
                                    <input class="{{ $control }}" type="text"
                                        id="STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT"
                                        name="STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT"
                                        value="{{ setting('STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT') ?? '' }}">
                                </div>
                            </div>
                            @push('js')
                                <script>
                                    $(document).ready(function() {
                                        $("#STYLE_3_TOP_MENU_BG_COLOR_CHOOSER").on("input", function() {
                                            $("#STYLE_3_TOP_MENU_BG_COLOR").val($(this).val());
                                        });
                                        $("#STYLE_3_TOP_MENU_BG_COLOR").on("keyup", function() {
                                            $("#STYLE_3_TOP_MENU_BG_COLOR_CHOOSER").val($(this).val());
                                        });
                                        $("#STYLE_3_TOP_MENU_LINK_COLOR_CHOOSER").on("input", function() {
                                            $("#STYLE_3_TOP_MENU_LINK_COLOR").val($(this).val());
                                        });
                                        $("#STYLE_3_TOP_MENU_LINK_COLOR").on("keyup", function() {
                                            $("#STYLE_3_TOP_MENU_LINK_COLOR_CHOOSER").val($(this).val());
                                        });
                                        $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR_CHOOSER").on("input", function() {
                                            $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR").val($(this).val());
                                        });
                                        $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR").on("keyup", function() {
                                            $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR_CHOOSER").val($(this).val());
                                        });
                                    });
                                </script>
                            @endpush
                        </div>

                        <div>
                            <label for="placeholder_one" class="mb-1 block text-sm font-medium text-slate-700">Placeholder One</label>
                            <input class="{{ $control }}" type="text" name="placeholder_one" id="placeholder_one"
                                value="{{ setting('placeholder_one') ?? 'Search by product name' }}">
                        </div>
                        <div>
                            <label for="placeholder_two" class="mb-1 block text-sm font-medium text-slate-700">Placeholder Two</label>
                            <input class="{{ $control }}" type="text" name="placeholder_two" id="placeholder_two"
                                value="{{ setting('placeholder_two') ?? 'Search by Vendor' }}">
                        </div>
                        <div>
                            <label for="placeholder_three" class="mb-1 block text-sm font-medium text-slate-700">Placeholder Three</label>
                            <input class="{{ $control }}" type="text" name="placeholder_three" id="placeholder_three"
                                value="{{ setting('placeholder_three') ?? 'Search by category' }}">
                        </div>
                        <div>
                            <label for="placeholder_four" class="mb-1 block text-sm font-medium text-slate-700">Placeholder Four</label>
                            <input class="{{ $control }}" type="text" name="placeholder_four" id="placeholder_four"
                                value="{{ setting('placeholder_four') ?? 'Search by product' }}">
                        </div>
                        <div>
                            <label for="FLOAT_LIVE_CHAT" class="mb-1 block text-sm font-medium text-slate-700">Float Live Chat</label>
                            <select class="{{ $control }} cursor-pointer" name="FLOAT_LIVE_CHAT" id="FLOAT_LIVE_CHAT">
                                <option value="{{ $FLOAT_LIVE_CHAT->value }}">{{ $FLOAT_LIVE_CHAT->value == 1 ? 'Live Chat' : 'WhatsApp' }}</option>
                                <option value="1">Live Chat</option>
                                <option value="0">WhatsApp</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Home components --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-images"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Home Components</h2>
                            <p class="text-xs text-slate-500">Slider and hero banner configuration</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="SLIDER_LAYOUT_STATUS" class="mb-1 block text-sm font-medium text-slate-700">Slider Status</label>
                            <select class="{{ $control }} cursor-pointer" name="SLIDER_LAYOUT_STATUS" id="SLIDER_LAYOUT_STATUS">
                                <option value="{{ $SLIDER_LAYOUT_STATUS->value }}">{{ $SLIDER_LAYOUT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                <option value="1">On</option>
                                <option value="0">Off</option>
                            </select>
                        </div>
                        <div>
                            <label for="SLIDER_LAYOUT" class="mb-1 block text-sm font-medium text-slate-700">Slider Layout</label>
                            <select class="{{ $control }} cursor-pointer" name="SLIDER_LAYOUT" id="SLIDER_LAYOUT">
                                <option value="{{ $SLIDER_LAYOUT->value }}">Style {{ $SLIDER_LAYOUT->value }}</option>
                                <option value="1">Style 1 - Container</option>
                                <option value="2">Style 2 - Full Width</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700" for="HERO_SLIDER_1">Hero Slider 1</label>
                                <select class="{{ $control }} cursor-pointer" name="HERO_SLIDER_1" id="HERO_SLIDER_1">
                                    <option value="{{ $HERO_SLIDER_1->value }}">{{ $HERO_SLIDER_1->value == 1 ? 'On' : 'Off' }}</option>
                                    <option value="1">On</option>
                                    <option value="0">Off</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700" for="HERO_SLIDER_2">Hero Slider 2</label>
                                <select class="{{ $control }} cursor-pointer" name="HERO_SLIDER_2" id="HERO_SLIDER_2">
                                    <option value="{{ $HERO_SLIDER_2->value }}">{{ $HERO_SLIDER_2->value == 1 ? 'On' : 'Off' }}</option>
                                    <option value="1">On</option>
                                    <option value="0">Off</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="HERO_SLIDER_1_TEXT" class="mb-1 block text-sm font-medium text-slate-700">Hero Slider 1 Title</label>
                            <input class="{{ $control }}" type="text" name="HERO_SLIDER_1_TEXT" id="HERO_SLIDER_1_TEXT"
                                value="{{ setting('HERO_SLIDER_1_TEXT') ?? '' }}">
                        </div>
                    </div>
                </div>

                {{-- Home sections --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Home Sections</h2>
                            <p class="text-xs text-slate-500">Show or hide each section on the homepage</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                            <div class="min-w-0 flex-1">
                                <label for="TOP_CAT_STATUS" class="block text-sm font-medium text-slate-700">Top Category Status</label>
                                <div class="mt-2">
                                    <label for="TOP_CAT" class="mb-1 block text-xs font-medium text-slate-500">Top Category Title</label>
                                    <input class="{{ $control }} max-w-sm" type="text" name="TOP_CAT" id="TOP_CAT"
                                        value="{{ setting('TOP_CAT') ?? '' }}">
                                </div>
                            </div>
                            <select class="{{ $toggleSelect }}" name="TOP_CAT_STATUS" id="TOP_CAT_STATUS">
                                <option value="{{ $TOP_CAT_STATUS->value }}">{{ $TOP_CAT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                <option value="1">On</option>
                                <option value="0">Off</option>
                            </select>
                        </div>

                        @php
                            $homeSections = [
                                ['name' => 'SELLER_STATUS', 'label' => 'Seller Status', 'hint' => 'Featured sellers strip', 'value' => $SELLER_STATUS->value],
                                ['name' => 'LATEST_PRODUCT_STATUS', 'label' => 'Latest Products Status', 'hint' => 'Newest arrivals section', 'value' => $LATEST_PRODUCT_STATUS->value],
                                ['name' => 'FEATURE_PRODUCT_STATUS', 'label' => 'Feature Products Status', 'hint' => 'Hand-picked featured products', 'value' => $FEATURE_PRODUCT_STATUS->value],
                                ['name' => 'CLASSIFIED_SELL_STATUS', 'label' => 'Classified Sell Status', 'hint' => 'Classified listings section', 'value' => $CLASSIFIED_SELL_STATUS->value],
                                ['name' => 'MEGA_CAT_PRODUCT_STATUS', 'label' => 'Mega Category Status', 'hint' => 'Products from the mega category', 'value' => $MEGA_CAT_PRODUCT_STATUS->value],
                                ['name' => 'SUB_CAT_PRODUCT_STATUS', 'label' => 'Sub Category Status', 'hint' => 'Products from the sub category', 'value' => $SUB_CAT_PRODUCT_STATUS->value],
                                ['name' => 'MINI_CAT_PRODUCT_STATUS', 'label' => 'Mini Category Status', 'hint' => 'Products from the mini category', 'value' => $MINI_CAT_PRODUCT_STATUS->value],
                                ['name' => 'EXTRA_CAT_PRODUCT_STATUS', 'label' => 'Extra Category Status', 'hint' => 'Products from the extra category', 'value' => $EXTRA_CAT_PRODUCT_STATUS->value],
                                ['name' => 'BRAND_STATUS', 'label' => 'Brand Status', 'hint' => 'Brand logos carousel', 'value' => $BRAND_STATUS->value],
                                ['name' => 'CATEGORY_SMALL_SUMMERY', 'label' => 'Category Small Summary Status', 'hint' => 'Compact category overview', 'value' => $CATEGORY_SMALL_SUMMERY->value],
                                ['name' => 'NEWS_LETTER_STATUS', 'label' => 'Newsletter Status', 'hint' => 'Newsletter subscribe box', 'value' => $NEWS_LETTER_STATUS->value],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            @foreach ($homeSections as $section)
                                <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                                    <div>
                                        <label for="{{ $section['name'] }}" class="block text-sm font-medium text-slate-700">{{ $section['label'] }}</label>
                                        <p class="mt-0.5 text-xs text-slate-500">{{ $section['hint'] }}</p>
                                    </div>
                                    <select class="{{ $toggleSelect }}" name="{{ $section['name'] }}" id="{{ $section['name'] }}">
                                        <option value="{{ $section['value'] }}">{{ $section['value'] == 1 ? 'On' : 'Off' }}</option>
                                        <option value="1">On</option>
                                        <option value="0">Off</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
