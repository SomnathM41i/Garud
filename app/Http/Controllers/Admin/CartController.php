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
        ]);

        $product = JewelleryProduct::findOrFail($request->product_id);

        // Stock check
        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

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
                'selling_price' => $product->selling_price, // FROM PRODUCT
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Security: only owner can update
        if ($cart->sales_user_id !== auth()->id()) {
            abort(403);
        }

        // Stock validation
        if ($request->quantity > $cart->product->stock_quantity) {
            return back()->with('error', 'Quantity exceeds available stock.');
        }

        $cart->update([
            'quantity' => $request->quantity,
            'selling_price' => $request->selling_price ?? $cart->selling_price, // Allow price update if provided
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
