@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Jewellers Products</h1>
    </div>


    {{-- Calculate totals by purity --}}
    @php
        $metalPurityTotals = [];

        foreach ($products as $product) {
            if ($product->stock_quantity > 0 && $product->status === 'active') {
                $weight = $product->weight * $product->stock_quantity;
                $metal = $product->metal_type;
                $purity = $product->purity;

                // Initialize array for this metal if not exists
                if (!isset($metalPurityTotals[$metal])) {
                    $metalPurityTotals[$metal] = [];
                }

                // Add weight for this purity under the metal
                $metalPurityTotals[$metal][$purity] = ($metalPurityTotals[$metal][$purity] ?? 0) + $weight;
            }
        }

        // Calculate overall grand total
        $grandTotalWeight = 0;
        foreach ($metalPurityTotals as $metalTotals) {
            foreach ($metalTotals as $weight) {
                $grandTotalWeight += $weight;
            }
        }
    @endphp


    {{-- Purity Summary Cards --}}
    @if(count($metalPurityTotals))
        <div class="row mb-3">
            @foreach($metalPurityTotals as $metal => $purityTotals)
                @foreach($purityTotals as $purity => $weight)
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="card text-white bg-primary h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $metal }} - {{ $purity }}% Purity</h5>
                                <p class="card-text" style="font-size: 1.2rem;">
                                    {{ number_format($weight, 2) }} g
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

            {{-- Grand Total Card --}}
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="card text-white bg-success h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Grand Total</h5>
                        <p class="card-text" style="font-size: 1.2rem;">
                            {{ number_format($grandTotalWeight, 2) }} g
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- Table Section --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-gem"></i> Jewellery Products
            </h3>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Product
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Metal</th>
                            <th>Weight</th>
                            <th>Purity</th>
                            <th>Selling Price</th>
                            <th>Handling Cost</th>
                            <th>Cost Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $product->product_code }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->category->category_name ?? '-' }}</td>
                                <td>{{ $product->metal_type }}</td>
                                <td>{{ $product->weight }} g</td>
                                <td>{{ $product->purity }}%</td>
                                {{-- Selling Price --}}
                                <td>₹{{ number_format($product->selling_price, 2) }}</td>

                                {{-- Handling / Making Cost --}}
                                <td>₹{{ number_format($product->handling_cost, 2) }}</td>
                                <td>₹{{ number_format($product->cost_price, 2) }}</td>

                                {{-- Stock --}}
                                <td>
                                    @if($product->stock_quantity > 10)
                                        <span class="badge badge-success">
                                            In Stock: {{ $product->stock_quantity }}
                                        </span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="badge badge-warning">
                                            Low Stock: {{ $product->stock_quantity }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger">Out of Stock</span>
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
                                <td class="d-flex gap-1">

                                    {{-- Add to Cart --}}
                                    @if($product->stock_quantity > 0 && $product->status === 'active')
                                        <form action="{{ route('admin.cart.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="selling_price" value="{{ $product->selling_price }}">
                                            <input type="hidden" name="handling_cost" value="{{ $product->handling_cost }}">
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
                                        onsubmit="return confirm('Are you sure?')">
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
                                <td colspan="10" class="text-center">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection