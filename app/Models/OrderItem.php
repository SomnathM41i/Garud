<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'cost_price',
        'making_charges',
    ];

    // Item belongs to order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Item belongs to product
    public function product()
    {
        return $this->belongsTo(JewelleryProduct::class, 'product_id');
    }

    // Profit for this item (helper)
    public function getProfitAttribute()
    {
        return (($this->price - $this->cost_price) * $this->quantity);
    }
}
