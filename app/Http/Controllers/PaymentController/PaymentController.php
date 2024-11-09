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
        $payments = Payment::all()->map(function ($payment) {
            if ($payment->payable_type === 'customer') {
                $payment->payable_name = Customer::where('id', $payment->payable_id)->value('name');
            } elseif ($payment->payable_type === 'supplier') {
                $payment->payable_name = Supplier::where('id', $payment->payable_id)->value('name');
            }
            return $payment;
        });

        return view('payments.index', compact('payments'));
    }

    
    public function edit(Payment $payment)
    {
        $paymentHeads = PaymentHead::all();
        $user = auth()->user();
        $parentUserId = $user->parent_user_id;

        if ($payment->payment_head == 'customer') {
            $payables = Customer::where('parent_user_id', $parentUserId)->get();
        } else {
            $payables = Supplier::where('parent_user_id', $parentUserId)->get();
        }

        return view('payments.edit', compact('payment', 'paymentHeads', 'payables'));
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
            'payment_date' => $request->payment_date,
            'note' => $request->note,
            'created_by' => auth()->id(), 
            
        ]);

        return redirect()->route('payments.create')->with('success', 'Payment created successfully!');
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function update(Request $request, $id)
        {
            $request->validate([
                'payment_head' => 'required|string',
                'payable_id' => 'required|integer',
                'amount' => 'required|numeric',
                'payment_date' => 'required|date',
                'invoice_id' => 'nullable|integer',
                'status' => 'required|string',
                'payment_type' => 'required|string',
                'payment_method' => 'required|string',
                'note' => 'nullable|string',
            ]);

            $payment = Payment::findOrFail($id);
            $payment->update($request->all());

            return redirect()->route('payments.edit', $payment->id)->with('success', 'Payment updated successfully');
        }


    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }
}