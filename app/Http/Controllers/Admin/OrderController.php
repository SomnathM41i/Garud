<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * List all orders
     */
    public function index()
    {
        $orders = Order::with('customer')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show create order page (cart + customer)
     */
    public function create()
    {
        $cartItems = Cart::with('product')
            ->where('sales_user_id', auth()->id())
            ->get();

        return view('admin.orders.create', compact('cartItems'));
    }

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'payment_method' => 'required|in:cash,card,upi,other',
        ]);

        DB::transaction(function () use ($request) {

            /** Customer */
            $customer = Customer::firstOrCreate(
                ['phone' => $request->phone],
                ['name' => $request->customer_name]
            );

            /** Create Order */
            $order = Order::create([
                'invoice_number' => 'Garud-' . strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'total_amount' => 0,
                'total_profit' => 0,
                'status' => 'pending',
            ]);

            $cartItems = Cart::with('product')
                ->where('sales_user_id', auth()->id())
                ->get();

            $totalAmount = 0;
            $totalProfit = 0;
            //  dd($cartItems);

            /** Order Items */
            foreach ($cartItems as $cart) {
                //  dd($cartItems, $cart->product->price);
                $subtotal = ($cart->price + $cart->making_charges) * $cart->quantity;
                $profit = (($cart->price - $cart->product->price) + $cart->making_charges) * $cart->quantity;
                //   dd($profit, $subtotal);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price,
                    'cost_price' => $cart->product->price,
                    'making_charges' => $cart->making_charges,
                ]);

                $totalAmount += $subtotal;
                $totalProfit += $profit;
            }

            /** Payment */
            Payment::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            /** Deduct Stock */
            foreach ($order->items as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            /** Final Order Update */
            $order->update([
                'total_amount' => $totalAmount,
                'total_profit' => $totalProfit,
                'status' => 'completed',
            ]);

            /** Clear Cart */
            Cart::where('sales_user_id', auth()->id())->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * View order details
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'customer', 'payment');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Delete order (admin only)
     */
    public function destroy(Order $order)
    {
        if ($order->status === 'completed') {
            return back()->with('error', 'Completed order cannot be deleted.');
        }

        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
