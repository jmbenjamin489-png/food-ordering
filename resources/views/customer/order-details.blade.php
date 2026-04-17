@extends('layouts.app')

@section('title', 'Order Details - JOLITS')

@section('content')
<div class="bg-dark text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold">Order #{{ $order->id }}</h1>
        <p class="mt-2">
            Placed on {{ $order->created_at->format('M d, Y - h:i A') }}
        </p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid md:grid-cols-3 gap-8">

        {{-- ================= LEFT CONTENT ================= --}}
        <div class="md:col-span-2 space-y-6">

            {{-- ORDER STATUS --}}
            <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-4">Order Status</h2>

                <div class="text-center">
                    <span class="inline-block px-6 py-2 rounded-full text-sm font-semibold
                        {{ $order->status === 'pending' ? 'bg-yellow-900 text-yellow-200' : '' }}
                        {{ $order->status === 'processing' ? 'bg-blue-900 text-blue-200' : '' }}
                        {{ $order->status === 'completed' ? 'bg-green-900 text-green-200' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-900 text-red-200' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            {{-- ORDER ITEMS --}}
            <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-4">Order Items</h2>

                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-700">
                            @if($item->menuItem?->image)
                                <img src="{{ asset('storage/' . $item->menuItem->image) }}"
                                     class="w-20 h-20 object-cover rounded">
                            @else
                                <div class="w-20 h-20 bg-gray-700 rounded"></div>
                            @endif

                            <div class="flex-1">
                                <p class="font-bold text-white">
                                    {{ $item->menuItem?->name ?? 'Item removed' }}
                                </p>
                                <p class="text-sm text-gray-400">
                                    ₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                </p>
                            </div>

                            <p class="font-bold text-white">
                                ₱{{ number_format($item->price * $item->quantity, 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t border-gray-700 space-y-2">
                    <div class="flex justify-between text-gray-400">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>Delivery Fee</span>
                        <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold">
                        <span class="text-white">Total</span>
                        <span class="text-cyan-400">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- DELIVERY INFO --}}
            <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4 text-white">Delivery Information</h2>

                <p class="text-gray-300"><strong>Address:</strong> {{ $order->delivery_address }}</p>
                <p class="text-gray-300"><strong>Phone:</strong> {{ $order->customer_phone }}</p>

                @if($order->notes)
                    <p class="mt-2 text-gray-300">
                        <strong>Notes:</strong> {{ $order->notes }}
                    </p>
                @endif
            </div>

        </div>

        {{-- ================= RIGHT SIDEBAR ================= --}}
        <div class="md:col-span-1">
            <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-6 sticky top-4">

                <h3 class="text-2xl font-bold mb-4 text-white">Order Summary</h3>

                <p class="text-sm text-gray-400">Payment Method</p>
                <p class="font-semibold mb-3 text-white">
                    {{ ucfirst($order->payment_method) }}
                </p>

                <p class="text-sm text-gray-400">Payment Status</p>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                    {{ $order->payment_status === 'paid' ? 'bg-green-900 text-green-200' : 'bg-yellow-900 text-yellow-200' }}">
                    {{ ucfirst($order->payment_status) }}
                </span>

                {{-- CANCEL ORDER --}}
                @if(in_array($order->status, ['pending', 'processing']))
                    <form method="POST"
                          action="{{ route('customer.orders.cancel', $order->id) }}"
                          onsubmit="return confirm('Are you sure you want to cancel this order?')"
                          class="mt-6">
                        @csrf
                        <button type="submit"
                                class="w-full bg-red-900 hover:bg-red-800 text-red-200 px-6 py-3 rounded-lg font-semibold transition">
                            Cancel Order
                        </button>
                    </form>
                @endif

                <a href="{{ route('customer.orders') }}"
                   class="block mt-4 text-center bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Back to Orders
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
