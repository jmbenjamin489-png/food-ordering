<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Carbon\Carbon;

class SalesReport extends Model
{
    protected $fillable = [
        'report_date',
        'total_orders',
        'total_sales',
        'total_tax',
        'total_delivery_fees',
        'completed_orders',
        'cancelled_orders',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public static function generateDailyReport(Carbon $date)
    {
        $completedOrders = Order::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        $cancelledOrders = Order::whereDate('created_at', $date)
            ->where('status', 'cancelled')
            ->count();

        return self::updateOrCreate(
            ['report_date' => $date->toDateString()],
            [
                'total_orders' => $completedOrders->count(),
                'completed_orders' => $completedOrders->count(),
                'cancelled_orders' => $cancelledOrders,
                'total_sales' => $completedOrders->sum('total_amount'),
                'total_tax' => $completedOrders->sum('tax'),
                'total_delivery_fees' => $completedOrders->sum('delivery_fee'),
            ]
        );
    }
}
