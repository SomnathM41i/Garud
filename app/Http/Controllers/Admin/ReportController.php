<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;

class ReportController extends Controller
{
    /**
     * Profit & Loss Report - Gold and Silver
     */
    public function profitLoss(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GOLD ITEMS
        |--------------------------------------------------------------------------
        */

        $goldItems = OrderItem::with([
            'order.customer',
            'product'
        ])
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->whereHas('product', function ($query) {
                $query->whereRaw('LOWER(metal_type) = ?', ['gold']);
            })
            ->whereHas('order')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SILVER ITEMS
        |--------------------------------------------------------------------------
        */

        $silverItems = OrderItem::with([
            'order.customer',
            'product'
        ])
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->whereHas('product', function ($query) {
                $query->whereRaw('LOWER(metal_type) = ?', ['silver']);
            })
            ->whereHas('order')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GOLD TOTALS
        |--------------------------------------------------------------------------
        */

        $totalGoldRevenue = $goldItems->sum('selling_price');

        $totalGoldProfitCash = $goldItems->sum(function ($item) {
            return $item->profit_gold * $item->gold_rate;
        });

        $totalGoldProfitWeight = $goldItems->sum('profit_gold');

        $totalGoldSold = $goldItems->sum(function ($item) {
            return $item->fine_gold_weight * $item->quantity;
        });

        /*
        |--------------------------------------------------------------------------
        | SILVER TOTALS
        |--------------------------------------------------------------------------
        */

        $totalSilverRevenue = $silverItems->sum('selling_price');

        $totalSilverProfitCash = $silverItems->sum(function ($item) {
            return $item->profit_gold * $item->gold_rate;
        });

        $totalSilverProfitWeight = $silverItems->sum('profit_gold');

        $totalSilverSold = $silverItems->sum(function ($item) {
            return $item->net_weight * $item->quantity;
        });

        /*
        |--------------------------------------------------------------------------
        | COMBINED TOTALS
        |--------------------------------------------------------------------------
        */

        $totalRevenue = $totalGoldRevenue + $totalSilverRevenue;

        $totalProfitCash = $totalGoldProfitCash + $totalSilverProfitCash;

        return view('admin.reports.profit_loss', compact(
            'goldItems',
            'silverItems',

            'totalGoldRevenue',
            'totalGoldProfitCash',
            'totalGoldProfitWeight',
            'totalGoldSold',

            'totalSilverRevenue',
            'totalSilverProfitCash',
            'totalSilverProfitWeight',
            'totalSilverSold',

            'totalRevenue',
            'totalProfitCash'
        ));
    }
}
