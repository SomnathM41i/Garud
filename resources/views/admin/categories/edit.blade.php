@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Update Category</h1>
    </div>

    {{-- Forms Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i> Update Jewellers Category
            </h3>
        </div>

        <div class="card-body">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="category_name">Category Name <span class="text-danger">*</span></label>
                <input type="text"
                    name="category_name"
                    id="category_name"
                    class="form-control @error('category_name') is-invalid @enderror"
                    value="{{ old('category_name', $category->category_name) }}"
                    placeholder="Enter category name">

                @error('category_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status"
                        id="status"
                        class="form-control form-select @error('status') is-invalid @enderror">
                    <option value="">Select status</option>
                    <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancel</a>
            </div>
        </form>
        </div>
    </div>


@endsection