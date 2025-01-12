<?php

namespace App\Http\Controllers\ExpenseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHead;
use App\Models\Payment; 
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
            
    
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $paymentHeadId = $request->input('payment_head_id');
    
        $paymentHeads = PaymentHead::all();
    
        $query = Payment::with('paymentHead')
            ->where('payable_type', 'expense');
    
        if ($startDate && $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }
    
        if ($paymentHeadId) {
            $query->where('payable_id', $paymentHeadId);
        }
    
        $payments = $query->get();
    
        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount');
        $totalCountAmount = $totalPayments * $totalAmount;
    
        return view('expenses.index', compact('payments', 'totalPayments', 'totalAmount', 'totalCountAmount', 'paymentHeads'));
    }
    


    public function indexPDF(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $paymentHeadId = $request->input('payment_head_id');

    $query = Payment::with('paymentHead')
                    ->where('payable_type', 'expense');

    if ($startDate && $endDate) {
        $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    if ($paymentHeadId) {
        $query->where('payable_id', $paymentHeadId);
    }

    $payments = $query->get();

    $totalPayments = $payments->count();
    $totalAmount = $payments->sum('amount');
    $totalCountAmount = $totalPayments * $totalAmount;

    $payments->each(function ($payment) {
        $payment->paymentHeadName = $payment->paymentHead ? $payment->paymentHead->name : 'Unknown Payment Head';
    });

    return response()->json([
        'payments' => $payments,
        'totalPayments' => $totalPayments,
        'totalAmount' => $totalAmount,
        'totalCountAmount' => $totalCountAmount,
    ]);
}


                    


    public function create()
    {
        $paymentHeads = PaymentHead::where('type', 'expense')->get(); // Fetch only expense heads
        return view('expenses.create', compact('paymentHeads'));
    }
    


        


public function store(Request $request)
{
    // Validate the incoming request data
    $validated = $request->validate([
        'payment_date' => 'required|date',
        'payment_head' => 'required',
        'amount' => 'required|numeric|min:0',
        'note' => 'nullable|string|max:255',
    ]);

    try {

        $paymentHead = PaymentHead::findOrFail($validated['payment_head']);

        // Create the expense record
        $expense = Payment::create([
            'payment_date' => $validated['payment_date'],
            'payable_id' => $paymentHead->id, // Store payment head ID in payable_id
            'payment_head' => $paymentHead->id, // Store payment head name in payment_head
            'payable_type' => 'expense', 
            'amount' => $validated['amount'],
            'note' => $validated['note'],
            'payment_type' => 'debit', 
            'status' => 'completed', 
            'created_by' => Auth::id(), 
        ]);

        // Redirect back with success message
        return redirect()->route('expenses.index')->with('success', 'Expense added successfully!');
    } catch (\Exception $e) {
        // Log error for debugging
        \Log::error('Failed to add expense: ' . $e->getMessage());

        // Redirect back with error message
        return back()->with('error', 'Failed to add expense. Please try again.');
    }
}

        
        public function edit($id)
        {
            $expenseHead = PaymentHead::findOrFail($id);
            return view('expenses-heads.edit-head', compact('expenseHead'));
        }
        
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
        
            $expenseHead = PaymentHead::findOrFail($id);
        
            $userId = auth()->id();
        
            // Update the expense head record
            $expenseHead->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_by' => $userId,  // Set the updated_by field
            ]);
        
            return redirect()->route('expenses-heads.index-head')->with('success', 'Expense Head updated successfully.');
        }

            
        // PaymentController.php

            public function destroy($payment)
            {
                // Find the payment by its ID
                $payment = Payment::findOrFail($payment);

                // Perform the delete operation
                $payment->delete();

                // Redirect back with a success message
                return redirect()->route('expenses.index')->with('success', 'Payment deleted successfully.');
            }


}
