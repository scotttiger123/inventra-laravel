<?php 
namespace App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Controller; 
use App\Models\Purchase;  
use App\Models\PurchaseItem;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Role;
use App\Models\Uom;
use App\Models\Status;
use App\Models\Warehouse;
use App\Models\Tax;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\User; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;


class PurchaseController extends Controller
{
    

    public function create()
    {
        $user = auth()->user();
        $vendors = Supplier::where('created_by', $user->id)->get();
        
        $products = Product::where('created_by', $user->id)
            ->orWhere('parent_user_id', $user->id)
            ->orWhere('parent_user_id', $user->parent_id)
            ->get();
        $uoms = Uom::all();
        $statuses = Status::all();
        $warehouses = Warehouse::where('created_by', $user->id)->get();
        $taxes = Tax::all();
        
        return view('purchases.create', compact('vendors', 'products', 'uoms', 'warehouses','statuses','taxes'));
    }

    
    public function getPurchase($customOrderId)
    {
        
        $purchaseOrder = Purchase::where('custom_purchase_id', $customOrderId)
            ->leftJoin('statuses', 'statuses.id', '=', 'purchases.status') 
            ->select('purchases.*', 'statuses.status_name')
            ->first();

        if (!$purchaseOrder) {
            return response()->json(['error' => 'Purchase Order not found.'], 404);
        }
    
        $supplier = $purchaseOrder->supplier;
    
        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found.'], 404);
        }
    
        // Fetch the purchase manager associated with this order (if any)
        $purchaseManager = User::find($purchaseOrder->purchase_manager_id);
        $purchaseOrder->purchase_manager_name = $purchaseManager ? $purchaseManager->name : '';
    
        $settings = Setting::whereIn('name', [
            'currency-symbol',
            'company-name',
            'address',
            'phone',
            'email',
            'invoice-footer'
        ])->pluck('value', 'name');

        $currencySymbol = $settings['currency-symbol'] ?? '$';
        $companyName = $settings['company-name'] ?? 'Your Company';
        $companyAddress = $settings['address'] ?? 'Your Address';
        $companyPhone = $settings['phone'] ?? 'Your Phone';
        $companyEmail = $settings['email'] ?? 'Your Email';
        $invoiceFooter = $settings['invoice-footer'] ?? 'Your Email';
        
        
            $purchaseItems = PurchaseItem::where('custom_purchase_id', $customOrderId)
            ->join('products', 'products.id', '=', 'purchase_items.product_id')
            ->leftJoin('uoms', 'uoms.id', '=', 'purchase_items.uom_id')
            ->leftJoin('warehouses', 'warehouses.id', '=', 'purchase_items.inward_warehouse_id') 
            ->select(
                'purchase_items.*', 
                'products.product_name', 
                'uoms.abbreviation as uom_name', 
                'warehouses.name as warehouse_name' // Select warehouse name
            )
            ->get();
    
        $purchaseItemsData = $purchaseItems->map(function ($item) {
            $unitPrice = (float)$item->rate;
            $quantity = (float)$item->quantity;
            $item->total_before_discount = $unitPrice * $quantity;
    
            // Calculate product-level 
            $discountAmount = (float)$item->discount_amount;
            if (strpos($item->discount_type, '%') !== false) {
                $discountPercentage = (float)rtrim($item->discount_amount, '%');
                $item->discount_amount_calculated = ($item->total_before_discount * $discountPercentage) / 100;
            } else {
                $item->discount_amount_calculated = $discountAmount;
            }
    
            $item->net_rate = $unitPrice - ($item->discount_amount_calculated / max($quantity, 1));
            $item->total_after_discount = $item->total_before_discount - $item->discount_amount_calculated;
    
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'quantity' => $quantity,
                'uom_id' => $item->uom_id,
                'uom_name' => $item->uom_name ?: '',
                'unit_price' => $unitPrice,
                'entry_warehouse' => $item->inward_warehouse_id,
                'warehouse_name' => $item->warehouse_name, 
                'discount_amount' => $item->discount_value > 0 ? $item->discount_value . $item->discount_type : '0',
                'net_rate' => $item->net_rate,
                'amount' => $item->total_after_discount,
            ];
        });
    
        
        $grossAmount = $purchaseItemsData->sum('amount');
    
        
        $orderDiscountType = $purchaseOrder->discount_type;
        $orderDiscountAmount = (float)$purchaseOrder->discount_amount;
    
        $orderDiscount = $orderDiscountType === '%'
            ? ($grossAmount * $orderDiscountAmount) / 100
            : $orderDiscountAmount;
    
        $grossAmountAfterOrderDiscount = $grossAmount - $orderDiscount;
    
        
        $otherCharges = (float)$purchaseOrder->other_charges;
        $netTotal = $grossAmountAfterOrderDiscount + $otherCharges;
    
        // Calculate Tax amount
        $taxRate = $purchaseOrder->tax_rate; 
        $taxAmount = 0;
        if ($taxRate) {
            $taxAmount = ($netTotal * $taxRate) / 100;
        }
        $netTotalWithTax = $netTotal + $taxAmount;

        // Calculate remaining amount
        $paidAmount = (float)$purchaseOrder->paid;
        $remainingAmount = $netTotalWithTax - $paidAmount;
    
        return response()->json([
            'purchaseOrder' => $purchaseOrder,
            'purchaseItems' => $purchaseItemsData,
            'grossAmount' => $grossAmount,
            'orderDiscount' => $orderDiscount,
            'grossAmountAfterOrderDiscount' => $grossAmountAfterOrderDiscount,
            'otherCharges' => $otherCharges,
            'netTotal' => $netTotal,
            'taxRate' => $purchaseOrder->tax_rate,
            'netTotalWithTax' => $netTotalWithTax,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
            'currencySymbol' => $currencySymbol,
            'companyName' => $companyName,
            'companyAddress' => $companyAddress,
            'companyPhone' => $companyPhone,
            'companyEmail' => $companyEmail,
            
        ]);
    }
    
    
    
       
    public function store(Request $request)
{
    \Log::info('Purchase creation request data:', $request->all());

    try {
        // Start a database transaction
        DB::beginTransaction();
        $branchId = auth()->user()->branch_id ?? 1;

        // Generate or check custom purchase ID
        if ($request->has('custom_purchase_id') && $request->custom_purchase_id) {
            $purchaseNumber = $request->custom_purchase_id;
            $existingPurchase = Purchase::where('custom_purchase_id', $purchaseNumber)->first();
            if ($existingPurchase) {
                return response()->json(['success' => false, 'message' => 'Purchase ID already exists.']);
            }
        } else {
            $purchaseNumber = $this->generatePurchaseNumber($branchId); // Assume this is a method similar to order number generation
        }

        \Log::info('Paid Amount:', [$request->paid_amount]);

        // Create the purchase record
        $purchase = new Purchase();
        
        $purchase->supplier_id = $request->vendor_id;
        $purchase->status = $request->status_id ?? 0;
        $purchase->other_charges = $request->other_charges ?? 0;
        $purchase->discount_amount = $request->order_discount ?? 0;
        $purchase->tax_rate = $request->tax_rate ?? 0;
        $purchase->purchase_date = $request->purchase_date;
        $purchase->custom_purchase_id = $purchaseNumber;
        $purchase->purchase_note = $request->purchase_note;
        $purchase->staff_note = $request->staff_note;
        $purchase->paid = $request->paid_amount ?: 0;  
        
        // Set discount type
        if ($request->order_discount_type == 'percentage') {
            $purchase->discount_type = '%'; // Append % for percentage
        } else {
            $purchase->discount_type = '-'; // Use - for flat discount
        }

        $purchase->paid = $request->paid_amount;
        $purchase->branch_id = auth()->user()->branch_id;

        // Track creator and editor
        $purchase->created_by = auth()->id();
        $purchase->updated_by = auth()->id();
        $purchase->save();

        // Save purchase items
        if ($request->has('purchaseData')) {
            $purchaseData = json_decode($request->purchaseData, true); // Decode JSON string to array
            if (is_array($purchaseData)) {
                \Log::info('Purchase Data:', $purchaseData);
                foreach ($purchaseData as $item) {
                    

                    // Retrieve the product's cost_price from the products table
                    $product = \DB::table('products')->where('id', $item['product_id'])->first();

                    if ($product) {
                        $costPrice = $product->cost;  
                    } else {
                        $costPrice = null; 
                    }

                    
                    $inwardWarehouseId = empty($item['inward_warehouse_id']) ? 0 : $item['inward_warehouse_id'];
                    $discount_value = empty($item['discount_value']) ? 0 : $item['discount_value'];

                    DB::table('purchase_items')->insert([
                        'order_id' => $purchase->purchase_id,
                        'custom_purchase_id' => $purchaseNumber,
                        'product_id' => $item['product_id'],
                        'uom_id' => $item['uom_id'] !== null ? (int)$item['uom_id'] : 0,
                        'quantity' => $item['qty'],
                        'rate' => $item['rate'],
                        'cost_price' => $costPrice, 
                        'discount_type' => (strpos($item['discount_type'], '%') !== false) ? '%' : '-',
                        'discount_value' => $discount_value,
                        'net_rate' => $item['net_rate'], // Assuming netRate is calculated and sent in request
                        'amount' => $item['amount'], // Assuming total amount is sent in request
                        'inward_warehouse_id' => $inwardWarehouseId, 

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                \Log::error('Invalid purchaseData format');
            }
        } else {
            \Log::error('Missing purchaseData');
        }

        // Commit transaction
        DB::commit();

        // Return a response indicating success
        return response()->json(['success' => true, 'message' => 'Purchase created successfully.']);

    } catch (Exception $e) {
        // Rollback transaction if something goes wrong
        DB::rollBack();

        // Log the error with additional context
        \Log::error('Purchase creation failed: ' . $e->getMessage(), [
            'request_data' => $request->all(),
            'exception' => $e
        ]);

        // Return a response indicating failure
        return response()->json(['success' => false, 'message' => 'There was an error creating the purchase. Please try again later.']);
    }
}


    
    private function generatePurchaseNumber($branchId)
{
    $year = date('Y');
    $prefix = $branchId;

        $lastOrder = Purchase::withTrashed() // Include soft-deleted records
        ->whereYear('created_at', $year)
        ->where('branch_id', $branchId)
        ->orderBy('custom_purchase_id', 'desc')
        ->first();

    // If no orders exist for the current year and branch, start with '001'
    if ($lastOrder) {
        // Extract the numeric part from the last order's custom_order_id
        $lastNumber = (int) substr($lastOrder->custom_purchase_id, -3);
        // Increment the last number
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // If no order exists, start from '001'
        $newNumber = '001';
    }

    // Return the generated order number
    return $prefix.$year.$newNumber;
}




public function index(Request $request)
{
    try {
        $user = auth()->user();
        $userId = $user->id;
        $parentUserId = $user->parent_id;

        $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value');

            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $query = Purchase::whereIn('created_by', [$userId, $parentUserId])
            ->with('supplier')
            ->orderBy('created_at', 'desc'); 
    
            
            if ($startDate) {
                $query->whereDate('purchase_date', '>=', $startDate); 
            }
            if ($endDate) {
                $query->whereDate('purchase_date', '<=', $endDate); 
            }
    
            $purchases = $query->get();

        $totalNetTotalWithTax = 0;
        $totalNetReturnAmount = 0;  

        foreach ($purchases as $purchase) {
            $grossAmount = 0;
            $purchaseItems = PurchaseItem::where('custom_purchase_id', $purchase->custom_purchase_id)->get(); 

            foreach ($purchaseItems as $item) {
                $totalBeforeDiscount = $item->rate * $item->quantity;
                $discountAmount = $item->discount_amount;

                // Calculate the discount for each item
                if ($item->discount_type == '%') {
                    $discountAmountCalculated = ($totalBeforeDiscount * $discountAmount) / 100;
                } else {
                    $discountAmountCalculated = $discountAmount;
                }

                $grossAmount += $totalBeforeDiscount - $discountAmountCalculated;
            }

            // Apply purchase-level discount (percentage or flat)
            $purchaseDiscount = 0;
            if ($purchase->discount_type == '%') {
                $purchaseDiscount = ($grossAmount * $purchase->discount_amount) / 100; // Calculate discount as a percentage
            } else {
                $purchaseDiscount = $purchase->discount_amount; // Flat discount amount
            }

            // Calculate gross amount after purchase-level discount
            $grossAmountAfterPurchaseDiscount = $grossAmount - $purchaseDiscount;

            // Add other charges to calculate the net total
            $netTotal = $grossAmountAfterPurchaseDiscount + $purchase->other_charges;

            // Calculate Tax
            $taxRate = $purchase->tax_rate; 
            $taxAmount = 0;
            if ($taxRate) {
                $taxAmount = ($netTotal * $taxRate) / 100;
            }
            $netTotalWithTax = $netTotal + $taxAmount;
            
            // Calculate remaining amount
            $remainingAmount = $netTotalWithTax - $purchase->paid;

            // Add to total net amount only if it is a regular purchase, not a return
            if ($purchase->status == '1') {
                $totalNetReturnAmount += $netTotalWithTax;
                
                
            } else {
                
                $purchase->grossAmount = $grossAmount;
                $purchase->purchaseDiscount = $purchaseDiscount;
                $purchase->grossAmountAfterPurchaseDiscount = $grossAmountAfterPurchaseDiscount;
                $purchase->netTotal = $netTotalWithTax;
                $purchase->remainingAmount = $remainingAmount;
                $totalNetTotalWithTax += $netTotalWithTax;
                
            }

            
        }

        // Return the purchase listings view
        return view('purchases.index', compact('purchases', 'totalNetTotalWithTax', 'totalNetReturnAmount'));
    } catch (Exception $e) {
        \Log::error('Failed to fetch purchases: ' . $e->getMessage());
        return redirect()->back()->withErrors('Failed to fetch purchases. Please try again later.');
    }
}





public function edit($customOrderId)
{
    // Fetch the order by custom_order_id
    $order = Order::where('custom_order_id', $customOrderId)->first();

    if (!$order) {
        return redirect()->route('orders.index')->with('error', 'Order not found.');
    }

    // Fetch all customers for the dropdown
    $customers = Customer::all();

    // Pass the order and customers to the view
    return view('orders.edit', compact('order', 'customers'));
}



public function update(Request $request)
{
    \Log::info('Purchase Order update request data:', $request->all());

    try {
        // Start a database transaction
        DB::beginTransaction();
        $branchId = auth()->user()->branch_id ?? 1;

        // Check if the purchase order exists by custom_purchase_order_id or any other unique identifier (like purchase_order_id)
        $existingPurchaseOrder = Purchase::where('custom_purchase_id', $request->custom_purchase_order_id)->first();

        if (!$existingPurchaseOrder) {
            return response()->json(['success' => false, 'message' => 'Purchase order not found.']);
        }

        // Update the purchase order details
        $existingPurchaseOrder->supplier_id = $request->vendor_id;
        $existingPurchaseOrder->purchase_manager_id = $request->purchase_manager_id;
        // $existingPurchaseOrder->total_amount = $request->total_amount;
        $existingPurchaseOrder->status = $request->status_id;
        $existingPurchaseOrder->other_charges = $request->other_charges ?? 0;
        $existingPurchaseOrder->discount_amount = $request->order_discount ?? 0;
        
        // $existingPurchaseOrder->payment_method = $request->payment_method;
        $existingPurchaseOrder->purchase_date = $request->purchase_date;
        $existingPurchaseOrder->purchase_note = $request->purchase_note;
        $existingPurchaseOrder->staff_note = $request->staff_note;
        $existingPurchaseOrder->tax_rate = $request->tax_rate ?? 0;

        if ($request->order_discount_type == 'percentage') {
            $existingPurchaseOrder->discount_type =  '%'; 
        } else {
            $existingPurchaseOrder->discount_type = '-';  
        }

        

        $existingPurchaseOrder->discount_amount = $request->order_discount;
        $existingPurchaseOrder->other_charges = $request->other_charges ?? 0;
        $existingPurchaseOrder->paid = $request->paid_amount ?? 0;

        $existingPurchaseOrder->updated_by = auth()->id();  // Set the user updating the purchase order
        $existingPurchaseOrder->save();

        // Clear previous purchase order items before updating (if needed)
        DB::table('purchase_items')->where('order_id', $existingPurchaseOrder->purchase_id)->delete();

        // Check if purchaseOrderData is provided
        if ($request->has('purchaseOrderData')) {
            $purchaseOrderData = json_decode($request->purchaseOrderData, true); // Decode JSON string to array
            if (is_array($purchaseOrderData)) {
                \Log::info('Purchase Order Data:', $purchaseOrderData);

                // Insert updated purchase order items
                foreach ($purchaseOrderData as $item) {

                    $product = \DB::table('products')->where('id', $item['product_id'])->first();

                    if ($product) {
                        $costPrice = $product->cost;  
                    } else {
                        $costPrice = null; 
                    }
                  
                    DB::table('purchase_items')->insert([
                        'order_id' => $existingPurchaseOrder->purchase_id,
                        'product_id' => $item['product_id'],
                        'uom_id' => $item['uom_id'] !== null ? (int) $item['uom_id'] : 0,
                        'quantity' => $item['qty'],
                        'rate' => $item['rate'],
                        'discount_type' => (strpos($item['discount_type'], '%') !== false) ? '%' : '-',
                        'discount_value' => $item['discount_value'] ?? 0,
                        'inward_warehouse_id' => $item['inward_warehouse_id'] ?? 0,
                        'custom_purchase_id' => $request->custom_purchase_order_id,
                        'cost_price' => $costPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                \Log::error('Invalid purchaseOrderData format');
            }
        } else {
            \Log::error('Missing purchaseOrderData');
        }

        // Commit transaction
        DB::commit();

        // Return a response indicating success
        return response()->json(['success' => true, 'message' => 'Purchase order updated successfully.']);

    } catch (Exception $e) {
        // Rollback transaction if something goes wrong
        DB::rollBack();

        // Log the error with additional context
        \Log::error('Purchase order update failed: ' . $e->getMessage(), [
            'request_data' => $request->all(),
            'exception' => $e
        ]);

        // Return a response indicating failure
        return response()->json(['success' => false, 'message' => 'There was an error updating the purchase order. Please try again later.']);
    }
}



public function destroy($customPurchaseId)
{
    // Fetch the purchase by custom_purchase_id
    $purchase = Purchase::where('custom_purchase_id', $customPurchaseId)->first();

    if (!$purchase) {
        return redirect()->back()->with('error', 'Purchase not found.');
    }

    // Soft delete the purchase
    $purchase->delete();

    return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
}


}
