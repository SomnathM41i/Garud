<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class ReportController extends Controller
{
    /**
     * Profit & Loss Report
     */
    public function profitLoss(Request $request)
    {
        $orders = Order::where('status', 'completed')
            ->latest()
            ->get();

        // Total revenue (stored)
        $totalRevenue = $orders->sum('total_amount');

        // Total profit (stored snapshot from cart)
        $totalProfit = $orders->sum('total_profit');

        // Total cost = Revenue - Profit
        $totalCost = $totalRevenue - $totalProfit;

        return view('admin.reports.profit_loss', compact(
            'orders',
            'totalRevenue',
            'totalCost',
            'totalProfit'
        ));
    }

}
