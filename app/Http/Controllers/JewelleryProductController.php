<?php

namespace App\Http\Controllers;

use App\Models\JewelleryProduct;
use App\Models\MetalRate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JewelleryProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = JewelleryProduct::get();

        $totals = [
            'gross_weight' => $products->sum(function ($product) {
                return $product->gross_weight * $product->stock_quantity;
            }),

            'stone_weight' => $products->sum(function ($product) {
                return $product->stone_weight * $product->stock_quantity;
            }),

            'net_weight' => $products->sum(function ($product) {
                return $product->net_weight * $product->stock_quantity;
            }),

            'fine_gold_weight' => $products->sum(function ($product) {
                return $product->fine_gold_weight * $product->stock_quantity;
            }),

            'cost_price' => $products->sum(function ($product) {
                return $product->cost_price * $product->stock_quantity;
            }),

            'making_charge' => $products->sum(function ($product) {
                return $product->making_charge * $product->stock_quantity;
            }),
        ];

        $rates = \App\Models\MetalRate::orderByDesc('rate_date')->get();

        return view('admin.products.index', compact('products', 'totals', 'rates'));
    }



    /**
     * Show the form for creating a new product.
     */
    public function create()
    {

        $metalRates = MetalRate::select('metal', 'purity_percent', 'rate_per_gram', 'rate_date')
            ->orderBy('rate_date', 'desc')
            ->get();

        // dd($metalRates);
        return view('admin.products.create', compact('metalRates'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $validated = $request->validate([
            
            'product_name' => 'required|string|max:150',
        
            'metal_type' => 'required|string|max:50',

            /* ===== WEIGHTS ===== */
            'gross_weight' => 'required|numeric|min:0',
            'stone_weight' => 'nullable|numeric|min:0',
            'net_weight' => 'required|numeric|min:0',

            /* ===== BUYING ===== */
            'buying_purity_percent' => 'required|numeric|min:0|max:100',
            'buying_price' => 'required|numeric|min:0',

            /* ===== PURITY ===== */
            'purity_percent' => 'required|numeric|min:0|max:100',
            'fine_gold_weight' => 'required|numeric|min:0',

            /* ===== COST ===== */
            'cost_price' => 'required|numeric|min:0',
            'handling_cost' => 'nullable|numeric|min:0',

            /* ===== MAKING ===== */
            'making_charge' => 'required|numeric|min:0',

            'stock_quantity' => 'nullable|integer|min:0',
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
  

        $metalRates = MetalRate::select('metal', 'purity_percent', 'rate_per_gram', 'rate_date')
            ->orderBy('rate_date', 'desc')
            ->get();

        return view('admin.products.edit', compact('product', 'metalRates'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, JewelleryProduct $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:150',
     
            'metal_type' => 'required|string|max:50',

            /* ===== WEIGHTS ===== */
            'gross_weight' => 'required|numeric|min:0',
            'stone_weight' => 'nullable|numeric|min:0',
            'net_weight' => 'required|numeric|min:0',

            /* ===== BUYING ===== */
            'buying_purity_percent' => 'required|numeric|min:0|max:100',
            'buying_price' => 'required|numeric|min:0',

            /* ===== PURITY ===== */
            'purity_percent' => 'required|numeric|min:0|max:100',
            'fine_gold_weight' => 'required|numeric|min:0',

            /* ===== COST ===== */
            'cost_price' => 'required|numeric|min:0',
            'handling_cost' => 'nullable|numeric|min:0',

            /* ===== MAKING ===== */
            'making_charge' => 'nullable|numeric|min:0',

            'stock_quantity' => 'nullable|integer|min:0',
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
