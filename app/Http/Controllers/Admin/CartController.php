<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\MetalRate;
use App\Models\JewelleryProduct;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * View cart items
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('sales_user_id', auth()->id())
            ->get();

        $totalAmount = $cartItems->sum(fn($item) => $item->subtotal);
        $totalProfit = $cartItems->sum(fn($item) => $item->total_profit);

        return view('admin.cart.index', compact('cartItems', 'totalAmount', 'totalProfit'));
    }

    /**
     * Add product to cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:jewellery_products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = JewelleryProduct::findOrFail($request->product_id);

        // SELLING RATE (based on product purity)
        $sellingRateRow = MetalRate::where('metal', $product->metal_type)
            ->where('purity_percent', $product->purity_percent)
            ->latest('rate_date')
            ->first();

        if (!$sellingRateRow) {
            return back()->with('error', 'Selling metal rate not available.');
        }

        // BUYING RATE (based on buying purity)
        $buyingRateRow = MetalRate::where('metal', $product->metal_type)
            ->where('purity_percent', $sellingRateRow->buying_purity_percent)
            ->latest('rate_date')
            ->first();

        if (!$buyingRateRow) {
            return redirect()
                ->route('admin.metal-rates.index')
                ->with('error', 'Buying metal rate not available.');
        }

        $sellingRatePerGram = $sellingRateRow->rate_per_gram;
        $buyingRatePerGram = $buyingRateRow->rate_per_gram;
        $buyingPurity = $sellingRateRow->buying_purity_percent;

        // Stock check
        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        // PROFIT CALCULATION
        $netWeight = $product->net_weight;

        $sellingValuePerUnit = $netWeight * ($product->purity_percent / 100) * $sellingRatePerGram;
        $buyingCostPerUnit = $netWeight * ($buyingPurity / 100) * $buyingRatePerGram;

        $profitPerUnit = $sellingValuePerUnit - $buyingCostPerUnit;
        $sellingPricePerUnit = $sellingValuePerUnit + ($product->making_charge ?? 0);

        // Check if product already in cart
        $cart = Cart::where('sales_user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQty = $cart->quantity + $request->quantity;

            if ($newQty > $product->stock_quantity) {
                return back()->with('error', 'Cart quantity exceeds available stock.');
            }

            $cart->update([
                'quantity' => $newQty,
                'selling_price' => $sellingPricePerUnit,
                'total_profit' => $profitPerUnit * $newQty,
            ]);

        } else {
            Cart::create([
                'sales_user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,

                'gross_weight' => $product->gross_weight,
                'net_weight' => $product->net_weight,
                'fine_gold_weight' => $product->fine_gold_weight,
                'purity_percent' => $product->purity_percent,

                'gold_rate' => $sellingRatePerGram,
                'gold_value' => $sellingValuePerUnit,
                'making_charge' => $product->making_charge ?? 0,

                'selling_price' => $sellingPricePerUnit,
                'total_profit' => $profitPerUnit * $request->quantity,
            ]);
        }

        return redirect()
            ->route('admin.cart.index')
            ->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update selling price & profit
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'selling_price' => 'required|numeric|min:0',
        ]);

        if ($cart->sales_user_id !== auth()->id()) {
            abort(403);
        }

        // SELLING RATE info stored in cart snapshot
        $sellingRatePerGram = $cart->gold_rate;
        $sellingPurity = $cart->purity_percent;

        // Step 1: Get the MetalRate row for this selling purity
        $sellingRateRow = MetalRate::where('metal', $cart->product->metal_type)
            ->where('purity_percent', $sellingPurity)
            ->latest('rate_date')
            ->first();

        if ($sellingRateRow) {
            $buyingPurity = $sellingRateRow->buying_purity_percent;

            // Step 2: Get buying rate row using buying purity
            $buyingRateRow = MetalRate::where('metal', $cart->product->metal_type)
                ->where('purity_percent', $buyingPurity)
                ->latest('rate_date')
                ->first();

            $buyingRatePerGram = $buyingRateRow ? $buyingRateRow->rate_per_gram : $sellingRatePerGram;
        } else {
            // Fallback: use selling rate as buying rate
            $buyingPurity = $sellingPurity;
            $buyingRatePerGram = $sellingRatePerGram;
        }

        // Calculate profit per unit
        $profitPerUnit = $request->selling_price - ($cart->net_weight * ($buyingPurity / 100) * $buyingRatePerGram);

        // Update cart
        $cart->update([
            'selling_price' => $request->selling_price,
            'total_profit' => $profitPerUnit * $cart->quantity,
        ]);

        return back()->with('success', 'Selling price and profit updated successfully.');
    }

    /**
     * Remove item from cart
     */
    public function destroy(Cart $cart)
    {
        if ($cart->sales_user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
