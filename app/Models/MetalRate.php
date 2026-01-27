<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetalRate extends Model
{
    protected $table = 'metal_rates';

    protected $fillable = [
        'metal',
        'purity_percent',
        'rate_per_gram',
        'rate_date',
    ];

    protected $casts = [
        'rate_date' => 'date',
        'purity_percent' => 'float',
        'rate_per_gram' => 'float',
    ];

    /**
     * Scope: get today's rate for metal + purity
     */
    public function scopeToday($query, $metal, $purityPercent = null)
    {
        $query
            ->whereRaw('LOWER(metal) = ?', [strtolower($metal)])
            ->whereDate('rate_date', now()->toDateString());

        if ($purityPercent !== null) {
            $query->where('purity_percent', $purityPercent);
        }

        return $query;
    }

    public function scopeLatestByPurity($query, $metal, $purityPercent)
    {
        $latestDate = self::whereRaw('LOWER(metal) = ?', [strtolower($metal)])
            ->max('rate_date');

        return $query
            ->whereRaw('LOWER(metal) = ?', [strtolower($metal)])
            ->where('purity_percent', $purityPercent)
            ->whereDate('rate_date', $latestDate);
    }

}
