@extends('layouts.app')

@section('title', 'Menu - JOLITS')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-gray-950 via-gray-900 to-gray-950 text-white py-16 border-b border-gray-800">
    <div class="container mx-auto px-4">
        <h1 class="text-5xl font-bold mb-2">🍽️ Our Menu</h1>
        <p class="text-xl text-gray-300">Discover our carefully crafted selection of delicious dishes</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <!-- Filter/Search Section -->
    <div class="mb-12">
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">🔍 Find Your Favorite</h3>
            <form method="GET" action="{{ route('customer.menu') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Search Items</label>
                    <input type="text" name="search" placeholder="e.g., Chicken, Salad..." 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-3 bg-gray-900 border-2 border-gray-700 text-white placeholder-gray-500 rounded-lg focus:border-cyan-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-3 bg-gray-900 border-2 border-gray-700 text-white rounded-lg focus:border-cyan-500 focus:outline-none transition">
                        <option value="" class="bg-gray-900">📋 All Categories</option>
                        @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" class="bg-gray-900" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold px-6 py-3 rounded-lg transition transform hover:scale-105">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Menu Items Grid -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-6">
            {{ request('search') ? 'Search Results for: ' . request('search') : 'All Menu Items' }}
        </h2>
        
        @if($menuItems->count() > 0)
            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($menuItems as $item)
                <div class="group bg-gray-800 border-2 border-gray-700 rounded-xl overflow-hidden hover:border-cyan-500 transition-all transform hover:scale-105 hover:shadow-2xl">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden h-48 bg-gray-700">
                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-gray-400">📷 No Image</span>
                        </div>
                        @endif
                        
                        <!-- Overlay Badge -->
                        @if(!$item->is_available)
                        <div class="absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center">
                            <span class="text-red-400 font-bold text-lg">Out of Stock</span>
                        </div>
                        @endif
                        
                        <!-- Category Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-cyan-600 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $item->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="font-bold text-lg mb-1 text-white group-hover:text-cyan-400 transition">{{ $item->name }}</h3>
                        <p class="text-gray-400 text-xs mb-4 line-clamp-2">{{ $item->description }}</p>
                        
                        <!-- Price and Prep Time -->
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-700">
                            <span class="text-cyan-400 font-bold text-2xl">${{ number_format($item->price, 2) }}</span>
                            @if($item->preparation_time)
                            <span class="text-gray-300 text-xs">⏱️ {{ $item->preparation_time }} min</span>
                            @endif
                        </div>

                        @auth
                        <form action="{{ route('customer.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                            <div class="flex gap-2">
                                <input type="number" name="quantity" value="1" min="1" 
                                       class="w-16 px-2 py-2 bg-gray-900 border-2 border-gray-700 text-white rounded-lg focus:border-cyan-500 focus:outline-none transition text-center">
                                <button type="submit" 
                                        class="flex-1 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold px-4 py-2 rounded-lg transition transform hover:scale-105 {{ !$item->is_available ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ !$item->is_available ? 'disabled' : '' }}>
                                    🛒 Add
                                </button>
                            </div>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="block text-center bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-600 hover:to-gray-500 text-white font-bold px-4 py-2 rounded-lg transition">
                            Login to Order
                        </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        @else
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🍽️</div>
            <p class="text-gray-300 text-lg mb-2">No menu items found</p>
            <p class="text-gray-400 mb-6">Try adjusting your filters or search terms</p>
            <a href="{{ route('customer.menu') }}" class="inline-block bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-bold px-6 py-3 rounded-lg transition">
                View All Items
            </a>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($menuItems->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $menuItems->links() }}
    </div>
    @endif
</div>
@endsection
