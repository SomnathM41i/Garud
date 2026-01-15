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

    /**
     * Cast numeric values correctly
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_profit' => 'decimal:2',
    ];

    /**
     * Relationships
     */
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

    /**
     * =====================
     * BUSINESS HELPERS
     * =====================
     */

    /**
     * Total cost of this order
     * (product cost + handling cost)
     */
    public function getTotalCostAttribute()
    {
        return $this->items->sum(function ($item) {
            return ($item->cost_price + $item->handling_cost) * $item->quantity;
        });
    }

    /**
     * Real profit (safe fallback)
     * Uses stored profit if available
     */
    // public function getProfitAttribute()
    // {
    //     if ($this->total_profit > 0) {
    //         return $this->total_profit;
    //     }

    //     return $this->total_amount - $this->total_cost;
    // }

    /**
     * Check order state
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getProfitAttribute()
    {
        return $this->items->sum->profit;
    }
}
