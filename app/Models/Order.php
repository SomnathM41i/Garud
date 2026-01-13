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

    // Order belongs to customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Order has many order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Order has one payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
