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

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        return view('admin.cart.index', compact('cartItems', 'totalAmount'));
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

        $goldRateRow = MetalRate::latestByPurity('gold', $product->purity_percent)->first();

        if (!$goldRateRow) {
            return back()->with('error', 'Gold rate not available.');
        }

        $todayGoldRate = $goldRateRow->rate_per_gram;

        // Stock check
        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

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
            ]);

        } else {

            Cart::create([
                'sales_user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,

                /* ===== SNAPSHOT (per unit only) ===== */
                'gross_weight' => $product->gross_weight,
                'net_weight' => $product->net_weight,
                'fine_gold_weight' => $product->fine_gold_weight,
                'purity_percent' => $product->purity_percent,

                'gold_rate' => $todayGoldRate,
                'gold_value' => $product->cost_price,
                'making_charge' => $product->making_charge ?? 0,
                'selling_price' => $product->cost_price + ($product->making_charge ?? 0),
            ]);

        }

        return back()->with('success', 'Product added to cart.');
    }

    /**
     * Update quantity
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->sales_user_id !== auth()->id()) {
            abort(403);
        }

        if ($request->quantity > $cart->product->stock_quantity) {
            return back()->with('error', 'Quantity exceeds available stock.');
        }

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove item
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
