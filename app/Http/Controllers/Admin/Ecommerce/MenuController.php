<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $items = MenuItem::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.e-commerce.menu.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        MenuItem::create([
            'label' => $data['label'],
            'url' => $data['url'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'new_tab' => $request->boolean('new_tab'),
            'status' => $request->boolean('status', true),
        ]);

        notify()->success('Menu item added', 'Success');

        return back();
    }

    public function update(Request $request, MenuItem $menu)
    {
        $data = $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $menu->update([
            'label' => $data['label'],
            'url' => $data['url'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'new_tab' => $request->boolean('new_tab'),
            'status' => $request->boolean('status'),
        ]);

        notify()->success('Menu item updated', 'Success');

        return back();
    }

    public function destroy(MenuItem $menu)
    {
        $menu->delete();

        notify()->success('Menu item removed', 'Success');

        return back();
    }
}
