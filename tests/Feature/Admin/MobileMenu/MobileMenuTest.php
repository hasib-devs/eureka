<?php

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

beforeEach(fn () => Cache::flush());

it('shows the mobile menu admin page to an admin', function () {
    $this->actingAs(adminUser())->get(route('admin.mobile-menu.index'))
        ->assertOk()
        ->assertSee('Mobile Menu');
});

it('redirects guests from the mobile menu admin page', function () {
    $this->get(route('admin.mobile-menu.index'))->assertRedirect(route('login'));
});

it('saves links, curated categories and socials', function () {
    $cat = Category::create(['name' => 'Glamora', 'slug' => 'glamora']);

    $this->actingAs(adminUser())->post(route('admin.mobile-menu.update'), [
        'links' => [
            ['key' => 'home', 'label' => 'Home', 'visible' => '1'],
            ['key' => 'shop', 'label' => 'Browse Shop', 'visible' => '1'],
        ],
        'categories' => [$cat->id],
        'socials' => [
            ['type' => 'facebook', 'url' => 'https://fb.com/anas', 'visible' => '1'],
            ['type' => 'tiktok', 'url' => ''],
        ],
    ])->assertRedirect();

    $links = json_decode(Setting::where('name', 'MOBILE_MENU_LINKS')->value('value'), true);
    expect(collect($links)->firstWhere('key', 'shop')['label'])->toBe('Browse Shop');

    expect(json_decode(Setting::where('name', 'MOBILE_MENU_CATEGORY_IDS')->value('value'), true))->toBe([$cat->id]);

    $socials = json_decode(Setting::where('name', 'MOBILE_MENU_SOCIALS')->value('value'), true);
    expect(collect($socials)->firstWhere('type', 'facebook')['url'])->toBe('https://fb.com/anas');
});

it('rejects a non-video upload', function () {
    $this->actingAs(adminUser())->post(route('admin.mobile-menu.update'), [
        'menu_video' => UploadedFile::fake()->create('evil.txt', 10, 'text/plain'),
    ])->assertSessionHasErrors('menu_video');

    expect(Setting::where('name', 'MENU_BG_VIDEO')->exists())->toBeFalse();
});

it('accepts and stores a webm background video', function () {
    $this->actingAs(adminUser())->post(route('admin.mobile-menu.update'), [
        'menu_video' => UploadedFile::fake()->create('bg.webm', 300, 'video/webm'),
    ])->assertRedirect();

    $name = Setting::where('name', 'MENU_BG_VIDEO')->value('value');
    expect($name)->not->toBeNull();

    $path = public_path('uploads/menu/'.$name);
    expect(file_exists($path))->toBeTrue();
    @unlink($path);
});
