<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * List all borrowings — pending/partial first, then completed
     */
    public function index()
    {
        $borrowings = Borrowing::with('customer', 'order')
            ->orderByRaw("FIELD(status, 'pending', 'partial', 'completed')")
            ->latest()
            ->get();

        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Show a single borrowing with full payment history
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load('customer', 'order.items.product', 'payments');

        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Record a new repayment against a borrowing
     */
    public function addPayment(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->isFullyPaid()) {
            return back()->with('error', 'This borrowing is already fully paid.');
        }

        $request->validate([
            'amount'         => 'required|numeric|min:1|max:' . $borrowing->remaining_amount,
            'payment_method' => 'required|in:cash,card,upi,other',
            'payment_date'   => 'required|date',
            'notes'          => 'nullable|string|max:255',
        ]);

        $borrowing->addPayment(
            $request->amount,
            $request->payment_method,
            $request->payment_date,
            $request->notes
        );

        return back()->with('success', 'Payment of ₹' . number_format($request->amount, 2) . ' recorded successfully.');
    }
}