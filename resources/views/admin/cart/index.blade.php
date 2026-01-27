@extends('layouts.admin')

@section('title', 'Cart')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Jewellers Products Cart</h1>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-shopping-cart me-2"></i> Cart Items
            </h3>

            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add Products
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Qty</th>
                            <th>Gross Wt (g)</th>
                            <th>Net Wt (g)</th>
                            <th>Purity %</th>
                            <th>Fine Gold (g)</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($cartItems as $item)
                            <tr class="text-center">
                                <td class="text-start"><strong>{{ $item->product->product_name }}</strong></td>
                                <td>{{ $item->product->product_code }}</td>

                                <td>
                                    <form action="{{ route('admin.cart.update', $item->id) }}" method="POST"
                                        class="d-flex justify-content-center gap-1 mb-0">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="form-control form-control-sm text-center" style="width: 70px">
                                        <button class="btn btn-success btn-sm" type="submit" title="Update Quantity">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>

                                {{-- Multiply per-unit values by quantity --}}
                                <td>{{ number_format($item->gross_weight * $item->quantity, 2) }}</td>
                                <td>{{ number_format($item->net_weight * $item->quantity, 2) }}</td>
                                <td>{{ number_format($item->purity_percent, 2) }}%</td>
                                <td>{{ number_format($item->fine_gold_weight * $item->quantity, 2) }}</td>

                                {{-- Price is per unit --}}
                                <td>₹{{ number_format($item->selling_price, 2) }}</td>

                                {{-- Total = price * quantity --}}
                                <td><strong>₹{{ number_format($item->selling_price * $item->quantity, 2) }}</strong></td>

                                <td class="text-center">
                                    <form action="{{ route('admin.cart.destroy', $item->id) }}" method="POST" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Remove item from cart?')"
                                            title="Remove Item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Cart is empty
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($cartItems->count())
            @php
                $grandTotal = $cartItems->sum(fn($i) => $i->selling_price * $i->quantity);
                $totalGross = $cartItems->sum(fn($i) => $i->gross_weight * $i->quantity);
                $totalNet = $cartItems->sum(fn($i) => $i->net_weight * $i->quantity);
                $totalFine = $cartItems->sum(fn($i) => $i->fine_gold_weight * $i->quantity);
            @endphp

            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap">
                <strong class="mb-2 mb-md-0">
                    Gross: {{ number_format($totalGross, 2) }} g |
                    Net: {{ number_format($totalNet, 2) }} g |
                    Fine Gold: {{ number_format($totalFine, 2) }} g |
                    Grand Total: ₹{{ number_format($grandTotal, 2) }}
                </strong>

                <a href="{{ route('admin.orders.create') }}" class="btn btn-success">
                    <i class="fas fa-credit-card me-1"></i> Proceed to Checkout
                </a>
            </div>
        @endif
    </div>

@endsection