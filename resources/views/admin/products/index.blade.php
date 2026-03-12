@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header mb-3">
        <h1 class="page-title">Jewellery Products</h1>
    </div>

    {{-- Products Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-gem me-2"></i> Jewellery Products
            </h3>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> New Product
                </a>
            </div>
        </div>

        {{-- ===================================================================
            METAL RATES SECTION — paste above your Summary Cards
            Requires: $rates passed from controller
            =================================================================== --}}

        <style>
            /* ── Metal Rate Section ── */
            .mr-section {
                padding: 1rem 1.5rem 0;
            }

            .mr-card {
                background: var(--card-bg);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                box-shadow: 0 1px 3px var(--card-shadow);
                margin-bottom: 1.25rem;
                overflow: hidden;
            }

            /* Top accent bar */
            .mr-card::before {
                content: '';
                display: block;
                height: 3px;
                background: var(--accent-gradient);
            }

            /* ── Header ── */
            .mr-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.875rem 1.25rem;
                border-bottom: 1px solid var(--border-color);
                gap: 0.75rem;
                flex-wrap: wrap;
            }

            .mr-header-left {
                display: flex;
                align-items: center;
                gap: 0.625rem;
            }

            .mr-header-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                background: var(--accent-gradient);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 0.875rem;
                flex-shrink: 0;
            }

            .mr-header-title {
                font-size: 0.9375rem;
                font-weight: 700;
                color: var(--text-primary);
                margin: 0;
                line-height: 1.2;
            }

            .mr-header-sub {
                font-size: 0.75rem;
                color: var(--text-muted);
                margin: 0;
                line-height: 1.2;
            }

            /* ── Add New Button ── */
            .mr-btn-add {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                padding: 0.45rem 0.9rem;
                border-radius: 8px;
                background: var(--accent-gradient);
                color: white;
                font-size: 0.8125rem;
                font-weight: 600;
                border: none;
                cursor: pointer;
                white-space: nowrap;
                box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
                transition: all 0.2s ease;
            }

            .mr-btn-add:hover {
                opacity: 0.9;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
            }

            /* ── Add Form Panel ── */
            .mr-add-panel {
                display: none;
                padding: 1rem 1.25rem;
                background: var(--bg-secondary);
                border-bottom: 1px solid var(--border-color);
                animation: mrSlideDown 0.22s ease;
            }

            .mr-add-panel.open {
                display: block;
            }

            @keyframes mrSlideDown {
                from { opacity: 0; transform: translateY(-8px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .mr-add-panel .mr-form-row {
                display: flex;
                flex-wrap: wrap;
                gap: 0.625rem;
                align-items: flex-end;
            }

            .mr-form-group {
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
            }

            .mr-form-label {
                font-size: 0.7rem;
                font-weight: 600;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .mr-input {
                padding: 0.45rem 0.75rem;
                border: 1px solid var(--input-border);
                border-radius: 8px;
                background: var(--input-bg);
                color: var(--text-primary);
                font-size: 0.875rem;
                font-family: inherit;
                transition: border-color 0.2s, box-shadow 0.2s;
                outline: none;
            }

            .mr-input:focus {
                border-color: var(--accent-primary);
                box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.12);
            }

            .mr-input-group {
                display: flex;
            }

            .mr-input-group .mr-input {
                border-radius: 8px 0 0 8px;
                border-right: none;
            }

            .mr-input-affix {
                display: flex;
                align-items: center;
                padding: 0 0.625rem;
                background: var(--bg-tertiary);
                border: 1px solid var(--input-border);
                border-radius: 0 8px 8px 0;
                color: var(--text-muted);
                font-size: 0.8125rem;
                font-weight: 600;
                white-space: nowrap;
            }

            .mr-input-prefix {
                border-radius: 8px 0 0 8px;
                border-right: none;
                border-left: 1px solid var(--input-border);
            }

            .mr-input-prefix + .mr-input {
                border-radius: 0 8px 8px 0;
                border-left: none;
            }

            /* ── Rates Table ── */
            .mr-body {
                padding: 0.75rem 1.25rem 1rem;
            }

            .mr-table-wrap {
                overflow-x: auto;
                border-radius: 10px;
                border: 1px solid var(--border-color);
            }

            .mr-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.875rem;
            }

            .mr-table thead tr {
                background: var(--bg-tertiary);
            }

            .mr-table th {
                padding: 0.6rem 1rem;
                text-align: left;
                font-size: 0.7rem;
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.6px;
                border-bottom: 1px solid var(--border-color);
                white-space: nowrap;
            }

            .mr-table td {
                padding: 0.625rem 1rem;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-primary);
                vertical-align: middle;
            }

            .mr-table tbody tr:last-child td {
                border-bottom: none;
            }

            .mr-table tbody tr:hover {
                background: var(--bg-secondary);
            }

            /* Metal pill */
            .mr-metal-pill {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.2rem 0.6rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.3px;
            }

            .mr-metal-pill.gold {
                background: rgba(212, 175, 55, 0.15);
                color: var(--accent-primary);
            }

            .mr-metal-pill.silver {
                background: rgba(148, 163, 184, 0.15);
                color: #94a3b8;
            }

            .mr-metal-pill .dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: currentColor;
                flex-shrink: 0;
            }

            /* Inline edit inputs inside table */
            .mr-table .mr-input {
                padding: 0.375rem 0.625rem;
                font-size: 0.8125rem;
            }

            .mr-table .mr-input-affix {
                padding: 0 0.5rem;
                font-size: 0.75rem;
            }

            /* ── Action Buttons ── */
            .mr-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.3rem;
                padding: 0.375rem 0.75rem;
                border-radius: 7px;
                font-size: 0.775rem;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.18s ease;
                white-space: nowrap;
                text-decoration: none;
            }

            .mr-btn:hover {
                transform: translateY(-1px);
            }

            .mr-btn-save {
                background: var(--accent-gradient);
                color: white;
                box-shadow: 0 2px 6px rgba(212, 175, 55, 0.25);
            }

            .mr-btn-save:hover {
                box-shadow: 0 4px 10px rgba(212, 175, 55, 0.35);
            }

            .mr-btn-delete {
                background: rgba(239, 68, 68, 0.1);
                color: var(--danger);
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            .mr-btn-delete:hover {
                background: var(--danger);
                color: white;
                border-color: var(--danger);
            }

            .mr-btn-cancel {
                background: var(--bg-tertiary);
                color: var(--text-secondary);
                border: 1px solid var(--border-color);
            }

            .mr-btn-cancel:hover {
                background: var(--border-color);
            }

            .mr-actions {
                display: flex;
                gap: 0.4rem;
                align-items: center;
            }

            /* ── Empty State ── */
            .mr-empty {
                text-align: center;
                padding: 2rem 1rem;
                color: var(--text-muted);
                font-size: 0.875rem;
            }

            .mr-empty i {
                display: block;
                font-size: 2rem;
                margin-bottom: 0.5rem;
                opacity: 0.4;
            }

            /* ── Responsive ── */
            @media (max-width: 768px) {
                .mr-section { padding: 0.75rem 1rem 0; }
                .mr-add-panel .mr-form-row { flex-direction: column; }
                .mr-table th:nth-child(5),
                .mr-table td:nth-child(5) { display: none; } /* hide date col on mobile */
            }
        </style>


        {{-- ===== METAL RATES SECTION ===== --}}
        <div class="mr-section">
            <div class="mr-card">

                {{-- ── Header ── --}}
                <div class="mr-header">
                    <div class="mr-header-left">
                        <div class="mr-header-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <p class="mr-header-title">Metal Rates</p>
                            <p class="mr-header-sub">Manage gold &amp; silver rates used for pricing</p>
                        </div>
                    </div>

                    <button class="mr-btn-add" onclick="mrToggleAdd()">
                        <i class="fas fa-plus"></i> Add New Rate
                    </button>
                </div>

                {{-- ── Add New Rate Panel ── --}}
                <div class="mr-add-panel" id="mrAddPanel">
                    <form action="{{ route('admin.metal-rates.store') }}" method="POST">
                        @csrf
                        <div class="mr-form-row">

                            {{-- Metal --}}
                            <div class="mr-form-group">
                                <label class="mr-form-label">Metal</label>
                                <select name="metal" class="mr-input" style="width:110px;">
                                    <option value="gold">🥇 Gold</option>
                                    <option value="silver">🥈 Silver</option>
                                </select>
                            </div>

                            {{-- Purity --}}
                            <div class="mr-form-group">
                                <label class="mr-form-label">Purity</label>
                                <div class="mr-input-group">
                                    <input type="number" name="purity_percent"
                                        step="0.01" min="0" max="100"
                                        placeholder="99.50"
                                        class="mr-input" style="width:90px;"
                                        required>
                                    <span class="mr-input-affix">%</span>
                                </div>
                            </div>

                            {{-- Rate / Gram --}}
                            <div class="mr-form-group">
                                <label class="mr-form-label">Rate / Gram</label>
                                <div class="mr-input-group">
                                    <span class="mr-input-affix mr-input-prefix">₹</span>
                                    <input type="number" name="rate_per_gram"
                                        step="0.01" min="0"
                                        placeholder="7500.00"
                                        class="mr-input" style="width:110px;"
                                        required>
                                </div>
                            </div>

                            {{-- Rate Date --}}
                            <div class="mr-form-group">
                                <label class="mr-form-label">Rate Date</label>
                                <input type="date" name="rate_date"
                                    value="{{ now()->toDateString() }}"
                                    class="mr-input" style="width:150px;"
                                    required>
                            </div>

                            {{-- Buttons --}}
                            <div class="mr-form-group" style="flex-direction:row; gap:0.5rem; padding-top:1.35rem;">
                                <button type="submit" class="mr-btn mr-btn-save">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <button type="button" class="mr-btn mr-btn-cancel" onclick="mrToggleAdd()">
                                    Cancel
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

                {{-- ── Existing Rates Table ── --}}
                <div class="mr-body">

                    @if($rates->isEmpty())

                    <div class="mr-empty">
                        <i class="fas fa-coins"></i>
                        No metal rates added yet. Click <strong>Add New Rate</strong> to get started.
                    </div>

                    @else

                    <div class="mr-table-wrap">
                        <table class="mr-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Metal</th>
                                    <th>Purity (%)</th>
                                    <th>Rate / Gram (₹)</th>
                                    <th>Rate Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates as $i => $rate)
                                <tr>
                                    <form action="{{ route('admin.metal-rates.update', $rate->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="rate_date"
                                            value="{{ $rate->rate_date instanceof \Carbon\Carbon ? $rate->rate_date->toDateString() : $rate->rate_date }}">

                                        {{-- # --}}
                                        <td style="color:var(--text-muted); font-size:0.75rem; width:36px;">
                                            {{ $i + 1 }}
                                        </td>

                                        {{-- Metal --}}
                                        <td>
                                            <select name="metal" class="mr-input" style="width:105px;">
                                                <option value="gold"   {{ $rate->metal === 'gold'   ? 'selected' : '' }}>🥇 Gold</option>
                                                <option value="silver" {{ $rate->metal === 'silver' ? 'selected' : '' }}>🥈 Silver</option>
                                            </select>
                                        </td>

                                        {{-- Purity --}}
                                        <td>
                                            <div class="mr-input-group">
                                                <input type="number"
                                                    name="purity_percent"
                                                    value="{{ $rate->purity_percent }}"
                                                    step="0.01" min="0" max="100"
                                                    class="mr-input" style="width:85px;"
                                                    required>
                                                <span class="mr-input-affix">%</span>
                                            </div>
                                        </td>

                                        {{-- Rate / Gram --}}
                                        <td>
                                            <div class="mr-input-group">
                                                <span class="mr-input-affix mr-input-prefix">₹</span>
                                                <input type="number"
                                                    name="rate_per_gram"
                                                    value="{{ $rate->rate_per_gram }}"
                                                    step="0.01" min="0"
                                                    class="mr-input" style="width:110px;"
                                                    required>
                                            </div>
                                        </td>

                                        {{-- Date --}}
                                        <td style="color:var(--text-muted); font-size:0.8rem; white-space:nowrap;">
                                            <i class="fas fa-calendar-alt me-1" style="opacity:0.5; font-size:0.7rem;"></i>
                                            {{ $rate->rate_date instanceof \Carbon\Carbon
                                                ? $rate->rate_date->format('d M, Y')
                                                : \Carbon\Carbon::parse($rate->rate_date)->format('d M, Y') }}
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="mr-actions">

                                                {{-- Update --}}
                                                <button type="submit" class="mr-btn mr-btn-save">
                                                    <i class="fas fa-save"></i> Update
                                                </button>

                                            </div>
                                        </td>

                                    </form>

                                    {{-- Delete (separate form, outside the update form) --}}
                                    <td style="padding-left:0;">
                                        <form action="{{ route('admin.metal-rates.destroy', $rate->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this {{ ucfirst($rate->metal) }} rate?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mr-btn mr-btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Rate count summary --}}
                    <div style="margin-top:0.625rem; font-size:0.75rem; color:var(--text-muted); text-align:right;">
                        {{ $rates->count() }} rate{{ $rates->count() !== 1 ? 's' : '' }} total
                        &nbsp;·&nbsp;
                        {{ $rates->where('metal','gold')->count() }} Gold
                        &nbsp;·&nbsp;
                        {{ $rates->where('metal','silver')->count() }} Silver
                    </div>

                    @endif

                </div>{{-- /.mr-body --}}

            </div>{{-- /.mr-card --}}
        </div>{{-- /.mr-section --}}

        <script>
            function mrToggleAdd() {
                const panel = document.getElementById('mrAddPanel');
                panel.classList.toggle('open');
            }
        </script>
        {{-- ===== END METAL RATES SECTION ===== --}}

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th class="text-start">Name</th>
                            <!-- <th>Category</th> -->
                            <th>Metal</th>
                            <th class="text-end">Gross</th>
                            <th class="text-end">Stone</th>
                            <!-- <th class="text-end">Net Wt</th> -->
                            <th class="text-end">Purity %</th>
                            <th class="text-end">Fine Gold</th>
                            <th class="text-end">Selling Price</th>
                            <!-- <th class="text-end">Making Charge</th> -->
                            <th class="text-end">Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="text-start">{{ $product->product_name }}</td>
                                <!-- <td>{{ $product->category->category_name ?? '-' }}</td> -->
                                <td>{{ $product->metal_type }}</td>
                                <td class="text-end">{{ number_format($product->gross_weight, 3) }} g</td>
                                <td class="text-end">{{ number_format($product->stone_weight, 3) }} g</td>
                                <!-- <td class="text-end">{{ number_format($product->net_weight, 3) }} g</td> -->
                                <td class="text-end">{{ $product->purity_percent }}%</td>
                                <td class="text-end">{{ number_format($product->fine_gold_weight, 3) }} g</td>
                                <td class="text-end">₹{{ number_format($product->cost_price, 2) }}</td>
                                <!-- <td class="text-end">₹{{ number_format($product->making_charge, 2) }}</td> -->

                                {{-- Stock --}}
                                <td>
                                    @if($product->stock_quantity > 10)
                                        <span class="badge badge-success">{{ $product->stock_quantity }}</span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="badge badge-warning">{{ $product->stock_quantity }}</span>
                                    @else
                                        <span class="badge badge-danger">0</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($product->status === 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="d-flex justify-content-center gap-1">
                                    {{-- Add to Cart --}}
                                    @if($product->stock_quantity > 0 && $product->status === 'active')
                                        <form action="{{ route('admin.cart.store') }}" method="POST" class="mb-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button class="btn btn-success btn-sm" title="Add to Cart">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?')" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center text-muted py-4">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection