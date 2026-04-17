<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->ordered()
            ->with(['availableMenuItems'])
            ->get();

        $featuredItems = MenuItem::available()
            ->featured()
            ->with('category')
            ->take(6)
            ->get();

        return view('customer.home', compact('categories', 'featuredItems'));
    }

    public function menu(Request $request)
    {
        $query = MenuItem::available()->with('category');

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $menuItems = $query->paginate(12);
        $categories = Category::active()->ordered()->get();

        return view('customer.menu', compact('menuItems', 'categories'));
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load('category');
        
        $relatedItems = MenuItem::available()
            ->where('category_id', $menuItem->category_id)
            ->where('id', '!=', $menuItem->id)
            ->take(4)
            ->get();

        return view('customer.menu-item', compact('menuItem', 'relatedItems'));
    }
}
