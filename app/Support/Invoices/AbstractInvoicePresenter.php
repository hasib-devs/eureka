<?php

namespace App\Support\Invoices;

abstract class AbstractInvoicePresenter
{
    /**
     * Build the normalized invoice view-model consumed by the print template.
     */
    abstract public function toArray(): array;

    protected function businessInfo(): array
    {
        return [
            'name' => setting('site_name') ?: (setting('site_title') ?: config('app.name')),
            'tagline' => setting('site_tagline', ''),
            'owner' => setting('holder_name', ''),
            'phone' => setting('phone') ?: setting('SITE_INFO_PHONE'),
            'email' => setting('email') ?: setting('SITE_INFO_SUPPORT_MAIL'),
            'website' => setting('site_url', ''),
            'address' => setting('address') ?: setting('SITE_INFO_ADDRESS'),
            'logo' => setting('logo') ? asset('uploads/setting/'.setting('logo')) : null,
            'signature' => setting('invoice_signature') ? asset('uploads/invoice/'.setting('invoice_signature')) : null,
        ];
    }

    protected function paymentDetails(?string $method): array
    {
        return [
            'bank' => [
                'bank_name' => setting('bank_name'),
                'account_name' => setting('holder_name'),
                'account_number' => setting('account_number'),
                'branch_name' => setting('branch_name'),
                'routing_number' => setting('routing_number'),
            ],
            'mobile' => $this->mobileFor($method),
        ];
    }

    protected function mobileFor(?string $method): ?array
    {
        $key = ['bKash' => 'bkash', 'Nagad' => 'nagad', 'Rocket' => 'rocket'][$method] ?? null;
        if (! $key) {
            return null;
        }

        $number = setting("invoice_{$key}_number");
        $qr = setting("invoice_{$key}_qr");
        if (! $number && ! $qr) {
            return null;
        }

        return [
            'label' => $method,
            'number' => $number,
            'qr' => $qr ? asset('uploads/invoice/'.$qr) : null,
        ];
    }

    protected function appearance(): array
    {
        return [
            'accent' => setting('invoice_accent', '#f5b400'),
            'header_bg' => setting('invoice_header_bg', '#1c1c22'),
        ];
    }
}
