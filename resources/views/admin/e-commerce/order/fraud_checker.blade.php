<div class="flex items-center justify-between bg-primary px-4 py-4 text-white">
    <h5 class="flex items-center gap-2 text-base font-semibold tracking-tight">
        <i class="fas fa-shield-alt"></i> Fraud Checker Report
    </h5>
    <button type="button" class="text-white/75 transition-colors hover:text-white" data-dismiss="modal" aria-label="Close">
        <i class="fas fa-times text-lg" aria-hidden="true"></i>
    </button>
</div>

<div class="space-y-4 p-5">
    {{-- Customer Info --}}
    <x-ui.card>
        <x-slot:header>
            <span class="text-sm font-semibold text-slate-700"><i class="fas fa-user"></i> Customer Information</span>
        </x-slot:header>

        @if(isset($api_error_message) && $api_error_message)
            <x-ui.alert variant="danger" class="mb-3">
                <i class="fas fa-exclamation-circle"></i>
                <strong>API Error:</strong> {{ $api_error_message }}
            </x-ui.alert>
        @endif

        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            <div>
                <p class="text-sm text-slate-700"><strong>Name:</strong> {{ $name }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-700"><strong>Phone:</strong> {{ $phone }}</p>
                <p class="text-sm text-slate-700"><strong>IP:</strong> {{ $ip ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-2">
            <p class="flex items-center gap-1.5 text-sm text-slate-700">
                <strong>Status:</strong>
                @if($status == 'success')
                    <x-ui.badge variant="success" dot>Verified Customer</x-ui.badge>
                @elseif($status == 'warning')
                    <x-ui.badge variant="warning" dot>Caution Required</x-ui.badge>
                @elseif($status == 'danger')
                    <x-ui.badge variant="danger" dot>High Risk Customer</x-ui.badge>
                @else
                    <x-ui.badge variant="neutral" dot>{{ $status }}</x-ui.badge>
                @endif
            </p>
        </div>
    </x-ui.card>

    {{-- Overall Summary --}}
    <x-ui.card>
        <x-slot:header>
            <span class="text-sm font-semibold text-info"><i class="fas fa-chart-pie"></i> Overall Summary</span>
        </x-slot:header>

        <div class="grid grid-cols-3 gap-3 text-center">
            <div class="min-h-[80px] rounded-xl bg-slate-50 p-3 ring-1 ring-inset ring-slate-200">
                <span class="block truncate text-xs font-medium uppercase tracking-wide text-slate-500">Total Parcels</span>
                <span class="mt-1 block text-2xl font-bold tracking-tight text-slate-800">{{ $total_parcel }}</span>
            </div>
            <div class="min-h-[80px] rounded-xl bg-success/10 p-3 ring-1 ring-inset ring-success/20">
                <span class="block truncate text-xs font-medium uppercase tracking-wide text-success">Success</span>
                <span class="mt-1 block text-2xl font-bold tracking-tight text-success">{{ $total_success }}</span>
            </div>
            <div class="min-h-[80px] rounded-xl bg-danger/10 p-3 ring-1 ring-inset ring-danger/20">
                <span class="block truncate text-xs font-medium uppercase tracking-wide text-danger">Cancelled</span>
                <span class="mt-1 block text-2xl font-bold tracking-tight text-danger">{{ $total_cancel }}</span>
            </div>
        </div>

        {{-- Success Ratio Progress Bar --}}
        @php
            $success_ratio = $total_parcel > 0 ? round(($total_success / $total_parcel) * 100, 2) : 0;
            $cancel_ratio = $total_parcel > 0 ? round(($total_cancel / $total_parcel) * 100, 2) : 0;
        @endphp

        <div class="mt-4">
            <h6 class="mb-1.5 text-sm font-semibold text-slate-700">Success Ratio: {{ $success_ratio }}%</h6>
            <div class="flex h-6 w-full overflow-hidden rounded-full bg-slate-100 ring-1 ring-inset ring-slate-200">
                <div class="flex items-center justify-center bg-success text-[11px] font-semibold text-white"
                     role="progressbar"
                     style="width: {{ $success_ratio }}%;"
                     aria-valuenow="{{ $success_ratio }}" aria-valuemin="0" aria-valuemax="100">
                    {{ $success_ratio }}% Success
                </div>
                <div class="flex items-center justify-center bg-danger text-[11px] font-semibold text-white"
                     role="progressbar"
                     style="width: {{ $cancel_ratio }}%;"
                     aria-valuenow="{{ $cancel_ratio }}" aria-valuemin="0" aria-valuemax="100">
                    {{ $cancel_ratio }}% Cancel
                </div>
            </div>
        </div>
    </x-ui.card>

    {{-- Courier Wise Details --}}
    <x-ui.card>
        <x-slot:header>
            <span class="text-sm font-semibold text-secondary"><i class="fas fa-shipping-fast"></i> Courier-wise Performance</span>
        </x-slot:header>

        <div class="overflow-x-auto rounded-lg ring-1 ring-slate-200">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-4 py-3 text-left">Courier</th>
                        <th class="px-4 py-3 text-center">Total</th>
                        <th class="px-4 py-3 text-center">Success</th>
                        <th class="px-4 py-3 text-center">Cancelled</th>
                        <th class="px-4 py-3 text-center">Success Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- Local Shop --}}
                    <tr class="transition-colors hover:bg-slate-50/80">
                        <td class="px-4 py-3">
                            <i class="fas fa-store text-primary"></i>
                            <strong>Our Shop (Local)</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums">{{ $local_shop_total ?? 0 }}</td>
                        <td class="px-4 py-3 text-center tabular-nums text-success">
                            <strong>{{ $local_shop_success ?? 0 }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums text-danger">
                            <strong>{{ $local_shop_cancel ?? 0 }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $local_total = $local_shop_total ?? 0;
                                $local_success = $local_shop_success ?? 0;
                                $local_rate = $local_total > 0 ? round(($local_success / $local_total) * 100, 1) : 0;
                            @endphp
                            <x-ui.badge variant="{{ $local_rate >= 70 ? 'success' : ($local_rate >= 50 ? 'warning' : 'danger') }}">
                                {{ $local_rate }}%
                            </x-ui.badge>
                        </td>
                    </tr>

                    {{-- Steadfast --}}
                    <tr class="transition-colors hover:bg-slate-50/80">
                        <td class="px-4 py-3">
                            <i class="fas fa-truck text-primary"></i>
                            <strong>Steadfast</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums">{{ $steadfast_total }}</td>
                        <td class="px-4 py-3 text-center tabular-nums text-success">
                            <strong>{{ $steadfast_success }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums text-danger">
                            <strong>{{ $steadfast_cancel }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $steadfast_rate = $steadfast_total > 0 ? round(($steadfast_success / $steadfast_total) * 100, 1) : 0;
                            @endphp
                            <x-ui.badge variant="{{ $steadfast_rate >= 70 ? 'success' : ($steadfast_rate >= 50 ? 'warning' : 'danger') }}">
                                {{ $steadfast_rate }}%
                            </x-ui.badge>
                        </td>
                    </tr>

                    {{-- Pathao --}}
                    <tr class="transition-colors hover:bg-slate-50/80">
                        <td class="px-4 py-3">
                            <i class="fas fa-truck text-info"></i>
                            <strong>Pathao</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums">{{ $pathao_total }}</td>
                        <td class="px-4 py-3 text-center tabular-nums text-success">
                            <strong>{{ $pathao_success }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums text-danger">
                            <strong>{{ $pathao_cancel }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $pathao_rate = $pathao_total > 0 ? round(($pathao_success / $pathao_total) * 100, 1) : 0;
                            @endphp
                            <x-ui.badge variant="{{ $pathao_rate >= 70 ? 'success' : ($pathao_rate >= 50 ? 'warning' : 'danger') }}">
                                {{ $pathao_rate }}%
                            </x-ui.badge>
                        </td>
                    </tr>

                    {{-- RedX --}}
                    <tr class="transition-colors hover:bg-slate-50/80">
                        <td class="px-4 py-3">
                            <i class="fas fa-truck text-danger"></i>
                            <strong>RedX</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums">{{ $redx_total }}</td>
                        <td class="px-4 py-3 text-center tabular-nums text-success">
                            <strong>{{ $redx_success }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums text-danger">
                            <strong>{{ $redx_cancel }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $redx_rate = $redx_total > 0 ? round(($redx_success / $redx_total) * 100, 1) : 0;
                            @endphp
                            <x-ui.badge variant="{{ $redx_rate >= 70 ? 'success' : ($redx_rate >= 50 ? 'warning' : 'danger') }}">
                                {{ $redx_rate }}%
                            </x-ui.badge>
                        </td>
                    </tr>

                    {{-- Paperfly --}}
                    <tr class="transition-colors hover:bg-slate-50/80">
                        <td class="px-4 py-3">
                            <i class="fas fa-truck text-success"></i>
                            <strong>Paperfly</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums">{{ $paperfly_total }}</td>
                        <td class="px-4 py-3 text-center tabular-nums text-success">
                            <strong>{{ $paperfly_success }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center tabular-nums text-danger">
                            <strong>{{ $paperfly_cancel }}</strong>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $paperfly_rate = $paperfly_total > 0 ? round(($paperfly_success / $paperfly_total) * 100, 1) : 0;
                            @endphp
                            <x-ui.badge variant="{{ $paperfly_rate >= 70 ? 'success' : ($paperfly_rate >= 50 ? 'warning' : 'danger') }}">
                                {{ $paperfly_rate }}%
                            </x-ui.badge>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t border-slate-200 bg-slate-50/80 font-semibold">
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-center tabular-nums">{{ $total_parcel }}</th>
                        <th class="px-4 py-3 text-center tabular-nums text-success">{{ $total_success }}</th>
                        <th class="px-4 py-3 text-center tabular-nums text-danger">{{ $total_cancel }}</th>
                        <th class="px-4 py-3 text-center">
                            <x-ui.badge variant="{{ $success_ratio >= 70 ? 'success' : ($success_ratio >= 50 ? 'warning' : 'danger') }}">
                                {{ $success_ratio }}%
                            </x-ui.badge>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-ui.card>

    {{-- Risk Assessment --}}
    <x-ui.card>
        <x-slot:header>
            <span class="text-sm font-semibold
                @if($success_ratio >= 70) text-success
                @elseif($success_ratio >= 50) text-warning
                @else text-danger
                @endif">
                <i class="fas fa-exclamation-triangle"></i> Risk Assessment
            </span>
        </x-slot:header>

        @if($total_parcel == 0)
            <x-ui.alert variant="info">
                <i class="fas fa-info-circle"></i>
                <strong>New Customer:</strong> No previous delivery history found.
            </x-ui.alert>
        @elseif($success_ratio >= 70)
            <x-ui.alert variant="success">
                <i class="fas fa-check-circle"></i>
                <strong>Low Risk:</strong> This customer has a good delivery success rate ({{ $success_ratio }}%). Safe to proceed with the order.
            </x-ui.alert>
        @elseif($success_ratio >= 50)
            <x-ui.alert variant="warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Medium Risk:</strong> This customer has a moderate success rate ({{ $success_ratio }}%). Consider confirming the order before shipping.
            </x-ui.alert>
        @else
            <x-ui.alert variant="danger">
                <i class="fas fa-times-circle"></i>
                <strong>High Risk:</strong> This customer has a low success rate ({{ $success_ratio }}%). Strongly recommend order confirmation before shipping.
            </x-ui.alert>
        @endif

        @if($total_cancel >= 3)
            <x-ui.alert variant="warning" class="mt-2">
                <i class="fas fa-ban"></i>
                <strong>Warning:</strong> Customer has {{ $total_cancel }} cancelled orders. Extra verification recommended.
            </x-ui.alert>
        @endif
    </x-ui.card>
</div>

<div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4 print:hidden">
    <x-ui.button variant="outline" data-dismiss="modal">
        <i class="fas fa-times"></i> Close
    </x-ui.button>
    <x-ui.button variant="primary" onclick="window.print()">
        <i class="fas fa-print"></i> Print Report
    </x-ui.button>
</div>
