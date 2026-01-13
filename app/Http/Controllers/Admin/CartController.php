<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\JewelleryProduct;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * View cart items for logged-in sales user
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('sales_user_id', auth()->id())
            ->get();

        return view('admin.cart.index', compact('cartItems'));
    }

    /**
     * Add product to cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:jewellery_products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'making_charges' => 'nullable|numeric|min:0',
        ]);

        $product = JewelleryProduct::findOrFail($request->product_id);

        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = Cart::where('sales_user_id', auth()->id())
            ->where('product_id', $request->product_id)
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
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'making_charges' => $request->making_charges ?? 0,
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    /**
     * Update cart item (quantity / price / making charges)
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'making_charges' => 'nullable|numeric|min:0',
        ]);

        // Security: only owner can update
        if ($cart->sales_user_id !== auth()->id()) {
            abort(403);
        }

        $cart->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
            'making_charges' => $request->making_charges ?? 0,
        ]);

        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove item from cart
     */
    public function destroy(Cart $cart)
    {
        // Security: only owner can delete
        if ($cart->sales_user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
