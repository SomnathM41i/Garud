@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Borrowings</h2>
        <span class="badge bg-warning text-dark fs-6">
            Pending: {{ $borrowings->where('status', '!=', 'completed')->count() }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total (₹)</th>
                        <th>Paid (₹)</th>
                        <th>Remaining (₹)</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $b)
                    <tr>
                        <td>{{ $b->order->invoice_number ?? '-' }}</td>
                        <td>{{ $b->customer->name }}<br><small class="text-muted">{{ $b->customer->phone }}</small></td>
                        <td>{{ number_format($b->total_amount, 2) }}</td>
                        <td>{{ number_format($b->paid_amount, 2) }}</td>
                        <td class="{{ $b->remaining_amount > 0 ? 'text-danger fw-semibold' : '' }}">
                            {{ number_format($b->remaining_amount, 2) }}
                        </td>
                        <td>{{ $b->due_date ? $b->due_date->format('d M Y') : '-' }}</td>
                        <td>
                            @if($b->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($b->status === 'partial')
                                <span class="badge bg-warning text-dark">उधारी</span>
                            @else
                                <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.borrowings.show', $b) }}" class="btn btn-sm btn-outline-primary">
                                View / Pay
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No borrowings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection