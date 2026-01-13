@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your jewelry store
            today.</p>
    </div>



    {{-- Forms Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i> Create Order Form
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf

                {{-- ================= CUSTOMER DETAILS ================= --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i> Customer Details
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                Customer Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                    </div>
                </div>

                {{-- ================= CART ITEMS ================= --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shopping-cart"></i> Cart Items
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price (Per Item)</th>
                                        <th>Making Cost (Per Item)</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp

                                    @forelse($cartItems as $item)
                                        @php
                                            $subtotal = ($item->price + $item->making_charges) * $item->quantity;
                                            $grandTotal += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product->product_name }}</strong><br>
                                                <small>{{ $item->product->product_code }}</small>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>₹{{ number_format($item->price, 2) }}</td>
                                            <td>₹{{ number_format($item->making_charges, 2) }}</td>
                                            <td>
                                                <strong>₹{{ number_format($subtotal, 2) }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                Cart is empty
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total</th>
                                        <th>₹{{ number_format($grandTotal, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ================= PAYMENT ================= --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-credit-card"></i> Payment
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                Payment Method <span class="text-danger">*</span>
                            </label>
                            <select name="payment_method" class="form-control form-select" required>
                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ================= ACTIONS ================= --}}
                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Complete Order
                    </button>

                    <a href="{{ route('admin.cart.index') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </a>
                </div>

            </form>

        </div>
    </div>




@endsection