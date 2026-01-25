@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Update Product</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i> Update Jewellers Product
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Product Name --}}
                <div class="form-group">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                        value="{{ old('product_name', $product->product_name) }}">
                    @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Product Code --}}
                <div class="form-group">
                    <label class="form-label">Product Code *</label>
                    <input type="text" name="product_code" class="form-control @error('product_code') is-invalid @enderror"
                        value="{{ old('product_code', $product->product_code) }}">
                    @error('product_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-control form-select">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Metal --}}
                <div class="form-group">
                    <label class="form-label">Metal Type *</label>
                    <select name="metal_type" id="metal_type" class="form-control form-select">
                        <option value="">Select metal</option>
                        @foreach($metalRates->pluck('metal')->unique() as $metal)
                            <option value="{{ $metal }}" {{ old('metal_type', $product->metal_type) == $metal ? 'selected' : '' }}>
                                {{ ucfirst($metal) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Purity --}}
                <div class="form-group">
                    <label class="form-label">Purity (%)</label>
                    <select name="purity" id="purity" class="form-control"></select>
                </div>

                {{-- Weight --}}
                <div class="form-group">
                    <label class="form-label">Weight (grams)</label>
                    <input type="number" step="0.01" name="weight" id="weight" class="form-control"
                        value="{{ old('weight', $product->weight) }}">
                </div>

                {{-- Handling --}}
                <div class="form-group">
                    <label class="form-label">Handling / Making Cost</label>
                    <input type="number" step="0.01" name="handling_cost" id="handling_cost" class="form-control"
                        value="{{ old('handling_cost', $product->handling_cost) }}">
                </div>

                {{-- Cost Price (MANUAL) --}}
                <div class="form-group">
                    <label class="form-label">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control"
                        value="{{ old('cost_price', $product->cost_price) }}">
                </div>

                {{-- Selling --}}
                <div class="form-group">
                    <label class="form-label">Selling Price</label>
                    <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control"
                        value="{{ old('selling_price', $product->selling_price) }}" readonly>
                </div>

                {{-- Stock --}}
                <div class="form-group">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="form-control"
                        value="{{ old('stock_quantity', $product->stock_quantity) }}">
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                        class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control form-select">
                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button class="btn btn-gold">Update Product</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const metalRates = @json($metalRates);
        const metal = document.getElementById('metal_type');
        const purity = document.getElementById('purity');
        const weight = document.getElementById('weight');
        const handling = document.getElementById('handling_cost');
        const selling = document.getElementById('selling_price');

        function loadPurity(selectedPurity = null) {
            purity.innerHTML = '';
            metalRates
                .filter(r => r.metal === metal.value)
                .forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.purity_percent;
                    opt.dataset.rate = r.rate_per_gram;
                    opt.text = `${r.purity_percent}% (₹${r.rate_per_gram}/gm)`;
                    if (selectedPurity == r.purity_percent) opt.selected = true;
                    purity.appendChild(opt);
                });
        }

        metal.addEventListener('change', () => loadPurity());

        function calculate() {
            const rate = purity.selectedOptions[0]?.dataset.rate || 0;
            selling.value = ((weight.value * rate) + (+handling.value || 0)).toFixed(2);
        }

        purity.addEventListener('change', calculate);
        weight.addEventListener('input', calculate);
        handling.addEventListener('input', calculate);

        // INITIAL LOAD
        if (metal.value) {
            loadPurity("{{ $product->purity }}");
        }
    </script>
@endsection