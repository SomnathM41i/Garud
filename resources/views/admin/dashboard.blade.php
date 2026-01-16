@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard Overview</h1>
    </div>

    {{-- Statistics Cards --}}
    <div class="stats-grid">

        {{-- Total Customers --}}
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $totalCustomers = App\Models\Customer::count();
                        $lastMonthCustomers = App\Models\Customer::whereMonth('created_at', now()->subMonth()->month)->count();
                        $customerTrend = $lastMonthCustomers ? round((($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $customerTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($customerTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Total Customers</div>
                <div class="stat-value">{{ $totalCustomers }}</div>
            </div>
            <div class="stat-footer">Compared to last month</div>
        </div>

        {{-- Total Revenue --}}
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $totalRevenue = App\Models\Order::where('status', 'completed')->sum('total_amount');
                        $lastMonthRevenue = App\Models\Order::where('status', 'completed')
                            ->whereMonth('created_at', now()->subMonth()->month)
                            ->sum('total_amount');
                        $revenueTrend = $lastMonthRevenue ? round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $revenueTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($revenueTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Total Revenue</div>
                <div class="stat-value">₹{{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="stat-footer">This month's earnings</div>
        </div>

        {{-- Total Profit --}}
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $totalProfit = App\Models\Order::where('status', 'completed')->sum('total_profit');
                        $lastMonthProfit = App\Models\Order::where('status', 'completed')
                            ->whereMonth('created_at', now()->subMonth()->month)
                            ->sum('total_profit');
                        $profitTrend = $lastMonthProfit ? round((($totalProfit - $lastMonthProfit) / $lastMonthProfit) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $profitTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($profitTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Total Profit</div>
                <div class="stat-value">₹{{ number_format($totalProfit, 2) }}</div>
            </div>
            <div class="stat-footer">This month's profit</div>
        </div>


        {{-- Completed Orders --}}
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $completedOrders = App\Models\Order::where('status', 'completed')->count();
                        $prevCompletedOrders = App\Models\Order::where('status', 'completed')
                            ->whereMonth('created_at', now()->subMonth()->month)
                            ->count();
                        $completedTrend = $prevCompletedOrders ? round((($completedOrders - $prevCompletedOrders) / $prevCompletedOrders) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $completedTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($completedTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Completed Orders</div>
                <div class="stat-value">{{ $completedOrders }}</div>
            </div>
            <div class="stat-footer">All good</div>
        </div>


        {{-- Jewelry Products --}}
        <div class="stat-card danger">
            <div class="stat-header">
                <div class="stat-icon gold">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $totalProducts = App\Models\JewelleryProduct::count();
                        $lastMonthProducts = App\Models\JewelleryProduct::whereMonth('created_at', now()->subMonth()->month)->count();
                        $productTrend = $lastMonthProducts ? round((($totalProducts - $lastMonthProducts) / $lastMonthProducts) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $productTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($productTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Jewelry Products</div>
                <div class="stat-value">{{ $totalProducts }}</div>
            </div>
            <div class="stat-footer">In stock items</div>
        </div>

    </div>

@endsection