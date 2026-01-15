<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',

        'selling_price',          // SELLING PRICE (per item)
        'cost_price',     // PURCHASE COST (per item)
        'handling_cost',  // MAKING / LABOUR COST (per item)
    ];

    /**
     * Relationships
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
     * BUSINESS LOGIC
     * =====================
     */

    /**
     * Cost per item
     */
    // public function getUnitCostAttribute()
    // {
    //     return $this->cost_price + $this->handling_cost;
    // }

    /**
     * Total cost for this item
     */
    public function getTotalCostAttribute()
    {
        return $this->unit_cost * $this->quantity;
    }

    /**
     * Profit for this item
     */
    // public function getProfitAttribute()
    // {
    //     return ($this->price - $this->unit_cost) * $this->quantity;
    // }

    public function getUnitCostAttribute()
    {
        return $this->cost_price + $this->handling_cost;
    }

    public function getProfitAttribute()
    {
        return ($this->selling_price - $this->unit_cost) * $this->quantity;
    }
}
