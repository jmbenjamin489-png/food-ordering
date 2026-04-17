<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'subtotal', 'tax', 'delivery_fee', 'total_amount',
        'status', 'payment_method', 'payment_status', 'delivery_address',
        'customer_phone', 'notes', 'confirmed_at', 'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->orderItems();
    }


    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'processing' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing'], true);
    }

    public function updateStatus(string $status): bool
    {
        $this->status = $status;
        if ($status === 'processing' && !$this->confirmed_at) {
            $this->confirmed_at = now();
        }
        if ($status === 'completed' && !$this->delivered_at) {
            $this->delivered_at = now();
        }
        return $this->save();
    }

    public function calculateTotals(): bool
    {
        $this->loadMissing('orderItems');

        $subtotal = (float) $this->orderItems->sum('subtotal');
        $tax = $subtotal * 0.12;
        $deliveryFee = (float) ($this->delivery_fee ?? 50);
        $total = $subtotal + $tax + $deliveryFee;

        $this->subtotal = round($subtotal, 2);
        $this->tax = round($tax, 2);
        $this->total_amount = round($total, 2);

        return $this->save();
    }
}
