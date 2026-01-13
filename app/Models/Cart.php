<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'sales_user_id',
        'product_id',
        'quantity',
        'price',
        'making_charges',
    ];

    // Cart item belongs to product
    public function product()
    {
        return $this->belongsTo(JewelleryProduct::class, 'product_id');
    }

    // Cart belongs to sales user
    public function user()
    {
        return $this->belongsTo(User::class, 'sales_user_id');
    }
}
