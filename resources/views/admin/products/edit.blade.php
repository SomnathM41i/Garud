@extends('layouts.admin')

@section('title', 'Update Product')

@section('content')

<div class="page-header">
    <h1 class="page-title">Update Product</h1>
</div>

<div class="card">


<div class="card-header">
    <h3 class="card-title">
        <i class="fas fa-edit"></i> Update Jewellery Product
    </h3>
</div>

<div class="card-body">

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- PRODUCT NAME --}}
        <div class="form-group">
            <label class="form-label">Product Name *</label>
            <input type="text"
                   name="product_name"
                   class="form-control @error('product_name') is-invalid @enderror"
                   value="{{ old('product_name', $product->product_name) }}">

            @error('product_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- METAL TYPE --}}
        <div class="form-group">
            <label class="form-label">Metal Type *</label>

            <select name="metal_type"
                    id="metal_type"
                    class="form-control form-select @error('metal_type') is-invalid @enderror">

                <option value="">Select Metal</option>

                @foreach($metalRates->pluck('metal')->unique() as $metal)
                    <option value="{{ $metal }}"
                        {{ old('metal_type', $product->metal_type) == $metal ? 'selected' : '' }}>
                        {{ ucfirst($metal) }}
                    </option>
                @endforeach

            </select>

            @error('metal_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- SELLING PURITY --}}
        <div class="form-group">
            <label class="form-label">Selling Purity (%)</label>

            <select name="purity_percent"
                    id="purity_percent"
                    class="form-control @error('purity_percent') is-invalid @enderror">
                <option value="">Select Purity</option>
            </select>

            @error('purity_percent')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- BUYING PURITY --}}
        <div class="form-group">
            <label class="form-label">Buying Purity (%)</label>

            <input type="number"
                step="0.01"
                id="buying_purity_percent"         
                name="buying_purity_percent"
                class="form-control @error('buying_purity_percent') is-invalid @enderror"
                value="{{ old('buying_purity_percent', $product->buying_purity_percent) }}">

            @error('buying_purity_percent')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- GROSS WEIGHT --}}
        <div class="form-group">
            <label class="form-label">Gross Weight (grams)</label>

            <div class="input-group">
                <input type="number"
                    step="0.001"
                    name="gross_weight"
                    id="gross_weight"
                    class="form-control @error('gross_weight') is-invalid @enderror"
                    value="{{ old('gross_weight', $product->gross_weight) }}">

                <button type="button"
                        id="calcStoneBtn"
                        class="btn btn-outline-secondary"
                        title="Auto-calculate Fine Metal Weight from Buying Purity">
                    <i class="fas fa-calculator"></i> Calc Fine Wt
                </button>
            </div>

            {{-- Fine metal display --}}
            <small class="text-muted mt-1 d-block">
                Fine Metal Weight: <strong id="fine_metal_display">—</strong> gm
            </small>

            @error('gross_weight')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>


        {{-- STONE WEIGHT --}}
        <div class="form-group">
            <label class="form-label">Stone Weight (grams)</label>

            <input type="number"
                   step="0.001"
                   name="stone_weight"
                   id="stone_weight"
                   class="form-control @error('stone_weight') is-invalid @enderror"
                   value="{{ old('stone_weight', $product->stone_weight ?? 0) }}">

            @error('stone_weight')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- MAKING CHARGE --}}
        <div class="form-group">
            <label class="form-label">Making Charge (₹)</label>

            <input type="number"
                   step="0.01"
                   name="making_charge"
                   id="making_charge"
                   class="form-control @error('making_charge') is-invalid @enderror"
                   value="{{ old('making_charge', $product->making_charge ?? 0) }}">

            @error('making_charge')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- SELLING GOLD VALUE --}}
        <div class="form-group">
            <label class="form-label">Selling Gold Value (₹)</label>

            <input type="number"
                   step="0.01"
                   name="cost_price"
                   id="cost_price"
                   class="form-control"
                   value="{{ old('cost_price', $product->cost_price) }}"
                   readonly>
        </div>


        {{-- BUYING PRICE --}}
        <div class="form-group">
            <label class="form-label">Buying Price (₹)</label>

            <input type="number"
                   step="0.01"
                   name="buying_price"
                   class="form-control @error('buying_price') is-invalid @enderror"
                   value="{{ old('buying_price', $product->buying_price) }}">

            @error('buying_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- HIDDEN CALCULATED VALUES --}}
        <input type="hidden"
               name="net_weight"
               id="net_weight"
               value="{{ old('net_weight', $product->net_weight) }}">

        <input type="hidden"
               name="fine_gold_weight"
               id="fine_gold_weight"
               value="{{ old('fine_gold_weight', $product->fine_gold_weight) }}">


        {{-- STOCK --}}
        <div class="form-group">
            <label class="form-label">Stock Quantity</label>

            <input type="number"
                   name="stock_quantity"
                   class="form-control"
                   value="{{ old('stock_quantity', $product->stock_quantity) }}">
        </div>


        {{-- STATUS --}}
        <div class="form-group">
            <label class="form-label">Status</label>

            <select name="status" class="form-control form-select">
                <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>


        {{-- BUTTONS --}}
        <div class="form-group mt-3">

            <button type="submit" class="btn btn-gold">
                <i class="fas fa-save"></i> Update Product
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


function loadPurity(selected = null)
{
    purityEl.innerHTML = '<option value="">Select Purity</option>';

    if (!metalEl.value) return;

    metalRates
        .filter(rate => rate.metal === metalEl.value)
        .forEach(rate => {

            const option = document.createElement('option');

            option.value = rate.purity_percent;
            option.dataset.rate = rate.rate_per_gram;

            option.textContent = `${rate.purity_percent}%`;

            if (selected == rate.purity_percent)
                option.selected = true;

            purityEl.appendChild(option);
        });

    calculate();
}


function calculate()
{
    const gross = parseFloat(grossEl.value) || 0;
    const stone = parseFloat(stoneEl.value) || 0;

    const purityOption = purityEl.selectedOptions[0];

    const rate = purityOption ? parseFloat(purityOption.dataset.rate) : 0;

    const net = Math.max(gross - stone, 0);

    const purity = purityOption ? purityOption.value : 0;

    const fineGold = net * purity / 100;

    const metalValue = fineGold * rate;

    netEl.value = net.toFixed(3);
    fineEl.value = fineGold.toFixed(3);
    costEl.value = metalValue.toFixed(2);
}


metalEl.addEventListener('change', () => {
    loadPurity("{{ old('purity_percent', $product->purity_percent) }}");
});

purityEl.addEventListener('change', calculate);
grossEl.addEventListener('input', calculate);
stoneEl.addEventListener('input', calculate);
makingEl.addEventListener('input', calculate);


if (metalEl.value)
{
    loadPurity("{{ old('purity_percent', $product->purity_percent) }}");
}


// ── STONE WEIGHT AUTO-CALC FROM BUYING PURITY ──────────────────────────
const buyingPurityEl     = document.getElementById('buying_purity_percent');
const calcStoneBtn       = document.getElementById('calcStoneBtn');
const fineMetalDisplayEl = document.getElementById('fine_metal_display');

calcStoneBtn.addEventListener('click', function () {

    const gross        = parseFloat(grossEl.value) || 0;
    const buyingPurity = parseFloat(buyingPurityEl.value) || 0;

    if (gross <= 0) {
        alert('Please enter Gross Weight first.');
        return;
    }

    if (buyingPurity <= 0 || buyingPurity > 100) {
        alert('Please enter a valid Buying Purity (1–100).');
        return;
    }

    const fineGoldFromBuying = gross * buyingPurity / 100;
    const calculatedStone    = Math.max(gross - fineGoldFromBuying, 0);

    stoneEl.value = calculatedStone.toFixed(3);

    // Update fine metal display text
    fineMetalDisplayEl.textContent = fineGoldFromBuying.toFixed(3);

    // Re-run selling calculation so cost_price updates too
    calculate();
});


// ── SHOW EXISTING FINE METAL ON PAGE LOAD ──────────────────────────────
(function initFineMetal() {

    const gross        = parseFloat(grossEl.value) || 0;
    const buyingPurity = parseFloat(buyingPurityEl.value) || 0;

    if (gross > 0 && buyingPurity > 0) {
        const fineGoldFromBuying     = gross * buyingPurity / 100;
        fineMetalDisplayEl.textContent = fineGoldFromBuying.toFixed(3);
    }

})();
</script>

@endsection
