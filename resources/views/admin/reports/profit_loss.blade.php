@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your jewelry store
            today.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-line"></i> Profit & Loss Report</h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card success">
                        <div class="stat-title">Total Revenue</div>
                        <div class="stat-value">₹{{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card warning">
                        <div class="stat-title">Total Cost</div>
                        <div class="stat-value">₹{{ number_format($totalCost, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card primary">
                        <div class="stat-title">Total Profit</div>
                        <div class="stat-value">₹{{ number_format($totalProfit, 2) }}</div>
                    </div>
                </div>
            </div>

            {{-- Optional: Detailed Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Cost</th>
                            <th>Profit</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $orderCost = 0;
                                foreach ($order->items as $item) {
                                    $orderCost += $item->cost_price * $item->quantity;
                                }
                                $orderProfit = $order->total_amount - $orderCost;
                            @endphp
                            <tr>
                                <td>{{ $order->invoice_number }}</td>
                                <td>{{ $order->customer->name ?? '-' }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>₹{{ number_format($orderCost, 2) }}</td>
                                <td>₹{{ number_format($orderProfit, 2) }}</td>
                                <td>{{ $order->created_at->format('d M, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection