@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@php
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\JewelleryProduct;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Customers
|--------------------------------------------------------------------------
*/
$totalCustomers = Customer::count();
$lastMonthCustomers = Customer::whereMonth('created_at', now()->subMonth()->month)->count();
$customerTrend = $lastMonthCustomers ? round((($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100,1) : 0;

/*
|--------------------------------------------------------------------------
| Revenue
|--------------------------------------------------------------------------
*/
$totalRevenue = Order::where('status','completed')->sum('total_amount');
$lastMonthRevenue = Order::where('status','completed')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->sum('total_amount');
$revenueTrend = $lastMonthRevenue ? round((($totalRevenue - $lastMonthRevenue)/$lastMonthRevenue)*100,1) : 0;

/*
|--------------------------------------------------------------------------
| Profit
|--------------------------------------------------------------------------
*/
$totalProfit = Order::where('status','completed')->sum('total_profit');
$lastMonthProfit = Order::where('status','completed')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->sum('total_profit');
$profitTrend = $lastMonthProfit ? round((($totalProfit - $lastMonthProfit)/$lastMonthProfit)*100,1) : 0;

/*
|--------------------------------------------------------------------------
| Completed Orders
|--------------------------------------------------------------------------
*/
$completedOrders = Order::where('status','completed')->count();
$prevCompletedOrders = Order::where('status','completed')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->count();
$completedTrend = $prevCompletedOrders ? round((($completedOrders - $prevCompletedOrders)/$prevCompletedOrders)*100,1) : 0;

/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/
$totalProducts = JewelleryProduct::count();
$lastMonthProducts = JewelleryProduct::whereMonth('created_at', now()->subMonth()->month)->count();
$productTrend = $lastMonthProducts ? round((($totalProducts - $lastMonthProducts)/$lastMonthProducts)*100,1) : 0;

/*
|--------------------------------------------------------------------------
| Gold Report
|--------------------------------------------------------------------------
*/
$report = OrderItem::select(
    DB::raw('SUM(profit_cash) as total_profit_cash'),
    DB::raw('SUM(profit_gold) as total_profit_gold'),
    DB::raw('SUM(fine_gold_weight * quantity) as total_gold_sold')
)
->whereHas('order', function ($query) {
    $query->where('status','completed');
})
->first();

$totalProfitCash = $report->total_profit_cash ?? 0;
$totalProfitGold = $report->total_profit_gold ?? 0;
$totalGoldSold   = $report->total_gold_sold ?? 0;

@endphp


<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Dashboard Overview</h1>
</div>


<div class="stats-grid">

    {{-- Customers --}}
    <div class="stat-card primary">
        <div class="stat-header">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div class="stat-trend">
                <i class="fas fa-arrow-{{ $customerTrend >= 0 ? 'up':'down' }}"></i>
                {{ abs($customerTrend) }}%
            </div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Total Customers</div>
            <div class="stat-value">{{ $totalCustomers }}</div>
        </div>
    </div>


    {{-- Revenue --}}
    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-icon success"><i class="fas fa-rupee-sign"></i></div>
            <div class="stat-trend">
                <i class="fas fa-arrow-{{ $revenueTrend >= 0 ? 'up':'down' }}"></i>
                {{ abs($revenueTrend) }}%
            </div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Total Revenue</div>
            <div class="stat-value">₹{{ number_format($totalRevenue,2) }}</div>
        </div>
    </div>


    {{-- Total Profit --}}
    <div class="stat-card primary">
        <div class="stat-header">
            <div class="stat-icon primary"><i class="fas fa-chart-line"></i></div>
            <div class="stat-trend">
                <i class="fas fa-arrow-{{ $profitTrend >= 0 ? 'up':'down' }}"></i>
                {{ abs($profitTrend) }}%
            </div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Total Profit</div>
            <div class="stat-value">₹{{ number_format($totalProfit,2) }}</div>
        </div>
    </div>


    {{-- Completed Orders --}}
    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
            <div class="stat-trend">
                <i class="fas fa-arrow-{{ $completedTrend >= 0 ? 'up':'down' }}"></i>
                {{ abs($completedTrend) }}%
            </div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Completed Orders</div>
            <div class="stat-value">{{ $completedOrders }}</div>
        </div>
    </div>


    {{-- Products --}}
    <div class="stat-card danger">
        <div class="stat-header">
            <div class="stat-icon gold"><i class="fas fa-gem"></i></div>
            <div class="stat-trend">
                <i class="fas fa-arrow-{{ $productTrend >= 0 ? 'up':'down' }}"></i>
                {{ abs($productTrend) }}%
            </div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Jewelry Products</div>
            <div class="stat-value">{{ $totalProducts }}</div>
        </div>
    </div>


    {{-- Cash Profit --}}
    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-icon success"><i class="fas fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Cash Profit</div>
            <div class="stat-value">₹{{ number_format($totalProfitCash,2) }}</div>
        </div>
    </div>


    {{-- Gold Profit --}}
    <div class="stat-card gold">
        <div class="stat-header">
            <div class="stat-icon gold"><i class="fas fa-coins"></i></div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Gold Profit</div>
            <div class="stat-value">{{ number_format($totalProfitGold,3) }} gm</div>
        </div>
    </div>


    {{-- Gold Sold --}}
    <div class="stat-card warning">
        <div class="stat-header">
            <div class="stat-icon warning"><i class="fas fa-gem"></i></div>
        </div>
        <div class="stat-body">
            <div class="stat-title">Gold Sold</div>
            <div class="stat-value">{{ number_format($totalGoldSold,3) }} gm</div>
        </div>
    </div>

</div>

@endsection