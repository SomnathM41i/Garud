<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'total_amount',
        'total_profit',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_profit' => 'decimal:2',
    ];

    /* =====================
       RELATIONSHIPS
    ===================== */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // NEW: borrowing relationship
    public function borrowing()
    {
        return $this->hasOne(Borrowing::class);
    }

    /* =====================
       HELPERS
    ===================== */

    public function getTotalCostAttribute()
    {
        return $this->items->sum(function ($item) {
            return ($item->cost_price + $item->handling_cost) * $item->quantity;
        });
    }

    public function getProfitAttribute()
    {
        return $this->items->sum->profit;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // NEW helper: check if this order is on borrowing
    public function isBorrowed(): bool
    {
        return $this->borrowing()->exists();
    }
}