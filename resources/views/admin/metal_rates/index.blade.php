@extends('layouts.admin')

@section('title', 'Metal Rates')

@section('content')

<div class="page-header mb-3">
    <h1 class="page-title">Metal Rates</h1>
</div>

<div class="card shadow-sm mb-3">

    {{-- ADD NEW RATE --}}
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-plus-circle text-success me-2"></i>
            Add New Metal Rate
        </h5>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.metal-rates.store') }}" method="POST">
            @csrf

            <div class="row g-2 align-items-end">

                <div class="col-md-2">
                    <label class="form-label">Metal</label>
                    <select name="metal" class="form-control form-control-sm">
                        <option value="gold">Gold</option>
                        <option value="silver">Silver</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Purity (%)</label>
                    <input type="number" name="purity_percent"
                           step="0.01"
                           class="form-control form-control-sm"
                           required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Rate / Gram</label>
                    <input type="number" name="rate_per_gram"
                           step="0.01"
                           class="form-control form-control-sm"
                           required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Rate Date</label>
                    <input type="date"
                           name="rate_date"
                           class="form-control form-control-sm"
                           required>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>



<div class="card shadow-sm">

    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-coins text-warning me-2"></i>
            Metal Rates List
        </h3>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-align-middle mb-0">

                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Metal</th>
                        <th>Purity (%)</th>
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

                            <input type="hidden" name="metal" value="{{ $rate->metal }}">
                            <input type="hidden" name="rate_date" value="{{ $rate->rate_date }}">

                            <td>{{ $key + 1 }}</td>

                            <td class="fw-semibold text-capitalize">
                                {{ $rate->metal }}
                            </td>

                            <td>
                                <input type="number"
                                       name="purity_percent"
                                       value="{{ $rate->purity_percent }}"
                                       step="0.01"
                                       class="form-control form-control-sm text-center"
                                       style="max-width:90px;">
                            </td>

                            <td>
                                <input type="number"
                                       name="rate_per_gram"
                                       value="{{ $rate->rate_per_gram }}"
                                       step="0.01"
                                       class="form-control form-control-sm text-center"
                                       style="max-width:110px;">
                            </td>

                            <td class="text-nowrap">
                                {{ $rate->rate_date->format('d M, Y') }}
                            </td>

                            <td>
                                <button class="btn btn-success btn-sm">
                                    <i class="fas fa-save"></i>
                                </button>
                            </td>

                        </form>

                        <td>

                            <form action="{{ route('admin.metal-rates.destroy', $rate->id) }}"
                                  method="POST"
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