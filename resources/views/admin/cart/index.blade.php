@extends('layouts.admin')

@section('title', 'Cart')

@section('content')

<div class="page-header mb-4">
    <h1 class="page-title">Jewellery Products Cart</h1>
</div>


{{-- ================= CART CARD ================= --}}
<div class="card shadow-sm border-0">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-shopping-cart me-2"></i>
            Cart Items
        </h5>

        <a href="{{ route('admin.products.index') }}" class="btn btn-dark btn-sm">
            <i class="fas fa-plus me-1"></i>
            Add Products
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0 text-center">

                <thead class="table-light">
                    <tr>
                        <th class="text-start">Product</th>
                        <th>Qty</th>
                        <th>Gross Wt</th>
                        <th>Purity</th>
                        <th>Fine Gold</th>
                        <th>Selling Price</th>
                        <th>Total</th>
                        <th width="90">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($cartItems as $item)

                    <tr>

                        {{-- PRODUCT --}}
                        <td class="text-start">

                            <div class="fw-semibold">
                                {{ $item->product->product_name }}
                            </div>

                            <small class="text-muted">
                                {{ $item->product->product_code }}
                            </small>

                        </td>

                        {{-- QTY --}}
                        <td>
                            <span class="badge bg-secondary">
                                {{ $item->quantity }}
                            </span>
                        </td>

                        {{-- GROSS --}}
                        <td>
                            {{ number_format($item->gross_weight * $item->quantity, 3) }} g
                        </td>

                        {{-- PURITY --}}
                        <td>
                            {{ number_format($item->purity_percent, 2) }} %
                        </td>

                        {{-- FINE GOLD --}}
                        <td class="fw-semibold">
                            {{ number_format($item->fine_gold_weight * $item->quantity, 3) }} g
                        </td>

                        {{-- SELLING PRICE --}}
                        <td>

                            <form action="{{ route('admin.cart.update', $item->id) }}"
                                  method="POST"
                                  class="d-flex justify-content-center align-items-center gap-2">

                                @csrf
                                @method('PUT')

                                <input type="number"
                                       name="selling_price"
                                       value="{{ $item->selling_price }}"
                                       step="0.01"
                                       min="0"
                                       class="form-control form-control-sm text-center"
                                       style="width:110px">

                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-save"></i>
                                </button>

                            </form>

                        </td>

                        {{-- TOTAL --}}
                        <td class="fw-bold text-success">
                            ₹{{ number_format($item->selling_price * $item->quantity, 2) }}
                        </td>

                        {{-- DELETE --}}
                        <td>

                            <form action="{{ route('admin.cart.destroy', $item->id) }}" method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Remove item from cart?')">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="py-5 text-muted">

                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>

                            <div>
                                Cart is empty
                            </div>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ================= ORDER SUMMARY ================= --}}
@if($cartItems->count() > 0)

<div class="row mt-4">

    {{-- SUMMARY --}}
    <div class="col-md-4">

        <div class="card shadow-sm border-0">

            <div class="card-header">
                <strong>Order Summary</strong>
            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Total Items</span>
                    <strong>{{ $cartItems->sum('quantity') }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Total Gold</span>
                    <strong>
                        {{ number_format($cartItems->sum(fn($i)=>$i->fine_gold_weight*$i->quantity),3) }} g
                    </strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5">
                    <strong>Total Amount</strong>
                    <strong class="text-success">
                        ₹{{ number_format($totalAmount,2) }}
                    </strong>
                </div>

            </div>

        </div>

    </div>



    {{-- CHECKOUT FORM --}}
    <div class="col-md-8">

        <div class="card shadow-sm border-0">

            <div class="card-header">
                <strong>Complete Order</strong>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.orders.store') }}" method="POST">

                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Customer Name
                            </label>

                            <input type="text"
                                   name="customer_name"
                                   class="form-control"
                                   required>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Phone Number
                            </label>

                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   required>

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Payment Method
                        </label>

                        <select name="payment_method"
                                class="form-control form-select"
                                required>

                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="card">Card</option>
                            <option value="other">Other</option>

                        </select>

                    </div>


                    <button class="btn btn-success px-4">

                        <i class="fas fa-check me-1"></i>

                        Complete Order

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endif

@endsection