<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'sales_user_id',
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

        /* ===== SELLING ===== */
        'selling_price',
        'total_profit',
    ];

    /**
     * =====================
     * RELATIONSHIPS
     * =====================
     */

    public function product()
    {
        return $this->belongsTo(JewelleryProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sales_user_id');
    }

    /**
     * =====================
     * CALCULATED HELPERS
     * =====================
     */

    /**
     * Total amount for this cart row
     */
    public function getSubtotalAttribute()
    {
        return $this->selling_price * $this->quantity;
    }

    /**
     * Total gold value for this cart row
     */
    public function getTotalGoldValueAttribute()
    {
        return $this->gold_value * $this->quantity;
    }
}
