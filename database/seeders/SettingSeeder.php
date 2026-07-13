<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'logo' => 'logo.svg',
            'auth_logo' => 'auth_logo.png',
            'favicon' => 'favicon.svg',
            'TOP_HEADER_STYLE' => '1',
            'MAIN_MENU_STYLE' => '1',
            'CURRENCY_CODE' => 'BDT',
            'CURRENCY_CODE_MIN' => '৳',
            'CURRENCY_ICON' => '৳',
            'COUNTRY_SERVE' => 'Bangladesh',
            'SITE_INFO_PHONE' => '+8801749699156',
            'SITE_INFO_ADDRESS' => 'Dhaka, Bangladesh',
            'shipping_charge' => '60',
            'shipping_charge_out_of_range' => '120',
            'shipping_free_above' => '5000',
            'CHECKOUT_TYPE' => '1',
            'GUEST_CHECKOUT' => '1',
            'is_point' => '0',
            'Default_Point' => '0',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'youtube' => 'https://youtube.com',
            'whatsapp' => '+8801749699156',
            // Payment gateway toggles. Stored as JSON strings ('true'/'false') to
            // match SettingController::setting_g(); the storefront checks == 'true',
            // so the legacy '1' values left every method hidden at checkout.
            'g_cod' => json_encode(true),
            'g_bkash' => json_encode(true),
            'g_bank' => json_encode(true),
            'g_nagad' => json_encode(false),
            'g_rocket' => json_encode(false),

            // Manual payment instructions rendered at checkout for bKash / Bank.
            'bkash' => '01749699156',
            'nagad' => '01749699156',
            'rocket' => '01749699156',
            'bank_name' => 'BRAC Bank',
            'bank_account' => '1234567890123',
            'branch_name' => 'Gulshan',
            'holder_name' => 'Eureka Ltd',
            'routing' => '060270234',

            // Hero slider: show it, using style 1 (overlay click-through fixed in E2).
            'SLIDER_LAYOUT_STATUS' => '1',
            'SLIDER_LAYOUT' => '1',

            // Storefront feature toggles.
            'wishlist_status' => '1',
            'google_login_status' => '0', // off until Google OAuth creds are added to .env

            // Brand palette — seeded with today's storefront colors so the admin
            // Color Settings screen reflects the live look and drives it going forward.
            'PRIMARY_COLOR' => '#f85606',
            'PRIMARY_BG_TEXT_COLOR' => '#ffffff',
            'SECONDARY_COLOR' => '#1e293b',
            'OPTIONAL_COLOR' => '#f2d231',
            'OPTIONAL_BG_TEXT_COLOR' => '#000000',
            'MAIN_MENU_BG' => '#ffffff',
            'MAIN_MENU_ul_li_color' => '#1e293b',
        ];

        foreach ($settings as $name => $value) {
            Setting::updateOrCreate(['name' => $name], ['value' => $value]);
        }
    }
}
