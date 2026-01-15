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
        $orders = Order::with('items')
            ->where('status', 'completed')
            ->get();

        // Total revenue (what customer paid)
        $totalRevenue = $orders->sum('total_amount');

        // Real profit (calculated from OrderItem model)
        $totalProfit = $orders->sum(function ($order) {
            return $order->profit;
        });

        // Real cost (product cost + handling cost)
        $totalCost = $orders->sum(function ($order) {
            return $order->total_cost;
        });

        return view('admin.reports.profit_loss', compact(
            'orders',
            'totalRevenue',
            'totalCost',
            'totalProfit'
        ));
    }
}
