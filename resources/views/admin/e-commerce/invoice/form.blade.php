@extends('layouts.admin.app')

@section('title', $heading)

@php
    $cur = setting('CURRENCY_CODE_MIN') ?? 'TK';
    $inp = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary';
    $lbl = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-wide text-slate-500';
    $rows = old('items', $items);
    $iv = fn ($key, $default = '') => old($key, $invoice?->{$key} ?? $default);
    $dateVal = fn ($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('Y-m-d') : '';
@endphp

@section('content')

    <form method="POST" action="{{ $action }}" class="mx-auto max-w-4xl space-y-4">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.invoices.index') }}" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">{{ $heading }}</h1>
            </div>
            <x-ui.button variant="primary" type="submit"><i class="fas fa-check"></i> Save Invoice</x-ui.button>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-1 list-inside list-disc">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Invoice meta --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-file-invoice"></i></div>
                <h3 class="text-base font-semibold text-slate-900">Invoice Details</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-3">
                <div>
                    <label class="{{ $lbl }}">Invoice Date</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $dateVal($invoice?->invoice_date ?? now())) }}" class="{{ $inp }}">
                </div>
                <div>
                    <label class="{{ $lbl }}">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $dateVal($invoice?->due_date)) }}" class="{{ $inp }}">
                </div>
                <div>
                    <label class="{{ $lbl }}">Status</label>
                    <select name="status" class="{{ $inp }}">
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected($iv('status', 'Draft') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Customer --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-user"></i></div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Billed To</h3>
                    <p class="text-xs text-slate-500">Type the customer, or pick one to prefill.</p>
                </div>
            </div>
            <div class="p-5">
                <div class="mb-4">
                    <label class="{{ $lbl }}">Pick existing customer (optional)</label>
                    <select id="customer-picker" class="{{ $inp }}">
                        <option value="">— Select to prefill —</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}{{ $c->phone ? ' ('.$c->phone.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="{{ $lbl }}">Name *</label>
                        <input id="customer_name" name="customer_name" value="{{ $iv('customer_name') }}" class="{{ $inp }}">
                    </div>
                    <div>
                        <label class="{{ $lbl }}">Phone</label>
                        <input id="customer_phone" name="customer_phone" value="{{ $iv('customer_phone') }}" class="{{ $inp }}">
                    </div>
                    <div>
                        <label class="{{ $lbl }}">Email</label>
                        <input id="customer_email" name="customer_email" value="{{ $iv('customer_email') }}" class="{{ $inp }}">
                    </div>
                    <div>
                        <label class="{{ $lbl }}">Address</label>
                        <input id="customer_address" name="customer_address" value="{{ $iv('customer_address') }}" class="{{ $inp }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-boxes"></i></div>
                    <h3 class="text-base font-semibold text-slate-900">Items</h3>
                </div>
                <x-ui.button variant="outline" size="sm" type="button" id="add-item"><i class="fas fa-plus"></i> Add Item</x-ui.button>
            </div>
            <div class="p-5">
                <div id="items-wrap">
                    @foreach ($rows as $i => $row)
                        <div class="mb-3 grid grid-cols-12 gap-3 rounded-lg border border-slate-200 p-3" data-row>
                            <div class="col-span-12 md:col-span-3">
                                <label class="{{ $lbl }}">Product</label>
                                <select class="item-product {{ $inp }}">
                                    <option value="">Custom item</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="{{ $lbl }}">Description</label>
                                <input name="items[{{ $i }}][description]" value="{{ $row['description'] ?? '' }}" class="item-desc {{ $inp }}">
                            </div>
                            <div class="col-span-4 md:col-span-1">
                                <label class="{{ $lbl }}">Qty</label>
                                <input type="number" step="any" min="0" name="items[{{ $i }}][qty]" value="{{ $row['qty'] ?? 1 }}" class="item-qty {{ $inp }}">
                            </div>
                            <div class="col-span-4 md:col-span-2">
                                <label class="{{ $lbl }}">Unit Price</label>
                                <input type="number" step="any" min="0" name="items[{{ $i }}][unit_price]" value="{{ $row['unit_price'] ?? 0 }}" class="item-price {{ $inp }}">
                            </div>
                            <div class="col-span-4 flex items-end justify-between gap-2 md:col-span-2">
                                <div><label class="{{ $lbl }}">Amount</label><div class="item-amount pt-2 text-sm font-bold text-slate-900">0.00</div></div>
                                <button type="button" class="item-remove grid h-9 w-9 place-items-center rounded-lg text-rose-500 hover:bg-rose-50"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Charges & payment --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-calculator"></i></div>
                <h3 class="text-base font-semibold text-slate-900">Charges &amp; Payment</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                <div>
                    <label class="{{ $lbl }}">Discount ({{ $cur }})</label>
                    <input type="number" step="any" min="0" id="discount" name="discount" value="{{ $iv('discount', 0) }}" class="{{ $inp }}">
                </div>
                <div>
                    <label class="{{ $lbl }}">Additional Charges ({{ $cur }})</label>
                    <input type="number" step="any" min="0" id="additional_charges" name="additional_charges" value="{{ $iv('additional_charges', 0) }}" class="{{ $inp }}">
                </div>
                <div class="md:col-span-2">
                    <label class="{{ $lbl }}">Delivery Charge</label>
                    <div class="mb-2 flex flex-wrap gap-2">
                        @foreach ($deliveryPresets as $preset)
                            <button type="button" class="delivery-preset rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:border-primary"
                                data-amount="{{ $preset['amount'] }}" data-label="{{ $preset['label'] }}">
                                {{ $preset['label'] }} ({{ number_format($preset['amount'], 0) }} {{ $cur }})
                            </button>
                        @endforeach
                        <button type="button" class="delivery-preset rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:border-primary" data-custom="1" data-label="Custom">
                            Custom Amount
                        </button>
                    </div>
                    <input type="number" step="any" min="0" id="delivery_charge" name="delivery_charge" value="{{ $iv('delivery_charge', 0) }}" class="{{ $inp }}">
                    <input type="hidden" id="delivery_label" name="delivery_label" value="{{ $iv('delivery_label') }}">
                </div>
                <div>
                    <label class="{{ $lbl }}">Advance Paid ({{ $cur }})</label>
                    <input type="number" step="any" min="0" id="advance_paid" name="advance_paid" value="{{ $iv('advance_paid', 0) }}" class="{{ $inp }}">
                </div>
                <div>
                    <label class="{{ $lbl }}">Payment Method</label>
                    <select name="payment_method" class="{{ $inp }}">
                        <option value="">—</option>
                        @foreach ($paymentMethods as $m)
                            <option value="{{ $m }}" @selected($iv('payment_method') === $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="{{ $lbl }}">Notes</label>
                    <textarea name="notes" rows="2" class="{{ $inp }}">{{ $iv('notes') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 border-t border-slate-200 p-5 text-sm sm:grid-cols-3">
                <div><span class="text-slate-500">Subtotal: </span><span id="sum-subtotal" class="font-bold text-slate-900">0.00 {{ $cur }}</span></div>
                <div><span class="text-slate-500">Grand Total: </span><span id="sum-grand" class="font-extrabold text-primary">0.00 {{ $cur }}</span></div>
                <div><span class="text-slate-500">Due: </span><span id="sum-due" class="font-bold text-rose-600">0.00 {{ $cur }}</span></div>
            </div>
        </div>

        <div class="flex justify-end gap-2 pb-8">
            <x-ui.button variant="ghost" :href="route('admin.invoices.index')">Cancel</x-ui.button>
            <x-ui.button variant="primary" type="submit"><i class="fas fa-check"></i> Save Invoice</x-ui.button>
        </div>
    </form>

@endsection

@push('js')
    <script>
        (function () {
            var PRODUCTS = @json($products);
            var CUSTOMERS = @json($customers);
            var CUR = @json($cur);
            var rowIndex = {{ count($rows) }};

            function num(v) { var n = parseFloat(v); return isNaN(n) ? 0 : n; }

            function recompute() {
                var subtotal = 0;
                $('[data-row]').each(function () {
                    var amount = num($(this).find('.item-qty').val()) * num($(this).find('.item-price').val());
                    $(this).find('.item-amount').text(amount.toFixed(2) + ' ' + CUR);
                    subtotal += amount;
                });
                var grand = subtotal - num($('#discount').val()) + num($('#delivery_charge').val()) + num($('#additional_charges').val());
                var due = grand - num($('#advance_paid').val());
                $('#sum-subtotal').text(subtotal.toFixed(2) + ' ' + CUR);
                $('#sum-grand').text(grand.toFixed(2) + ' ' + CUR);
                $('#sum-due').text(due.toFixed(2) + ' ' + CUR);
            }

            function escapeHtml(s) {
                return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                });
            }

            function productOptions() {
                var html = '<option value="">Custom item</option>';
                PRODUCTS.forEach(function (p) {
                    html += '<option value="' + p.id + '">' + escapeHtml(p.title) + '</option>';
                });
                return html;
            }

            function buildRow(i) {
                return '<div class="mb-3 grid grid-cols-12 gap-3 rounded-lg border border-slate-200 p-3" data-row>'
                    + '<div class="col-span-12 md:col-span-3"><label class="{{ $lbl }}">Product</label>'
                    + '<select class="item-product {{ $inp }}">' + productOptions() + '</select></div>'
                    + '<div class="col-span-12 md:col-span-4"><label class="{{ $lbl }}">Description</label>'
                    + '<input name="items[' + i + '][description]" class="item-desc {{ $inp }}"></div>'
                    + '<div class="col-span-4 md:col-span-1"><label class="{{ $lbl }}">Qty</label>'
                    + '<input type="number" step="any" min="0" name="items[' + i + '][qty]" value="1" class="item-qty {{ $inp }}"></div>'
                    + '<div class="col-span-4 md:col-span-2"><label class="{{ $lbl }}">Unit Price</label>'
                    + '<input type="number" step="any" min="0" name="items[' + i + '][unit_price]" value="0" class="item-price {{ $inp }}"></div>'
                    + '<div class="col-span-4 flex items-end justify-between gap-2 md:col-span-2">'
                    + '<div><label class="{{ $lbl }}">Amount</label><div class="item-amount pt-2 text-sm font-bold text-slate-900">0.00</div></div>'
                    + '<button type="button" class="item-remove grid h-9 w-9 place-items-center rounded-lg text-rose-500 hover:bg-rose-50"><i class="fas fa-trash"></i></button>'
                    + '</div></div>';
            }

            $(document).on('input change', '.item-qty, .item-price, #discount, #delivery_charge, #additional_charges, #advance_paid', recompute);

            $(document).on('change', '.item-product', function () {
                var id = $(this).val();
                var row = $(this).closest('[data-row]');
                if (id) {
                    var p = PRODUCTS.find(function (x) { return String(x.id) === String(id); });
                    if (p) {
                        row.find('.item-desc').val(p.title);
                        row.find('.item-price').val(p.regular_price);
                    }
                }
                recompute();
            });

            $('#customer-picker').on('change', function () {
                var id = $(this).val();
                var c = CUSTOMERS.find(function (x) { return String(x.id) === String(id); });
                if (c) {
                    $('#customer_name').val(c.name || '');
                    $('#customer_phone').val(c.phone || '');
                    $('#customer_email').val(c.email || '');
                }
            });

            $('#add-item').on('click', function () {
                $('#items-wrap').append(buildRow(rowIndex++));
                recompute();
            });

            $(document).on('click', '.item-remove', function () {
                if ($('[data-row]').length > 1) {
                    $(this).closest('[data-row]').remove();
                    recompute();
                }
            });

            $('.delivery-preset').on('click', function () {
                $('.delivery-preset').removeClass('border-primary bg-primary/10 text-primary');
                $(this).addClass('border-primary bg-primary/10 text-primary');
                if ($(this).data('custom')) {
                    $('#delivery_charge').prop('readonly', false).trigger('focus');
                } else {
                    $('#delivery_charge').val($(this).data('amount')).prop('readonly', true);
                }
                $('#delivery_label').val($(this).data('label'));
                recompute();
            });

            recompute();
        })();
    </script>
@endpush
