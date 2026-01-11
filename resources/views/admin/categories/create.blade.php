@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Create Category</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your jewelry store
            today.</p>
    </div>

    {{-- Forms Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus"></i> Add Jewellery Category
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                {{-- Category Name --}}
                <div class="form-group">
                    <label class="form-label" for="category_name">
                        Category Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="category_name" name="category_name"
                        class="form-control @error('category_name') is-invalid @enderror" placeholder="Enter category name"
                        value="{{ old('category_name') }}">

                    @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label" for="status">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select name="status" id="status"
                        class="form-control form-select @error('status') is-invalid @enderror">
                        <option value="">Select status</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="form-group d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-save"></i> Save Category
                    </button>

                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection