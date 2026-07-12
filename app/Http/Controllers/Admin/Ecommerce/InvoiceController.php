<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\PartialPayment;
use App\Support\Invoices\ManualInvoicePresenter;
use App\Support\Invoices\OrderInvoicePresenter;
use App\Support\Invoices\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

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
}
