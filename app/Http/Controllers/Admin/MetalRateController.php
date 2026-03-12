<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetalRate;
use App\Models\JewelleryProduct;
use Illuminate\Http\Request;

class MetalRateController extends Controller
{
    /**
     * List all metal rates
     */
    public function index()
    {
        $rates = MetalRate::latest('rate_date')->get();
        return view('admin.metal_rates.index', compact('rates'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.metal_rates.create');
    }

    /**
     * Store metal rate
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'metal' => 'required|in:gold,silver',
            'purity_percent' => 'required|numeric|min:0|max:100',
            'rate_per_gram' => 'required|numeric|min:0',
            'rate_date' => 'required|date',
        ]);

        MetalRate::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Metal rate added successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(MetalRate $metalRate)
    {
        return view('admin.products.index', compact('metalRate'));
    }

    /**
     * Update metal rate
     */
    public function update(Request $request, MetalRate $metalRate)
    {
        $validated = $request->validate([
            'metal' => 'required|in:gold,silver',
            'purity_percent' => 'required|numeric|min:0|max:100',
            'rate_per_gram' => 'required|numeric|min:0',
            'rate_date' => 'required|date',
        ]);

        $metalRate->update($validated);
        $this->updateProductSellingPrices(
            $metalRate->metal,
            $metalRate->purity_percent,
            $metalRate->rate_per_gram
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Metal rate updated successfully.');
    }

    /**
     * Update selling prices for products based on metal rate
     */
    protected function updateProductSellingPrices(string $metal, float $purity, float $ratePerGram): void
    {
        $products = JewelleryProduct::where('metal_type', $metal)
            ->where('purity_percent', $purity)
            ->get();

        foreach ($products as $product) {
            // Calculate new cost price based on fine gold weight
            $costPrice = $product->fine_gold_weight * $ratePerGram;

            // Update selling price including handling_cost + making_charge
            $product->update([
                'cost_price' => $costPrice + ($product->handling_cost ?? 0) + ($product->making_charge ?? 0),
            ]);
        }
    }

    /**
     * Delete metal rate
     */
    public function destroy(MetalRate $metalRate)
    {
        $metalRate->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Metal rate deleted successfully.');
    }
}
