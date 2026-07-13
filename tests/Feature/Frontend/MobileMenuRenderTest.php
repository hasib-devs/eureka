<?php

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

beforeEach(fn () => Cache::flush());

it('renders the configured links, curated category and social in the mobile menu partial', function () {
    $cat = Category::create(['name' => 'Cozy Lighting', 'slug' => 'cozy-lighting']);
    Setting::updateOrCreate(['name' => 'MOBILE_MENU_LINKS'], ['value' => json_encode([['key' => 'shop', 'label' => 'Browse Shop', 'visible' => true]])]);
    Setting::updateOrCreate(['name' => 'MOBILE_MENU_CATEGORY_IDS'], ['value' => json_encode([$cat->id])]);
    Setting::updateOrCreate(['name' => 'MOBILE_MENU_SOCIALS'], ['value' => json_encode([['type' => 'facebook', 'url' => 'https://fb.com/anas', 'visible' => true]])]);
    Cache::flush();

    $view = $this->view('layouts.frontend.partials.mobile-menu');

    $view->assertSee('alw-menu-overlay', false);
    $view->assertSee('Browse Shop');
    $view->assertSee('Cozy Lighting');
    $view->assertSee('https://fb.com/anas', false);
});

it('hides the categories accordion when none are curated', function () {
    Cache::flush();

    $view = $this->view('layouts.frontend.partials.mobile-menu');

    // default links still render, but the categories accordion is not emitted
    $view->assertSee('Home');
    $view->assertDontSee('alw-has-cats', false);
});
