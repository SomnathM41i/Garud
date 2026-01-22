@extends('layouts.admin')

@section('content')
    <div class="card">

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-coins"></i> Metal Rate
            </h3>
            <span class="badge bg-info">
                {{ $today }}
            </span>
        </div>

        {{-- Body --}}
        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- GOLD RATE --}}
            <form method="POST" action="{{ route('admin.metal-rate.update') }}" class="mb-4">
                @csrf
                <input type="hidden" name="metal" value="gold">

                <div class="form-group mb-3">
                    <label class="fw-semibold">
                        Gold Rate (₹ per gram)
                        @if($goldRate && $goldRate->rate_date != \Carbon\Carbon::today()->toDateString())
                            <small class="text-muted">
                                (Last updated: {{ \Carbon\Carbon::parse($goldRate->rate_date)->format('d M Y') }})
                            </small>
                        @endif
                    </label>

                    <input type="number" step="0.01" name="rate_per_gram" class="form-control"
                        value="{{ old('rate_per_gram', $goldRate->rate_per_gram ?? '') }}"
                        placeholder="Enter today's gold rate" required>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Update Gold Rate
                </button>
            </form>

            <hr>

            {{-- SILVER RATE --}}
            <form method="POST" action="{{ route('admin.metal-rate.update') }}">
                @csrf
                <input type="hidden" name="metal" value="silver">

                <div class="form-group mb-3">
                    <label class="fw-semibold">
                        Silver Rate (₹ per gram)
                        @if($silverRate && $silverRate->rate_date != \Carbon\Carbon::today()->toDateString())
                            <small class="text-muted">
                                (Last updated: {{ \Carbon\Carbon::parse($silverRate->rate_date)->format('d M Y') }})
                            </small>
                        @endif
                    </label>

                    <input type="number" step="0.01" name="rate_per_gram" class="form-control"
                        value="{{ old('rate_per_gram', $silverRate->rate_per_gram ?? '') }}"
                        placeholder="Enter today's silver rate" required>
                </div>

                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-save"></i> Update Silver Rate
                </button>
            </form>

        </div>
    </div>
@endsection