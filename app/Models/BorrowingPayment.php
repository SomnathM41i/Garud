<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingPayment extends Model
{
    protected $fillable = [
        'borrowing_id',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}