@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Borrowing Detail</h2>
        <a href="{{ route('admin.borrowings.index') }}" class="btn btn-sm btn-secondary">← Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Summary card --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Customer</h6>
                    <div class="fw-semibold">{{ $borrowing->customer->name }}</div>
                    <div class="text-muted small">{{ $borrowing->customer->phone }}</div>
                    <div class="mt-2 text-muted small">Order: {{ $borrowing->order->invoice_number }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Payment Summary</h6>
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <span class="fw-semibold">₹{{ number_format($borrowing->total_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-success">
                        <span>Paid</span>
                        <span>₹{{ number_format($borrowing->paid_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-danger fw-bold mt-1">
                        <span>Remaining</span>
                        <span>₹{{ number_format($borrowing->remaining_amount, 2) }}</span>
                    </div>
                    @if($borrowing->due_date)
                    <div class="mt-2 text-muted small">Due: {{ $borrowing->due_date->format('d M Y') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Status</h6>
                    @if($borrowing->status === 'completed')
                        <span class="badge bg-success fs-6">Fully Paid</span>
                    @elseif($borrowing->status === 'partial')
                        <span class="badge bg-warning text-dark fs-6">Partially Paid</span>
                    @else
                        <span class="badge bg-danger fs-6">Nothing Paid Yet</span>
                    @endif
                    @if($borrowing->notes)
                    <div class="mt-3 text-muted small">Note: {{ $borrowing->notes }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Progress bar --}}
    @php $pct = $borrowing->total_amount > 0 ? ($borrowing->paid_amount / $borrowing->total_amount) * 100 : 0; @endphp
    <div class="mb-4">
        <div class="d-flex justify-content-between small text-muted mb-1">
            <span>Payment progress</span>
            <span>{{ number_format($pct, 1) }}%</span>
        </div>
        <div class="progress" style="height:12px">
            <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
        </div>
    </div>

    {{-- Add repayment form (hidden if fully paid) --}}
    @if(!$borrowing->isFullyPaid())
    <div class="card mb-4">
        <div class="card-header fw-semibold">Record a Repayment</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.borrowings.addPayment', $borrowing) }}">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Amount (₹)</label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                               value="{{ old('amount') }}" min="1" max="{{ $borrowing->remaining_amount }}"
                               step="0.01" required placeholder="Max ₹{{ number_format($borrowing->remaining_amount, 2) }}">
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="upi">UPI</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="payment_date" class="form-control"
                               value="{{ old('payment_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Notes (optional)</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}"
                               placeholder="e.g. Cash via family">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Record Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Payment history --}}
    <div class="card">
        <div class="card-header fw-semibold">Payment History</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Date</th><th>Amount (₹)</th><th>Method</th><th>Notes</th></tr>
                </thead>
                <tbody>
                    @forelse($borrowing->payments->sortBy('payment_date') as $i => $pay)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $pay->payment_date->format('d M Y') }}</td>
                        <td class="fw-semibold">{{ number_format($pay->amount, 2) }}</td>
                        <td>{{ ucfirst($pay->payment_method) }}</td>
                        <td class="text-muted">{{ $pay->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No payments recorded yet.</td></tr>
                    @endforelse
                </tbody>
                @if($borrowing->payments->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Total Paid</td>
                        <td class="fw-bold text-success">₹{{ number_format($borrowing->paid_amount, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection