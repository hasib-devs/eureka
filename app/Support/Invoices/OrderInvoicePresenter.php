<?php

namespace App\Support\Invoices;

use App\Models\Order;
use App\Models\PartialPayment;

class OrderInvoicePresenter extends AbstractInvoicePresenter
{
    public function __construct(protected Order $order) {}

    public function toArray(): array
    {
        $order = $this->order;

        $advance = (float) PartialPayment::where('order_id', $order->id)
            ->where('status', 1)
            ->sum('amount');

        $grandTotal = (float) $order->total;

        $items = $order->orderDetails->map(fn ($d) => [
            'description' => $d->title,
            'qty' => (float) $d->qty,
            'unit_price' => (float) $d->price,
            'line_total' => (float) $d->total_price,
        ])->all();

        return array_merge([
            'source' => 'order',
            'id' => $order->id,
            'number' => $order->invoice ?: $order->order_id,
            'date' => $order->created_at,
            'due_date' => null,
            'status' => $this->paymentStatus($grandTotal, $advance),
            'customer' => [
                'name' => trim($order->first_name.' '.$order->last_name),
                'phone' => $order->phone,
                'email' => $order->email,
                'address' => $this->address(),
            ],
            'items' => $items,
            'subtotal' => (float) $order->subtotal,
            'discount' => (float) $order->discount,
            'delivery_charge' => (float) $order->shipping_charge,
            'delivery_label' => null,
            'additional_charges' => (float) ($order->gift_wrap_fee ?? 0),
            'advance_paid' => $advance,
            'grand_total' => $grandTotal,
            'due_amount' => $grandTotal - $advance,
            'payment_method' => $order->payment_method,
            'notes' => null,
            'business' => $this->businessInfo(),
            'payment_details' => $this->paymentDetails($order->payment_method),
        ], $this->appearance());
    }

    protected function paymentStatus(float $grandTotal, float $advance): string
    {
        if ($grandTotal > 0 && $advance >= $grandTotal) {
            return 'Paid';
        }
        if ($advance > 0) {
            return 'Partially Paid';
        }
        if ($this->order->pay_staus == 1) {
            return 'Paid';
        }

        return 'Unpaid';
    }

    protected function address(): string
    {
        return collect([$this->order->address, $this->order->town, $this->order->district])
            ->filter()
            ->implode(', ');
    }
}
