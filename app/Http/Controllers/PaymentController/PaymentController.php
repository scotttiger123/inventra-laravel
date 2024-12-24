<?php

namespace App\Http\Controllers\PaymentController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentHead;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SamplePaymentExport;
use Carbon\Carbon;



class PaymentController extends Controller
{
    
  
      
        public function create()
        {
            $payments = Payment::all();
            $paymentMethods = PaymentMethod::all();
            $accounts = Account::all();

            return view('payments.create', compact('payments', 'paymentMethods', 'accounts'));
        }

    



        public function index(Request $request)
        {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
        
            $query = Payment::whereIn('payable_type', ['customer', 'supplier']);
        
            if ($startDate) {
                $query->whereDate('payment_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('payment_date', '<=', $endDate);
            }
        
            $payments = $query->get()->map(function ($payment) {
                if ($payment->payable_type === 'customer') {
                    $payment->payable_name = Customer::where('id', $payment->payable_id)->value('name');
                } elseif ($payment->payable_type === 'supplier') {
                    $payment->payable_name = Supplier::where('id', $payment->payable_id)->value('name');
                }
                return $payment;
            });
        
            // Group payments by month and calculate total credit and debit for each month
            $monthlyPayments = $payments->groupBy(function ($payment) {
                return \Carbon\Carbon::parse($payment->payment_date)->format('F Y');
            })->map(function ($group) {
                return [
                    'totalCredit' => $group->where('payment_type', 'credit')->sum('amount'),
                    'totalDebit' => $group->where('payment_type', 'debit')->sum('amount'),
                ];
            });
        
            // Calculate overall totals
            $totalDebit = $payments->where('payment_type', 'debit')->sum('amount');
            $totalCredit = $payments->where('payment_type', 'credit')->sum('amount');
        
            return view('payments.index', compact('payments', 'monthlyPayments', 'totalDebit', 'totalCredit', 'startDate', 'endDate'));
        }
        



                

      public function edit(Payment $payment)
      { 
            $paymentHeads = PaymentHead::all();

          if ($payment->payable_type  == 'customer') {
              $payables = Customer::all(); 
          } elseif ($payment->payable_type  == 'supplier') {
              $payables = Supplier::all(); 
          } else {
              $payables = collect(); 
          }
      
          return view('payments.edit', compact('payment', 'payables','paymentHeads'));
      }
      

    
        public function getPayableOptions($head)
        {
            
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
    // Validation for the CSV file
    $request->validate([
        'import_csv' => 'nullable|file|mimes:csv,xlsx,txt|max:2048',  // Max size 2MB
    ]);

    // Validate amount if no CSV file is uploaded
    if (!$request->hasFile('import_csv')) {
        $request->validate([
            'amount' => 'required|numeric|min:1', 
        ]);
    }

    // Initialize payment variables
    $paymentHeadId = null;
    $payableType = null;
    $paymentType = null;
    $paymentMethodId = null;
    $paymentMethodId = PaymentMethod::where('name', $request->payment_method)->first()->id ?? null;

    
    if ($request->input('payment_head') == 'customer') {
        $paymentHead = PaymentHead::where('type', 'customer')->first();
        if ($paymentHead) {
            $payableType = 'customer';   
            $paymentHeadId = $paymentHead->id;
            $paymentType = 'credit';
        }
    } elseif ($request->input('payment_head') == 'supplier') {
        $paymentHead = PaymentHead::where('type', 'supplier')->first();
        if ($paymentHead) {
            $payableType = 'supplier';
            $paymentHeadId = $paymentHead->id;
            $paymentType = 'debit';
        }
    }

    // If CSV file is uploaded, process the file
    if ($request->hasFile('import_csv')) {
        $file = $request->file('import_csv');
        $path = $file->getRealPath();

        // Read and process CSV data
        $csvData = array_map('str_getcsv', file($path));
        $header = $csvData[0];
        $rows = array_slice($csvData, 1);

        // Insert each row from CSV into the database
        foreach ($rows as $row) {
            $paymentData = array_combine($header, $row);

            // Get the payment method ID based on the 'name' in the CSV
            $paymentMethodId = PaymentMethod::where('name', $paymentData['Payment Method'])->first()->id ?? null;
             
            $paymentDate = Carbon::createFromFormat('d/m/Y', $paymentData['Date'])->format('Y-m-d');


            Payment::create([
                'amount' => $paymentData['Amount'] ?? null,
                'payment_date' => $paymentDate ?? null,
                'status' => $paymentData['Status'] ?? null,
                'payment_type' => $paymentType ?? null,
                'payment_method' => $paymentMethodId ?? null,
                'note' => $paymentData['Payment Note'] ?? null,
                'payable_id' => $request->payable_id ?? null,  
                'payable_type' => $payableType ?? null,
                'payment_head' => $paymentHeadId ?? '',
                'account_id' => $request->account_id ?? null,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('payments.create')->with('success', 'Payments imported successfully!');
    }

    // If no CSV file is uploaded, create payment manually
    Payment::create([
        'amount' => $request->amount,
        'status' => $request->status,
        'payment_type' => $paymentType,
        'payable_id' => $request->payable_id,
        'payable_type' => $payableType ?? null,
        'payment_method' => $paymentMethodId,
        'payment_date' => $request->payment_date,
        'note' => $request->note,
        'payment_head' => $paymentHeadId,
        'account_id' => $request->account_id ?? null,
        'created_by' => auth()->id(),
    ]);

    return redirect()->route('payments.create')->with('success', 'Payment created successfully!');
}

        
        
        
        /**
         * Get Payment Head ID based on the type.
         */
        private function getPaymentHeadId($type)
        {
            $paymentHead = PaymentHead::where('type', $type)->first();
            return $paymentHead ? $paymentHead->id : null;
        }
        
        
        



               
        public function storeUsingSale(Request $request)
        {
            $request->validate([
                'amount' => 'required|numeric',
                'payment_type' => 'required|in:credit,debit',
                'payable_id' => 'required|integer',
                'payable_type' => 'required|string',
                'payment_date' => 'required|date',
                'note' => 'nullable|string',
                
            ]);
        
            // Determine the payment_head ID
            $paymentHeadId = null;
        
            if ($request->input('payment_head') == 'customer') {
                $paymentHead = PaymentHead::where('type', 'customer')->first();
                if ($paymentHead) {
                    $paymentHeadId = $paymentHead->id;
                }
            } elseif ($request->input('payment_head') == 'supplier') {
                $paymentHead = PaymentHead::where('type', 'supplier')->first();
                if ($paymentHead) {
                    $paymentHeadId = $paymentHead->id;
                }
            }

            $order = Order::where('custom_order_id', $request->payable_id)->first();
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }
        
            $customerId = $order->customer_id; 
            
        
            // Create the payment record
            Payment::create([
                'amount' => $request->amount,
                'status' => $request->status,
                'payment_type' => $request->payment_type,
                'payable_id' => 12,
                'invoice_id' => $request->payable_id,

                'payable_type' => $request->payable_type,
                'account_id' => $request->account_id,
                'payment_date' => $request->payment_date,
                'note' => $request->note,
                'payment_method' => $request->payment_method,
                'payment_head' => $paymentHeadId, 
                'created_by' => auth()->id(),
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Payment saved successfully!',
                 
            ]);
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
    
        $paymentHeadId = null;
    
        if ($request->input('payment_head') == 'customer') {
            $paymentHead = PaymentHead::where('type', 'customer')->first();
            if ($paymentHead) {
                $paymentHeadId = $paymentHead->id;
            }
        } elseif ($request->input('payment_head') == 'supplier') {
            $paymentHead = PaymentHead::where('type', 'supplier')->first();
            if ($paymentHead) {
                $paymentHeadId = $paymentHead->id;
            }
        }
    
        $payment = Payment::findOrFail($id);
    
        $payment->payment_head = $paymentHeadId;
        $payment->payable_id = $request->input('payable_id');
        $payment->amount = $request->input('amount');
        $payment->payment_date = $request->input('payment_date');
        $payment->invoice_id = $request->input('invoice_id');
        $payment->status = $request->input('status');
        $payment->payment_type = $request->input('payment_type');
        $payment->payment_method = $request->input('payment_method');
        $payment->note = $request->input('note');
        $payment->updated_by = auth()->user()->id;
    
        $payment->save();
    
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
    


    public function viewPayments($orderId)
        {
            try {
                // Use left join to include payments even if no matching account exists
                $payments = Payment::where('invoice_id', $orderId)
                    ->leftJoin('accounts', 'payments.account_id', '=', 'accounts.id') 
                    ->select('payments.*', 'accounts.name as account_name')
                    ->get();

                return response()->json([
                    'success' => true,
                    'payments' => $payments,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error fetching payments: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch payment details.',
                ]);
            }
        }


    public function downloadSampleExcel()
        {
           
               
        $data = [
            ['Amount', 'Date', 'Status', 'Payment Method','Payment Note'],
            ['1000', '2024-12-19', 'Completed', 'Cash','credit note'],
            ['2000', '2024-12-18', 'Pending', 'Online','debit note'],
        ];

        
        $fileName = 'sample_payment_data.csv';

        // Set headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // Use a callback to write data to output
        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        // Return the response as a streamed download
        return response()->stream($callback, 200, $headers);

        }


}