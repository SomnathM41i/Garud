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
        $request->validate([
            'product_code' => 'required|string|max:50|unique:jewellery_products,product_code',
            'product_name' => 'required|string|max:150',
            'category_id' => 'required|exists:jewellery_categories,id',
            'metal_type' => 'required|string|max:50',
            'purity' => 'nullable|string|max:20',
            'weight' => 'nullable|numeric',
            'making_charges' => 'nullable|numeric',
            'price' => 'required|numeric',
            'stock_quantity' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        JewelleryProduct::create($request->all());

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
        $request->validate([
            'product_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('jewellery_products')->ignore($product->id),
            ],
            'product_name' => 'required|string|max:150',
            'category_id' => 'required|exists:jewellery_categories,id',
            'metal_type' => 'required|string|max:50',
            'purity' => 'nullable|string|max:20',
            'weight' => 'nullable|numeric',
            'making_charges' => 'nullable|numeric',
            'price' => 'required|numeric',
            'stock_quantity' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($request->all());

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
