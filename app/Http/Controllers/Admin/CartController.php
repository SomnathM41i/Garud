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

        $totalAmount = $cartItems->sum(fn($item) => $item->selling_price * $item->quantity);
        $totalProfit = $cartItems->sum('total_profit');
        $totalProfitGold = $cartItems->sum('profit_gold');

        return view('admin.cart.index', compact(
            'cartItems',
            'totalAmount',
            'totalProfit',
            'totalProfitGold'
        ));
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

        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        /*
        |---------------------------------------
        | GET CURRENT SELLING GOLD RATE
        |---------------------------------------
        */

        $sellingRateRow = MetalRate::where('metal', $product->metal_type)
            ->where('purity_percent', $product->purity_percent)
            ->latest('rate_date')
            ->first();

        if (!$sellingRateRow) {
            return back()->with('error', 'Metal rate not available.');
        }

        $goldRate = $sellingRateRow->rate_per_gram;

        /*
        |---------------------------------------
        | SELLING CALCULATIONS
        |---------------------------------------
        */

        $sellingGold = $product->fine_gold_weight;

        $goldValuePerUnit = $sellingGold * $goldRate;

        $sellingPricePerUnit = $goldValuePerUnit + ($product->making_charge ?? 0);

        /*
        |---------------------------------------
        | BUYING GOLD CALCULATION
        |---------------------------------------
        */

        $buyingGold = $product->net_weight * ($product->buying_purity_percent / 100);

        /*
        |---------------------------------------
        | PROFIT CALCULATIONS
        |---------------------------------------
        */

        $profitGoldPerUnit = $sellingGold - $buyingGold;

        $profitCashPerUnit = $profitGoldPerUnit * $goldRate;

        /*
        |---------------------------------------
        | CHECK IF PRODUCT ALREADY IN CART
        |---------------------------------------
        */

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
                'total_profit' => $profitCashPerUnit * $newQty,
                'profit_gold' => $profitGoldPerUnit * $newQty,
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

                /* BUYING SNAPSHOT */
                'buying_purity_percent' => $product->buying_purity_percent,
                'buying_gold_weight' => $buyingGold,

                /* RATE SNAPSHOT */
                'gold_rate' => $goldRate,
                'gold_value' => $goldValuePerUnit,
                'making_charge' => $product->making_charge ?? 0,

                'selling_price' => $sellingPricePerUnit,

                /* PROFIT */
                'profit_gold' => $profitGoldPerUnit * $request->quantity,
                'total_profit' => $profitCashPerUnit * $request->quantity,
            ]);
        }

        return redirect()
            ->route('admin.cart.index')
            ->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update selling price
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'selling_price' => 'required|numeric|min:0',
        ]);

        if ($cart->sales_user_id !== auth()->id()) {
            abort(403);
        }

        $newSellingPrice = $request->selling_price;

        /*
        |---------------------------------------
        | GOLD SNAPSHOT VALUES
        |---------------------------------------
        */

        $sellingGold = $cart->fine_gold_weight;
        $buyingGold  = $cart->buying_gold_weight;
        $goldRate    = $cart->gold_rate;

        /*
        |---------------------------------------
        | PROFIT GOLD
        |---------------------------------------
        */

        $profitGoldPerUnit = $sellingGold - $buyingGold;

        /*
        |---------------------------------------
        | PROFIT CASH
        |---------------------------------------
        */

        $goldCostPerUnit = $buyingGold * $goldRate;

        $profitCashPerUnit = $newSellingPrice - $goldCostPerUnit;

        /*
        |---------------------------------------
        | UPDATE CART
        |---------------------------------------
        */

        $cart->update([
            'selling_price' => $newSellingPrice,
            'profit_gold'   => $profitGoldPerUnit,
            'total_profit'  => $profitCashPerUnit * $cart->quantity,
        ]);

        return back()->with('success', 'Selling price updated successfully.');
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