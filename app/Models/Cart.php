<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'sales_user_id',
        'product_id',
        'quantity',

        'selling_price',   // PRICE CUSTOMER PAYS (per item)
    ];

    /**
     * Relationships
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

    public function getSubtotalAttribute()
    {
        return $this->selling_price * $this->quantity;
    }

}
