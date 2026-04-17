@extends('layouts.app')

@section('title', 'My Orders - JOLITS')

@section('content')
<div class="bg-dark text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold">My Orders</h1>
        <p class="mt-2">Track and view your order history</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">

    <!-- Filter Tabs (UI only for now) -->
    <div class="mb-6 flex gap-4 border-b-2 border-gray-700">
        <button class="px-4 py-2 font-semibold text-cyan-400 border-b-2 border-cyan-400 -mb-0.5">
            All Orders
        </button>
        <button class="px-4 py-2 font-semibold text-gray-400 hover:text-cyan-400">
            Pending
        </button>
        <button class="px-4 py-2 font-semibold text-gray-400 hover:text-cyan-400">
            Completed
        </button>
    </div>

    @forelse($orders as $order)

        @php
            // SUPPORT BOTH relationship names safely
            $items = $order->items ?? $order->orderItems ?? collect();
        @endphp

        <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-6 mb-4 hover:border-gray-600 transition">

            <!-- Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-white">
                        Order #{{ $order->id }}
                    </h3>
                    <p class="text-sm text-gray-400">
                        {{ optional($order->created_at)->format('M d, Y - h:i A') }}
                    </p>
                </div>

                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($order->status === 'pending') bg-yellow-900 text-yellow-200 @endif
                    @if($order->status === 'processing') bg-blue-900 text-blue-200 @endif
                    @if($order->status === 'completed') bg-green-900 text-green-200 @endif
                    @if($order->status === 'cancelled') bg-red-900 text-red-200 @endif
                ">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <!-- Items Preview -->
            <div class="mb-4 space-y-3">
                @foreach($items->take(3) as $item)
                    @php
                        $menu = $item->menuItem ?? null;
                    @endphp

                    <div class="flex items-center gap-3">
                        @if($menu && $menu->image)
                            <img
                                src="{{ asset('storage/' . $menu->image) }}"
                                alt="{{ $menu->name }}"
                                class="w-12 h-12 object-cover rounded"
                            >
                        @else
                            <div class="w-12 h-12 bg-gray-700 rounded"></div>
                        @endif

                        <div class="flex-1">
                            <p class="font-semibold text-white">
                                {{ $menu->name ?? 'Item removed' }}
                            </p>
                            <p class="text-sm text-gray-400">
                                Quantity: {{ $item->quantity ?? 0 }}
                            </p>
                        </div>

                        <p class="font-semibold text-white">
                            ${{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 2) }}
                        </p>
                    </div>
                @endforeach

                @if($items->count() > 3)
                    <p class="text-sm text-gray-400 ml-15">
                        + {{ $items->count() - 3 }} more items
                    </p>
                @endif
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center pt-4 border-t-2 border-gray-700">
                <div>
                    <p class="text-gray-600 text-sm">Total Amount</p>
                    <p class="text-2xl font-bold text-primary">
                        ${{ number_format($order->total_amount ?? 0, 2) }}
                    </p>
                </div>

                <div class="flex gap-3">
                    <a
                        href="{{ route('customer.order-details', $order->id) }}"
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition"
                    >
                        View Details
                    </a>

                    @if($order->status === 'completed')
                        <button
                            class="bg-dark text-white px-6 py-2 rounded-lg hover:opacity-90 transition"
                        >
                            Reorder
                        </button>
                    @endif
                </div>
            </div>
        </div>

    @empty
        <div class="text-center py-12">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>

            <h2 class="text-2xl font-bold text-dark mb-2">No orders yet</h2>
            <p class="text-gray-600 mb-6">Start ordering your favorite food</p>

            <a
                href="{{ route('customer.menu') }}"
                class="inline-block bg-primary text-white px-8 py-3 rounded-lg hover:opacity-90 transition"
            >
                Browse Menu
            </a>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection
