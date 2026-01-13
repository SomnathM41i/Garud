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
        $orders = Order::where('status', 'completed')->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalProfit = $orders->sum('total_profit');

        $totalCost = $orders->sum(function ($order) {
            return $order->total_amount - $order->total_profit;
        });
        //   dd($orders);
        return view('admin.reports.profit_loss', compact(
            'orders',
            'totalRevenue',
            'totalCost',
            'totalProfit'
        ));
    }
}
