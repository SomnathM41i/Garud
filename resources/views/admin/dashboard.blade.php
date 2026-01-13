@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your jewelry store
            today.</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="stats-grid">

        {{-- Total Customers --}}
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $totalCustomers = App\Models\Customer::count();
                        $lastMonthCustomers = App\Models\Customer::whereMonth('created_at', now()->subMonth()->month)->count();
                        $customerTrend = $lastMonthCustomers ? round((($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $customerTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($customerTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Total Customers</div>
                <div class="stat-value">{{ $totalCustomers }}</div>
            </div>
            <div class="stat-footer">Compared to last month</div>
        </div>

        {{-- Total Revenue --}}
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $totalRevenue = App\Models\Order::where('status', 'completed')->sum('total_amount');
                        $lastMonthRevenue = App\Models\Order::where('status', 'completed')
                            ->whereMonth('created_at', now()->subMonth()->month)
                            ->sum('total_amount');
                        $revenueTrend = $lastMonthRevenue ? round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $revenueTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($revenueTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Total Revenue</div>
                <div class="stat-value">₹{{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="stat-footer">This month's earnings</div>
        </div>

        {{-- Pending Orders --}}
        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-icon warning">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-trend down">
                    @php
                        $pendingOrders = App\Models\Order::where('status', 'pending')->count();
                        $prevPendingOrders = App\Models\Order::where('status', 'pending')
                            ->whereMonth('created_at', now()->subMonth()->month)
                            ->count();
                        $pendingTrend = $prevPendingOrders ? round((($pendingOrders - $prevPendingOrders) / $prevPendingOrders) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $pendingTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($pendingTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Pending Orders</div>
                <div class="stat-value">{{ $pendingOrders }}</div>
            </div>
            <div class="stat-footer">Require attention</div>
        </div>

        {{-- Jewelry Products --}}
        <div class="stat-card danger">
            <div class="stat-header">
                <div class="stat-icon gold">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="stat-trend up">
                    @php
                        $totalProducts = App\Models\JewelleryProduct::count();
                        $lastMonthProducts = App\Models\JewelleryProduct::whereMonth('created_at', now()->subMonth()->month)->count();
                        $productTrend = $lastMonthProducts ? round((($totalProducts - $lastMonthProducts) / $lastMonthProducts) * 100, 1) : 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $productTrend >= 0 ? 'up' : 'down' }}"></i> {{ abs($productTrend) }}%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-title">Jewelry Products</div>
                <div class="stat-value">{{ $totalProducts }}</div>
            </div>
            <div class="stat-footer">In stock items</div>
        </div>

    </div>


    {{-- Alerts Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bell"></i> System Notifications
            </h3>
        </div>
        <div class="card-body">
            <div class="alert alert-success">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Success!</div>
                    <div>Your jewelry inventory has been successfully updated.</div>
                </div>
                <button class="alert-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="alert alert-warning">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Warning</div>
                    <div>Low stock alert: 5 products are running low on inventory.</div>
                </div>
                <button class="alert-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="alert alert-info">
                <div class="alert-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Information</div>
                    <div>You have 3 new customer inquiries waiting for response.</div>
                </div>
                <button class="alert-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="alert alert-danger">
                <div class="alert-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Error</div>
                    <div>Payment gateway connection failed. Please check your settings.</div>
                </div>
                <button class="alert-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Forms Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i> Sample Form Components
            </h3>
        </div>
        <div class="card-body">
            <form>
                {{-- Text Input --}}
                <div class="form-group">
                    <label class="form-label" for="productName">
                        Product Name <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" id="productName" class="form-control" placeholder="Enter product name">
                </div>

                {{-- Email Input --}}
                <div class="form-group">
                    <label class="form-label" for="email">
                        Email Address
                    </label>
                    <input type="email" id="email" class="form-control" placeholder="customer@example.com">
                </div>

                {{-- Number Input with Icon --}}
                <div class="form-group">
                    <label class="form-label" for="price">
                        Price
                    </label>
                    <div class="input-group">
                        <input type="number" id="price" class="form-control" placeholder="0.00">
                        <span class="input-group-text">
                            <i class="fas fa-indian-rupee-sign"></i>
                        </span>
                    </div>
                </div>

                {{-- Select Dropdown --}}
                <div class="form-group">
                    <label class="form-label" for="category">
                        Jewelry Category
                    </label>
                    <select id="category" class="form-control form-select">
                        <option value="">Select a category</option>
                        <option value="rings">Rings</option>
                        <option value="necklaces">Necklaces</option>
                        <option value="earrings">Earrings</option>
                        <option value="bracelets">Bracelets</option>
                        <option value="pendants">Pendants</option>
                    </select>
                </div>

                {{-- Textarea --}}
                <div class="form-group">
                    <label class="form-label" for="description">
                        Description
                    </label>
                    <textarea id="description" class="form-control" rows="4"
                        placeholder="Enter product description..."></textarea>
                </div>

                {{-- Checkboxes --}}
                <div class="form-group">
                    <label class="form-label">Metal Types</label>
                    <div class="form-check">
                        <input type="checkbox" id="gold" class="form-check-input">
                        <label class="form-check-label" for="gold">Gold</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="silver" class="form-check-input">
                        <label class="form-check-label" for="silver">Silver</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="platinum" class="form-check-input">
                        <label class="form-check-label" for="platinum">Platinum</label>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="form-group" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="button" class="btn btn-gold"
                        onclick="showToast('Success', 'Product saved successfully!', 'success')">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                    <button type="button" class="btn btn-primary" onclick="showToast('Info', 'Draft saved!', 'info')">
                        <i class="fas fa-file"></i> Save Draft
                    </button>
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-check"></i> Publish
                    </button>
                    <button type="button" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger"
                        onclick="showToast('Error', 'Failed to delete product!', 'danger')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>

                {{-- Button Sizes --}}
                <div class="form-group" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Small Button
                    </button>
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-check"></i> Regular Button
                    </button>
                    <button type="button" class="btn btn-gold btn-lg">
                        <i class="fas fa-star"></i> Large Button
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table"></i> Recent Orders
            </h3>
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Order
            </button>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>#ORD-1001</strong></td>
                            <td>Priya Sharma</td>
                            <td>Diamond Ring</td>
                            <td>₹85,000</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>Jan 15, 2026</td>
                            <td>
                                <button class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1002</strong></td>
                            <td>Rahul Mehta</td>
                            <td>Gold Necklace</td>
                            <td>₹1,25,000</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>Jan 14, 2026</td>
                            <td>
                                <button class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1003</strong></td>
                            <td>Anjali Verma</td>
                            <td>Silver Earrings</td>
                            <td>₹15,500</td>
                            <td><span class="badge badge-info">Processing</span></td>
                            <td>Jan 14, 2026</td>
                            <td>
                                <button class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1004</strong></td>
                            <td>Vikram Singh</td>
                            <td>Platinum Band</td>
                            <td>₹95,000</td>
                            <td><span class="badge badge-danger">Cancelled</span></td>
                            <td>Jan 13, 2026</td>
                            <td>
                                <button class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1005</strong></td>
                            <td>Neha Kapoor</td>
                            <td>Gemstone Pendant</td>
                            <td>₹45,000</td>
                            <td><span class="badge badge-gold">On Hold</span></td>
                            <td>Jan 12, 2026</td>
                            <td>
                                <button class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Badge Examples --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-tags"></i> Badge Components
            </h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                <span class="badge badge-success">
                    <i class="fas fa-check"></i> Active
                </span>
                <span class="badge badge-warning">
                    <i class="fas fa-clock"></i> Pending
                </span>
                <span class="badge badge-danger">
                    <i class="fas fa-times"></i> Cancelled
                </span>
                <span class="badge badge-info">
                    <i class="fas fa-info"></i> Processing
                </span>
                <span class="badge badge-gold">
                    <i class="fas fa-star"></i> Premium
                </span>
                <span class="badge badge-success">In Stock: 150</span>
                <span class="badge badge-warning">Low Stock: 5</span>
                <span class="badge badge-danger">Out of Stock</span>
            </div>
        </div>
    </div>

    {{-- Toast Demo Button --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-message"></i> Toast Notifications Demo
            </h3>
        </div>
        <div class="card-body">
            <p style="margin-bottom: 1rem; color: var(--text-secondary);">
                Click the buttons below to see toast notifications in action:
            </p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <button class="btn btn-success"
                    onclick="showToast('Success!', 'Your action was completed successfully.', 'success')">
                    Show Success Toast
                </button>
                <button class="btn btn-warning"
                    onclick="showToast('Warning!', 'Please review this action carefully.', 'warning')">
                    Show Warning Toast
                </button>
                <button class="btn btn-danger"
                    onclick="showToast('Error!', 'Something went wrong. Please try again.', 'danger')">
                    Show Error Toast
                </button>
                <button class="btn btn-info"
                    onclick="showToast('Information', 'Here is some useful information for you.', 'info')">
                    Show Info Toast
                </button>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bolt"></i> Quick Actions
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <button class="btn btn-outline" style="height: 80px; flex-direction: column;">
                    <i class="fas fa-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    Add New Product
                </button>
                <button class="btn btn-outline" style="height: 80px; flex-direction: column;">
                    <i class="fas fa-file-invoice" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    Create Invoice
                </button>
                <button class="btn btn-outline" style="height: 80px; flex-direction: column;">
                    <i class="fas fa-users" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    Manage Customers
                </button>
                <button class="btn btn-outline" style="height: 80px; flex-direction: column;">
                    <i class="fas fa-chart-bar" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    View Reports
                </button>
            </div>
        </div>
    </div>

@endsection