@extends('layouts.admin')

@section('title', 'Cart')

@section('content')

    <div class="page-header mb-4">
        <h1 class="page-title">Jewellers Products Cart</h1>
    </div>

    <div class="card shadow-sm border-0">

        {{-- ================= HEADER ================= --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i> Cart Items
            </h5>

            <a href="{{ route('admin.products.index') }}" class="btn btn-dark btn-sm">
                <i class="fas fa-plus me-1"></i> Add Products
            </a>
        </div>

        {{-- ================= TABLE SECTION ================= --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Product</th>
                            <th>Code</th>
                            <th>Qty</th>
                            <th>Gross Wt (g)</th>
                            <th>Purity %</th>
                            <th>Fine Gold (g)</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Profit</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($cartItems as $item)
                            <tr>
                                <td class="text-start fw-semibold">
                                    {{ $item->product->product_name }}
                                </td>

                                <td>{{ $item->product->product_code }}</td>
                                <td>{{ $item->quantity }}</td>

                                <td>{{ number_format($item->gross_weight * $item->quantity, 2) }}</td>
                                <td>{{ number_format($item->purity_percent, 2) }}%</td>
                                <td>{{ number_format($item->fine_gold_weight * $item->quantity, 2) }}</td>

                                {{-- Selling Price Editable --}}
                                <td>
                                    <form action="{{ route('admin.cart.update', $item->id) }}" method="POST"
                                        class="d-flex justify-content-center gap-1">
                                        @csrf
                                        @method('PUT')

                                        <input type="number" name="selling_price" value="{{ $item->selling_price }}" step="0.01"
                                            min="0" class="form-control form-control-sm text-center" style="width: 110px">

                                        <button type="submit" class="btn btn-success btn-sm" title="Update Price">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>

                                {{-- Total --}}
                                <td class="fw-bold">
                                    ₹{{ number_format($item->selling_price * $item->quantity, 2) }}
                                </td>

                                {{-- Profit --}}
                                <td class="fw-bold {{ $item->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₹{{ number_format($item->total_profit, 2) }}
                                </td>

                                {{-- Delete --}}
                                <td>
                                    <form action="{{ route('admin.cart.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Remove item from cart?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-muted py-4">
                                    Cart is empty
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    {{-- ================= CALCULATION + SUMMARY ================= --}}
    @if($cartItems->count())

        @php
            $grandTotal = 0;
            $totalBuyingCost = 0;
            $totalProfit = 0;
            $totalFineGold = 0;
        @endphp

        <div class="card mt-4 shadow-sm border-0">
            <div class="card-body">

                @foreach($cartItems as $item)

                    @php
                        $sellingRateRow = App\Models\MetalRate::where('metal', $item->product->metal_type)
                            ->where('purity_percent', $item->purity_percent)
                            ->latest('rate_date')
                            ->first();

                        $sellingRate = $sellingRateRow->rate_per_gram ?? 0;
                        $buyingPurity = $sellingRateRow->buying_purity_percent ?? 0;

                        $buyingRateRow = App\Models\MetalRate::where('metal', $item->product->metal_type)
                            ->where('purity_percent', $buyingPurity)
                            ->latest('rate_date')
                            ->first();

                        $buyingRate = $buyingRateRow->rate_per_gram ?? 0;

                        $fineWeight = $item->net_weight * ($item->purity_percent / 100);
                        $buyingCost = $item->net_weight * ($buyingPurity / 100) * $buyingRate;

                        $sellingUnit = $item->selling_price;
                        $profitPerUnit = $sellingUnit - $buyingCost;

                        $grandTotal += $sellingUnit * $item->quantity;
                        $totalBuyingCost += $buyingCost * $item->quantity;
                        $totalProfit += $profitPerUnit * $item->quantity;
                        $totalFineGold += $fineWeight * $item->quantity;
                    @endphp

                    {{-- Item Breakdown --}}
                    <div class="border rounded p-3 mb-3 bg-light">

                        <div class="d-flex justify-content-between mb-2">
                            <strong>{{ $item->product->product_name }}</strong>
                            <span class="badge {{ $profitPerUnit >= 0 ? 'badge badge-success' : 'badge badge-danger' }}">
                                Profit ₹{{ number_format($profitPerUnit, 2) }}
                            </span>
                        </div>

                        <div class="row small">
                            <div class="col-md-4 fs-6">
                                <div>Selling Rate: ₹{{ number_format($sellingRate, 2) }}/g</div>
                                <div>Purity: {{ number_format($item->purity_percent, 2) }}%</div>
                            </div>

                            <div class="col-md-4 fs-6">
                                <div>Buying Rate: ₹{{ number_format($buyingRate, 2) }}/g</div>
                                <div>Buying Purity: {{ number_format($buyingPurity, 2) }}%</div>
                            </div>

                            <div class="col-md-4 fs-6">
                                <div>Fine Gold: {{ number_format($fineWeight, 3) }} g</div>
                                <div>Buying Cost: ₹{{ number_format($buyingCost, 2) }}</div>
                            </div>
                        </div>

                    </div>

                @endforeach


                {{-- Summary --}}
                <div class="border-top pt-4 mt-4">
                    <div class="row text-center">

                        <div class="col-md-3">
                            <small class="text-muted">Total Fine Gold</small>
                            <h5 class="fw-bold">{{ number_format($totalFineGold, 3) }} g</h5>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Total Buying Cost</small>
                            <h5 class="fw-bold">₹{{ number_format($totalBuyingCost, 2) }}</h5>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Grand Total</small>
                            <h5 class="fw-bold text-primary">₹{{ number_format($grandTotal, 2) }}</h5>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Total Profit</small>
                            <h5 class="fw-bold {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($totalProfit, 2) }}
                            </h5>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.orders.create') }}" class="btn btn-dark px-4">
                            <i class="fas fa-credit-card me-1"></i> Proceed to Checkout
                        </a>
                    </div>
                </div>

            </div>
        </div>

    @endif

@endsection