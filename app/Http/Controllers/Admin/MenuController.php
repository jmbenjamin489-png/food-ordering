<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::with('category');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $menuItems = $query->latest()->paginate(15);
        $categories = Category::active()->get();

        return view('admin.menu.index', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'preparation_time' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $validated['is_available'] = $request->has('is_available');
        $validated['is_featured'] = $request->has('is_featured');

        MenuItem::create($validated);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load('category', 'orderItems.order');
        return view('admin.menu.show', compact('menuItem'));
    }
public function edit(MenuItem $menu)
{
    $categories = Category::active()->ordered()->get();
    return view('admin.menu.edit', compact('menu', 'categories'));
}

public function update(Request $request, MenuItem $menu)
{
    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'preparation_time' => 'required|integer|min:1',
    ]);

    if ($request->hasFile('image')) {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $validated['image'] = $request->file('image')->store('menu-items', 'public');
    }

    $validated['is_available'] = $request->has('is_available');
    $validated['is_featured'] = $request->has('is_featured');

    $menu->update($validated);

    return redirect()->route('admin.menu.index')
        ->with('success', 'Menu item updated successfully.');
}



   public function destroy(MenuItem $menu)
{
    if ($menu->image) {
        Storage::disk('public')->delete($menu->image);
    }

    $menu->delete();

    return redirect()->route('admin.menu.index')
        ->with('success', 'Menu item deleted successfully.');
}


    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->update([
            'is_available' => !$menuItem->is_available
        ]);

        $status = $menuItem->is_available ? 'available' : 'unavailable';

        return back()->with('success', "Menu item is now {$status}.");
    }
}
