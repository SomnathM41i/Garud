<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JewelleryProduct extends Model
{
    protected $fillable = [
        'product_code',
        'product_name',
        'category_id',
        'metal_type',
        'purity',
        'weight',
        'making_charges',
        'price',
        'stock_quantity',
        'description',
        'status',
    ];

    // Relation to category
    public function category()
    {
        return $this->belongsTo(JewelleryCategory::class, 'category_id');
    }

    // Product can appear in many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Product can be in many carts
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
}
