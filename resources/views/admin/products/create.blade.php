@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Create New Product</h1>
    </div>

    {{-- Forms Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i> Create New Jewellers Product
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf

                {{-- Product Name --}}
                <div class="form-group">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="product_name"
                        class="form-control @error('product_name') is-invalid @enderror"
                        value="{{ old('product_name') }}">
                    @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Product Code --}}
                <div class="form-group">
                    <label class="form-label">Product Code <span class="text-danger">*</span></label>
                    <input type="text" name="product_code"
                        class="form-control @error('product_code') is-invalid @enderror"
                        value="{{ old('product_code') }}">
                    @error('product_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id"
                        class="form-control form-select @error('category_id') is-invalid @enderror">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Metal Type --}}
                <div class="form-group">
                    <label class="form-label">Metal Type <span class="text-danger">*</span></label>
                    <select name="metal_type"
                        class="form-control form-select @error('metal_type') is-invalid @enderror">
                        <option value="">Select metal</option>
                        <option value="Gold" {{ old('metal_type') == 'Gold' ? 'selected' : '' }}>Gold</option>
                        <option value="Silver" {{ old('metal_type') == 'Silver' ? 'selected' : '' }}>Silver</option>
                        <option value="Platinum" {{ old('metal_type') == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                    </select>
                    @error('metal_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Purity --}}
                <div class="form-group">
                    <label class="form-label">Purity</label>
                    <input type="text" name="purity"
                        class="form-control @error('purity') is-invalid @enderror"
                        value="{{ old('purity') }}">
                    @error('purity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Weight --}}
                <div class="form-group">
                    <label class="form-label">Weight (grams)</label>
                    <input type="number" step="0.01" name="weight"
                        class="form-control @error('weight') is-invalid @enderror"
                        value="{{ old('weight') }}">
                    @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- COST PRICE --}}
                <div class="form-group">
                    <label class="form-label">Cost Price (Your Cost) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="cost_price"
                            class="form-control @error('cost_price') is-invalid @enderror"
                            value="{{ old('cost_price') }}">
                        <span class="input-group-text">₹</span>
                    </div>
                    @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- HANDLING COST --}}
                <div class="form-group">
                    <label class="form-label">Handling / Making Cost</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="handling_cost"
                            class="form-control @error('handling_cost') is-invalid @enderror"
                            value="{{ old('handling_cost', 0) }}">
                        <span class="input-group-text">₹</span>
                    </div>
                    @error('handling_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- SELLING PRICE --}}
                <div class="form-group">
                    <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="selling_price"
                            class="form-control @error('selling_price') is-invalid @enderror"
                            value="{{ old('selling_price') }}">
                        <span class="input-group-text">₹</span>
                    </div>
                    @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Stock Quantity --}}
                <div class="form-group">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity"
                        class="form-control @error('stock_quantity') is-invalid @enderror"
                        value="{{ old('stock_quantity', 0) }}">
                    @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status"
                        class="form-control form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Buttons --}}
                <div class="form-group d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection