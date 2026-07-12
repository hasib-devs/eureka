<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\PartialPayment;
use App\Models\Product;
use App\Models\User;
use App\Support\Invoices\InvoiceNumber;
use App\Support\Invoices\InvoiceTotals;
use App\Support\Invoices\ManualInvoicePresenter;
use App\Support\Invoices\OrderInvoicePresenter;
use App\Support\Invoices\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public const STATUSES = ['Draft', 'Unpaid', 'Partially Paid', 'Paid'];

    /**
     * Unified list of order-derived invoices and manual invoices.
     */
    public function index(Request $request)
    {
        $source = $request->query('source', 'all');
        $status = $request->query('status', 'All');
        $search = trim((string) $request->query('search', ''));
        $from = $request->query('date_from');
        $to = $request->query('date_to');

        $rows = collect();

        if ($source === 'all' || $source === 'order') {
            $rows = $rows->merge($this->orderRows($search, $from, $to));
        }

        if ($source === 'all' || $source === 'manual') {
            $rows = $rows->merge($this->manualRows($search, $from, $to));
        }

        if ($status !== 'All') {
            $rows = $rows->where('status', $status);
        }

        $rows = $rows->sortByDesc(fn ($r) => $r['date'])->values();

        $perPage = 15;
        $page = Paginator::resolveCurrentPage();
        $invoices = new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('admin.e-commerce.invoice.index', [
            'invoices' => $invoices,
            'statuses' => self::STATUSES,
            'filters' => [
                'source' => $source,
                'status' => $status,
                'search' => $search,
                'date_from' => $from,
                'date_to' => $to,
            ],
        ]);
    }

    /**
     * Show the manual invoice builder.
     */
    public function create()
    {
        return view('admin.e-commerce.invoice.form', array_merge($this->formOptions(), [
            'invoice' => null,
            'items' => [['description' => '', 'qty' => 1, 'unit_price' => 0]],
            'action' => route('admin.invoices.store'),
            'method' => 'POST',
            'heading' => 'Create Invoice',
        ]));
    }

    /**
     * Persist a new manual invoice.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = $this->persist(new Invoice, $request);

        notify()->success('Invoice created');

        return redirect()->route('admin.invoices.show', $invoice->id);
    }

    /**
     * Render a manual invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('items');

        return view('admin.e-commerce.invoice.show', [
            'vm' => (new ManualInvoicePresenter($invoice))->toArray(),
        ]);
    }

    /**
     * Render an existing order as an invoice.
     */
    public function showOrder(Order $order)
    {
        $order->load('orderDetails');

        return view('admin.e-commerce.invoice.show', [
            'vm' => (new OrderInvoicePresenter($order))->toArray(),
        ]);
    }

    private function orderRows(string $search, $from, $to): Collection
    {
        $query = Order::query()
            ->select(['id', 'invoice', 'order_id', 'first_name', 'last_name', 'total', 'pay_staus', 'created_at'])
            ->latest('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'like', "%{$search}%")
                    ->orWhere('order_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $orders = $query->get();

        $paidByOrder = PartialPayment::where('status', 1)
            ->whereIn('order_id', $orders->pluck('id'))
            ->groupBy('order_id')
            ->selectRaw('order_id, sum(amount) as paid')
            ->pluck('paid', 'order_id');

        return $orders->map(function ($o) use ($paidByOrder) {
            $total = (float) $o->total;
            $paid = (float) ($paidByOrder[$o->id] ?? 0);

            return [
                'source' => 'order',
                'number' => $o->invoice ?: $o->order_id,
                'customer' => trim($o->first_name.' '.$o->last_name),
                'date' => $o->created_at,
                'total' => $total,
                'due' => $total - $paid,
                'status' => PaymentStatus::forOrder($total, $paid, $o->pay_staus),
                'url' => route('admin.invoices.order', $o->id),
            ];
        });
    }

    private function manualRows(string $search, $from, $to): Collection
    {
        $query = Invoice::query()->latest('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        if ($from) {
            $query->whereDate('invoice_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        return $query->get()->map(fn ($inv) => [
            'source' => 'manual',
            'number' => $inv->invoice_no,
            'customer' => $inv->customer_name,
            'date' => $inv->invoice_date,
            'total' => (float) $inv->grand_total,
            'due' => (float) $inv->due_amount,
            'status' => $inv->status,
            'url' => route('admin.invoices.show', $inv->id),
        ]);
    }

    /**
     * Fill, compute totals for, and save a manual invoice with its items.
     */
    private function persist(Invoice $invoice, StoreInvoiceRequest $request): Invoice
    {
        $data = $request->validated();

        $items = collect($data['items'])->map(fn ($i) => [
            'description' => $i['description'],
            'qty' => (float) $i['qty'],
            'unit_price' => (float) $i['unit_price'],
            'line_total' => round((float) $i['qty'] * (float) $i['unit_price'], 2),
        ])->all();

        $totals = InvoiceTotals::compute(
            $items,
            $data['discount'] ?? 0,
            $data['delivery_charge'] ?? 0,
            $data['additional_charges'] ?? 0,
            $data['advance_paid'] ?? 0,
        );

        return DB::transaction(function () use ($invoice, $data, $items, $totals) {
            $invoice->fill([
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'customer_address' => $data['customer_address'] ?? null,
                'discount' => $data['discount'] ?? 0,
                'delivery_label' => $data['delivery_label'] ?? null,
                'delivery_charge' => $data['delivery_charge'] ?? 0,
                'additional_charges' => $data['additional_charges'] ?? 0,
                'advance_paid' => $data['advance_paid'] ?? 0,
                'subtotal' => $totals['subtotal'],
                'grand_total' => $totals['grand_total'],
                'due_amount' => $totals['due_amount'],
                'status' => $data['status'],
                'payment_method' => $data['payment_method'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            if (! $invoice->exists) {
                $invoice->invoice_no = InvoiceNumber::next();
                $invoice->created_by = auth()->id();
            }

            $invoice->save();

            $invoice->items()->delete();
            $invoice->items()->createMany($items);

            return $invoice;
        });
    }

    private function formOptions(): array
    {
        return [
            'customers' => User::where('role_id', 3)->orderBy('name')->get(['id', 'name', 'phone', 'email']),
            'products' => Product::where('status', 1)->orderBy('title')->get(['id', 'title', 'regular_price']),
            'deliveryPresets' => [
                ['label' => 'Dhaka City', 'amount' => 80],
                ['label' => 'Outside Dhaka', 'amount' => 150],
            ],
            'statuses' => self::STATUSES,
            'paymentMethods' => ['Cash', 'Bank Transfer', 'bKash', 'Nagad', 'Rocket'],
        ];
    }
}
