<?php

namespace App\Http\Controllers;

use App\Models\JewelleryProduct;
use App\Models\JewelleryCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JewelleryProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = JewelleryProduct::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = JewelleryCategory::where('status', 'active')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|max:50|unique:jewellery_products,product_code',
            'product_name' => 'required|string|max:150',
            'category_id' => 'required|exists:jewellery_categories,id',
            'metal_type' => 'required|string|max:50',
            'purity' => 'nullable|string|max:20',
            'weight' => 'nullable|numeric',

            // COST STRUCTURE
            'cost_price' => 'required|numeric|min:0',
            'handling_cost' => 'nullable|numeric|min:0',

            // SELLING
            'selling_price' => 'required|numeric|min:0',

            'stock_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        JewelleryProduct::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product added successfully.');
    }

    /**
     * Show the form for editing the product.
     */
    public function edit(JewelleryProduct $product)
    {
        $categories = JewelleryCategory::where('status', 'active')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, JewelleryProduct $product)
    {
        $validated = $request->validate([
            'product_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('jewellery_products', 'product_code')->ignore($product->id),
            ],
            'product_name' => 'required|string|max:150',
            'category_id' => 'required|exists:jewellery_categories,id',
            'metal_type' => 'required|string|max:50',
            'purity' => 'nullable|string|max:20',
            'weight' => 'nullable|numeric',

            // COST STRUCTURE
            'cost_price' => 'required|numeric|min:0',
            'handling_cost' => 'nullable|numeric|min:0',

            // SELLING
            'selling_price' => 'required|numeric|min:0',

            'stock_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(JewelleryProduct $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
