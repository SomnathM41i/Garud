@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Jewellers Products Cart</h1>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shopping-cart"></i> Cart Items
            </h3>

            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Products
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($cartItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->product_name }}</strong>
                                </td>

                                <td>{{ $item->product->product_code }}</td>

                                <td>
                                    <form action="{{ route('admin.cart.update', $item->id) }}" method="POST"
                                        class="d-flex gap-1">
                                        @csrf
                                        @method('PUT')

                                        <input type="number"
                                            name="quantity"
                                            value="{{ $item->quantity }}"
                                            min="1"
                                            class="form-control form-control-sm"
                                            style="width: 70px">
                                </td>

                                <td>
                                    <input type="number"
                                        step="0.01"
                                        name="selling_price"
                                        value="{{ $item->selling_price }}"
                                        class="form-control form-control-sm"
                                        style="width: 90px">
                                </td>

                                <td>
                                    ₹{{ number_format($item->subtotal, 2) }}
                                </td>

                                <td class="d-flex gap-1">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    </form>

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
                                <td colspan="6" class="text-center text-muted py-4">
                                    Cart is empty
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($cartItems->count())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <strong>
                    Grand Total:
                    ₹{{ number_format($cartItems->sum('subtotal'), 2) }}
                </strong>

                <a href="{{ route('admin.orders.create') }}" class="btn btn-gold">
                    <i class="fas fa-credit-card"></i> Proceed to Checkout
                </a>
            </div>
        @endif
    </div>





@endsection