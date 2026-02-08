@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Update Metal Rate</h1>
    </div>

    {{-- Forms Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-coins"></i> Update Metal Rate
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.metal-rates.update', $metalRate->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Metal --}}
                <div class="form-group">
                    <label class="form-label">Metal <span class="text-danger">*</span></label>
                    <select name="metal" class="form-control form-select @error('metal') is-invalid @enderror">
                        <option value="">Select Metal</option>
                        <option value="gold" {{ old('metal', $metalRate->metal) == 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="silver" {{ old('metal', $metalRate->metal) == 'silver' ? 'selected' : '' }}>Silver
                        </option>
                    </select>
                    @error('metal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Purity Percent --}}
                <div class="form-group">
                    <label class="form-label">Purity (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="purity_percent"
                        class="form-control @error('purity_percent') is-invalid @enderror"
                        value="{{ old('purity_percent', $metalRate->purity_percent) }}" placeholder="e.g., 99.9">
                    @error('purity_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Buying Purity Percent --}}
                <div class="form-group">
                    <label class="form-label">Buying Purity (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="buying_purity_percent"
                        class="form-control @error('buying_purity_percent') is-invalid @enderror"
                        value="{{ old('buying_purity_percent', $metalRate->buying_purity_percent) }}"
                        placeholder="e.g., 75">
                    @error('buying_purity_percent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Rate per Gram --}}
                <div class="form-group">
                    <label class="form-label">Rate per Gram (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="rate_per_gram"
                        class="form-control @error('rate_per_gram') is-invalid @enderror"
                        value="{{ old('rate_per_gram', $metalRate->rate_per_gram) }}" placeholder="e.g., 15431.00">
                    @error('rate_per_gram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Rate Date --}}
                <div class="form-group">
                    <label class="form-label">Rate Date <span class="text-danger">*</span></label>
                    <input type="date" name="rate_date" class="form-control @error('rate_date') is-invalid @enderror"
                        value="{{ old('rate_date', $metalRate->rate_date->format('Y-m-d')) }}">
                    @error('rate_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Buttons --}}
                <div class="form-group d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-save"></i> Update Rate
                    </button>
                    <a href="{{ route('admin.metal-rates.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection