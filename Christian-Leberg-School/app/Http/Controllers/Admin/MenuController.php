<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('items')->latest()->paginate(20);
        return view('admin.cms.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.cms.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_menus,slug',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $menu = Menu::create($validated);

        return redirect()->route('admin.cms.menus.edit', $menu)
            ->with('success', 'Menu created successfully. Add menu items below.');
    }

    public function show(Menu $menu)
    {
        $menu->load('items.children');
        return view('admin.cms.menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $menu->load('items.children');
        return view('admin.cms.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_menus,slug,' . $menu->id,
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.cms.menus.index')
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.cms.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }

    public function addItem(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:cms_menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'icon' => 'nullable|string|max:100',
            'css_class' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'active' => 'boolean',
        ]);

        $validated['menu_id'] = $menu->id;
        MenuItem::create($validated);

        return redirect()->route('admin.cms.menus.edit', $menu)
            ->with('success', 'Menu item added successfully.');
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:cms_menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'icon' => 'nullable|string|max:100',
            'css_class' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'active' => 'boolean',
        ]);

        $item->update($validated);

        return redirect()->route('admin.cms.menus.edit', $menu)
            ->with('success', 'Menu item updated successfully.');
    }

    public function deleteItem(Menu $menu, MenuItem $item)
    {
        $item->delete();

        return redirect()->route('admin.cms.menus.edit', $menu)
            ->with('success', 'Menu item deleted successfully.');
    }

    public function reorderItems(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:cms_menu_items,id',
            'items.*.order' => 'required|integer',
            'items.*.parent_id' => 'nullable|exists:cms_menu_items,id',
        ]);

        foreach ($validated['items'] as $itemData) {
            MenuItem::where('id', $itemData['id'])->update([
                'order' => $itemData['order'],
                'parent_id' => $itemData['parent_id'] ?? null,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
