@extends('layouts.app')

@section('title', 'Order Placed - JOLITS')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gray-950">
    <div class="max-w-2xl w-full">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-green-900 rounded-full mx-auto mb-6 flex items-center justify-center">
                <svg class="w-12 h-12 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">Order Placed Successfully!</h1>
            <p class="text-lg text-gray-400">Thank you for your order</p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-8 mb-6">
            <div class="text-center mb-6">
                <p class="text-gray-400 mb-2">Your Order Number</p>
                <p class="text-4xl font-bold text-cyan-400">#{{ $order->id ?? '' }}</p>
            </div>

            <div class="border-t-2 border-gray-700 pt-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-400">Order Date:</span>
                    <span class="font-semibold text-white">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Amount:</span>
                    <span class="font-bold text-2xl text-cyan-400">${{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Payment Method:</span>
                    <span class="font-semibold text-white">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Delivery Address:</span>
                    <span class="font-semibold text-white text-right">{{ $order->delivery_address }}</span>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">What's Next?</h2>
            <div class="space-y-4">
                <div class="flex gap-4">
                    <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Order Confirmation</p>
                        <p class="text-sm text-gray-400">We've sent a confirmation email to your registered email address.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Preparing Your Order</p>
                        <p class="text-sm text-gray-400">Our kitchen is preparing your delicious meal with care.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Out for Delivery</p>
                        <p class="text-sm text-gray-400">Your order will be on its way to you shortly.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('customer.order-details', $order->id) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                View Order Details
            </a>
            <a href="{{ route('customer.menu') }}" class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                Continue Shopping
            </a>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('customer.home') }}" class="text-cyan-400 hover:text-cyan-300">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
