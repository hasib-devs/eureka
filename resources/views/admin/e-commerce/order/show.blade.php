@extends('layouts.admin.app')

@section('title', 'Order Information')

@php
    $statusMap = [
        0 => ['label' => 'Pending', 'variant' => 'warning'],
        1 => ['label' => 'Confirmed', 'variant' => 'primary'],
        2 => ['label' => 'Canceled', 'variant' => 'danger'],
        3 => ['label' => 'Delivered', 'variant' => 'success'],
        4 => ['label' => 'Shipping', 'variant' => 'info'],
        5 => ['label' => 'Refund', 'variant' => 'danger'],
        6 => ['label' => 'Return Requested', 'variant' => 'warning'],
        7 => ['label' => 'Returning by Customer', 'variant' => 'warning'],
        8 => ['label' => 'Returned', 'variant' => 'danger'],
        9 => ['label' => 'Sent to Courier', 'variant' => 'info'],
    ];
@endphp

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-slate-900">
                    Order Details
                    @if ($order->gift_wrap)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                            <i class="fas fa-gift"></i>
                            Gift Wrapping — {{ $order->gift_wrap_fee + 0 }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}
                        </span>
                    @endif
                </h1>
                <p class="mt-1 text-sm text-slate-500">Invoice {{ $order->invoice }}</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Order Information</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="space-y-4">

        {{-- Order actions --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-semibold text-slate-900">Order Actions</h2>
                    @php $orderStatus = $statusMap[$order->status] ?? null; @endphp
                    @if ($orderStatus)
                        <x-ui.badge variant="{{ $orderStatus['variant'] }}" dot class="whitespace-nowrap">{{ $orderStatus['label'] }}</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral" dot>Unknown</x-ui.badge>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if ($order->status != 5)
                        @if ($order->status != 2)
                            @if ($order->status != 3)
                                <x-ui.button
                                    :href="route('admin.order.pay', ['id' => $order->id])"
                                    :variant="$order->pay_staus == 1 ? 'danger' : 'success'"
                                    size="sm"
                                    :title="$order->pay_staus == 1 ? 'Unpaid' : 'Paid'"
                                >
                                    <i class="fas fa-money-bill"></i>
                                    @if ($order->pay_staus == 1)
                                        Unpaid
                                    @else
                                        Paid
                                    @endif
                                </x-ui.button>
                            @endif
                        @endif

                        @if (setting('PATHAO_STATUS') == 1 && $order->status != 9)
                            <x-ui.button :href="route('admin.setting.pathao.send', $order->id)" variant="info" size="sm">
                                <i class="fas fa-motorcycle"></i> Send Pathao
                            </x-ui.button>
                        @endif

                        @if (setting('STEEDFAST_STATUS') == 1 && $order->status != 9)
                            <form action="{{ route('admin.setting.courier.sendsteedfast') }}" method="POST">
                                @csrf
                                <input type="hidden" name="invoice" value="{{ $order->invoice }}">
                                <input type="hidden" name="recipient_name" value="{{ $order->first_name }}">
                                <input type="hidden" name="recipient_phone" value="{{ $order->phone }}">
                                <input type="hidden" name="recipient_address"
                                    value="{{ $order->address . ', ' . $order->town . ', ' . $order->district . ', ' . $order->post_code }}">
                                @if ($order->pay_staus == 1)
                                    <input type="hidden" name="cod_amount" value="0.00">
                                @else
                                    <input type="hidden" name="cod_amount" value="{{ $order->total }}">
                                @endif
                                <input type="hidden" name="note" value="N/A">
                                <x-ui.button type="submit" variant="info" size="sm">Send Courier</x-ui.button>
                            </form>
                        @else
                            <x-ui.button variant="info" size="sm" type="button">Courierd Already</x-ui.button>
                        @endif

                        <x-ui.button
                            :href="routeHelper('order/status/processing/' . $order->id)"
                            variant="primary"
                            size="sm"
                            title="Processing"
                            onclick="alert('Are you sure change status this order?')"
                        >
                            <i class="fas fa-running"></i>
                            Processing
                        </x-ui.button>

                        @if ($order->status == 6)
                            <x-ui.button
                                :href="routeHelper('order/status/return_req_accept/' . $order->id)"
                                variant="success"
                                size="sm"
                                title="Accept return request"
                                onclick="alert('Return process are start')"
                            >
                                Return Accept
                            </x-ui.button>
                        @elseif ($order->status == 7)
                            <x-ui.button
                                :href="routeHelper('order/status/return_complete/' . $order->id)"
                                variant="success"
                                size="sm"
                                title="Complete the return process, you got the product from customer as a return completely."
                                onclick="alert('Complete the return, you got the product from customer?')"
                            >
                                Return Complete
                            </x-ui.button>
                        @elseif ($order->status != 2 && $order->status != 3 && $order->status != 6 && $order->status != 7 && $order->status != 8)
                            <x-ui.button
                                :href="routeHelper('order/status/shipping/' . $order->id)"
                                variant="info"
                                size="sm"
                                id="btnShipping"
                                title="Shipping"
                                onclick="return confirm('Are you sure Shipping this order?')"
                            >
                                <i class="fas fa-plane"></i> Shipping
                            </x-ui.button>

                            <x-ui.button
                                :href="routeHelper('order/status/delivered/' . $order->id)"
                                variant="success"
                                size="sm"
                                title="Delivered"
                                onclick="alert('Are you sure change status this order?')"
                            >
                                <i class="fas fa-thumbs-up"></i>
                                Delivered
                            </x-ui.button>
                        @endif

                        @if ($order->status != 3 && $order->status != 2)
                            <x-ui.button
                                :href="routeHelper('order/status/cancel/' . $order->id)"
                                variant="warning"
                                size="sm"
                                title="Cancel"
                                onclick="alert('Are you sure change status this order?')"
                            >
                                <i class="fas fa-window-close"></i>
                                Cancel
                            </x-ui.button>
                        @endif
                    @endif

                    @if ($order->status == 3)
                        <x-ui.button
                            variant="warning"
                            size="sm"
                            type="button"
                            @click="$dispatch('open-modal-refund')"
                        >
                            Refund
                        </x-ui.button>
                    @endif

                    @if ($order->status == 2)
                        <x-ui.button
                            variant="warning"
                            size="sm"
                            type="button"
                            @click="$dispatch('open-modal-refund2')"
                        >
                            Refund
                        </x-ui.button>
                    @endif

                    <x-ui.button
                        :href="route('admin.order.delete', ['did' => $order->id])"
                        variant="danger"
                        size="sm"
                    >
                        <i class="fas fa-trash-alt"></i> Delete
                    </x-ui.button>

                    <x-ui.button
                        :href="routeHelper('order/print/' . $order->id)"
                        variant="outline"
                        size="sm"
                        target="_blank"
                        rel="noopener"
                    >
                        <i class="fas fa-print"></i> Print
                    </x-ui.button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3">

            {{-- Customer Information --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Customer Information</h3>
                        <p class="text-xs text-slate-500">Billing and shipping details for this order</p>
                    </div>
                </div>
                <div class="p-5">
                    <dl class="grid grid-cols-1 gap-x-10 text-sm md:grid-cols-2">
                        @if (!empty($order->meet_time))
                            <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                                <dt class="text-slate-500">Meet Time</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->meet_time }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Customer Name</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->first_name }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Order ID</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->order_id }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Invoice</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->invoice }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Company Name</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->company_name }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Country</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->country }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Address</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->address }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Town</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->town }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">District</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->district }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Post Code</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->post_code }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Phone</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->phone }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Email</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->email }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Shipping Method</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->shipping_method }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Payment Summary</h3>
                        <p class="text-xs text-slate-500">Amounts, payment and order status</p>
                    </div>
                </div>
                <div class="p-5">
                    <dl class="divide-y divide-slate-100 text-sm">
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Payment Method</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->payment_method }}</dd>
                        </div>
                        @if ($order->payment_method == 'Bkash' || $order->payment_method == 'Nagad' || $order->payment_method == 'Rocket')
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Mobile Number</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->mobile_number }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Transaction ID</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->transaction_id }}</dd>
                            </div>
                        @elseif ($order->payment_method == 'Bank')
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Bank Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->bank_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Account Number</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->account_number }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Holder Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->holder_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Branch Name</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->branch_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Routing Number</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->routing_number }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Coupon Code</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->coupon_code }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Subtotal</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $order->subtotal }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Shipping Charge</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $order->shipping_charge }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Discount</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $order->discount }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Gift Wrapping</dt>
                            <dd class="text-right">
                                @if ($order->gift_wrap)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                        <i class="fas fa-gift"></i>
                                        Yes — {{ $order->gift_wrap_fee + 0 }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}
                                    </span>
                                @else
                                    <span class="text-sm text-slate-400">No</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Payment Status</dt>
                            <dd class="text-right">
                                @if ($order->pay_staus == 1)
                                    <x-ui.badge variant="success" dot>Paid</x-ui.badge>
                                @else
                                    <x-ui.badge variant="warning" dot>Unpaid</x-ui.badge>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Payment Date</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $order->pay_date }}</dd>
                        </div>
                        @php
                            $part = App\Models\PartialPayment::where('order_id', $order->id)
                                ->where('status', 1)
                                ->sum('amount');
                            $ds = $order->total;
                        @endphp
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Partial Payment</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $part }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Due</dt>
                            <dd class="text-right font-medium tabular-nums text-slate-800">{{ $order->total - $part }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="font-semibold text-slate-700">Total</dt>
                            <dd class="text-right font-semibold tabular-nums text-slate-900">{{ $ds }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-slate-500">Status</dt>
                            <dd class="text-right">
                                @if ($orderStatus)
                                    <x-ui.badge variant="{{ $orderStatus['variant'] }}" dot class="whitespace-nowrap">{{ $orderStatus['label'] }}</x-ui.badge>
                                @else
                                    <x-ui.badge variant="neutral" dot>Unknown</x-ui.badge>
                                @endif
                            </dd>
                        </div>
                        @if ($order->status == 5)
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-slate-500">Refund Method</dt>
                                <dd class="text-right font-medium text-slate-800">{{ $order->refund_method }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- Refund Modal (status == 3 / delivered) --}}
        <x-ui.modal name="refund" title="Refund">
            <form method="post" action="{{ route('admin.refund') }}">
                @csrf
                <div class="mb-4">
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <x-ui.input name="amount" type="text" placeholder="amount" />
                </div>
                <div class="mb-4">
                    <x-ui.select name="method">
                        <option value="wallate">Wallate</option>
                        <option value="Bank">Bank</option>
                        <option value="Bkash">Bkash</option>
                        <option value="Nagad">Nagad</option>
                        <option value="Rocket">Rocket</option>
                        <option value="Cash">Cash</option>
                    </x-ui.select>
                </div>
                <x-ui.button type="submit" variant="primary">Refund</x-ui.button>
            </form>
            <x-slot:footer>
                <x-ui.button type="button" variant="outline" @click="$dispatch('close-modal-refund')">Close</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>

        {{-- Refund Modal (status == 2 / canceled) --}}
        <x-ui.modal name="refund2" title="Refund">
            <form method="post" action="{{ route('admin.refund_2') }}">
                @csrf
                <div class="mb-4">
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <x-ui.input name="amount" type="text" placeholder="amount" />
                </div>
                <div class="mb-4">
                    <x-ui.select name="method">
                        <option value="wallate">Wallate</option>
                        <option value="Bank">Bank</option>
                        <option value="Bkash">Bkash</option>
                        <option value="Nagad">Nagad</option>
                        <option value="Rocket">Rocket</option>
                        <option value="Cash">Cash</option>
                    </x-ui.select>
                </div>
                <x-ui.button type="submit" variant="primary">Refund</x-ui.button>
            </form>
            <x-slot:footer>
                <x-ui.button type="button" variant="outline" @click="$dispatch('close-modal-refund2')">Close</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>

        {{-- Order Products --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Order Products</h3>
                    <p class="text-xs text-slate-500">Items in this order, grouped by seller</p>
                </div>
            </div>
            <div class="p-5">
                @php
                    $vendors = DB::table('multi_order')->where('order_id', $order->id)->get();
                @endphp

                @foreach ($vendors as $key => $vendor)
                    @php($us = App\Models\User::find($vendor->vendor_id))

                    {{-- Vendor summary row --}}
                    <div class="mb-3 flex flex-wrap items-center gap-x-6 gap-y-2 rounded-lg bg-slate-50 px-4 py-3 text-sm ring-1 ring-inset ring-slate-200">
                        <div><span class="text-slate-500">Seller:</span> <span class="font-semibold text-slate-800">{{ $us->name }}</span></div>
                        <div><span class="text-slate-500">Total:</span> <span class="font-medium tabular-nums text-slate-800">{{ $vendor->total }}</span></div>
                        <div><span class="text-slate-500">Payment:</span> <span class="font-medium tabular-nums text-slate-800">{{ $vendor->partial_pay }}</span></div>
                        <div><span class="text-slate-500">Discount:</span> <span class="font-medium tabular-nums text-slate-800">{{ $vendor->discount }}</span></div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500">Status:</span>
                            @php $vendorStatus = $statusMap[$vendor->status] ?? null; @endphp
                            @if ($vendorStatus)
                                <x-ui.badge variant="{{ $vendorStatus['variant'] }}" dot class="whitespace-nowrap">{{ $vendorStatus['label'] }}</x-ui.badge>
                            @else
                                <x-ui.badge variant="neutral" dot>Unknown</x-ui.badge>
                            @endif
                        </div>
                    </div>

                    {{-- Sub-status action links --}}
                    <div class="mb-3 flex flex-wrap gap-2 text-sm">
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 1, 'vendor' => $vendor->vendor_id]) }}"
                            class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm transition-colors hover:border-primary hover:text-primary">Processing</a>
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 4, 'vendor' => $vendor->vendor_id]) }}"
                            class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm transition-colors hover:border-primary hover:text-primary">Shipping</a>
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 2, 'vendor' => $vendor->vendor_id]) }}"
                            class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm transition-colors hover:border-danger hover:text-danger">Canceled</a>
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 3, 'vendor' => $vendor->vendor_id]) }}"
                            class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm transition-colors hover:border-success hover:text-success">Delivered</a>
                    </div>

                    <div class="mb-6 overflow-x-auto rounded-lg ring-1 ring-slate-200">
                        <table class="w-full min-w-[760px] text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    <th class="w-14 px-4 py-3.5">SL</th>
                                    <th class="px-4 py-3.5">Product</th>
                                    <th class="px-4 py-3.5">Title</th>
                                    <th class="px-4 py-3.5">Size</th>
                                    <th class="px-4 py-3.5">Color</th>
                                    <th class="px-4 py-3.5 text-right">Qty</th>
                                    <th class="px-4 py-3.5 text-right">Price</th>
                                    <th class="px-4 py-3.5 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($order->orderDetails as $key => $item)
                                    @if (isset($item->product->user_id) && $item->product->user_id == $vendor->vendor_id)
                                        <tr class="group transition-colors hover:bg-slate-50/80">
                                            <td class="px-4 py-4 text-slate-500">{{ $key + 1 }}</td>
                                            <td class="px-4 py-4">
                                                <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                                                    alt="Product Image" class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                            </td>
                                            <td class="px-4 py-4">
                                                <a href="{{ route('admin.product.show', $item->product->id) }}"
                                                    target="_blank" class="font-semibold text-primary hover:underline">{{ $item->title }}</a>
                                            </td>
                                            <td class="px-4 py-4 text-slate-600"><?php
                                            $data = json_decode($item->size);
                                            if ($data != null && $data != '""' && $data != '[]' && $data != '"\"\""') {
                                                foreach ($data as $key => $attr) {
                                                    $value = DB::table('attribute_values')->where('id', $attr)->first();
                                                    $name = DB::table('attributes')->where('slug', $key)->first();
                                                    echo $name?->name;
                                                    if ($name) {
                                                        // echo $vl = $name->name . ':' . $value->name . ', ';
                                                    }
                                                }
                                            }
                                            ?></td>
                                            <td class="px-4 py-4 text-slate-600">
                                                @php
                                                    $colorRow = $item->color && $item->color != 'blank'
                                                        ? DB::table('colors')->where('slug', $item->color)->orWhere('name', $item->color)->first()
                                                        : null;
                                                @endphp
                                                @if ($colorRow)
                                                    <span class="inline-flex items-center gap-2">
                                                        <span class="inline-block h-4 w-4 rounded-full ring-1 ring-slate-300" style="background: {{ $colorRow->code ?? '#ddd' }}"></span>
                                                        {{ $colorRow->name }}
                                                    </span>
                                                @elseif ($item->color != 'blank')
                                                    {{ $item->color }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-right tabular-nums text-slate-600">{{ $item->qty }}</td>
                                            <td class="px-4 py-4 text-right tabular-nums text-slate-500">{{ $item->price }}</td>
                                            <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">{{ $item->total_price }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach

                {{-- Orders without per-vendor rows (no multi_order records) would otherwise
                     show nothing — fall back to a flat list of the order's items. --}}
                @if ($vendors->isEmpty())
                    <div class="overflow-x-auto rounded-lg ring-1 ring-slate-200">
                        <table class="w-full min-w-[760px] text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    <th class="w-14 px-4 py-3.5">SL</th>
                                    <th class="px-4 py-3.5">Product</th>
                                    <th class="px-4 py-3.5">Title</th>
                                    <th class="px-4 py-3.5">Color</th>
                                    <th class="px-4 py-3.5 text-right">Qty</th>
                                    <th class="px-4 py-3.5 text-right">Price</th>
                                    <th class="px-4 py-3.5 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($order->orderDetails as $item)
                                    <tr class="group transition-colors hover:bg-slate-50/80">
                                        <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4">
                                            @if ($item->product)
                                                <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                                                    alt="Product Image" class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($item->product)
                                                <a href="{{ route('admin.product.show', $item->product->id) }}"
                                                    target="_blank" class="font-semibold text-primary hover:underline">{{ $item->title }}</a>
                                            @else
                                                <span class="font-semibold text-slate-800">{{ $item->title }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-slate-600">
                                            @php
                                                $colorRow = $item->color && $item->color != 'blank'
                                                    ? DB::table('colors')->where('slug', $item->color)->orWhere('name', $item->color)->first()
                                                    : null;
                                            @endphp
                                            @if ($colorRow)
                                                <span class="inline-flex items-center gap-2">
                                                    <span class="inline-block h-4 w-4 rounded-full ring-1 ring-slate-300" style="background: {{ $colorRow->code ?? '#ddd' }}"></span>
                                                    {{ $colorRow->name }}
                                                </span>
                                            @elseif ($item->color != 'blank')
                                                {{ $item->color }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-right tabular-nums text-slate-600">{{ $item->qty }}</td>
                                        <td class="px-4 py-4 text-right tabular-nums text-slate-500">{{ $item->price }}</td>
                                        <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">{{ $item->total_price }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">No items found on this order.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </section>
@endsection
