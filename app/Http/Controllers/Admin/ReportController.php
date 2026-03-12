<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Profit & Loss Report
     */
    public function profitLoss(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GET COMPLETED ORDERS
        |--------------------------------------------------------------------------
        */

        $orders = Order::with('customer')
            ->where('status', 'completed')
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOTAL REVENUE
        |--------------------------------------------------------------------------
        */

        $totalRevenue = Order::where('status', 'completed')
            ->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | ORDER ITEM REPORT DATA
        |--------------------------------------------------------------------------
        */

        $report = OrderItem::select(
                DB::raw('SUM(profit_cash) as total_profit_cash'),
                DB::raw('SUM(profit_gold) as total_profit_gold'),
                DB::raw('SUM(fine_gold_weight * quantity) as total_gold_sold')
            )
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | FINAL TOTALS
        |--------------------------------------------------------------------------
        */

        $totalProfitCash = $report->total_profit_cash ?? 0;
        $totalProfitGold = $report->total_profit_gold ?? 0;
        $totalGoldSold   = $report->total_gold_sold ?? 0;
// dd($orders->toArray());
        return view('admin.reports.profit_loss', compact(
            'orders',
            'totalRevenue',
            'totalProfitCash',
            'totalProfitGold',
            'totalGoldSold'
        ));
    }
}