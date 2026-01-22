<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetalRate extends Model
{
    protected $table = 'metal_rates';

    protected $fillable = [
        'metal',
        'rate_per_gram',
        'rate_date',
    ];

    protected $casts = [
        'rate_date' => 'date',
    ];

    /**
     * Scope: get today's rate for a metal
     */
    public function scopeToday($query, $metal)
    {
        return $query
            ->whereRaw('LOWER(metal) = ?', [strtolower($metal)])
            ->whereDate('rate_date', now()->toDateString());
    }

}
