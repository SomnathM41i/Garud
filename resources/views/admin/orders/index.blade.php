@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your jewelry store
            today.</p>
    </div>



    {{-- Table Section --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-table"></i> Orders
            </h3>

            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Order
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Profit</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->invoice_number }}</strong>
                                </td>

                                <td>
                                    {{ $order->customer->name ?? '-' }} <br>
                                    <small class="text-muted">
                                        {{ $order->customer->phone ?? '' }}
                                    </small>
                                </td>

                                <td>
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </td>

                                {{-- ✅ Uses Order::getProfitAttribute() --}}
                                <td>
                                    ₹{{ number_format($order->profit, 2) }}
                                </td>

                                <td>
                                    @if($order->status === 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                <td class="text-end">
                                    {{-- View --}}
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-outline-primary btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Delete (only if not completed) --}}
                                    @if(!$order->isCompleted())
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this order?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>




@endsection