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

        /* ===== WEIGHTS ===== */
        'gross_weight',
        'stone_weight',
        'net_weight',

        /* ===== PURITY ===== */
        'purity_percent',
        'fine_gold_weight',

        /* ===== COST ===== */
        'cost_price',
        'handling_cost',

        /* ===== MAKING ===== */
        'making_charge',

        'stock_quantity',
        'description',
        'status',
    ];

    /**
     * =====================
     * RELATIONSHIPS
     * =====================
     */

    public function category()
    {
        return $this->belongsTo(JewelleryCategory::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    /**
     * =====================
     * CALCULATED HELPERS
     * =====================
     */

    /**
     * Gold value (without making, GST, etc.)
     */
    public function getGoldValueAttribute()
    {
        return $this->fine_gold_weight * $this->getCurrentGoldRate();
    }

    /**
     * OPTIONAL:
     * Keep gold rate outside DB (recommended)
     */
    private function getCurrentGoldRate()
    {
        // Example: inject from config / service later
        return config('gold.rate', 0);
    }
}
