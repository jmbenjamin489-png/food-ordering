@extends('layouts.admin')

@section('title', 'Sales Reports')

@section('page-title', 'Sales Reports')

@section('content')
<!-- Report Filters -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
    <h2 class="text-2xl font-bold text-dark mb-4">Generate Report</h2>
    
    <form action="{{ route('admin.reports.generate') }}" method="POST" class="grid md:grid-cols-4 gap-4">
        @csrf
        
        <div>
            <label class="block text-dark font-semibold mb-2">Report Type</label>
            <select name="type" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>

        <div>
            <label class="block text-dark font-semibold mb-2">Start Date</label>
            <input type="date" name="start_date" 
                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none"
                   value="{{ date('Y-m-d') }}">
        </div>

        <div>
            <label class="block text-dark font-semibold mb-2">End Date</label>
            <input type="date" name="end_date" 
                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none"
                   value="{{ date('Y-m-d') }}">
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                Generate Report
            </button>
        </div>
    </form>
</div>

<!-- Summary Statistics -->
<div class="grid md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Total Revenue</p>
        <p class="text-3xl font-bold text-primary">${{ number_format($totalRevenue ?? 0, 2) }}</p>
        <p class="text-sm text-gray-600 mt-1">{{ $period ?? 'Today' }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Total Orders</p>
        <p class="text-3xl font-bold text-dark">{{ $totalOrders ?? 0 }}</p>
        <p class="text-sm text-gray-600 mt-1">{{ $period ?? 'Today' }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Average Order</p>
        <p class="text-3xl font-bold text-dark">${{ number_format($averageOrder ?? 0, 2) }}</p>
        <p class="text-sm text-gray-600 mt-1">Per order</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <p class="text-gray-600 text-sm mb-1">Completed Orders</p>
        <p class="text-3xl font-bold text-dark">{{ $completedOrders ?? 0 }}</p>
        <p class="text-sm text-gray-600 mt-1">{{ number_format($completionRate ?? 0, 1) }}% rate</p>
    </div>
</div>

<!-- Top Selling Items -->
<div class="grid md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-dark">Top Selling Items</h2>
        </div>
        
        <div class="space-y-3">
            @forelse($topItems ?? [] as $item)
            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    @if($item->image)
                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-12 h-12 object-cover rounded">
                    @else
                    <div class="w-12 h-12 bg-gray-200 rounded"></div>
                    @endif
                    <div>
                        <p class="font-semibold text-dark">{{ $item->name }}</p>
                        <p class="text-sm text-gray-600">{{ $item->total_quantity }} sold</p>
                    </div>
                </div>
                <p class="font-bold text-primary">${{ number_format($item->total_revenue, 2) }}</p>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">No data available</p>
            @endforelse
        </div>
    </div>

    <!-- Revenue by Category -->
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <h2 class="text-2xl font-bold text-dark mb-4">Revenue by Category</h2>
        
        <div class="space-y-3">
            @forelse($categoryRevenue ?? [] as $category)
            <div class="pb-3 border-b border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-dark">{{ $category->name }}</span>
                    <span class="font-bold text-primary">${{ number_format($category->revenue, 2) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $category->percentage }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">No data available</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-dark">Recent Transactions</h2>
        <a href="{{ route('admin.reports.export') }}" class="bg-dark text-white px-4 py-2 rounded-lg hover:opacity-90 transition">
            Export to CSV
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="text-left py-3 px-4 text-dark">Order ID</th>
                    <th class="text-left py-3 px-4 text-dark">Customer</th>
                    <th class="text-left py-3 px-4 text-dark">Date</th>
                    <th class="text-left py-3 px-4 text-dark">Items</th>
                    <th class="text-left py-3 px-4 text-dark">Total</th>
                    <th class="text-left py-3 px-4 text-dark">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions ?? [] as $order)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-4 font-semibold text-dark">#{{ $order->id }}</td>
                    <td class="py-3 px-4">{{ $order->user->name }}</td>
                    <td class="py-3 px-4 text-sm text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                    <td class="py-3 px-4">{{ $order->orderItems->count() }}</td>
                    <td class="py-3 px-4 font-semibold text-primary">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">No transactions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
