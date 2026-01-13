<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    // A customer can have many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
