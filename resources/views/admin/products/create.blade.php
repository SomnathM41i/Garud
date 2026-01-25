@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Create New Product</h1>
    </div>

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
                    <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                        value="{{ old('product_name') }}">
                    @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Product Code --}}
                <div class="form-group">
                    <label class="form-label">Product Code <span class="text-danger">*</span></label>
                    <input type="text" name="product_code" class="form-control @error('product_code') is-invalid @enderror"
                        value="{{ old('product_code') }}">
                    @error('product_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-control form-select @error('category_id') is-invalid @enderror">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Metal Type --}}
                <div class="form-group">
                    <label class="form-label">Metal Type <span class="text-danger">*</span></label>
                    <select name="metal_type" id="metal_type"
                        class="form-control form-select @error('metal_type') is-invalid @enderror">
                        <option value="">Select metal</option>
                        @foreach($metalRates->pluck('metal')->unique() as $metal)
                            <option value="{{ $metal }}">{{ ucfirst($metal) }}</option>
                        @endforeach
                    </select>
                    @error('metal_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Purity (Dependent on Metal) --}}
                <div class="form-group">
                    <label class="form-label">Purity (%)</label>
                    <select name="purity" id="purity" class="form-control @error('purity') is-invalid @enderror">
                        <option value="">Select purity</option>
                    </select>
                    @error('purity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Weight --}}
                <div class="form-group">
                    <label class="form-label">Weight (grams)</label>
                    <input type="number" step="0.01" name="weight" id="weight"
                        class="form-control @error('weight') is-invalid @enderror">
                    @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Handling Cost --}}
                <div class="form-group">
                    <label class="form-label">Handling / Making Cost</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="handling_cost" id="handling_cost"
                            class="form-control @error('handling_cost') is-invalid @enderror" value="0">
                        <span class="input-group-text">₹</span>
                    </div>
                    @error('handling_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- COST PRICE (Auto from metal rate × weight) --}}
                <div class="form-group">
                    <label class="form-label">Cost Price (Metal Value)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="cost_price" id="cost_price"
                            class="form-control @error('cost_price') is-invalid @enderror">
                        <span class="input-group-text">₹</span>
                    </div>
                    @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Selling Price --}}
                <div class="form-group">
                    <label class="form-label">Selling Price</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="selling_price" id="selling_price"
                            class="form-control @error('selling_price') is-invalid @enderror" readonly>
                        <span class="input-group-text">₹</span>
                    </div>
                    @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Stock Quantity --}}
                <div class="form-group">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity"
                        class="form-control @error('stock_quantity') is-invalid @enderror" value="0">
                    @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror"></textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
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

@section('scripts')
    <script>
        const metalRates = @json($metalRates);
        console.log(metalRates);

        const metal = document.getElementById('metal_type');
        const purity = document.getElementById('purity');
        const weight = document.getElementById('weight');
        const handling = document.getElementById('handling_cost');
        const selling = document.getElementById('selling_price');

        // Load purity based on metal
        metal.addEventListener('change', function () {
            purity.innerHTML = '<option value="">Select purity</option>';
            purity.disabled = true;
            selling.value = '';

            if (!this.value) return;

            metalRates
                .filter(r => r.metal === this.value)
                .forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.purity_percent;
                    opt.dataset.rate = r.rate_per_gram;

                    // SHOW RATE PER GRAM IN DROPDOWN
                    opt.textContent = `${r.purity_percent}% (₹${r.rate_per_gram} / gm)`;

                    purity.appendChild(opt);
                });

            purity.disabled = false;
        });

        // Auto calculate selling price
        function calculate() {
            const w = parseFloat(weight.value) || 0;
            const h = parseFloat(handling.value) || 0;
            const rate = purity.selectedOptions[0]
                ? parseFloat(purity.selectedOptions[0].dataset.rate)
                : 0;

            selling.value = (w > 0 && rate > 0)
                ? ((w * rate) + h).toFixed(2)
                : '';
        }

        purity.addEventListener('change', calculate);
        weight.addEventListener('input', calculate);
        handling.addEventListener('input', calculate);
    </script>
@endsection