<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',

        /* ===== GOLD SNAPSHOT ===== */
        'gross_weight',
        'net_weight',
        'fine_gold_weight',
        'purity_percent',

        'gold_rate',
        'gold_value',
        'making_charge',

        /* ===== FINANCIAL SNAPSHOT ===== */
        'selling_price',
        'cost_price',
        'handling_cost',
    ];

    /**
     * =====================
     * RELATIONSHIPS
     * =====================
     */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(JewelleryProduct::class, 'product_id');
    }

    /**
     * =====================
     * CALCULATED HELPERS
     * =====================
     */

    /**
     * Cost per item (what it actually costs you)
     */
    public function getUnitCostAttribute()
    {
        return $this->cost_price + $this->handling_cost;
    }

    /**
     * Total cost for this order item
     */
    public function getTotalCostAttribute()
    {
        return $this->unit_cost * $this->quantity;
    }

    /**
     * Profit for this order item
     */
    public function getProfitAttribute()
    {
        return ($this->selling_price - $this->unit_cost) * $this->quantity;
    }

    /**
     * Total selling amount
     */
    public function getSubtotalAttribute()
    {
        return $this->selling_price * $this->quantity;
    }
}
