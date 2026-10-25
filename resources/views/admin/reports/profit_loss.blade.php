@extends('layouts.admin')

@section('title', 'Gold & Silver Profit Report')

@section('content')

<div class="page-header mb-4">
    <h1 class="page-title">Gold & Silver Profit & Loss Report</h1>
    <p class="text-muted mb-0">
        Separate profit and sales reports for Gold and Silver.
    </p>
</div>


{{-- ========================================================= --}}
{{-- COMBINED SUMMARY --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-line"></i>
            Overall Summary
        </h3>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">
                <div class="stat-card success">
                    <div class="stat-title">
                        Total Revenue
                    </div>

                    <div class="stat-value">
                        ₹{{ number_format($totalRevenue, 2) }}
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="stat-card primary">
                    <div class="stat-title">
                        Total Cash Profit
                    </div>

                    <div class="stat-value">
                        ₹{{ number_format($totalProfitCash, 2) }}
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- GOLD REPORT --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-gem"></i>
            Gold Profit Report
        </h3>
    </div>

    <div class="card-body">

        {{-- GOLD SUMMARY --}}

        <div class="row mb-4">

            <div class="col-md-3 mb-3">
                <div class="stat-card success">

                    <div class="stat-title">
                        Gold Revenue
                    </div>

                    <div class="stat-value">
                        ₹{{ number_format($totalGoldRevenue, 2) }}
                    </div>

                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="stat-card primary">

                    <div class="stat-title">
                        Gold Cash Profit
                    </div>

                    <div class="stat-value">
                        ₹{{ number_format($totalGoldProfitCash, 2) }}
                    </div>

                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="stat-card warning">

                    <div class="stat-title">
                        Gold Profit Weight
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalGoldProfitWeight, 3) }} g
                    </div>

                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="stat-card info">

                    <div class="stat-title">
                        Total Gold Sold
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalGoldSold, 3) }} g
                    </div>

                </div>
            </div>

        </div>


        {{-- GOLD TABLE --}}

        <div class="table-responsive">

            <table class="table table-bordered table-striped table-hover">

                <thead class="table-light">

                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Gross Weight</th>
                        <th>Net Weight</th>
                        <th>Fine Gold</th>
                        <th>Gold Rate</th>
                        <th>Gold Value</th>
                        <th>Making Charge</th>
                        <th>Selling Price</th>
                        <th>Profit Cash</th>
                        <th>Profit Weight</th>
                        <th>Date</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($goldItems as $item)

                    <tr>

                        <td>
                            {{ $item->order->invoice_number ?? '-' }}
                        </td>

                        <td>
                            {{ $item->order->customer->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->product->product_name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            {{ number_format($item->gross_weight, 3) }} g
                        </td>

                        <td>
                            {{ number_format($item->net_weight, 3) }} g
                        </td>

                        <td>
                            {{ number_format($item->fine_gold_weight, 3) }} g
                        </td>

                        <td>
                            ₹{{ number_format($item->gold_rate, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($item->gold_value, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($item->making_charge, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($item->selling_price, 2) }}
                        </td>

                        <td class="text-success fw-bold">
                            ₹{{ number_format($item->profit_gold * $item->gold_rate, 2) }}
                        </td>

                        <td class="text-warning fw-bold">
                            {{ number_format($item->profit_gold, 3) }} g
                        </td>

                        <td>
                            {{ $item->order && $item->order->created_at
                                    ? $item->order->created_at->format('d M Y')
                                    : '-' }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="14" class="text-center text-muted">
                            No completed Gold orders found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SILVER REPORT --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-coins"></i>
            Silver Profit Report
        </h3>
    </div>

    <div class="card-body">

        {{-- SILVER SUMMARY --}}

        <div class="row mb-4">

            <div class="col-md-3 mb-3">
                <div class="stat-card success">

                    <div class="stat-title">
                        Silver Revenue
                    </div>

                    <div class="stat-value">
                        ₹{{ number_format($totalSilverRevenue, 2) }}
                    </div>

                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="stat-card primary">

                    <div class="stat-title">
                        Silver Cash Profit
                    </div>

                    <div class="stat-value">
                        ₹{{ number_format($totalSilverProfitCash, 2) }}
                    </div>

                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="stat-card warning">

                    <div class="stat-title">
                        Silver Profit Weight
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalSilverProfitWeight, 3) }} g
                    </div>

                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="stat-card info">

                    <div class="stat-title">
                        Total Silver Sold
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalSilverSold, 3) }} g
                    </div>

                </div>
            </div>

        </div>


        {{-- SILVER TABLE --}}

        <div class="table-responsive">

            <table class="table table-bordered table-striped table-hover">

                <thead class="table-light">

                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Gross Weight</th>
                        <th>Net Weight</th>
                        <th>Purity</th>
                        <th>Rate</th>
                        <th>Metal Value</th>
                        <th>Making Charge</th>
                        <th>Selling Price</th>
                        <th>Profit Cash</th>
                        <th>Profit Weight</th>
                        <th>Date</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($silverItems as $item)

                    <tr>

                        <td>
                            {{ $item->order->invoice_number ?? '-' }}
                        </td>

                        <td>
                            {{ $item->order->customer->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->product->product_name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            {{ number_format($item->gross_weight, 3) }} g
                        </td>

                        <td>
                            {{ number_format($item->net_weight, 3) }} g
                        </td>

                        <td>
                            {{ number_format($item->purity_percent, 2) }}%
                        </td>

                        <td>
                            ₹{{ number_format($item->gold_rate, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($item->gold_value, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($item->making_charge, 2) }}
                        </td>

                        <td>
                            ₹{{ number_format($item->selling_price, 2) }}
                        </td>

                        <td class="text-success fw-bold">
                            ₹{{ number_format($item->profit_gold * $item->gold_rate, 2) }}
                        </td>

                        <td class="text-warning fw-bold">
                            {{ number_format($item->profit_gold, 3) }} g
                        </td>

                        <td>
                            {{ $item->order && $item->order->created_at
                                    ? $item->order->created_at->format('d M Y')
                                    : '-' }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="14" class="text-center text-muted">
                            No completed Silver orders found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- CALCULATION NOTES --}}
{{-- ========================================================= --}}

<div class="alert alert-light mt-4">

    <h5>
        <i class="fas fa-info-circle"></i>
        Report Information
    </h5>

    <ul class="mb-0">

        <li>
            Only orders with status <strong>Completed</strong> are included.
        </li>

        <li>
            Gold and Silver are classified using the product's
            <code>metal_type</code> column.
        </li>

        <li>
            Gold sold is calculated using
            <strong>Fine Gold Weight × Quantity</strong>.
        </li>

        <li>
            Silver sold is calculated using
            <strong>Net Weight × Quantity</strong>.
        </li>

        <li>
            Cash profit is calculated from
            <strong>Profit Weight × Metal Rate</strong>.
        </li>

        <li>
            Profit weight uses the existing
            <code>profit_gold</code> field. For Silver, this assumes
            the field stores the silver weight profit as well.
        </li>

    </ul>

</div>

@endsection