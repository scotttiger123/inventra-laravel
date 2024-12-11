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
        $customers = Customer::select('id', 'name')->get();

        // Get the currency symbol
        $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value');

        // Get selected customer ID
        $customerId = $request->input('customer_id');

        // Get start and end dates from the request, defaulting to the last 30 days if not provided
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // If no date range is selected, set start and end dates to null to fetch all data for the customer
        if (!$startDate && !$endDate) {
            $startDate = null;
            $endDate = null;
        }

        // If a date range is provided, parse the dates
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        // Fetch sale orders for the customer
        $saleOrdersQuery = Order::query()
            ->select('custom_order_id', 'total_amount', 'created_at')
            ->where('customer_id', $customerId)
            ->whereNull('deleted_at'); // Exclude soft-deleted orders

        // Apply date range filter if dates are provided
        if ($startDate && $endDate) {
            $saleOrdersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $saleOrders = $saleOrdersQuery->get();

        // Fetch payments for the customer
        $paymentsQuery = Payment::query()
            ->select('amount', 'created_at', 'invoice_id')
            ->where('payable_id', $customerId);

        // Apply date range filter if dates are provided
        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $payments = $paymentsQuery->get();

        // Combine sale orders and payments into one collection
        $ledgerData = collect();

        // Instantiate the OrderController to call the getInvoice method
        $orderController = new OrderController();

        // Add sale orders to ledgerData with 'Sale Order' type
        foreach ($saleOrders as $order) {
            // Fetch order details using the getInvoice method from OrderController
            $orderData = $orderController->getInvoice($order->custom_order_id)->getData();

            $ledgerData->push([
                'date' => $order->created_at->format('Y-m-d'),
                'order_number' => $order->custom_order_id,
                'payment_amount' => null, // No payment amount for sale orders
                'total_amount' => $orderData->netTotalWithTax, // Total amount after taxes
                'balance' => null,
                'entry_type' => 'Sale Order',
            ]);
        }

        // Add payments to ledgerData with 'Payment' type
        foreach ($payments as $payment) {
            $ledgerData->push([
                'date' => $payment->created_at->format('Y-m-d'),
                'order_number' => $payment->invoice_id,
                'payment_amount' => $payment->amount,
                'total_amount' => null, // No total amount for payments
                'balance' => null,
                'entry_type' => 'Payment',
            ]);
        }

        // Sort the combined ledger data by date (oldest first)
        $ledgerData = $ledgerData->sortBy('date');

        // Initialize balance
        $balance = 0;

         $ledgerData = $ledgerData->map(function ($entry) use (&$balance, $currencySymbol) {
            if ($entry['entry_type'] == 'Payment') {
                // Add payment amount to balance
                $balance += $entry['payment_amount'];
            }

            if ($entry['entry_type'] == 'Sale Order') {
                // Deduct sale amount from balance
                $balance -= abs($entry['total_amount']);
            }

            // Update the balance for each entry
            $entry['balance'] = $currencySymbol . ' ' . number_format($balance, 2);

            return $entry;
        });


        // Return the view with the ledger data
        return view('customers.customer-ledger', compact('customers', 'ledgerData', 'currencySymbol', 'startDate', 'endDate', 'customerId'));
    } catch (Exception $e) {
        // Log the error and return with an error message
        \Log::error('Failed to fetch ledger data: ' . $e->getMessage());
        return redirect()->back()->withErrors('Failed to fetch ledger data. Please try again later.');
    }
}

    

}
