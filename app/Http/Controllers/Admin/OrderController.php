<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Borrowing;
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
        $orders = Order::with('customer', 'payment', 'borrowing')
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
     * Store new order — supports full payment OR borrowing
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'phone'          => 'required|string|max:15',
            'payment_type'   => 'required|in:full,borrow',

            // Full payment fields
            'payment_method' => 'required_if:payment_type,full|in:cash,card,upi,other',

            // Borrowing fields
            'upfront_amount' => 'required_if:payment_type,borrow|nullable|numeric|min:0',
            'due_date'       => 'nullable|date|after:today',
            'borrow_notes'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {

            /* ======================
               CUSTOMER
            ====================== */
            $customer = Customer::firstOrCreate(
                ['phone' => $request->phone],
                ['name'  => $request->customer_name]
            );

            /* ======================
               CREATE ORDER
            ====================== */
            $order = Order::create([
                'invoice_number' => 'GARUD-' . strtoupper(Str::random(8)),
                'customer_id'    => $customer->id,
                'total_amount'   => 0,
                'total_profit'   => 0,
                'status'         => 'pending',
            ]);

            /* ======================
               CART ITEMS → ORDER ITEMS
            ====================== */
            $cartItems = Cart::with('product')
                ->where('sales_user_id', auth()->id())
                ->get();

            $totalAmount = 0;
            $totalProfit = 0;

            foreach ($cartItems as $cart) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $cart->product_id,
                    'quantity'          => $cart->quantity,
                    'gross_weight'      => $cart->gross_weight,
                    'net_weight'        => $cart->net_weight,
                    'fine_gold_weight'  => $cart->fine_gold_weight,
                    'purity_percent'    => $cart->purity_percent,
                    'gold_rate'         => $cart->gold_rate,
                    'gold_value'        => $cart->gold_value,
                    'making_charge'     => $cart->making_charge,
                    'buying_gold_weight'=> $cart->buying_gold_weight,
                    'selling_price'     => $cart->selling_price,
                    'profit_gold'       => $cart->profit_gold,
                    'profit_cash'       => $cart->total_profit,
                ]);

                $totalAmount += $cart->selling_price * $cart->quantity;
                $totalProfit += $cart->total_profit;
            }

            /* ======================
               PAYMENT OR BORROWING
            ====================== */
            if ($request->payment_type === 'full') {

                // Full payment — existing behavior
                Payment::create([
                    'order_id'       => $order->id,
                    'amount'         => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'status'         => 'completed',
                ]);

                $order->update([
                    'total_amount' => $totalAmount,
                    'total_profit' => $totalProfit,
                    'status'       => 'completed',
                ]);

            } else {

                // Borrowing — customer pays partial now, rest later
                $upfront   = (float) ($request->upfront_amount ?? 0);
                $remaining = $totalAmount - $upfront;

                $borrowing = Borrowing::create([
                    'order_id'         => $order->id,
                    'customer_id'      => $customer->id,
                    'total_amount'     => $totalAmount,
                    'paid_amount'      => $upfront,
                    'remaining_amount' => $remaining,
                    'due_date'         => $request->due_date,
                    'notes'            => $request->borrow_notes,
                    'status'           => $upfront > 0 ? 'partial' : 'pending',
                ]);

                // Record upfront payment as first BorrowingPayment if any was paid
                if ($upfront > 0) {
                    $borrowing->payments()->create([
                        'amount'         => $upfront,
                        'payment_method' => $request->payment_method ?? 'cash',
                        'payment_date'   => now()->toDateString(),
                        'notes'          => 'Upfront payment at time of purchase',
                    ]);
                }

                // Order stays as completed (product is handed over), but payment is pending
                $order->update([
                    'total_amount' => $totalAmount,
                    'total_profit' => $totalProfit,
                    'status'       => 'completed',
                ]);
            }

            /* ======================
               REDUCE STOCK
            ====================== */
            foreach ($cartItems as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }

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
        $order->load('items.product', 'customer', 'payment', 'borrowing.payments');

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