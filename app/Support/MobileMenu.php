<?php

namespace App\Support;

use App\Models\Category;
use Illuminate\Support\Collection;

class MobileMenu
{
    public const LINK_KEYS = ['home', 'shop', 'categories', 'blog', 'track', 'contact'];

    public const SOCIAL_TYPES = ['call', 'whatsapp', 'facebook', 'instagram', 'tiktok'];

    /**
     * The 6 primary links, stored values merged over defaults so all keys always exist.
     */
    public static function links(): array
    {
        $stored = collect(json_decode(setting('MOBILE_MENU_LINKS', ''), true) ?: [])->keyBy('key');

        return collect(self::defaultLinks())->map(function ($default) use ($stored) {
            $s = $stored->get($default['key']);

            return $s
                ? ['key' => $default['key'], 'label' => $s['label'] ?? $default['label'], 'visible' => (bool) ($s['visible'] ?? true)]
                : $default;
        })->all();
    }

    public static function socials(): array
    {
        $stored = collect(json_decode(setting('MOBILE_MENU_SOCIALS', ''), true) ?: [])->keyBy('type');

        return collect(self::SOCIAL_TYPES)->map(fn ($type) => [
            'type' => $type,
            'url' => $stored->get($type)['url'] ?? '',
            'visible' => (bool) ($stored->get($type)['visible'] ?? false),
        ])->all();
    }

    /**
     * @return array<int, int>
     */
    public static function curatedIds(): array
    {
        return array_values(array_map('intval', json_decode(setting('MOBILE_MENU_CATEGORY_IDS', ''), true) ?: []));
    }

    /**
     * Curated categories, preserving the stored order.
     */
    public static function categories(): Collection
    {
        $ids = self::curatedIds();
        if (empty($ids)) {
            return collect();
        }

        $found = Category::whereIn('id', $ids)->where('status', 1)->get(['id', 'name', 'slug'])->keyBy('id');

        return collect($ids)->map(fn ($id) => $found->get($id))->filter()->values();
    }

    private static function defaultLinks(): array
    {
        return [
            ['key' => 'home', 'label' => 'Home', 'visible' => true],
            ['key' => 'shop', 'label' => 'Shop', 'visible' => true],
            ['key' => 'categories', 'label' => 'Categories', 'visible' => true],
            ['key' => 'blog', 'label' => 'Blog', 'visible' => true],
            ['key' => 'track', 'label' => 'Track Order', 'visible' => true],
            ['key' => 'contact', 'label' => 'Contact', 'visible' => true],
        ];
    }
}
