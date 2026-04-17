<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\User;
use App\Models\SalesReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::today()->count();
        $todaySales = Order::today()->sum('total_amount');
        $pendingOrders = Order::pending()->count();
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $totalCustomers = User::customers()->count();
        $totalMenuItems = MenuItem::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $weeklyData[] = [
                'date' => $date->format('M d'),
                'sales' => Order::whereDate('created_at', $date)->sum('total_amount'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        $popularItems = MenuItem::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayOrders',
            'todaySales',
            'pendingOrders',
            'recentOrders',
            'totalCustomers',
            'totalMenuItems',
            'totalOrders',
            'totalRevenue',
            'weeklyData',
            'popularItems'
        ));
    }
}
