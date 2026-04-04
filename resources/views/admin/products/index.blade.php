@extends('layouts.admin')

@section('title', 'Jewellery Products')

@section('content')

<style>
    /* ══════════════════════════════════════════
       SUMMARY CARDS
    ══════════════════════════════════════════ */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 3px var(--card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--accent-gradient);
    }

    .summary-card .sc-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        background: rgba(212,175,55,0.12);
        display: flex; align-items: center; justify-content: center;
        color: var(--accent-primary);
        font-size: 0.95rem;
        margin-bottom: 0.65rem;
    }

    .summary-card .sc-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        margin-bottom: 0.3rem;
    }

    .summary-card .sc-value {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.1;
    }

    .summary-card .sc-unit {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-left: 0.2rem;
    }

    .summary-card .sc-sub {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }

    /* ══════════════════════════════════════════
       PER-METAL CARD GROUPS
    ══════════════════════════════════════════ */
    .metal-group {
        margin-bottom: 1.25rem;
    }

    .metal-group-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.6rem;
    }

    .metal-group-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.25rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metal-group-badge.gold-badge {
        background: rgba(212,175,55,0.15);
        color: #b8860b;
        border: 1.5px solid rgba(212,175,55,0.35);
    }

    .metal-group-badge.silver-badge {
        background: rgba(148,163,184,0.15);
        color: #64748b;
        border: 1.5px solid rgba(148,163,184,0.35);
    }

    .metal-group-badge.other-badge {
        background: rgba(100,100,100,0.1);
        color: var(--text-muted);
        border: 1.5px solid var(--border-color);
    }

    /* Gold group – golden accent stripe */
    .metal-group.gold-group  .summary-card::before { background: linear-gradient(90deg, #d4af37, #f5d06e); }
    .metal-group.gold-group  .summary-card .sc-icon { background: rgba(212,175,55,0.12); color: #b8860b; }
    .metal-group.gold-group  .summary-card .sc-value { color: var(--text-primary); }

    /* Silver group – silver accent stripe */
    .metal-group.silver-group .summary-card::before { background: linear-gradient(90deg, #94a3b8, #cbd5e1); }
    .metal-group.silver-group .summary-card .sc-icon { background: rgba(148,163,184,0.15); color: #64748b; }
    .metal-group.silver-group .summary-card .sc-value { color: var(--text-primary); }

    /* ══════════════════════════════════════════
       METAL TYPE FILTER TABS
    ══════════════════════════════════════════ */
    .metal-filter-wrap {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--bg-secondary);
    }

    .metal-filter-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-right: 0.25rem;
        white-space: nowrap;
    }

    .mf-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        border: 1.5px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-secondary);
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .mf-tab:hover {
        border-color: var(--accent-primary);
        color: var(--accent-primary);
    }

    .mf-tab.active {
        background: var(--accent-gradient);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 2px 8px rgba(212,175,55,0.3);
    }

    .mf-tab .mf-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 20px;
        background: rgba(255,255,255,0.25);
        font-size: 0.65rem;
        font-weight: 800;
    }

    .mf-tab:not(.active) .mf-count {
        background: var(--bg-tertiary);
        color: var(--text-muted);
    }

    /* ══════════════════════════════════════════
       PER-METAL TOTALS BAR
    ══════════════════════════════════════════ */
    .metal-totals-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        padding: 0.6rem 1.25rem;
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-color);
        font-size: 0.78rem;
        color: var(--text-secondary);
    }

    .metal-totals-bar span {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .metal-totals-bar strong {
        color: var(--text-primary);
        font-weight: 700;
    }

    .metal-totals-bar .sep {
        color: var(--border-color);
        font-size: 1rem;
        line-height: 1;
    }

    /* ══════════════════════════════════════════
       PRODUCT TABLE
    ══════════════════════════════════════════ */
    .products-table-wrap { overflow-x: auto; }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .products-table thead tr { background: var(--bg-tertiary); }

    .products-table th {
        padding: 0.65rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.55px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }

    .products-table td {
        padding: 0.7rem 1rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        vertical-align: middle;
    }

    .products-table tbody tr:last-child td { border-bottom: none; }
    .products-table tbody tr:hover { background: var(--bg-secondary); }

    .product-row[data-hidden="true"] { display: none; }

    /* Metal badge */
    .metal-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .metal-badge.gold   { background: rgba(212,175,55,0.15); color: var(--accent-primary); }
    .metal-badge.silver { background: rgba(148,163,184,0.15); color: #94a3b8; }
    .metal-badge.other  { background: rgba(100,100,100,0.1);  color: var(--text-muted); }

    /* stock / status */
    .stock-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .stock-ok    { background: rgba(34,197,94,0.12);  color: #22c55e; }
    .stock-low   { background: rgba(234,179,8,0.12);  color: #eab308; }
    .stock-out   { background: rgba(239,68,68,0.12);  color: #ef4444; }
    .status-on   { background: rgba(34,197,94,0.12);  color: #22c55e; }
    .status-off  { background: rgba(239,68,68,0.12);  color: #ef4444; }

    /* empty state */
    .no-rows-msg {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--text-muted);
        font-size: 0.875rem;
        display: none;
    }

    .no-rows-msg i {
        display: block;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.35;
    }

    /* ══════════════════════════════════════════
       METAL RATES SECTION
    ══════════════════════════════════════════ */
    .mr-section { padding: 1rem 1.5rem 0; }
    .mr-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 1px 3px var(--card-shadow); margin-bottom: 1.25rem; overflow: hidden; }
    .mr-card::before { content: ''; display: block; height: 3px; background: var(--accent-gradient); }
    .mr-header { display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--border-color); gap: 0.75rem; flex-wrap: wrap; }
    .mr-header-left { display: flex; align-items: center; gap: 0.625rem; }
    .mr-header-icon { width: 32px; height: 32px; border-radius: 8px; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.875rem; flex-shrink: 0; }
    .mr-header-title { font-size: 0.9375rem; font-weight: 700; color: var(--text-primary); margin: 0; line-height: 1.2; }
    .mr-header-sub { font-size: 0.75rem; color: var(--text-muted); margin: 0; line-height: 1.2; }
    .mr-btn-add { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.45rem 0.9rem; border-radius: 8px; background: var(--accent-gradient); color: white; font-size: 0.8125rem; font-weight: 600; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 8px rgba(212,175,55,0.3); transition: all 0.2s ease; }
    .mr-btn-add:hover { opacity: 0.9; transform: translateY(-1px); }
    .mr-add-panel { display: none; padding: 1rem 1.25rem; background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); animation: mrSlideDown 0.22s ease; }
    .mr-add-panel.open { display: block; }
    @keyframes mrSlideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
    .mr-add-panel .mr-form-row { display: flex; flex-wrap: wrap; gap: 0.625rem; align-items: flex-end; }
    .mr-form-group { display: flex; flex-direction: column; gap: 0.3rem; }
    .mr-form-label { font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .mr-input { padding: 0.45rem 0.75rem; border: 1px solid var(--input-border); border-radius: 8px; background: var(--input-bg); color: var(--text-primary); font-size: 0.875rem; font-family: inherit; transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
    .mr-input:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(212,175,55,0.12); }
    .mr-input-group { display: flex; }
    .mr-input-group .mr-input { border-radius: 8px 0 0 8px; border-right: none; }
    .mr-input-affix { display: flex; align-items: center; padding: 0 0.625rem; background: var(--bg-tertiary); border: 1px solid var(--input-border); border-radius: 0 8px 8px 0; color: var(--text-muted); font-size: 0.8125rem; font-weight: 600; white-space: nowrap; }
    .mr-input-prefix { border-radius: 8px 0 0 8px; border-right: none; border-left: 1px solid var(--input-border); }
    .mr-input-prefix + .mr-input { border-radius: 0 8px 8px 0; border-left: none; }
    .mr-body { padding: 0.75rem 1.25rem 1rem; }
    .mr-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid var(--border-color); }
    .mr-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .mr-table thead tr { background: var(--bg-tertiary); }
    .mr-table th { padding: 0.6rem 1rem; text-align: left; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
    .mr-table td { padding: 0.625rem 1rem; border-bottom: 1px solid var(--border-color); color: var(--text-primary); vertical-align: middle; }
    .mr-table tbody tr:last-child td { border-bottom: none; }
    .mr-table tbody tr:hover { background: var(--bg-secondary); }
    .mr-btn { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.375rem 0.75rem; border-radius: 7px; font-size: 0.775rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.18s ease; white-space: nowrap; text-decoration: none; }
    .mr-btn:hover { transform: translateY(-1px); }
    .mr-btn-save { background: var(--accent-gradient); color: white; box-shadow: 0 2px 6px rgba(212,175,55,0.25); }
    .mr-btn-save:hover { box-shadow: 0 4px 10px rgba(212,175,55,0.35); }
    .mr-btn-delete { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); }
    .mr-btn-delete:hover { background: var(--danger); color: white; border-color: var(--danger); }
    .mr-btn-cancel { background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-color); }
    .mr-btn-cancel:hover { background: var(--border-color); }
    .mr-actions { display: flex; gap: 0.4rem; align-items: center; }
    .mr-empty { text-align: center; padding: 2rem 1rem; color: var(--text-muted); font-size: 0.875rem; }
    .mr-empty i { display: block; font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.4; }

    @media (max-width: 768px) {
        .mr-section { padding: 0.75rem 1rem 0; }
        .mr-add-panel .mr-form-row { flex-direction: column; }
        .mr-table th:nth-child(5), .mr-table td:nth-child(5) { display: none; }
        .summary-grid { grid-template-columns: repeat(2, 1fr); }
        .metal-breakdown { flex-direction: column; }
    }
</style>

<!-- ═══════════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════════ -->
<div class="page-header mb-3">
    <h1 class="page-title">Jewellery Products</h1>
</div>

<!-- ═══════════════════════════════════════════════
     PER-METAL SUMMARY CARD ROWS
     One row of 5 cards per metal type present
═══════════════════════════════════════════════ -->
@php
    $metalConfig = [
        'gold'   => ['emoji' => '🥇', 'label' => 'Gold',   'group' => 'gold-group',   'badge' => 'gold-badge',   'fine_label' => 'Fine Gold Wt',   'sub_gross' => 'gross gold weight',   'sub_fine' => 'net fine gold'],
        'silver' => ['emoji' => '🥈', 'label' => 'Silver', 'group' => 'silver-group', 'badge' => 'silver-badge', 'fine_label' => 'Fine Silver Wt', 'sub_gross' => 'gross silver weight', 'sub_fine' => 'net fine silver'],
    ];
@endphp

@foreach($metalTotals as $metal => $mt)
@php
    $cfg = $metalConfig[$metal] ?? [
        'emoji'       => '⚙️',
        'label'       => ucfirst($metal),
        'group'       => 'other-group',
        'badge'       => 'other-badge',
        'fine_label'  => 'Fine Wt',
        'sub_gross'   => 'gross weight',
        'sub_fine'    => 'net fine weight',
    ];
    $activeCount = $products->where('metal_type', $metal)->where('status', 'active')->count();
@endphp

<div class="metal-group {{ $cfg['group'] }}" data-metal-group="{{ $metal }}">

    {{-- Group header --}}
    <div class="metal-group-header">
        <span class="metal-group-badge {{ $cfg['badge'] }}">
            {{ $cfg['emoji'] }} {{ $cfg['label'] }}
        </span>
        <span style="font-size:0.72rem; color:var(--text-muted);">
            {{ $mt['count'] }} product{{ $mt['count'] !== 1 ? 's' : '' }}
            &nbsp;·&nbsp; {{ $activeCount }} active
        </span>
    </div>

    {{-- 5-card row --}}
    <div class="summary-grid">

        {{-- Total Products --}}
        <div class="summary-card">
            <div class="sc-icon"><i class="fas fa-gem"></i></div>
            <div class="sc-label">Total Products</div>
            <div class="sc-value">{{ $mt['count'] }}</div>
            <div class="sc-sub">{{ $activeCount }} active</div>
        </div>

        {{-- Gross Weight --}}
        <div class="summary-card">
            <div class="sc-icon"><i class="fas fa-balance-scale"></i></div>
            <div class="sc-label">Total Gross Wt</div>
            <div class="sc-value">
                {{ number_format($mt['gross_weight'], 3) }}<span class="sc-unit">g</span>
            </div>
            <div class="sc-sub">{{ $cfg['sub_gross'] }}</div>
        </div>

        {{-- Fine Weight (Gold → Fine Gold, Silver → Fine Silver) --}}
        <div class="summary-card">
            <div class="sc-icon"><i class="fas fa-coins"></i></div>
            <div class="sc-label">{{ $cfg['fine_label'] }}</div>
            <div class="sc-value">
                {{ number_format($mt['fine_gold_weight'], 3) }}<span class="sc-unit">g</span>
            </div>
            <div class="sc-sub">{{ $cfg['sub_fine'] }}</div>
        </div>

        {{-- Total Amount --}}
        <div class="summary-card">
            <div class="sc-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="sc-label">Total Amount</div>
            <div class="sc-value">₹{{ number_format($mt['cost_price'], 0) }}</div>
            <div class="sc-sub">cost price × qty</div>
        </div>

        {{-- Making Charges --}}
        <div class="summary-card">
            <div class="sc-icon"><i class="fas fa-tools"></i></div>
            <div class="sc-label">Making Charges</div>
            <div class="sc-value">₹{{ number_format($mt['making_charge'], 0) }}</div>
            <div class="sc-sub">total making × qty</div>
        </div>

    </div>
</div>
@endforeach

<!-- ═══════════════════════════════════════════════
     METAL RATES SECTION
═══════════════════════════════════════════════ -->
<div class="mr-section">
    <div class="mr-card">
        <div class="mr-header">
            <div class="mr-header-left">
                <div class="mr-header-icon"><i class="fas fa-coins"></i></div>
                <div>
                    <p class="mr-header-title">Metal Rates</p>
                    <p class="mr-header-sub">Manage gold &amp; silver rates used for pricing</p>
                </div>
            </div>
            <button class="mr-btn-add" onclick="mrToggleAdd()">
                <i class="fas fa-plus"></i> Add New Rate
            </button>
        </div>

        <div class="mr-add-panel" id="mrAddPanel">
            <form action="{{ route('admin.metal-rates.store') }}" method="POST">
                @csrf
                <div class="mr-form-row">
                    <div class="mr-form-group">
                        <label class="mr-form-label">Metal</label>
                        <select name="metal" class="mr-input" style="width:110px;">
                            <option value="gold">🥇 Gold</option>
                            <option value="silver">🥈 Silver</option>
                        </select>
                    </div>
                    <div class="mr-form-group">
                        <label class="mr-form-label">Purity</label>
                        <div class="mr-input-group">
                            <input type="number" name="purity_percent" step="0.01" min="0" max="100" placeholder="99.50" class="mr-input" style="width:90px;" required>
                            <span class="mr-input-affix">%</span>
                        </div>
                    </div>
                    <div class="mr-form-group">
                        <label class="mr-form-label">Rate / Gram</label>
                        <div class="mr-input-group">
                            <span class="mr-input-affix mr-input-prefix">₹</span>
                            <input type="number" name="rate_per_gram" step="0.01" min="0" placeholder="7500.00" class="mr-input" style="width:110px;" required>
                        </div>
                    </div>
                    <div class="mr-form-group">
                        <label class="mr-form-label">Rate Date</label>
                        <input type="date" name="rate_date" value="{{ now()->toDateString() }}" class="mr-input" style="width:150px;" required>
                    </div>
                    <div class="mr-form-group" style="flex-direction:row; gap:0.5rem; padding-top:1.35rem;">
                        <button type="submit" class="mr-btn mr-btn-save"><i class="fas fa-save"></i> Save</button>
                        <button type="button" class="mr-btn mr-btn-cancel" onclick="mrToggleAdd()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

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
                                    @csrf @method('PUT')
                                    <input type="hidden" name="rate_date"
                                        value="{{ $rate->rate_date instanceof \Carbon\Carbon ? $rate->rate_date->toDateString() : $rate->rate_date }}">
                                    <td style="color:var(--text-muted); font-size:0.75rem; width:36px;">{{ $i + 1 }}</td>
                                    <td>
                                        <select name="metal" class="mr-input" style="width:105px;">
                                            <option value="gold"   {{ $rate->metal==='gold'   ? 'selected':'' }}>🥇 Gold</option>
                                            <option value="silver" {{ $rate->metal==='silver' ? 'selected':'' }}>🥈 Silver</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="mr-input-group">
                                            <input type="number" name="purity_percent" value="{{ $rate->purity_percent }}" step="0.01" min="0" max="100" class="mr-input" style="width:85px;" required>
                                            <span class="mr-input-affix">%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mr-input-group">
                                            <span class="mr-input-affix mr-input-prefix">₹</span>
                                            <input type="number" name="rate_per_gram" value="{{ $rate->rate_per_gram }}" step="0.01" min="0" class="mr-input" style="width:110px;" required>
                                        </div>
                                    </td>
                                    <td style="color:var(--text-muted); font-size:0.8rem; white-space:nowrap;">
                                        <i class="fas fa-calendar-alt me-1" style="opacity:0.5; font-size:0.7rem;"></i>
                                        {{ $rate->rate_date instanceof \Carbon\Carbon
                                            ? $rate->rate_date->format('d M, Y')
                                            : \Carbon\Carbon::parse($rate->rate_date)->format('d M, Y') }}
                                    </td>
                                    <td>
                                        <div class="mr-actions">
                                            <button type="submit" class="mr-btn mr-btn-save"><i class="fas fa-save"></i> Update</button>
                                        </div>
                                    </td>
                                </form>
                                <td style="padding-left:0;">
                                    <form action="{{ route('admin.metal-rates.destroy', $rate->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this {{ ucfirst($rate->metal) }} rate?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="mr-btn mr-btn-delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:0.625rem; font-size:0.75rem; color:var(--text-muted); text-align:right;">
                    {{ $rates->count() }} rate{{ $rates->count() !== 1 ? 's' : '' }} total
                    &nbsp;·&nbsp; {{ $rates->where('metal','gold')->count() }} Gold
                    &nbsp;·&nbsp; {{ $rates->where('metal','silver')->count() }} Silver
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     PRODUCTS TABLE CARD
═══════════════════════════════════════════════ -->

{{-- Pass per-metal data to JS --}}
<script>
    const metalTotalsData = @json($metalTotals);
</script>

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

    {{-- ── Metal Type Filter Tabs ── --}}
    @php $metalTypes = $products->pluck('metal_type')->unique()->sort()->values(); @endphp

    <div class="metal-filter-wrap">
        <span class="metal-filter-label"><i class="fas fa-filter me-1"></i> Filter:</span>

        <button class="mf-tab active" data-metal="all" onclick="filterMetal('all', this)">
            All <span class="mf-count">{{ $products->count() }}</span>
        </button>

        @foreach($metalTypes as $mt)
        @php $mtCount = $products->where('metal_type', $mt)->count(); @endphp
        <button class="mf-tab" data-metal="{{ strtolower($mt) }}" onclick="filterMetal('{{ strtolower($mt) }}', this)">
            @if(strtolower($mt) === 'gold') 🥇
            @elseif(strtolower($mt) === 'silver') 🥈
            @else <i class="fas fa-circle" style="font-size:0.5rem;"></i>
            @endif
            {{ $mt }}
            <span class="mf-count">{{ $mtCount }}</span>
        </button>
        @endforeach
    </div>

    {{-- ── Per-Metal Totals Bar ── --}}
    <div class="metal-totals-bar" id="metalTotalsBar">
        <span>
            <i class="fas fa-balance-scale" style="opacity:0.5; font-size:0.7rem;"></i>
            Gross: <strong id="bar-gross">{{ number_format($totals['gross_weight'],3) }} g</strong>
        </span>
        <span class="sep">|</span>
        <span>
            <i class="fas fa-coins" style="opacity:0.5; font-size:0.7rem;"></i>
            {{-- Label updates via JS when a metal tab is clicked --}}
            <span id="bar-fine-label">Fine Wt:</span>
            <strong id="bar-fine">{{ number_format($totals['fine_gold_weight'],3) }} g</strong>
        </span>
        <span class="sep">|</span>
        <span>
            <i class="fas fa-rupee-sign" style="opacity:0.5; font-size:0.7rem;"></i>
            Amount: <strong id="bar-amount">₹{{ number_format($totals['cost_price'],0) }}</strong>
        </span>
        <span class="sep">|</span>
        <span>
            <i class="fas fa-box" style="opacity:0.5; font-size:0.7rem;"></i>
            Items: <strong id="bar-count">{{ $products->count() }}</strong>
        </span>
    </div>

    {{-- ── Products Table ── --}}
    <div class="card-body p-0">
        <div class="products-table-wrap">
            <table class="products-table text-center align-middle">
                <thead>
                    <tr>
                        <th style="text-align:left;">#</th>
                        <th style="text-align:left;">Name</th>
                        <th>Metal</th>
                        <th style="text-align:right;">Gross Wt</th>
                        <th style="text-align:right;">Stone Wt</th>
                        <th style="text-align:right;">Purity %</th>
                        {{-- This column header updates dynamically --}}
                        <th style="text-align:right;" id="th-fine-wt">Fine Wt</th>
                        <th style="text-align:right;">Amount (₹)</th>
                        <th style="text-align:right;">Making (₹)</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    @forelse($products as $key => $product)
                    <tr class="product-row"
                        data-metal="{{ strtolower($product->metal_type) }}"
                        data-gross="{{ $product->gross_weight * $product->stock_quantity }}"
                        data-fine="{{ $product->fine_gold_weight * $product->stock_quantity }}"
                        data-amount="{{ $product->cost_price * $product->stock_quantity }}"
                        data-making="{{ $product->making_charge * $product->stock_quantity }}">

                        <td style="text-align:left; color:var(--text-muted); font-size:0.8rem;">{{ $key + 1 }}</td>

                        <td style="text-align:left;">
                            <span style="font-weight:600;">{{ $product->product_name }}</span>
                        </td>

                        <td>
                            @php $ml = strtolower($product->metal_type); @endphp
                            <span class="metal-badge {{ in_array($ml,['gold','silver']) ? $ml : 'other' }}">
                                @if($ml==='gold') 🥇
                                @elseif($ml==='silver') 🥈
                                @else <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                                @endif
                                {{ $product->metal_type }}
                            </span>
                        </td>

                        <td style="text-align:right; font-variant-numeric:tabular-nums;">
                            {{ number_format($product->gross_weight, 3) }}<span style="font-size:0.7rem; color:var(--text-muted); margin-left:2px;">g</span>
                        </td>

                        <td style="text-align:right; font-variant-numeric:tabular-nums;">
                            {{ number_format($product->stone_weight, 3) }}<span style="font-size:0.7rem; color:var(--text-muted); margin-left:2px;">g</span>
                        </td>

                        <td style="text-align:right;">{{ $product->purity_percent }}%</td>

                        {{-- Fine weight value – same field for all metals, label distinguishes context --}}
                        <td style="text-align:right; font-variant-numeric:tabular-nums;">
                            <span style="font-weight:600; color:var(--accent-primary);">
                                {{ number_format($product->fine_gold_weight, 3) }}
                            </span><span style="font-size:0.7rem; color:var(--text-muted); margin-left:2px;">g</span>
                        </td>

                        <td style="text-align:right; font-variant-numeric:tabular-nums; font-weight:600;">
                            ₹{{ number_format($product->cost_price, 2) }}
                        </td>

                        <td style="text-align:right; font-variant-numeric:tabular-nums; color:var(--text-muted);">
                            ₹{{ number_format($product->making_charge, 2) }}
                        </td>

                        <td>
                            @if($product->stock_quantity > 10)
                                <span class="stock-badge stock-ok">{{ $product->stock_quantity }}</span>
                            @elseif($product->stock_quantity > 0)
                                <span class="stock-badge stock-low">{{ $product->stock_quantity }}</span>
                            @else
                                <span class="stock-badge stock-out">0</span>
                            @endif
                        </td>

                        <td>
                            @if($product->status === 'active')
                                <span class="stock-badge status-on">Active</span>
                            @else
                                <span class="stock-badge status-off">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex justify-content-center gap-1">
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

                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="btn btn-outline-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?')" class="mb-0">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="no-rows-msg" id="noRowsMsg">
                <i class="fas fa-gem"></i>
                No products found for this metal type.
            </div>
        </div>

        {{-- Footer totals --}}
        <div style="display:flex; flex-wrap:wrap; gap:1rem; padding:0.75rem 1.25rem; border-top:1px solid var(--border-color); background:var(--bg-tertiary); font-size:0.8rem; color:var(--text-muted);">
            <span>Showing <strong id="footer-showing" style="color:var(--text-primary);">{{ $products->count() }}</strong> products</span>
            <span style="color:var(--border-color);">|</span>
            <span>Gross: <strong id="footer-gross" style="color:var(--text-primary);">{{ number_format($totals['gross_weight'],3) }} g</strong></span>
            <span style="color:var(--border-color);">|</span>
            <span><span id="footer-fine-label">Fine Wt:</span> <strong id="footer-fine" style="color:var(--accent-primary);">{{ number_format($totals['fine_gold_weight'],3) }} g</strong></span>
            <span style="color:var(--border-color);">|</span>
            <span>Total Amount: <strong id="footer-amount" style="color:var(--text-primary);">₹{{ number_format($totals['cost_price'],0) }}</strong></span>
        </div>
    </div>
</div>

<script>
    function mrToggleAdd() {
        document.getElementById('mrAddPanel').classList.toggle('open');
    }

    /**
     * Returns the correct "fine weight" label for a given metal.
     * gold   → "Fine Gold Wt"
     * silver → "Fine Silver Wt"
     * all    → "Fine Wt"
     * other  → "Fine Wt"
     */
    function fineLabel(metal) {
        if (metal === 'gold')   return 'Fine Gold Wt:';
        if (metal === 'silver') return 'Fine Silver Wt:';
        return 'Fine Wt:';
    }

    /**
     * Filter products by metal type and recompute the totals bar, footer.
     * Also visually highlights the matching metal-group card row.
     */
    function filterMetal(metal, btn) {
        // ── Update active tab ───────────────────────────────────────
        document.querySelectorAll('.mf-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        // ── Highlight matching metal group ──────────────────────────
        document.querySelectorAll('.metal-group').forEach(g => {
            const gm = g.dataset.metalGroup;
            g.style.opacity = (metal === 'all' || gm === metal) ? '1' : '0.35';
            g.style.transition = 'opacity 0.2s ease';
        });

        // ── Walk rows ───────────────────────────────────────────────
        const rows = document.querySelectorAll('.product-row');
        let visibleCount = 0;
        let gross = 0, fine = 0, amount = 0, making = 0;

        rows.forEach(row => {
            const rowMetal = row.dataset.metal;
            const show = metal === 'all' || rowMetal === metal;
            row.setAttribute('data-hidden', show ? 'false' : 'true');

            if (show) {
                visibleCount++;
                gross  += parseFloat(row.dataset.gross  || 0);
                fine   += parseFloat(row.dataset.fine   || 0);
                amount += parseFloat(row.dataset.amount || 0);
                making += parseFloat(row.dataset.making || 0);
            }
        });

        // ── Empty state ─────────────────────────────────────────────
        document.getElementById('noRowsMsg').style.display = visibleCount === 0 ? 'block' : 'none';

        // ── Fine-weight labels ──────────────────────────────────────
        const label = fineLabel(metal);
        document.getElementById('bar-fine-label').textContent    = label;
        document.getElementById('footer-fine-label').textContent = label;
        document.getElementById('th-fine-wt').textContent =
            metal === 'gold'   ? 'Fine Gold Wt'   :
            metal === 'silver' ? 'Fine Silver Wt'  : 'Fine Wt';

        // ── Totals bar ──────────────────────────────────────────────
        document.getElementById('bar-gross').textContent  = fmt(gross, 3) + ' g';
        document.getElementById('bar-fine').textContent   = fmt(fine, 3) + ' g';
        document.getElementById('bar-amount').textContent = '₹' + fmt(amount, 0);
        document.getElementById('bar-count').textContent  = visibleCount;

        // ── Footer ──────────────────────────────────────────────────
        document.getElementById('footer-showing').textContent = visibleCount;
        document.getElementById('footer-gross').textContent   = fmt(gross, 3) + ' g';
        document.getElementById('footer-fine').textContent    = fmt(fine, 3) + ' g';
        document.getElementById('footer-amount').textContent  = '₹' + fmt(amount, 0);
    }

    function fmt(val, decimals) {
        return parseFloat(val).toLocaleString('en-IN', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }
</script>

@endsection