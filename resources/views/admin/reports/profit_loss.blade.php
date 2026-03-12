@extends('layouts.admin')

@section('title', 'Profit & Loss Report')

@section('content')

<div class="page-header mb-4">
    <h1 class="page-title">Profit & Loss Report</h1>
</div>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-line"></i> Jewellery Profit Report
        </h3>
    </div>

<div class="card-body">

{{-- ================= SUMMARY ================= --}}

<div class="row mb-4">

    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-title">Total Revenue</div>
            <div class="stat-value">
                ₹{{ number_format($totalRevenue,2) }}
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-title">Profit (Cash)</div>
            <div class="stat-value">
                ₹{{ number_format($totalProfitCash,2) }}
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-title">Profit (Gold)</div>
            <div class="stat-value">
                {{ number_format($totalProfitGold,3) }} g
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-title">Total Gold Sold</div>
            <div class="stat-value">
                {{ number_format($totalGoldSold,3) }} g
            </div>
        </div>
    </div>

</div>


{{-- ================= ORDER ITEM REPORT ================= --}}

<div class="table-responsive">

<table class="table table-bordered table-striped table-hover">

<thead class="table-light">

<tr>

<th>Invoice</th>
<th>Customer</th>
<th>Product</th>
<!-- <th>Qty</th> -->

<th>Gross Wt</th>
<!-- <th>Net Wt</th> -->
<th>Fine Gold</th>

<th>Gold Rate</th>
<th>Gold Value</th>
<th>Making Charge</th>

<th>Selling Price</th>

<th>Profit Cash</th>
<th>Profit Gold</th>

<th>Date</th>

</tr>

</thead>


<tbody>

@forelse($orders as $order)

    @foreach($order->items as $item)

    <tr>

        <td>
            {{ $order->invoice_number }}
        </td>

        <td>
            {{ $order->customer->name ?? '-' }}
        </td>

        <td>
            {{ $item->product->product_name ?? '-' }}
        </td>

        <!-- <td>
            {{ $item->quantity }}
        </td> -->

        {{-- GOLD DETAILS --}}
        <td>
            {{ number_format($item->gross_weight,3) }} g
        </td>

        <!-- <td>
            {{ number_format($item->net_weight,3) }} g
        </td> -->

        <td>
            {{ number_format($item->fine_gold_weight,3) }} g
        </td>


        {{-- GOLD CALCULATION --}}
        <td>
            ₹{{ number_format($item->gold_rate,2) }}
        </td>

        <td>
            ₹{{ number_format($item->gold_value,2) }}
        </td>

        <td>
            ₹{{ number_format($item->making_charge,2) }}
        </td>


        {{-- SELLING --}}
        <td>
            ₹{{ number_format($item->selling_price,2) }}
        </td>


        {{-- PROFIT --}}
        <td class="text-success fw-bold">
            ₹{{ number_format($item->profit_cash,2) }}
        </td>

        <td class="text-warning fw-bold">
            {{ number_format($item->profit_gold,3) }} g
        </td>


        <td>
            {{ $order->created_at->format('d M Y') }}
        </td>

    </tr>

    @endforeach

@empty

<tr>
<td colspan="14" class="text-center text-muted">
No completed orders found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>


{{-- ================= PROFIT CALCULATION NOTE ================= --}}

<div class="alert alert-light mt-4">

<strong>Profit Calculation:</strong>

<ul class="mb-0">

<li>Gold Price = <b>Fine Gold Weight × Gold Rate</b></li>

<li>Total Cost = <b>Gold Price + Making Charge</b></li>

<li>Profit (Cash) = <b>Selling Price − Total Cost</b></li>

<li>Profit (Gold) = <b>Fine Gold Weight  − Buying Gold Weight</b></li>

</ul>

</div>


</div>

</div>

@endsection