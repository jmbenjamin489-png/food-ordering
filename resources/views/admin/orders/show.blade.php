@extends('layouts.admin')

@section('title', 'Order Details')

@section('page-title', 'Order #' . ($order->id ?? ''))

@section('content')
<div class="grid md:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="md:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
            <h2 class="text-2xl font-bold text-dark mb-4">Order Items</h2>
            
            <div class="space-y-4">
                @foreach($order->orderItems ?? [] as $item)
                <div class="flex items-center gap-4 pb-4 border-b border-gray-200">
                    @if($item->menuItem->image)
                    <img src="{{ asset('storage/' . $item->menuItem->image) }}" alt="{{ $item->menuItem->name }}" class="w-20 h-20 object-cover rounded">
                    @else
                    <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-gray-400 text-xs">No Image</span>
                    </div>
                    @endif

                    <div class="flex-1">
                        <h3 class="font-bold text-dark">{{ $item->menuItem->name }}</h3>
                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                        <p class="text-primary font-semibold">${{ number_format($item->price, 2) }} each</p>
                    </div>

                    <div class="text-right">
                        <p class="font-bold text-dark">${{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 pt-6 border-t-2 border-gray-200">
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600 mb-3">
                    <span>Delivery Fee</span>
                    <span>${{ number_format($order->delivery_fee ?? 5, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-xl text-dark">
                    <span>Total</span>
                    <span class="text-primary">${{ number_format($order->total_amount ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
            <h2 class="text-2xl font-bold text-dark mb-4">Delivery Information</h2>
            
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Customer Name</p>
                    <p class="font-semibold text-dark">{{ $order->delivery_name ?? $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Phone Number</p>
                    <p class="font-semibold text-dark">{{ $order->customer_phone ?? $order->user->phone }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Delivery Address</p>
                    <p class="font-semibold text-dark">{{ $order->delivery_address }}</p>
                </div>
                @if($order->notes)
                <div>
                    <p class="text-gray-600 text-sm">Special Instructions</p>
                    <p class="font-semibold text-dark">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Summary Card -->
    <div class="md:col-span-1">
        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 sticky top-4">
            <h2 class="text-2xl font-bold text-dark mb-4">Order Summary</h2>
            
            <div class="space-y-3 mb-6">
                <div>
                    <p class="text-gray-600 text-sm">Order ID</p>
                    <p class="font-bold text-dark">#{{ $order->id }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Date & Time</p>
                    <p class="font-semibold text-dark">{{ $order->created_at->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $order->created_at->format('h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Payment Method</p>
                    <p class="font-semibold text-dark">{{ ucfirst($order->payment_method) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Current Status</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <!-- Update Status -->
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mb-4">
                @csrf
                <label class="block text-dark font-bold mb-2">Update Status</label>
                <select name="status" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none mb-3">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="w-full bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                    Update Status
                </button>
            </form>

            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="block w-full text-center bg-dark text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                Print Receipt
            </a>

            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Delete this order permanently?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                    Delete Order
                </button>
            </form>

            <a href="{{ route('admin.orders.index') }}" class="block w-full text-center text-dark px-6 py-2 rounded-lg mt-3 border-2 border-gray-300 hover:border-primary transition">
                Back to Orders
            </a>
        </div>
    </div>
</div>
@endsection
