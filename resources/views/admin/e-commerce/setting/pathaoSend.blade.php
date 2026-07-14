@extends('layouts.admin.app')

@section('title', 'Send to Pathao')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Send to Pathao</h1>
                <p class="mt-1 text-sm text-slate-500">Order {{ $order->order_id }} — {{ $order->first_name }} ({{ $order->phone }})</p>
            </div>
            <a href="{{ route('admin.order.show', $order->id) }}" class="text-sm font-medium text-primary hover:underline">
                <i class="fas fa-arrow-left text-xs"></i> Back to order
            </a>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    @endphp

    <section class="mb-6">
        <div class="mx-auto w-full max-w-2xl space-y-4">

            <div class="rounded-xl border border-slate-200 bg-white p-5 text-sm shadow-sm">
                <p class="font-medium text-slate-700">Delivery address on the order</p>
                <p class="mt-1 text-slate-500">{{ trim($order->address.', '.$order->thana.', '.$order->district, ', ') ?: '—' }}</p>
                <p class="mt-1 text-xs text-slate-400">Order total: {{ $order->total }} — {{ $order->pay_staus == 1 ? 'PAID online' : 'Unpaid (COD)' }}</p>
            </div>

            <form action="{{ route('admin.setting.pathao.send.store', $order->id) }}" method="POST"
                class="rounded-xl border border-slate-200 bg-white shadow-sm">
                @csrf

                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                    <div>
                        <label for="city_id" class="mb-1 block text-sm font-medium text-slate-700">City <span class="text-danger">*</span></label>
                        <select name="city_id" id="city_id" required class="{{ $control }}"
                            data-zones-url="{{ url('admin/setting/pathao/zones') }}"
                            data-areas-url="{{ url('admin/setting/pathao/areas') }}">
                            <option value="">Select city</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city['city_id'] }}" @if (old('city_id') == $city['city_id']) selected @endif>
                                    {{ $city['city_name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label for="zone_id" class="mb-1 block text-sm font-medium text-slate-700">Zone <span class="text-danger">*</span></label>
                        <select name="zone_id" id="zone_id" required class="{{ $control }}">
                            <option value="">Select city first</option>
                        </select>
                        @error('zone_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label for="area_id" class="mb-1 block text-sm font-medium text-slate-700">Area <span class="text-slate-400">(optional)</span></label>
                        <select name="area_id" id="area_id" class="{{ $control }}">
                            <option value="">Select zone first</option>
                        </select>
                    </div>

                    <div>
                        <label for="item_weight" class="mb-1 block text-sm font-medium text-slate-700">Weight (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" min="0.1" max="100" name="item_weight" id="item_weight"
                            value="{{ old('item_weight', '0.5') }}" required class="{{ $control }}">
                        @error('item_weight')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label for="amount_to_collect" class="mb-1 block text-sm font-medium text-slate-700">Amount to Collect (COD) <span class="text-danger">*</span></label>
                        <input type="number" step="1" min="0" name="amount_to_collect" id="amount_to_collect"
                            value="{{ old('amount_to_collect', $order->pay_staus == 1 ? 0 : (int) round($order->total)) }}"
                            required class="{{ $control }}">
                        @error('amount_to_collect')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label for="special_instruction" class="mb-1 block text-sm font-medium text-slate-700">Note <span class="text-slate-400">(optional)</span></label>
                        <input type="text" name="special_instruction" id="special_instruction" maxlength="500"
                            value="{{ old('special_instruction') }}" class="{{ $control }}">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Send to Pathao
                    </x-ui.button>
                </div>
            </form>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var citySelect = document.getElementById('city_id');
            var zoneSelect = document.getElementById('zone_id');
            var areaSelect = document.getElementById('area_id');
            var zonesUrl = citySelect.dataset.zonesUrl;
            var areasUrl = citySelect.dataset.areasUrl;

            function fill(select, items, idKey, nameKey, placeholder) {
                select.innerHTML = '<option value="">' + placeholder + '</option>';
                items.forEach(function (item) {
                    var opt = document.createElement('option');
                    opt.value = item[idKey];
                    opt.textContent = item[nameKey];
                    select.appendChild(opt);
                });
            }

            citySelect.addEventListener('change', function () {
                fill(zoneSelect, [], '', '', 'Loading...');
                fill(areaSelect, [], '', '', 'Select zone first');
                if (!this.value) return;
                fetch(zonesUrl + '/' + this.value, { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (zones) { fill(zoneSelect, zones, 'zone_id', 'zone_name', 'Select zone'); })
                    .catch(function () { fill(zoneSelect, [], '', '', 'Failed to load zones'); });
            });

            zoneSelect.addEventListener('change', function () {
                fill(areaSelect, [], '', '', 'Loading...');
                if (!this.value) return;
                fetch(areasUrl + '/' + this.value, { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (areas) { fill(areaSelect, areas, 'area_id', 'area_name', 'Any area (optional)'); })
                    .catch(function () { fill(areaSelect, [], '', '', 'Failed to load areas'); });
            });
        });
    </script>

@endsection
