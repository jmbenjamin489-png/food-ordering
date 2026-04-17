@extends('layouts.app')

@section('title', 'Shopping Cart - JOLITS')

@section('content')
<div class="bg-dark text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold">Shopping Cart</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    @if(session('cart') && count(session('cart')) > 0)
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="md:col-span-2">
            @foreach(session('cart') as $id => $details)
            <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-4 mb-4 flex items-center gap-4">
                @if($details['image'])
                <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" class="w-24 h-24 object-cover rounded">
                @else
                <div class="w-24 h-24 bg-gray-700 rounded flex items-center justify-center">
                    <span class="text-gray-400 text-xs">No Image</span>
                </div>
                @endif

                <div class="flex-1">
                    <h3 class="font-bold text-lg text-white">{{ $details['name'] }}</h3>
                    <p class="text-cyan-400 font-semibold">${{ number_format($details['price'], 2) }} each</p>
                </div>

                <div class="flex items-center gap-4">
                    <form action="{{ route('customer.cart.update') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" 
                               class="w-20 px-2 py-1 bg-gray-700 border-2 border-gray-600 text-white rounded focus:border-gray-500 focus:outline-none">
                        <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded transition text-sm">
                            Update
                        </button>
                    </form>

                    <form action="{{ route('customer.cart.remove', $id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="text-right">
                    <p class="font-bold text-white">${{ number_format($details['price'] * $details['quantity'], 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="md:col-span-1">
            <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-6 sticky top-4">
                <h3 class="text-2xl font-bold mb-4 text-white">Order Summary</h3>
                
                @php
                    $total = 0;
                    foreach(session('cart') as $details) {
                        $total += $details['price'] * $details['quantity'];
                    }
                @endphp

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-400">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>Delivery Fee</span>
                        <span>$5.00</span>
                    </div>
                    <div class="border-t-2 border-gray-700 pt-3 flex justify-between font-bold text-lg text-white">
                        <span>Total</span>
                        <span class="text-cyan-400">${{ number_format($total + 5, 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('customer.checkout') }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center px-6 py-3 rounded-lg font-semibold transition">
                    Proceed to Checkout
                </a>

                <a href="{{ route('customer.menu') }}" class="block w-full text-center text-white px-6 py-3 rounded-lg mt-3 border-2 border-gray-700 hover:border-gray-600 transition">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-24 h-24 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h2 class="text-2xl font-bold text-white mb-2">Your cart is empty</h2>
        <p class="text-gray-400 mb-6">Add some delicious items to your cart</p>
        <a href="{{ route('customer.menu') }}" class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition">
            Browse Menu
        </a>
    </div>
    @endif
</div>
@endsection
