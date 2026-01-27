@extends('layouts.admin')

@section('title', 'Metal Rates')

@section('content')
    <!-- Page Header -->
    <div class="page-header mb-3">
        <h1 class="page-title">Metal Rates</h1>
    </div>

    {{-- Table Section --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h3 class="card-title mb-0">
                <i class="fas fa-coins me-2 text-warning"></i> Metal Rates
            </h3>

            <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> New Rate
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Metal</th>
                            <th>Purity (%)</th>
                            <th>Rate / Gram (₹)</th>
                            <th>Rate Date</th>
                            <th style="width: 130px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($rates as $key => $rate)
                            <tr class="text-center align-middle">
                                <td>{{ $key + 1 }}</td>

                                <td class="text-capitalize fw-semibold">
                                    {{ $rate->metal }}
                                </td>

                                <td>
                                    <form action="{{ route('admin.metal-rates.update', $rate->id) }}" method="POST"
                                        class="d-flex justify-content-center align-items-center gap-2 mb-0">
                                        @csrf
                                        @method('PUT')

                                        {{-- Hidden unchanged fields --}}
                                        <input type="hidden" name="metal" value="{{ $rate->metal }}">
                                        <input type="hidden" name="rate_date" value="{{ $rate->rate_date }}">

                                        <input type="number" name="purity_percent" value="{{ $rate->purity_percent }}"
                                            step="0.01" class="form-control form-control-sm text-center"
                                            style="max-width: 90px;">
                                </td>

                                <td>
                                    <input type="number" name="rate_per_gram" value="{{ $rate->rate_per_gram }}" step="0.01"
                                        class="form-control form-control-sm text-center" style="max-width: 110px;">
                                </td>

                                <td class="text-nowrap">
                                    {{ $rate->rate_date->format('d M, Y') }}
                                </td>

                                <td>
                                    <button class="btn btn-success btn-sm me-1" title="Update Rate">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    </form>

                                    <form action="{{ route('admin.metal-rates.destroy', $rate->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this metal rate?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Delete Rate">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No metal rates found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection