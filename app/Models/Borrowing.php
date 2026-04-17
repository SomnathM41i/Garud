<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'due_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'total_amount'     => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date'         => 'date',
    ];

    /* =====================
       RELATIONSHIPS
    ===================== */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(BorrowingPayment::class);
    }

    /* =====================
       HELPERS
    ===================== */

    /**
     * Add a repayment and update totals + status automatically.
     */
    public function addPayment(float $amount, string $method = 'cash', string $date = null, string $notes = null): BorrowingPayment
    {
        $payment = $this->payments()->create([
            'amount'         => $amount,
            'payment_method' => $method,
            'payment_date'   => $date ?? now()->toDateString(),
            'notes'          => $notes,
        ]);

        $this->paid_amount      += $amount;
        $this->remaining_amount  = max(0, $this->total_amount - $this->paid_amount);
        $this->status            = $this->remaining_amount <= 0 ? 'completed' : 'partial';
        $this->save();

        return $payment;
    }

    public function isFullyPaid(): bool
    {
        return $this->status === 'completed';
    }
}