@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Invoice Details</h1>
    </div>

    {{-- Forms Section --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-file-invoice"></i> Invoice
            </h3>

            <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-print"></i> Print
            </button>
        </div>

        <div class="card-body">

            {{-- ================= INVOICE INFO ================= --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5><strong>Invoice:</strong> {{ $order->invoice_number }}</h5>
                    <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p>
                        <strong>Status:</strong>
                        <span class="badge badge-success">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>

                <div class="col-md-6 text-end">
                    <h5><strong>Customer</strong></h5>
                    <p>{{ $order->customer->name }}</p>
                    <p>{{ $order->customer->phone }}</p>
                </div>
            </div>

            {{-- ================= ITEMS ================= --}}
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price (Per Item)</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $key => $item)
                            @php
                                $subtotal = $item->selling_price * $item->quantity;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->product_name }}</strong><br>
                                    <small>{{ $item->product->product_code }}</small>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    ₹{{ number_format($item->selling_price, 2) }}
                                </td>
                                <td>
                                    <strong>
                                        ₹{{ number_format($subtotal, 2) }}
                                    </strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ================= TOTALS ================= --}}
            <div class="row">
                <div class="col-md-6">
                    <p>
                        <strong>Payment Method:</strong>
                        {{ strtoupper($order->payment->payment_method) }}
                    </p>
                    <p>
                        <strong>Payment Status:</strong>
                        {{ ucfirst($order->payment->status) }}
                    </p>
                </div>

                <div class="col-md-6 text-end">
                    <h4>Total Amount</h4>
                    <h3 class="text-success">
                        ₹{{ number_format($order->total_amount, 2) }}
                    </h3>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= ACTIONS ================= --}}
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>



@endsection