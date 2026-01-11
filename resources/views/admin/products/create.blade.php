@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Add New Product</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your jewelry store
            today.</p>
    </div>

    {{-- Forms Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i> Add New Jewellery Product
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf

                {{-- Product Name --}}
                <div class="form-group">
                    <label class="form-label" for="product_name">
                        Product Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="product_name" id="product_name"
                        class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}"
                        placeholder="Enter product name">
                    @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Product Code --}}
                <div class="form-group">
                    <label class="form-label" for="product_code">
                        Product Code <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="product_code" id="product_code"
                        class="form-control @error('product_code') is-invalid @enderror" value="{{ old('product_code') }}"
                        placeholder="Enter unique product code">
                    @error('product_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label class="form-label" for="category_id">
                        Category <span class="text-danger">*</span>
                    </label>
                    <select name="category_id" id="category_id"
                        class="form-control form-select @error('category_id') is-invalid @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Metal Type --}}
                <div class="form-group">
                    <label class="form-label" for="metal_type">
                        Metal Type <span class="text-danger">*</span>
                    </label>
                    <select name="metal_type" id="metal_type"
                        class="form-control form-select @error('metal_type') is-invalid @enderror">
                        <option value="">Select metal</option>
                        <option value="Gold" {{ old('metal_type') == 'Gold' ? 'selected' : '' }}>Gold</option>
                        <option value="Silver" {{ old('metal_type') == 'Silver' ? 'selected' : '' }}>Silver</option>
                        <option value="Platinum" {{ old('metal_type') == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                    </select>
                    @error('metal_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Purity --}}
                <div class="form-group">
                    <label class="form-label" for="purity">Purity</label>
                    <input type="text" name="purity" id="purity" class="form-control @error('purity') is-invalid @enderror"
                        value="{{ old('purity') }}" placeholder="e.g. 22K, 18K, 925">
                    @error('purity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Weight --}}
                <div class="form-group">
                    <label class="form-label" for="weight">Weight (grams)</label>
                    <input type="number" step="0.01" name="weight" id="weight"
                        class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight') }}"
                        placeholder="Enter weight in grams">
                    @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Making Charges --}}
                <div class="form-group">
                    <label class="form-label" for="making_charges">Making Charges</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="making_charges" id="making_charges"
                            class="form-control @error('making_charges') is-invalid @enderror"
                            value="{{ old('making_charges') }}" placeholder="0.00">
                        <span class="input-group-text"><i class="fas fa-indian-rupee-sign"></i></span>
                    </div>
                    @error('making_charges')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="form-group">
                    <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                            placeholder="0.00">
                        <span class="input-group-text"><i class="fas fa-indian-rupee-sign"></i></span>
                    </div>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stock Quantity --}}
                <div class="form-group">
                    <label class="form-label" for="stock_quantity">Stock Quantity</label>
                    <input type="number" name="stock_quantity" id="stock_quantity"
                        class="form-control @error('stock_quantity') is-invalid @enderror"
                        value="{{ old('stock_quantity', 0) }}">
                    @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Enter product description">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select name="status" id="status"
                        class="form-control form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="form-group d-flex gap-2 flex-wrap mt-3">
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-save"></i> Save Product
                    </button>

                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection