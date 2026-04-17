@extends('layouts.admin')

@section('title', 'Orders Management')

@section('page-title', 'Orders Management')

@section('content')
<div class="mb-6 flex gap-4">
    <select id="statusFilter" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-dark">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>
    <input type="date" id="dateFilter" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-dark">
</div>

<div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr class="border-b-2 border-gray-200">
                <th class="text-left py-4 px-6 text-dark font-bold">Order ID</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Customer</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Items</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Total</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Payment</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Status</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Date</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders ?? [] as $order)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="py-4 px-6 font-semibold text-dark">#{{ $order->id ?? 'N/A' }}</td>

                <td class="py-4 px-6">
                    <p class="font-semibold text-dark">{{ $order->user->name ?? 'Guest' }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->email ?? '-' }}</p>
                </td>

                <td class="py-4 px-6 text-dark">
                    {{ $order->items?->count() ?? 0 }} item{{ ($order->items?->count() ?? 0) !== 1 ? 's' : '' }}
                </td>

                <td class="py-4 px-6 font-semibold text-primary">
                    ${{ number_format($order->total ?? 0, 2) }}
                </td>

                <td class="py-4 px-6">
                    <span class="text-sm text-dark">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                </td>

                <td class="py-4 px-6">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        <select name="status" onchange="this.form.submit()"
                                class="px-3 py-1 rounded-full text-xs font-semibold border-2 text-dark
                                {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : '' }}
                                {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800 border-blue-300' : '' }}
                                {{ $order->status == 'completed' ? 'bg-green-100 text-green-800 border-green-300' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800 border-red-300' : '' }}">
                            <option value="pending" {{ ($order->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ ($order->status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ ($order->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ ($order->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </td>

                <td class="py-4 px-6 text-sm text-dark">
                    {{ optional($order->created_at)->format('M d, Y') ?? '-' }}<br>
                    <span class="text-xs text-dark">{{ optional($order->created_at)->format('h:i A') ?? '-' }}</span>
                </td>

                <td class="py-4 px-6">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:underline">View</a>
                        <a href="{{ route('admin.orders.print', $order->id) }}" class="text-dark hover:underline" target="_blank">Print</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500">No orders found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($orders) && $orders->hasPages())
<div class="mt-6">
    {{ $orders->links() }}
</div>
@endif
@endsection
