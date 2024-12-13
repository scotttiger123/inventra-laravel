<?php 

namespace App\Http\Controllers\CustomerController;

use App\Http\Controllers\Controller; 
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Order;
use App\Http\Controllers\OrderController\OrderController;  

class CustomerController extends Controller
{
    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')
                    ->where(function ($query) use ($request) {
                        $query->where('created_by', auth()->id())
                              ->orWhere('parent_user_id', auth()->user()->parent_id);
                    })
                ],
                'phone' => [
                    'nullable',
                    'string',
                    'max:15',
                    Rule::unique('customers', 'phone')
                        ->where(function ($query) use ($request) {
                            $query->where('created_by', auth()->id())
                                ->orWhere('parent_user_id', auth()->user()->parent_id);
                        })
                ],
            

            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'po_box' => 'nullable|string|max:10',
            'tax_number' => 'nullable|string|max:20',
            'initial_balance' => 'nullable|numeric',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric'
        ]);

        // Create a new customer instance
        $customer = new Customer();
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->city = $request->input('city');
        $customer->po_box = $request->input('po_box');
        $customer->tax_number = $request->input('tax_number');
        $customer->initial_balance = $request->input('initial_balance');
        $customer->discount_type = $request->input('discount_type');
        $customer->discount_value = $request->input('discount_value');
        
        $customer->created_by = auth()->id(); 
        $customer->parent_user_id = auth()->user()->parent_id;

        try {
            $customer->save();
            return redirect()->route('customers.create')->with('success', 'Customer added successfully!');
        } catch (QueryException $e) {
            Log::error('Error saving customer: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }

    public function index()
    {
        $customers = Customer::with('creator')->get(); 
        return view('customers.index', compact('customers'));
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->delete();
            return response()->json(['success' => true, 'message' => 'Customer deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Customer not found.']);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id . ',id,parent_user_id,' . auth()->user()->parent_id . ',created_by,' . auth()->id(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'po_box' => 'nullable|string|max:10',
            'tax_number' => 'nullable|string|max:20',
            'initial_balance' => 'nullable|numeric',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric'
        ]);

        // Update the customer instance
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->city = $request->input('city');
        $customer->po_box = $request->input('po_box');
        $customer->tax_number = $request->input('tax_number');
        $customer->initial_balance = $request->input('initial_balance');
        $customer->discount_type = $request->input('discount_type');
        $customer->discount_value = $request->input('discount_value');

        $customer->updated_by = auth()->id();

        try {
            $customer->save();
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
        } catch (QueryException $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }

 


    public function ledger(Request $request)
    {
        try {
            // Get all customers
            $customers = Customer::select('id', 'name', 'initial_balance')->get();  // Fetch initial_balance
    
            // Get the currency symbol
            $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value');
    
            // Get selected customer ID
            $customerId = $request->input('customer_id');
    
            // Get start and end dates from the request
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
    
            if (!$startDate && !$endDate) {
                $startDate = null;
                $endDate = null;
            }
    
            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate)->startOfDay();
                $endDate = Carbon::parse($endDate)->endOfDay();
            }
    
            // Fetch sale orders and payments in the specified date range
            $saleOrdersQuery = Order::query()
                ->select('custom_order_id', 'total_amount', 'order_date', 'status')
                ->where('customer_id', $customerId)
                ->whereNull('deleted_at');
    
            $paymentsQuery = Payment::query()
                ->select('amount', 'payment_date', 'invoice_id', 'payment_type', 'id')
                ->where('payable_id', $customerId);
    
            if ($startDate && $endDate) {
                $saleOrdersQuery->whereBetween('order_date', [$startDate, $endDate]);
                $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
            }
    
            $saleOrders = $saleOrdersQuery->get();
            $payments = $paymentsQuery->get();
    
            // Fetch opening balance
            $openingBalance = 0;
            
            // Get customer's initial balance
            $initialBalance = 0;
            if($customerId){
                $customer = Customer::find($customerId);
                $initialBalance = $customer->initial_balance == NULL ? 0 : $customer->initial_balance;
            }    
            // Include initial balance in the opening balance
            $openingBalance += $initialBalance;
    
            if ($startDate) {
                // Get transactions before the start date to calculate opening balance
                $previousOrders = Order::where('customer_id', $customerId)
                    ->whereNull('deleted_at')
                    ->where('order_date', '<', $startDate)
                    ->get();
    
                $previousPayments = Payment::where('payable_id', $customerId)
                    ->where('payment_date', '<', $startDate)
                    ->get();
    
                // Apply the same balance calculation logic to previous orders
                foreach ($previousOrders as $order) {
                    $orderData = (new OrderController())->getInvoice($order->custom_order_id)->getData();
                    $entryType = ($order->status == 1) ? 'Return' : 'Sale Order';
                    
                    // Calculate entry balance
                    $entryBalance = 0;
                    if ($entryType == 'Sale Order') {
                        $entryBalance = abs($orderData->netTotalWithTax);
                    } elseif ($entryType == 'Return') {
                        $entryBalance = -abs($orderData->netTotalWithTax);
                    }
    
                    // Adjust the opening balance based on the entry type
                    $openingBalance += $entryBalance;
                }
    
                // Apply the same balance calculation logic to previous payments
                foreach ($previousPayments as $payment) {
                    // Debit payment reduces balance, Credit payment adds to the balance
                    if ($payment->payment_type == 'debit') {
                        $openingBalance += $payment->amount;
                    } elseif ($payment->payment_type == 'credit') {
                        $openingBalance -= $payment->amount;
                    }
                }
            }
    
            // Prepare ledger data
            $ledgerData = collect();
    
            // Add opening balance entry
            $ledgerData->prepend([
                'date' => $startDate ? Carbon::parse($startDate)->format('Y-m-d') : null,
                'order_number' => 'Opening Balance',
                'payment_amount' => null,
                'total_amount' => null,
                'payment_type' => null,
                'balance' => $currencySymbol . ' ' . number_format($openingBalance, 2),
                'entry_type' => 'Opening Balance',
            ]);
    
            // Add sale orders to ledger
            foreach ($saleOrders as $order) {
                $orderData = (new OrderController())->getInvoice($order->custom_order_id)->getData();
                $entryType = ($order->status == 1) ? 'Return' : 'Sale Order';
    
                $ledgerData->push([
                    'date' => Carbon::parse($order->order_date)->format('Y-m-d'),
                    'order_number' => $order->custom_order_id,
                    'payment_amount' => null,
                    'total_amount' => $orderData->netTotalWithTax,
                    'payment_type' => null,
                    'balance' => null,
                    'entry_type' => $entryType,
                ]);
            }
    
            // Add payments to ledger
            foreach ($payments as $payment) {
                $ledgerData->push([
                    'date' => Carbon::parse($payment->payment_date)->format('Y-m-d'),
                    'order_number' => $payment->id,
                    'payment_amount' => $payment->amount,
                    'payment_type' => $payment->payment_type,
                    'total_amount' => null,
                    'balance' => null,
                    'entry_type' => 'Payment',
                ]);
            }
    
            // Sort ledger data by date
            $ledgerData = $ledgerData->sortBy(function ($entry) {
                return $entry['entry_type'] === 'Opening Balance' ? Carbon::now()->subYears(100) : $entry['date'];
            });
    
            $ledgerData = $ledgerData->values();
    
            // Calculate balances
            $balance = $openingBalance;
            $ledgerData = $ledgerData->map(function ($entry) use (&$balance, $currencySymbol) {
                if ($entry['payment_type'] == 'credit' || $entry['entry_type'] == 'Sale Order') {
                    $balance += $entry['total_amount'] - $entry['payment_amount'];
                }
    
                if ($entry['entry_type'] == 'Return') {
                    $balance += -$entry['total_amount'];
                }
    
                if ($entry['payment_type'] == 'debit') {
                    $balance += $entry['payment_amount'];
                }
    
                $entry['balance'] = $currencySymbol . ' ' . number_format($balance, 2);
                return $entry;
            });
    
            // Closing balance is the final balance after the end date
            $closingBalance = number_format($balance, 2);
    
        
            return view('customers.customer-ledger', compact('closingBalance', 'openingBalance', 'customers', 'ledgerData', 'currencySymbol', 'startDate', 'endDate', 'customerId'));

        } catch (Exception $e) {
            \Log::error('Failed to fetch ledger data: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to fetch ledger data. Please try again later.');
        }
    }
    

    public function ledgerPDF(Request $request)
    {
        try {
            // Get all customers, including their initial balance
            $customers = Customer::select('id', 'name', 'initial_balance')->get();
    
            // Get the currency symbol
            $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value');
    
            // Get selected customer ID
            $customerId = $request->input('customer_id');
    
            // Get start and end dates from the request
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
    
            if (!$startDate && !$endDate) {
                $startDate = null;
                $endDate = null;
            }
    
            if ($startDate && $endDate) {
                $startDate = Carbon::parse($startDate)->startOfDay();
                $endDate = Carbon::parse($endDate)->endOfDay();
            }
    
            // Fetch sale orders and payments in the specified date range
            $saleOrdersQuery = Order::query()
                ->select('custom_order_id', 'total_amount', 'order_date', 'status')
                ->where('customer_id', $customerId)
                ->whereNull('deleted_at');
    
            $paymentsQuery = Payment::query()
                ->select('amount', 'payment_date', 'invoice_id', 'payment_type', 'id')
                ->where('payable_id', $customerId);
    
            if ($startDate && $endDate) {
                $saleOrdersQuery->whereBetween('order_date', [$startDate, $endDate]);
                $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
            }
    
            $saleOrders = $saleOrdersQuery->get();
            $payments = $paymentsQuery->get();
    
            // Fetch the customer's initial balance
            $customer = Customer::find($customerId);
            $initialBalance = $customer->initial_balance === null ? 0 : $customer->initial_balance;
            $customerName = $customer->name;
            
            // Fetch opening balance
            $openingBalance = $initialBalance;
            if ($startDate) {
                // Get transactions before the start date to calculate opening balance
                $previousOrders = Order::where('customer_id', $customerId)
                    ->whereNull('deleted_at')
                    ->where('order_date', '<', $startDate)
                    ->get();
    
                $previousPayments = Payment::where('payable_id', $customerId)
                    ->where('payment_date', '<', $startDate)
                    ->get();
    
                foreach ($previousOrders as $order) {
                    $orderData = (new OrderController())->getInvoice($order->custom_order_id)->getData();
                    $entryType = ($order->status == 1) ? 'Return' : 'Sale Order';
    
                    // Calculate entry balance
                    $entryBalance = 0;
                    if ($entryType == 'Sale Order') {
                        $entryBalance = abs($orderData->netTotalWithTax);
                    } elseif ($entryType == 'Return') {
                        $entryBalance = -abs($orderData->netTotalWithTax);
                    }
    
                    // Adjust the opening balance based on the entry type
                    $openingBalance += $entryBalance;
                }
    
                foreach ($previousPayments as $payment) {
                    $openingBalance -= $payment->amount;
                }
            }
    
            // Prepare ledger data
            $ledgerData = collect();
    
            // Add opening balance entry
            $ledgerData->prepend([
                'date' => $startDate ? Carbon::parse($startDate)->format('Y-m-d') : null,
                'order_number' => 'Opening Balance',
                'payment_amount' => null,
                'total_amount' => null,
                'payment_type' => null,
                'balance' => $currencySymbol . ' ' . number_format($openingBalance, 2),
                'entry_type' => 'Opening Balance',
            ]);
    
            // Add sale orders to ledger
            foreach ($saleOrders as $order) {
                $orderData = (new OrderController())->getInvoice($order->custom_order_id)->getData();
                $entryType = ($order->status == 1) ? 'Return' : 'Sale Order';
    
                $ledgerData->push([
                    'date' => Carbon::parse($order->order_date)->format('Y-m-d'),
                    'order_number' => $order->custom_order_id,
                    'payment_amount' => null,
                    'total_amount' => $orderData->netTotalWithTax,
                    'payment_type' => null,
                    'balance' => null,
                    'entry_type' => $entryType,
                ]);
            }
    
            // Add payments to ledger
            foreach ($payments as $payment) {
                $ledgerData->push([
                    'date' => Carbon::parse($payment->payment_date)->format('Y-m-d'),
                    'order_number' => $payment->id,
                    'payment_amount' => $payment->amount,
                    'payment_type' => $payment->payment_type,
                    'total_amount' => null,
                    'balance' => null,
                    'entry_type' => 'Payment',
                ]);
            }
    
            // Sort ledger data by date
            $ledgerData = $ledgerData->sortBy(function ($entry) {
                return $entry['entry_type'] === 'Opening Balance' ? Carbon::now()->subYears(100) : $entry['date'];
            });
    
            $ledgerData = $ledgerData->values();
    
            // Calculate balances
            $balance = $openingBalance;
            $ledgerData = $ledgerData->map(function ($entry) use (&$balance, $currencySymbol) {
                if ($entry['payment_type'] == 'credit' || $entry['entry_type'] == 'Sale Order') {
                    $balance += $entry['total_amount'] - $entry['payment_amount'];
                }
    
                if ($entry['entry_type'] == 'Return') {
                    $balance += -$entry['total_amount'];
                }
    
                if ($entry['payment_type'] == 'debit') {
                    $balance += $entry['payment_amount'];
                }
    
                $entry['balance'] = $currencySymbol . ' ' . number_format($balance, 2);
                return $entry;
            });
    
            // Closing balance is the final balance after the end date
            $closingBalance = number_format($balance, 2);
    
            $logoUrl = asset('../../dist/img/logo.png'); 

            return response()->json([
                'success' => true,
                'customer_id' => $customerId,
                'customerName' => $customerName,
                'start_date' => $startDate ? $startDate->toDateString() : null,
                'end_date' => $endDate ? $endDate->toDateString() : null,
                'currency_symbol' => $currencySymbol,
                'opening_balance' => $currencySymbol . ' ' . number_format($openingBalance, 2),
                'closing_balance' => $currencySymbol . ' ' . $closingBalance,
                'ledger_data' => $ledgerData,
                'logoUrl' => $logoUrl,
            ]);

            
            

        } catch (Exception $e) {
            \Log::error('Failed to fetch ledger data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch ledger data. Please try again later.',
            ]);
        }
    }
    




    


    

}
