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
        // print ($orders->toJson());
        // exit;
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

            /* ======================
            CUSTOMER
            ====================== */

            $customer = Customer::firstOrCreate(
                ['phone' => $request->phone],
                ['name' => $request->customer_name]
            );

            /* ======================
            CREATE ORDER
            ====================== */

            $order = Order::create([
                'invoice_number' => 'GARUD-' . strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'total_amount' => 0,
                'total_profit' => 0,
                'status' => 'pending',
            ]);

            /* ======================
            CART ITEMS
            ====================== */

            $cartItems = Cart::with('product')
                ->where('sales_user_id', auth()->id())
                ->get();

            $totalAmount = 0;
            $totalProfit = 0;

            foreach ($cartItems as $cart) {

                /* ======================
                CREATE ORDER ITEM
                ====================== */

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,

                    /* GOLD SNAPSHOT */
                    'gross_weight' => $cart->gross_weight,
                    'net_weight' => $cart->net_weight,
                    'fine_gold_weight' => $cart->fine_gold_weight,
                    'purity_percent' => $cart->purity_percent,

                    'gold_rate' => $cart->gold_rate,
                    'gold_value' => $cart->gold_value,
                    'making_charge' => $cart->making_charge,

                    /* BUYING SNAPSHOT */
                    'buying_gold_weight' => $cart->buying_gold_weight,

                    /* SELLING */
                    'selling_price' => $cart->selling_price,

                    /* PROFIT SNAPSHOT */
                    'profit_gold' => $cart->profit_gold,
                    'profit_cash' => $cart->total_profit,
                ]);

                $totalAmount += $cart->selling_price * $cart->quantity;
                $totalProfit += $cart->total_profit;
            }

            /* ======================
            PAYMENT
            ====================== */

            Payment::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            /* ======================
            STOCK REDUCTION
            ====================== */

            foreach ($cartItems as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            /* ======================
            FINAL ORDER UPDATE
            ====================== */

            $order->update([
                'total_amount' => $totalAmount,
                'total_profit' => $totalProfit,
                'status' => 'completed',
            ]);

            /* ======================
            CLEAR CART
            ====================== */

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
