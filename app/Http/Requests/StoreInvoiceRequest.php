<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Route is already gated by the auth + admin middleware.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'delivery_label' => ['nullable', 'string', 'max:100'],
            'delivery_charge' => ['nullable', 'numeric', 'min:0'],
            'additional_charges' => ['nullable', 'numeric', 'min:0'],
            'advance_paid' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['Draft', 'Unpaid', 'Partially Paid', 'Paid'])],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Add at least one line item.',
            'items.*.description.required' => 'Item description is required.',
        ];
    }
}
