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
            <h3 class="card-title mb-0"><i class="fas fa-gem me-2"></i> Jewellery Products</h3>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> New Product
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product Code</th>
                            <th class="text-start">Name</th>
                            <th>Category</th>
                            <th>Metal</th>
                            <th class="text-end">Gross Wt</th>
                            <th class="text-end">Stone Wt</th>
                            <th class="text-end">Net Wt</th>
                            <th class="text-end">Purity %</th>
                            <th class="text-end">Fine Gold Wt</th>
                            <th class="text-end">Cost Price</th>
                            <th class="text-end">Making Charge</th>
                            <th class="text-end">Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $product->product_code }}</td>
                                <td class="text-start">{{ $product->product_name }}</td>
                                <td>{{ $product->category->category_name ?? '-' }}</td>
                                <td>{{ $product->metal_type }}</td>
                                <td class="text-end">{{ number_format($product->gross_weight, 3) }} g</td>
                                <td class="text-end">{{ number_format($product->stone_weight, 3) }} g</td>
                                <td class="text-end">{{ number_format($product->net_weight, 3) }} g</td>
                                <td class="text-end">{{ $product->purity_percent }}%</td>
                                <td class="text-end">{{ number_format($product->fine_gold_weight, 3) }} g</td>
                                <td class="text-end">₹{{ number_format($product->cost_price, 2) }}</td>
                                <td class="text-end">₹{{ number_format($product->making_charge, 2) }}</td>

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