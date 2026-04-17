@extends('layouts.app')

@section('title', 'Home - JOLITS')

@section('content')
<!-- Hero Section with Gradient -->
<div class="relative bg-gradient-to-r from-gray-950 via-gray-900 to-gray-950 text-white py-32 overflow-hidden">
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 text-center relative z-10">
        <h1 class="text-6xl md:text-7xl font-bold mb-6 leading-tight">Welcome to <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">JOLITS</span></h1>
        <p class="text-xl md:text-2xl mb-2 text-gray-200 font-light">Delicious food delivered to your doorstep</p>
        <p class="text-lg text-gray-400 mb-10">Experience the finest culinary delights with fast, reliable delivery</p>
        <a href="{{ route('customer.menu') }}" class="inline-block bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white px-10 py-4 rounded-lg text-lg font-semibold transition transform hover:scale-105 shadow-lg">
            📱 Order Now
        </a>
    </div>
</div>

<!-- Features Section -->
<div class="container mx-auto px-4 py-20">
    <h2 class="text-4xl font-bold text-center mb-16 text-white">Why Choose JOLITS?</h2>
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="group bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-8 hover:border-cyan-500 transition transform hover:scale-105 hover:shadow-xl">
            <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3 text-white text-center">Lightning Fast</h3>
            <p class="text-gray-300 text-center">Get your food delivered quickly with our optimized delivery network</p>
        </div>

        <!-- Feature 2 -->
        <div class="group bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-8 hover:border-green-500 transition transform hover:scale-105 hover:shadow-xl">
            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3 text-white text-center">Premium Quality</h3>
            <p class="text-gray-300 text-center">Fresh ingredients and professional chefs ensuring the best taste</p>
        </div>

        <!-- Feature 3 -->
        <div class="group bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-8 hover:border-purple-500 transition transform hover:scale-105 hover:shadow-xl">
            <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3 text-white text-center">Great Prices</h3>
            <p class="text-gray-300 text-center">Competitive pricing with regular discounts and special offers</p>
        </div>
    </div>
</div>

<!-- Popular Items -->
<div class="bg-gray-950 py-20 border-t border-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-bold text-white mb-2">Popular Items</h2>
                <p class="text-gray-400">Customer favorites that keep them coming back</p>
            </div>
            <a href="{{ route('customer.menu') }}" class="text-cyan-400 hover:text-cyan-300 transition font-semibold text-sm">
                View All →
            </a>
        </div>
        
        <div class="grid md:grid-cols-4 gap-6">
            @foreach($popularItems ?? [] as $item)
            <div class="group bg-gray-900 border border-gray-700 rounded-xl overflow-hidden hover:border-cyan-500 transition transform hover:scale-105 hover:shadow-2xl">
                <div class="relative overflow-hidden h-48">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-700 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition"></div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg mb-1 text-white group-hover:text-cyan-400 transition">{{ $item->name }}</h3>
                    <p class="text-gray-400 text-xs mb-4 line-clamp-2">{{ $item->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-cyan-400 font-bold text-2xl">${{ number_format($item->price, 2) }}</span>
                        <a href="{{ route('customer.menu.show', $item->id) }}" class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold transform hover:scale-105">
                            Order
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('customer.menu') }}" class="inline-block border-2 border-cyan-500 hover:bg-cyan-500 hover:bg-opacity-10 text-cyan-400 hover:text-cyan-300 px-12 py-4 rounded-lg transition font-semibold">
                Explore Full Menu
            </a>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-cyan-600 to-blue-600 py-16 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">Ready to Order?</h2>
        <p class="text-xl mb-8 opacity-90">Browse our full menu and place your order today</p>
        <a href="{{ route('customer.menu') }}" class="inline-block bg-white text-cyan-600 hover:bg-gray-100 px-10 py-4 rounded-lg font-bold transition transform hover:scale-105">
            Start Ordering Now
        </a>
    </div>
</div>
@endsection
