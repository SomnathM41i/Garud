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
                <i class="fas fa-edit"></i> Create New Jewellery Product
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

                {{-- Purity --}}
                <div class="form-group">
                    <label class="form-label">Purity (%)</label>
                    <select name="purity_percent" id="purity_percent"
                        class="form-control @error('purity_percent') is-invalid @enderror">
                        <option value="">Select purity</option>
                    </select>
                    @error('purity_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Gross Weight --}}
                <div class="form-group">
                    <label class="form-label">Gross Weight (grams)</label>
                    <input type="number" step="0.001" name="gross_weight" id="gross_weight"
                        class="form-control @error('gross_weight') is-invalid @enderror" value="{{ old('gross_weight') }}">
                    @error('gross_weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Stone Weight --}}
                <div class="form-group">
                    <label class="form-label">Stone Weight (grams)</label>
                    <input type="number" step="0.001" name="stone_weight" id="stone_weight"
                        class="form-control @error('stone_weight') is-invalid @enderror"
                        value="{{ old('stone_weight', 0) }}">
                    @error('stone_weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Making Charge --}}
                <div class="form-group">
                    <label class="form-label">Making Charge (₹)</label>
                    <input type="number" step="0.01" name="making_charge" id="making_charge"
                        class="form-control @error('making_charge') is-invalid @enderror"
                        value="{{ old('making_charge', 0) }}">
                    @error('making_charge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Cost Price (auto) --}}
                <div class="form-group">
                    <label class="form-label">Cost Price (Metal Value)</label>
                    <input type="number" step="0.01" name="cost_price" id="cost_price"
                        class="form-control @error('cost_price') is-invalid @enderror" readonly>
                    @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Fine Gold Weight (auto) --}}
                <input type="hidden" name="net_weight" id="net_weight">
                <input type="hidden" name="fine_gold_weight" id="fine_gold_weight">

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

        const metalEl = document.getElementById('metal_type');
        const purityEl = document.getElementById('purity_percent');
        const grossEl = document.getElementById('gross_weight');
        const stoneEl = document.getElementById('stone_weight');
        const makingEl = document.getElementById('making_charge');
        const costEl = document.getElementById('cost_price');
        const netEl = document.getElementById('net_weight');
        const fineEl = document.getElementById('fine_gold_weight');

        // Populate purity based on metal
        metalEl.addEventListener('change', function () {
            purityEl.innerHTML = '<option value="">Select purity</option>';
            purityEl.disabled = true;

            if (!this.value) return;

            metalRates.filter(r => r.metal === this.value).forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.purity_percent;
                opt.dataset.rate = r.rate_per_gram;
                opt.textContent = `${r.purity_percent}% (₹${r.rate_per_gram}/gm)`;
                purityEl.appendChild(opt);
            });

            purityEl.disabled = false;
            calculate();
        });

        function calculate() {
            const gross = parseFloat(grossEl.value) || 0;
            const stone = parseFloat(stoneEl.value) || 0;
            const making = parseFloat(makingEl.value) || 0;
            const purityOption = purityEl.selectedOptions[0];
            const rate = purityOption ? parseFloat(purityOption.dataset.rate) : 0;

            const net = Math.max(gross - stone, 0);
            const fineGold = net * (purityOption ? purityOption.value / 100 : 0);

            netEl.value = net.toFixed(3);
            fineEl.value = fineGold.toFixed(3);

            costEl.value = (fineGold * rate).toFixed(2);
        }

        grossEl.addEventListener('input', calculate);
        stoneEl.addEventListener('input', calculate);
        makingEl.addEventListener('input', calculate);
        purityEl.addEventListener('change', calculate);

    </script>
@endsection