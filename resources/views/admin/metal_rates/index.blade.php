@extends('layouts.admin')

@section('title', 'Metal Rates')

@section('content')
    <div class="page-header mb-3">
        <h1 class="page-title">Metal Rates</h1>
    </div>

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
                            <th style="width:50px;">#</th>
                            <th>Metal</th>
                            <th>Purity (%)</th>
                            <th>Buying Purity (%)</th>
                            <th>Rate / Gram (₹)</th>
                            <th>Rate Date</th>
                            <th>Save</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($rates as $key => $rate)
                            <tr class="text-center align-middle">

                                <form action="{{ route('admin.metal-rates.update', $rate->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    {{-- Hidden --}}
                                    <input type="hidden" name="metal" value="{{ $rate->metal }}">
                                    <input type="hidden" name="rate_date" value="{{ $rate->rate_date }}">

                                    <td>{{ $key + 1 }}</td>

                                    <td class="text-capitalize fw-semibold">
                                        {{ $rate->metal }}
                                    </td>

                                    {{-- Selling Purity --}}
                                    <td>
                                        <input type="number" name="purity_percent" value="{{ $rate->purity_percent }}"
                                            step="0.01" class="form-control form-control-sm text-center"
                                            style="max-width:90px;">
                                    </td>

                                    {{-- Buying Purity --}}
                                    <td>
                                        <input type="number" name="buying_purity_percent"
                                            value="{{ $rate->buying_purity_percent }}" step="0.01"
                                            class="form-control form-control-sm text-center" style="max-width:90px;">
                                    </td>

                                    {{-- Rate --}}
                                    <td>
                                        <input type="number" name="rate_per_gram" value="{{ $rate->rate_per_gram }}" step="0.01"
                                            class="form-control form-control-sm text-center" style="max-width:110px;">
                                    </td>

                                    <td class="text-nowrap">
                                        {{ $rate->rate_date->format('d M, Y') }}
                                    </td>

                                    <td>
                                        <button type="submit" class="btn btn-success btn-sm me-1">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </td>
                                </form>

                                <td>
                                    <form action="{{ route('admin.metal-rates.destroy', $rate->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this metal rate?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
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