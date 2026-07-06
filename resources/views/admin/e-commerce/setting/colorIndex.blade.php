@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Color Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Define the brand colors used across the storefront</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Colors</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Brand Colors</h2>
                        <p class="text-xs text-slate-500">Pick a color or type a hex value &mdash; both fields stay in sync</p>
                    </div>
                </div>

                <form id="color_form" action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="4">

                    @php
                        $colorFields = [
                            ['id' => 'PRIMARY_COLOR', 'label' => 'Primary Color', 'hint' => 'Main accent used for buttons and links', 'value' => $PRIMARY_COLOR->value],
                            ['id' => 'PRIMARY_BG_TEXT_COLOR', 'label' => 'Primary Background Text Color', 'hint' => 'Text shown on primary-colored backgrounds', 'value' => $PRIMARY_BG_TEXT_COLOR->value],
                            ['id' => 'SECONDARY_COLOR', 'label' => 'Secondary Color', 'hint' => 'Supporting accent color', 'value' => $SECONDARY_COLOR->value],
                            ['id' => 'OPTIONAL_COLOR', 'label' => 'Optional Color', 'hint' => 'Extra accent for highlights', 'value' => $OPTIONAL_COLOR->value],
                            ['id' => 'OPTIONAL_BG_TEXT_COLOR', 'label' => 'Optional Background Text Color', 'hint' => 'Text shown on optional-colored backgrounds', 'value' => $OPTIONAL_BG_TEXT_COLOR->value],
                            ['id' => 'MAIN_MENU_BG', 'label' => 'Main Menu Background', 'hint' => 'Background of the main navigation bar', 'value' => $MAIN_MENU_BG->value],
                            ['id' => 'MAIN_MENU_ul_li_color', 'label' => 'Main Menu Item Color', 'hint' => 'Color of the main navigation links', 'value' => $MAIN_MENU_ul_li_color->value],
                        ];
                    @endphp

                    <div class="divide-y divide-slate-100 px-5">
                        @foreach ($colorFields as $field)
                            <div class="flex flex-wrap items-center justify-between gap-3 py-4">
                                <div>
                                    <label for="{{ $field['id'] }}" class="block text-sm font-medium text-slate-700">{{ $field['label'] }}</label>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $field['hint'] }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="color" id="{{ $field['id'] }}_CHOOSER"
                                        value="{{ $field['value'] }}"
                                        class="h-10 w-14 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 shadow-sm transition-shadow hover:shadow">
                                    <input type="text" id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                                        value="{{ $field['value'] }}"
                                        class="h-10 w-32 rounded-lg border border-slate-300 bg-white px-3 text-sm tabular-nums text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $("#PRIMARY_COLOR_CHOOSER").on("input", function() {
                $("#PRIMARY_COLOR").val($(this).val());
            });
            $("#PRIMARY_COLOR").on("keyup", function() {
                $("#PRIMARY_COLOR_CHOOSER").val($(this).val());
            });

            $("#PRIMARY_BG_TEXT_COLOR_CHOOSER").on("input", function() {
                $("#PRIMARY_BG_TEXT_COLOR").val($(this).val());
            });
            $("#PRIMARY_BG_TEXT_COLOR").on("keyup", function() {
                $("#PRIMARY_BG_TEXT_COLOR_CHOOSER").val($(this).val());
            });

            $("#SECONDARY_COLOR_CHOOSER").on("input", function() {
                $("#SECONDARY_COLOR").val($(this).val());
            });
            $("#SECONDARY_COLOR").on("keyup", function() {
                $("#SECONDARY_COLOR_CHOOSER").val($(this).val());
            });

            $("#OPTIONAL_COLOR_CHOOSER").on("input", function() {
                $("#OPTIONAL_COLOR").val($(this).val());
            });
            $("#OPTIONAL_COLOR").on("keyup", function() {
                $("#OPTIONAL_COLOR_CHOOSER").val($(this).val());
            });

            $("#OPTIONAL_BG_TEXT_COLOR_CHOOSER").on("input", function() {
                $("#OPTIONAL_BG_TEXT_COLOR").val($(this).val());
            });
            $("#OPTIONAL_BG_TEXT_COLOR").on("keyup", function() {
                $("#OPTIONAL_BG_TEXT_COLOR_CHOOSER").val($(this).val());
            });

            $("#MAIN_MENU_BG_CHOOSER").on("input", function() {
                $("#MAIN_MENU_BG").val($(this).val());
            });
            $("#MAIN_MENU_BG").on("keyup", function() {
                $("#MAIN_MENU_BG_CHOOSER").val($(this).val());
            });

            $("#MAIN_MENU_ul_li_color_CHOOSER").on("input", function() {
                $("#MAIN_MENU_ul_li_color").val($(this).val());
            });
            $("#MAIN_MENU_ul_li_color").on("keyup", function() {
                $("#MAIN_MENU_ul_li_color_CHOOSER").val($(this).val());
            });
        });
    </script>
@endpush
