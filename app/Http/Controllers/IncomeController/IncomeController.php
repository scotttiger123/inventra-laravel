<?php


namespace App\Http\Controllers\IncomeController;

use App\Http\Controllers\Controller;
use App\Models\PaymentHead;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $paymentHeadId = $request->input('payment_head_id');
    
        $paymentHeads = PaymentHead::all();
    
        $query = Payment::with('paymentHead')
            ->where('payable_type', 'income');
    
        if ($startDate && $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }
    
        if ($paymentHeadId) {
            $query->where('payable_id', $paymentHeadId);
        }
    
        $payments = $query->get();
    
        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount');
        $totalCountAmount = $totalAmount;
    
        return view('incomes.index', compact('payments', 'totalPayments', 'totalAmount', 'totalCountAmount', 'paymentHeads'));
    }


    public function indexPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $paymentHeadId = $request->input('payment_head_id');
    
        $query = Payment::with('paymentHead')
                        ->where('payable_type', 'income');
    
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
        $paymentHeads = PaymentHead::where('type', 'income')->get(); // Fetch only income heads
        return view('incomes.create', compact('paymentHeads'));
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

            // Create the income record
            $income = Payment::create([
                'payment_date' => $validated['payment_date'],
                'payable_id' => $paymentHead->id, // Store payment head ID in payable_id
                'payment_head' => $paymentHead->id, // Store payment head name in payment_head
                'payable_type' => 'income',
                'amount' => $validated['amount'],
                'note' => $validated['note'],
                'payment_type' => 'credit',
                'status' => 'completed',
                'created_by' => Auth::id(),
            ]);

            // Redirect back with success message
            return redirect()->route('income.index')->with('success', 'Income added successfully!');
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Failed to add income: ' . $e->getMessage());

            // Redirect back with error message
            return back()->with('error', 'Failed to add income. Please try again.');
        }
    }

    public function edit($id)
    {
        
        $income = Payment::findOrFail($id);
          
        $paymentHeads = PaymentHead::where('type', 'income')->get();
    
        
        return view('incomes.edit', compact('income', 'paymentHeads'));
    }
    
    
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_head' => 'required',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);
    
        try {
            // Find the income record to update
            $income = Payment::findOrFail($id);
    
            // Fetch the payment head based on the selected ID
            $paymentHead = PaymentHead::findOrFail($validated['payment_head']);
    
            // Update the income record
            $income->update([
                'payment_date' => $validated['payment_date'],
                'payable_id' => $paymentHead->id, // Store payment head ID in payable_id
                'payment_head' => $paymentHead->id, // Store payment head name in payment_head
                'payable_type' => 'income',
                'amount' => $validated['amount'],
                'note' => $validated['note'],
                'updated_by' => Auth::id(), // Log who updated the record
            ]);
    
            // Redirect back with success message
            return redirect()->route('income.index')->with('success', 'Income updated successfully!');
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Failed to update income: ' . $e->getMessage());
    
            // Redirect back with error message
            return back()->with('error', 'Failed to update income. Please try again.');
        }
    }
    

    public function destroy($payment)
    {
        // Find the payment by its ID
        $payment = Payment::findOrFail($payment);

        // Perform the delete operation
        $payment->delete();

        // Redirect back with a success message
        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
    }
}
