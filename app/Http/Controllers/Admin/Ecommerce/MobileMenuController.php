<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use App\Support\MobileMenu;
use Illuminate\Http\Request;

class MobileMenuController extends Controller
{
    public function index()
    {
        return view('admin.e-commerce.mobile-menu.index', [
            'links' => MobileMenu::links(),
            'socials' => MobileMenu::socials(),
            'curatedIds' => MobileMenu::curatedIds(),
            'categories' => Category::where('status', 1)->orderBy('name')->get(['id', 'name', 'slug']),
            'video' => setting('MENU_BG_VIDEO'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'links' => ['array'],
            'links.*.key' => ['required', 'string'],
            'links.*.label' => ['required', 'string', 'max:60'],
            'categories' => ['array'],
            'categories.*' => ['integer'],
            'socials' => ['array'],
            'socials.*.type' => ['required', 'string'],
            'socials.*.url' => ['nullable', 'string', 'max:255'],
            'menu_video' => ['nullable', 'file', 'mimetypes:video/webm,video/mp4,video/quicktime', 'max:204800'],
        ]);

        $links = collect($request->input('links', []))
            ->filter(fn ($l) => in_array($l['key'] ?? null, MobileMenu::LINK_KEYS, true))
            ->map(fn ($l) => [
                'key' => $l['key'],
                'label' => trim($l['label']) ?: ucfirst($l['key']),
                'visible' => isset($l['visible']),
            ])->values()->all();
        Setting::updateOrCreate(['name' => 'MOBILE_MENU_LINKS'], ['value' => json_encode($links)]);

        $ids = array_values(array_map('intval', $request->input('categories', [])));
        Setting::updateOrCreate(['name' => 'MOBILE_MENU_CATEGORY_IDS'], ['value' => json_encode($ids)]);

        $socials = collect($request->input('socials', []))
            ->filter(fn ($s) => in_array($s['type'] ?? null, MobileMenu::SOCIAL_TYPES, true))
            ->map(fn ($s) => [
                'type' => $s['type'],
                'url' => trim($s['url'] ?? ''),
                'visible' => isset($s['visible']),
            ])->values()->all();
        Setting::updateOrCreate(['name' => 'MOBILE_MENU_SOCIALS'], ['value' => json_encode($socials)]);

        if ($request->hasFile('menu_video')) {
            Setting::updateOrCreate(['name' => 'MENU_BG_VIDEO'], ['value' => $this->storeVideo($request->file('menu_video'))]);
        }

        notify()->success('Mobile menu saved');

        return back();
    }

    private function storeVideo($file): string
    {
        $dir = public_path('uploads/menu');
        if (! file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $old = setting('MENU_BG_VIDEO');
        if ($old && file_exists($dir.'/'.$old)) {
            @unlink($dir.'/'.$old);
        }

        $name = 'menu-bg-'.now()->timestamp.'.'.$file->getClientOriginalExtension();
        $file->move($dir, $name);

        return $name;
    }
}
