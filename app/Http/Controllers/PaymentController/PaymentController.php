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
    


      // Show create role form
      public function create()
      {
          
          $Payment = Payment::all(); // Fetch all roles
          return view('payments.create', compact('Payment')); // Pass roles to the view
      }

      public function index()
      {
         
        $payments = Payment::whereIn('payable_type', ['customer', 'supplier'])->get()->map(function ($payment) {
            if ($payment->payable_type === 'customer') {
                $payment->payable_name = Customer::where('id', $payment->payable_id)->value('name');
            } elseif ($payment->payable_type === 'supplier') {
                $payment->payable_name = Supplier::where('id', $payment->payable_id)->value('name');
            }
            return $payment;
        });
  
                    
        $totalDebit = $payments->where('payment_type', 'debit')->sum('amount');
    
        $totalCredit = $payments->where('payment_type', 'credit')->sum('amount');

          // Pass all data to the view, including individual payment details
          return view('payments.index', compact('payments',
              'totalDebit', 'totalCredit'
              
          ));
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



    public function getPaymentDetails($voucherId)
    {
        try {
            $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value');
            $payment = Payment::findOrFail($voucherId);
    
            $payableType = $payment->payable_type;
            $payableId = $payment->payable_id;
    
            if ($payableType == 'customer') {
                $payable = Customer::findOrFail($payableId);
                $payableName = $payable->name;
            } elseif ($payableType == 'supplier') {
                $payable = Supplier::findOrFail($payableId);
                $payableName = $payable->name;
            } else {
                $payableName = 'Unknown';
            }
    
            $data = [
                'payment' => [
                    'payment_date' => $payment->payment_date,
                    'amount' =>  number_format($payment->amount, 2), // Format amount with currency
                    'payment_type' => $payment->payment_type,
                    
                    'payment_method' => $payment->payment_method,
                    'note' => $payment->note,
                    'payable_name'  => $payableName,
                    'currency'      => $currencySymbol,
                    
                ]
            ];
    
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment not found or an error occurred.'], 404);
        }
    }
    

}