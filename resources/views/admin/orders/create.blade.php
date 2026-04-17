@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Create New Order</h2>

    @if($cartItems->isEmpty())
        <div class="alert alert-warning">Your cart is empty. <a href="{{ route('admin.cart.index') }}">Go to cart</a></div>
    @else
    <div class="card mb-4">
        <div class="card-header">Cart Items</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($cartItems as $item)
                        @php $grandTotal += $item->selling_price * $item->quantity; @endphp
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->selling_price, 2) }}</td>
                            <td>₹{{ number_format($item->selling_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Total</td>
                        <td>₹{{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.orders.store') }}">
        @csrf

        {{-- Customer details --}}
        <div class="card mb-4">
            <div class="card-header">Customer Details</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                           value="{{ old('customer_name') }}" required>
                    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Payment type --}}
        <div class="card mb-4">
            <div class="card-header">Payment</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Payment Type</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_type" id="type_full"
                                   value="full" {{ old('payment_type', 'full') === 'full' ? 'checked' : '' }}
                                   onchange="togglePaymentType()">
                            <label class="form-check-label" for="type_full">Full Payment</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_type" id="type_borrow"
                                   value="borrow" {{ old('payment_type') === 'borrow' ? 'checked' : '' }}
                                   onchange="togglePaymentType()">
                            <label class="form-check-label" for="type_borrow">Borrowing (Pay later)</label>
                        </div>
                    </div>
                </div>

                {{-- Full payment fields --}}
                <div id="section_full">
                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="upi">UPI</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                {{-- Borrowing fields --}}
                <div id="section_borrow" style="display:none;">
                    <div class="alert alert-warning mb-3 py-2">
                        Customer is taking the product now and will pay the remaining amount later.
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Upfront Amount Paid Now (₹)</label>
                            <input type="number" name="upfront_amount" class="form-control"
                                   value="{{ old('upfront_amount', 0) }}" min="0" step="0.01"
                                   placeholder="0 if nothing paid today">
                            <div class="form-text">Leave 0 if customer pays nothing today.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Payment Method (for upfront)</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="upi">UPI</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expected Full Payment By</label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                            <div class="form-text">Optional</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <input type="text" name="borrow_notes" class="form-control"
                                   value="{{ old('borrow_notes') }}"
                                   placeholder="e.g. Customer will pay after Diwali">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
    @endif
</div>

<script>
function togglePaymentType() {
    const isBorrow = document.getElementById('type_borrow').checked;
    document.getElementById('section_full').style.display  = isBorrow ? 'none' : '';
    document.getElementById('section_borrow').style.display = isBorrow ? '' : 'none';
}
// Run on page load to restore state after validation error
togglePaymentType();
</script>
@endsection