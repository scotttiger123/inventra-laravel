<?php

namespace App\Http\Controllers\PaymentController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentHead;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all(); // Fetch all payments
        return view('payments.index', compact('payments'));
    }

    
    public function create()
    {
        $paymentHeads = PaymentHead::all(); 
        $user = auth()->user(); 
        $parentUserId = $user->parent_user_id; 

    
        $customers = Customer::where('parent_user_id', $parentUserId)->get();
        $suppliers = Supplier::where('parent_user_id', $parentUserId)->get();

        return view('payments.create', compact('paymentHeads', 'customers', 'suppliers'));
    }

    
    public function getPayableOptions($head)
    {
        // Log to verify that $head is received correctly
        \Log::debug('Payment Head:', [$head]);
    
        $user = auth()->user();
        $parentUserId = $user->parent_user_id;
    
        $customers = [];
        $suppliers = [];
    
        if ($head === 'customer') {
            $customers = Customer::where(function ($query) use ($parentUserId, $user) {
                $query->where('parent_user_id', $parentUserId)
                      ->orWhere('created_by', $user->id);
            })->get();
        } elseif ($head === 'supplier') {
            $suppliers = Supplier::where(function ($query) use ($parentUserId, $user) {
                $query->where('parent_user_id', $parentUserId)
                      ->orWhere('created_by', $user->id);
            })->get();
        }
    
        return response()->json(['customers' => $customers, 'suppliers' => $suppliers]);
    }
    




    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
            'payment_type' => 'required|in:credit,debit',
            
        ]);

        Payment::create([
            'amount' => $request->amount,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
            'payable_id' => $request->payable_id,
            'payable_type' => $request->payable_type,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
            'created_by' => auth()->id(), 
            
        ]);

        return redirect()->route('payments.create')->with('success', 'Payment created successfully!');
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $paymentHeads = PaymentHead::all();
        return view('payments.edit', compact('payment', 'paymentHeads'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
            'payment_type' => 'required|in:credit,debit',
        ]);

        $payment->update([
            'amount' => $request->amount,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }
}