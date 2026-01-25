@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Metal Rates</h1>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-coins"></i> Metal Rates
            </h3>
            <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Rate
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Metal</th>
                            <th>Purity (%)</th>
                            <th>Rate per Gram (₹)</th>
                            <th>Rate Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rates as $key => $rate)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ ucfirst($rate->metal) }}</td>
                                <td>{{ $rate->purity_percent }}</td>
                                <td>₹{{ number_format($rate->rate_per_gram, 2) }}</td>
                                <td>{{ $rate->rate_date->format('d M, Y') }}</td>
                                <td class="d-flex gap-1">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.metal-rates.edit', $rate->id) }}"
                                        class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.metal-rates.destroy', $rate->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this rate?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No metal rates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection