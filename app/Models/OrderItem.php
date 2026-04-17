<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'price',
        'subtotal',
        'special_instructions',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->subtotal = bcmul(
                (string) $item->price,
                (string) $item->quantity,
                2
            );
        });

        static::created(function ($item) {
            $item->order->load('orderItems');
            $item->order->calculateTotals();
        });

        static::updated(function ($item) {
            $item->order->load('orderItems');
            $item->order->calculateTotals();
        });

        static::deleted(function ($item) {
            $item->order->load('orderItems');
            $item->order->calculateTotals();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
