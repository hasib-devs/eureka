@extends('layouts.admin.app')

@section('title', 'Order Now')

@push('css')
    <link rel="stylesheet" href="{{ asset('/') }}assets/frontend/css/toast.min.css">
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Order Now</h1>
                <p class="mt-1 text-sm text-slate-500">Place a manual order for {{ $product->title }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('product')">
                    <i class="fas fa-long-arrow-alt-left"></i>
                    Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Order Now</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <form action="{{ route('admin.product.order.store') }}" method="POST">
            @csrf

            <div class="space-y-4">

                {{-- Billing Info --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-address-card"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Billing Info</h3>
                            <p class="text-xs text-slate-500">Customer contact and delivery address</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input name="first_name" label="First Name *" type="text" required />
                            <x-ui.input name="last_name" label="Last Name *" type="text" required />
                            <x-ui.input name="company" label="Company (optional)" type="text" required />
                            <x-ui.input name="country" label="Country/Region *" type="text" />
                            <x-ui.input name="address" label="Street address *" type="text" required />
                            <x-ui.input name="city" label="Town/City *" type="text" required />
                            <x-ui.input name="district" label="District *" type="text" required />
                            <x-ui.input name="postcode" label="Postcode / ZIP (optional)" type="text" required />
                            <x-ui.input name="phone" label="Phone *" type="text" required />
                            <x-ui.input name="email" label="Email Address *" type="text" required />
                        </div>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Shipping Method</h3>
                            <p class="text-xs text-slate-500">How the order will be shipped and paid</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Shipping Method</label>
                                <div class="mt-1 flex items-center gap-4">
                                    <label class="flex items-center gap-1.5 text-sm text-slate-700">
                                        <input name="shipping_method" value="Free" type="radio"> Free
                                    </label>
                                    <label class="flex items-center gap-1.5 text-sm text-slate-700">
                                        <input name="shipping_method" value="Prime" type="radio"> Prime
                                    </label>
                                </div>
                                @error('shipping_method')
                                    <small class="text-sm text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div>
                                <x-ui.select name="payment_method" label="Payment Method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                </x-ui.select>
                                @error('payment_method')
                                    <small class="text-sm text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2" id="payment-details"></div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Order Summary</h3>
                            <p class="text-xs text-slate-500">Product, quantity, coupon and totals</p>
                        </div>
                    </div>
                    <div class="space-y-5 p-5">

                        <div class="overflow-x-auto rounded-lg ring-1 ring-slate-200">
                            <table class="w-full min-w-[760px] text-left text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                        <th class="w-14 px-4 py-3.5">SL</th>
                                        <th class="px-4 py-3.5">Image</th>
                                        <th class="px-4 py-3.5">Title</th>
                                        <th class="px-4 py-3.5 text-right">Price</th>
                                        <th class="px-4 py-3.5">Color</th>
                                        <th class="px-4 py-3.5">Size</th>
                                        <th class="px-4 py-3.5">Qty</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="group transition-colors hover:bg-slate-50/80">
                                        <td class="px-4 py-4"></td>
                                        <td class="px-4 py-4">
                                            <img src="{{ asset('uploads/product/' . $product->image) }}"
                                                alt="Product Image" class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                        </td>
                                        <td class="px-4 py-4">
                                            <a href="{{ route('admin.product.show', $product->id) }}" class="font-semibold text-primary hover:underline">
                                                {{ $product->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-4 text-right tabular-nums text-slate-600">{{ $product->regular_price }}</td>
                                        <td class="px-4 py-4">
                                            <div class="flex flex-wrap gap-3">
                                                @foreach ($product->colors as $color)
                                                    <label class="inline-flex items-center gap-1.5 text-sm text-slate-700">
                                                        <input type="radio" name="color" id="color"
                                                            value="{{ $color->name }}"> {{ $color->name }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex flex-wrap gap-3">
                                                @foreach ($product->sizes as $size)
                                                    <label class="inline-flex items-center gap-1.5 text-sm text-slate-700">
                                                        <input type="radio" name="size" id="size"
                                                            value="{{ $size->name }}"> {{ $size->name }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" name="qty"
                                                id="qty" value="1"
                                                class="block w-20 rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                            <input type="hidden" name="id" id="id"
                                                value="{{ $product->id }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            <div>
                                <div class="mb-4">
                                    <label for="coupon" class="mb-1 block text-sm font-medium text-slate-700">
                                        Apply Coupon:
                                    </label>
                                    <input type="text" id="coupon" placeholder="Enter coupon here"
                                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <small class="text-sm text-danger"></small>
                                </div>
                                <x-ui.button type="button" id="apply-coupon" variant="primary">
                                    <i class="fas fa-plus-circle"></i>
                                    Apply
                                </x-ui.button>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-inset ring-slate-200">
                                <table class="w-full text-left text-sm">
                                    <tbody class="divide-y divide-slate-200/70 [&_td]:py-2 [&_th]:py-2">
                                        <tr>
                                            <th class="w-2/5 font-medium text-slate-500">Subtotal</th>
                                            <td id="subtotal" class="text-right tabular-nums text-slate-700">{{ $product->regular_price }}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-medium text-slate-500">Quantity</th>
                                            <td id="quantity" class="text-right tabular-nums text-slate-700">1</td>
                                        </tr>
                                        <tr>
                                            <th class="font-medium text-slate-500">Shipping Charge</th>
                                            <td id="shipping_charge" class="text-right tabular-nums text-slate-700">0</td>
                                        </tr>
                                        <tr>
                                            <th class="font-medium text-slate-500">Coupon (<span id="coupon_name">{{ Session::has('coupon') ? Session::get('coupon')['name'] : '' }}</span>)</th>
                                            <td id="coupon_charge" class="text-right tabular-nums text-slate-700">{{ Session::has('coupon') ? Session::get('coupon')['discount'] : '0' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-semibold text-slate-700">Total</th>
                                            <td id="total" class="text-right font-semibold tabular-nums text-slate-900">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    <x-ui.button variant="outline" :href="routeHelper('product')">Cancel</x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-plus-circle"></i>
                        Submit
                    </x-ui.button>
                </div>

            </div>
        </form>
    </section>

@endsection

@push('js')
    <script src="{{ asset('/') }}assets/frontend/js/toast.min.js"></script>

    <script>
        let quantity = parseInt($('td#quantity').text());
        let subtotal = parseInt($('td#subtotal').text());
        let coupon_charge = parseInt($('td#coupon_charge').text());
        let shipping_charge = parseInt($('td#shipping_charge').text());

        calculateAmount(subtotal, quantity, shipping_charge, coupon_charge);


        function calculateAmount(subtotal, quantity, shipping_charge, coupon) {

            let total = (subtotal * quantity) + shipping_charge + coupon;
            $('td#total').text(total);
        }

        $(document).on('input', '#qty', function(e) {

            let quantity = $(this).val();
            let subtotal = parseInt($('td#subtotal').text());
            let coupon_charge = parseInt($('td#coupon_charge').text());
            let shipping_charge = parseInt($('td#shipping_charge').text());

            $('input#qty').val(quantity);
            $('td#quantity').text(quantity);
            calculateAmount(subtotal, quantity, shipping_charge, coupon_charge);

        })

        $(document).on('click', '#apply-coupon', function(e) {
            e.preventDefault();

            $('#coupon').removeClass('border-danger');

            let code = $('input#coupon').val();
            let id = "{!! $product->id !!}";
            let subtotal = parseInt($('td#subtotal').text());
            let shipping_charge = parseInt($('td#shipping_charge').text());
            let quantity = parseInt($('td#quantity').text());

            if (code != '') {
                $.ajax({
                    type: 'GET',
                    url: '/admin/apply/coupon/' + code + '/' + id,
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);

                        if (response.alert == 'Success') {
                            calculateAmount(subtotal, quantity, shipping_charge, response.discount);

                            $('td#coupon_charge').text(response.discount);
                            $('span#coupon_name').text(code);
                            $('#coupon').val('');
                        }

                        $.toast({
                            heading: response.alert,
                            text: response.message,
                            icon: response.alert.toLowerCase(),
                            position: 'top-right',
                            stack: false
                        });
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: xhr.status,
                            text: xhr.responseJSON.message,
                            icon: 'error',
                            position: 'top-right',
                            stack: false
                        });
                    }
                });
            } else {
                $('#coupon').addClass('border-danger');
            }

        });

        $(document).on('change', '#payment_method', function(e) {
            let method = $(this).val();
            let inputClass = 'block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
            let labelClass = 'mb-1 block text-sm font-medium text-slate-700';
            let html = '';
            if (method == 'Bkash' || method == 'Nagad' || method == 'Rocket') {
                html += '<div>'
                html += '<label for="mobile_number" class="' + labelClass + '">Mobile Number</label>'
                html +=
                    '<input required type="text" name="mobile_number" id="mobile_number" class="' + inputClass + '" placeholder="Enter your mobile number"/>'
                html += '</div>'
                html += '<div>'
                html += '<label for="transaction_id" class="' + labelClass + '">Transaction ID</label>'
                html +=
                    '<input required type="text" name="transaction_id" id="transaction_id" class="' + inputClass + '" placeholder="Enter transaction id"/>'
                html += '</div>'
            } else if (method == 'Bank') {
                html += '<div>'
                html += '<label for="bank_name" class="' + labelClass + '">Bank Name</label>'
                html +=
                    '<input required type="text" name="bank_name" id="bank_name" class="' + inputClass + '" placeholder="Enter bank name"/>'
                html += '</div>'
                html += '<div>'
                html += '<label for="account_number" class="' + labelClass + '">Account Number</label>'
                html +=
                    '<input required type="text" name="account_number" id="account_number" class="' + inputClass + '" placeholder="Enter account number"/>'
                html += '</div>'
                html += '<div>'
                html += '<label for="holder_name" class="' + labelClass + '">Holder Name</label>'
                html +=
                    '<input required type="text" name="holder_name" id="holder_name" class="' + inputClass + '" placeholder="Enter holder name"/>'
                html += '</div>'
                html += '<div>'
                html += '<label for="branch" class="' + labelClass + '">Branch Name</label>'
                html +=
                    '<input required type="text" name="branch" id="branch" class="' + inputClass + '" placeholder="Enter branch name"/>'
                html += '</div>'
                html += '<div>'
                html += '<label for="routing" class="' + labelClass + '">Routing Number</label>'
                html +=
                    '<input required type="text" name="routing" id="routing" class="' + inputClass + '" placeholder="Enter routing number"/>'
                html += '</div>'
            }

            $('#payment-details').html(html);
        })

        $(document).on('click', 'input[name="shipping_method"]', function(e) {
            let shipping_charge = 0;

            if ($("input[name='shipping_method']:checked").val() == 'Prime') {
                let charge = "{!! setting('shipping_charge') !!}";
                shipping_charge += parseInt(charge);
            } else {
                shipping_charge += 0;
            }

            $('td#shipping_charge').text(shipping_charge);

            let subtotal = parseInt($('td#subtotal').text());
            let quantity = parseInt($('td#quantity').text());
            let coupon_charge = parseInt($('td#coupon_charge').text());

            calculateAmount(subtotal, quantity, shipping_charge, coupon_charge);
        });
    </script>
@endpush
