<?php

namespace App\Support\Invoices;

use App\Models\Invoice;

class ManualInvoicePresenter extends AbstractInvoicePresenter
{
    public function __construct(protected Invoice $invoice) {}

    public function toArray(): array
    {
        $invoice = $this->invoice;

        $items = $invoice->items->map(fn ($i) => [
            'description' => $i->description,
            'qty' => (float) $i->qty,
            'unit_price' => (float) $i->unit_price,
            'line_total' => (float) $i->line_total,
        ])->all();

        return array_merge([
            'source' => 'manual',
            'id' => $invoice->id,
            'number' => $invoice->invoice_no,
            'date' => $invoice->invoice_date,
            'due_date' => $invoice->due_date,
            'status' => $invoice->status,
            'customer' => [
                'name' => $invoice->customer_name,
                'phone' => $invoice->customer_phone,
                'email' => $invoice->customer_email,
                'address' => $invoice->customer_address,
            ],
            'items' => $items,
            'subtotal' => (float) $invoice->subtotal,
            'discount' => (float) $invoice->discount,
            'delivery_charge' => (float) $invoice->delivery_charge,
            'delivery_label' => $invoice->delivery_label,
            'additional_charges' => (float) $invoice->additional_charges,
            'advance_paid' => (float) $invoice->advance_paid,
            'grand_total' => (float) $invoice->grand_total,
            'due_amount' => (float) $invoice->due_amount,
            'payment_method' => $invoice->payment_method,
            'notes' => $invoice->notes,
            'business' => $this->businessInfo(),
            'payment_details' => $this->paymentDetails($invoice->payment_method),
        ], $this->appearance());
    }
}
