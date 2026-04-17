<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        $todayOrders = Order::today()->count();
        $pendingOrders = Order::pending()->today()->count();
        $completedOrders = Order::completed()->today()->count();
        $processingOrders = Order::processing()->today()->count();
        $todayRevenue = Order::today()->sum('total_amount');

        return view('admin.orders.index', compact(
            'orders',
            'todayOrders',
            'pendingOrders',
            'completedOrders',
            'processingOrders',
            'todayRevenue'
        ));
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.menuItem');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,processing,cancelled',
        ]);

        $order->updateStatus($validated['status']);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function dailyHistory(Request $request)
    {
        $date = $request->has('date')
            ? Carbon::parse($request->date)
            : Carbon::today();

        $orders = Order::with('user', 'orderItems.menuItem')
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'total_sales' => $orders->sum('total_amount'),
            'pending' => $orders->where('status', 'pending')->count(),
            'completed' => $orders->where('status', 'completed')->count(),
            'processing' => $orders->where('status', 'processing')->count(),
            'cancelled' => $orders->where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.daily-history', compact('orders', 'date', 'stats'));
    }

 public function printReceipt(Order $order)
{
    $order->load('user', 'orderItems.menuItem');
    return view('admin.orders.receipt', compact('order'));
}

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

}
