@extends('layouts.admin')

@section('title', 'Daily Order History')

@section('page-title', 'Daily Order History')

@section('content')
<!-- Date Filter -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
    <form method="GET" action="{{ route('admin.orders.daily-history') }}" class="flex gap-4 items-end">
        <div class="flex-1">
            <label class="block text-dark font-bold mb-2">Select Date</label>
            <input type="date" name="date" 
                   value="{{ request('date', date('Y-m-d')) }}"
                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">
        </div>
        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            View History
        </button>
        <a href="{{ route('admin.orders.daily-history') }}" class="bg-dark text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            Today
        </a>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Total Orders</p>
        <p class="text-3xl font-bold text-dark">{{ $stats['total_orders'] ?? 0 }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Total Revenue</p>
        <p class="text-3xl font-bold text-primary">${{ number_format($stats['total_sales'] ?? 0, 2) }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Completed</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] ?? 0 }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Cancelled</p>
        <p class="text-3xl font-bold text-red-600">{{ $stats['cancelled'] ?? 0 }}</p>
    </div>
</div>

<!-- Orders by Hour Chart -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
    <h2 class="text-2xl font-bold text-dark mb-4">Orders by Hour</h2>
    <div class="grid grid-cols-24 gap-1">
        @for($hour = 0; $hour < 24; $hour++)
            @php
                $count = $ordersByHour[$hour] ?? 0;
                $maxCount = max(array_values($ordersByHour ?? [1]));
                $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
            @endphp
            <div class="text-center">
                <div class="h-32 flex items-end justify-center">
                    <div class="w-full bg-primary rounded-t" style="height: {{ $height }}%" title="{{ $count }} orders"></div>
                </div>
                <div class="text-xs text-gray-600 mt-1">{{ sprintf('%02d', $hour) }}</div>
            </div>
        @endfor
    </div>
</div>

<!-- Detailed Order List -->
<div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden">
    <div class="p-6 border-b-2 border-gray-200 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-dark">Order Details - {{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('M d, Y') : 'Today' }}</h2>
        <button onclick="window.print()" class="bg-dark text-white px-4 py-2 rounded-lg hover:opacity-90 transition">
            Print Report
        </button>
    </div>

    <table class="w-full">
        <thead class="bg-gray-50">
            <tr class="border-b-2 border-gray-200">
                <th class="text-left py-4 px-6 text-dark font-bold">Time</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Order ID</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Customer</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Items</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Total</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Payment</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Status</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders ?? [] as $order)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="py-4 px-6 text-sm text-gray-600">{{ $order->created_at->format('h:i A') }}</td>
                <td class="py-4 px-6 font-semibold text-dark">#{{ $order->id }}</td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-dark">{{ $order->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->phone }}</p>
                </td>
                <td class="py-4 px-6 text-dark">{{ $order->orderItems->count() }}</td>
                <td class="py-4 px-6 font-semibold text-primary">${{ number_format($order->total_amount, 2) }}</td>
                <td class="py-4 px-6 text-sm text-dark">{{ ucfirst($order->payment_method) }}</td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500">No orders found for this date</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Order Summary by Status -->
<div class="grid md:grid-cols-2 gap-6 mt-6">
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <h3 class="text-xl font-bold text-dark mb-4">Payment Methods</h3>
        <div class="space-y-3">
            @foreach($paymentMethods ?? [] as $method => $data)
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <span class="font-semibold text-dark">{{ ucfirst($method) }}</span>
                <div class="text-right">
                    <p class="font-bold text-primary">${{ number_format($data['total'], 2) }}</p>
                    <p class="text-sm text-gray-600">{{ $data['count'] }} orders</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <h3 class="text-xl font-bold text-dark mb-4">Top Items Today</h3>
        <div class="space-y-3">
            @foreach($topItems ?? [] as $item)
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <div>
                    <p class="font-semibold text-dark">{{ $item->name }}</p>
                    <p class="text-sm text-gray-600">{{ $item->quantity }} sold</p>
                </div>
                <p class="font-bold text-primary">${{ number_format($item->revenue, 2) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
