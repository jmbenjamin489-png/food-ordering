@extends('layouts.admin')

@section('title', 'Daily Sales Report')

@section('page-title', 'Daily Sales Report')

@section('content')
<!-- Date Selector -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.daily') }}" class="flex gap-4 items-end">
        <div class="flex-1">
            <label class="block text-dark font-bold mb-2">Select Date</label>
            <input type="date" name="date" 
                   value="{{ request('date', date('Y-m-d')) }}"
                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">
        </div>
        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            Generate Report
        </button>
        <a href="{{ route('admin.reports.daily') }}" class="bg-dark text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            Today
        </a>
    </form>
</div>

<!-- Report Header -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
    <div class="text-center">
        <h2 class="text-3xl font-bold text-dark mb-2">Daily Sales Report</h2>
        <p class="text-gray-600">{{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('l, F d, Y') : \Carbon\Carbon::today()->format('l, F d, Y') }}</p>
    </div>
</div>

<!-- Key Metrics -->
<div class="grid md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-sm">Total Revenue</p>
            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-primary">${{ number_format($report['total_revenue'] ?? 0, 2) }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-sm">Orders</p>
            <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-dark">{{ $report['total_orders'] ?? 0 }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-sm">Average Order</p>
            <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-dark">${{ number_format($report['average_order'] ?? 0, 2) }}</p>
    </div>

    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-sm">Items Sold</p>
            <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-dark">{{ $report['items_sold'] ?? 0 }}</p>
    </div>
</div>

<!-- Sales Breakdown -->
<div class="grid md:grid-cols-2 gap-6 mb-6">
    <!-- By Payment Method -->
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <h3 class="text-xl font-bold text-dark mb-4">Sales by Payment Method</h3>
        <div class="space-y-3">
            @foreach($report['payment_methods'] ?? [] as $method => $data)
            <div class="pb-3 border-b border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-dark">{{ ucfirst($method) }}</span>
                    <span class="font-bold text-primary">${{ number_format($data['amount'], 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $data['count'] }} orders</span>
                    <span>{{ number_format($data['percentage'], 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- By Status -->
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <h3 class="text-xl font-bold text-dark mb-4">Orders by Status</h3>
        <div class="space-y-3">
            @foreach($report['order_statuses'] ?? [] as $status => $count)
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <span class="font-semibold text-dark">{{ ucfirst($status) }}</span>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ $count }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top Selling Items -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-dark mb-4">Top Selling Items</h3>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr class="border-b-2 border-gray-200">
                <th class="text-left py-3 px-4 text-dark">Rank</th>
                <th class="text-left py-3 px-4 text-dark">Item</th>
                <th class="text-left py-3 px-4 text-dark">Quantity Sold</th>
                <th class="text-left py-3 px-4 text-dark">Revenue</th>
                <th class="text-left py-3 px-4 text-dark">% of Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['top_items'] ?? [] as $index => $item)
            <tr class="border-b border-gray-200">
                <td class="py-3 px-4 font-bold text-dark">{{ $index + 1 }}</td>
                <td class="py-3 px-4 font-semibold text-dark">{{ $item->name }}</td>
                <td class="py-3 px-4">{{ $item->quantity }}</td>
                <td class="py-3 px-4 font-semibold text-primary">${{ number_format($item->revenue, 2) }}</td>
                <td class="py-3 px-4 text-gray-600">{{ number_format($item->percentage, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Export Options -->
<div class="bg-white border-2 border-gray-200 rounded-lg p-6">
    <h3 class="text-xl font-bold text-dark mb-4">Export Report</h3>
    <div class="flex gap-4">
        <button onclick="window.print()" class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            Print Report
        </button>
        <a href="{{ route('admin.reports.export', ['date' => request('date')]) }}" class="bg-dark text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
            Export to CSV
        </a>
    </div>
</div>
@endsection
