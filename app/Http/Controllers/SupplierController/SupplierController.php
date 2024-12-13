<?php 

namespace App\Http\Controllers\SupplierController;

use App\Http\Controllers\Controller; 
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Http\Controllers\PurchaseController\PurchaseController;  



class SupplierController extends Controller
{
    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers', 'email')
                    ->where(function ($query) use ($request) {
                        $query->where('created_by', auth()->id())
                              ->orWhere('parent_user_id', auth()->user()->parent_id);
                    })
        ],
        'phone' => [
            'nullable',
            'string',
            'max:15',
            Rule::unique('suppliers', 'phone')
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

        // Create a new supplier instance
        $supplier = new Supplier();
        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->address = $request->input('address');
        $supplier->city = $request->input('city');
        $supplier->po_box = $request->input('po_box');
        $supplier->tax_number = $request->input('tax_number');
        $supplier->initial_balance = $request->input('initial_balance');
        $supplier->discount_type = $request->input('discount_type');
        $supplier->discount_value = $request->input('discount_value');
        
        $supplier->created_by = auth()->id(); 
        $supplier->parent_user_id = auth()->user()->parent_id;

        try {
            $supplier->save();
            return redirect()->route('suppliers.create')->with('success', 'Supplier added successfully!');
        } catch (QueryException $e) {
            Log::error('Error saving supplier: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }

    public function index()
    {
        $suppliers = Supplier::with('creator')->get(); 
        return view('suppliers.index', compact('suppliers'));
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if ($supplier) {
            $supplier->delete();
            return response()->json(['success' => true, 'message' => 'Supplier deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Supplier not found.']);
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id . ',id,parent_user_id,' . auth()->user()->parent_id . ',created_by,' . auth()->id(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'po_box' => 'nullable|string|max:10',
            'tax_number' => 'nullable|string|max:20',
            'initial_balance' => 'nullable|numeric',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric'
        ]);

        // Update the supplier instance
        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->address = $request->input('address');
        $supplier->city = $request->input('city');
        $supplier->po_box = $request->input('po_box');
        $supplier->tax_number = $request->input('tax_number');
        $supplier->initial_balance = $request->input('initial_balance');
        $supplier->discount_type = $request->input('discount_type');
        $supplier->discount_value = $request->input('discount_value');

        $supplier->updated_by = auth()->id();

        try {
            $supplier->save();
            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
        } catch (QueryException $e) {
            Log::error('Error updating supplier: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }




    public function ledger(Request $request)
{
    try {
        // Get all suppliers
        $suppliers = Supplier::select('id', 'name', 'initial_balance')->get();  // Fetch initial_balance

        // Get the currency symbol
        $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value');

        // Get selected supplier ID
        $supplierId = $request->input('supplier_id');

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

        // Fetch purchase orders and payments in the specified date range
        $purchaseOrdersQuery = Purchase::query()
            ->select('custom_purchase_id', 'total_amount', 'purchase_date', 'status')
            ->where('supplier_id', $supplierId)
            ->whereNull('deleted_at');

        $paymentsQuery = Payment::query()
            ->select('amount', 'payment_date', 'invoice_id', 'payment_type', 'id')
            ->where('payable_id', $supplierId);

        if ($startDate && $endDate) {
            $purchaseOrdersQuery->whereBetween('order_date', [$startDate, $endDate]);
            $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        }

        $purchaseOrders = $purchaseOrdersQuery->get();
        $payments = $paymentsQuery->get();

        // Fetch opening balance
        $openingBalance = 0;

        // Get supplier's initial balance
        $initialBalance = 0;
        if ($supplierId) {
            $supplier = Supplier::find($supplierId);
            $initialBalance = $supplier->initial_balance == NULL ? 0 : $supplier->initial_balance;
        }
        // Include initial balance in the opening balance
        $openingBalance += $initialBalance;

        if ($startDate) {
            // Get transactions before the start date to calculate opening balance
            $previousOrders = Purchase::where('supplier_id', $supplierId)
                ->whereNull('deleted_at')
                ->where('order_date', '<', $startDate)
                ->get();

            $previousPayments = Payment::where('payable_id', $supplierId)
                ->where('payment_date', '<', $startDate)
                ->get();

            // Apply the same balance calculation logic to previous orders
            foreach ($previousOrders as $order) {
                $orderData = (new PurchaseController())->getInvoice($order->custom_purchase_id)->getData();
                $entryType = ($order->status == 1) ? 'Return' : 'Purchase Order';
                
                // Calculate entry balance
                $entryBalance = 0;
                if ($entryType == 'Purchase Order') {
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

        // Add purchase orders to ledger
        foreach ($purchaseOrders as $order) {
            $orderData = (new PurchaseController())->getPurchase($order->custom_purchase_id)->getData();
            $entryType = ($order->status == 1) ? 'Return' : 'Purchase Order';

            $ledgerData->push([
                'date' => Carbon::parse($order->order_date)->format('Y-m-d'),
                'order_number' => $order->custom_purchase_id,
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
            if ($entry['payment_type'] == 'credit' || $entry['entry_type'] == 'Purchase Order') {
                $balance -= ($entry['total_amount'] + $entry['payment_amount']);
            }

            if ($entry['entry_type'] == 'Return') {
                $balance += $entry['total_amount'];
            }

            if ($entry['payment_type'] == 'debit') {
                $balance += $entry['payment_amount'];
            }
            \Log::info("After Transaction: Updated Balance = {$balance}");
            $entry['balance'] = $currencySymbol . ' ' . number_format($balance, 2);
            return $entry;
        });

        // Closing balance is the final balance after the end date
        $closingBalance = $balance;

        return view('suppliers.supplier-ledger', compact('closingBalance', 'openingBalance', 'suppliers', 'ledgerData', 'currencySymbol', 'startDate', 'endDate', 'supplierId'));

    } catch (Exception $e) {
        \Log::error('Failed to fetch ledger data: ' . $e->getMessage());
        return redirect()->back()->withErrors('Failed to fetch ledger data. Please try again later.');
    }
}   


}