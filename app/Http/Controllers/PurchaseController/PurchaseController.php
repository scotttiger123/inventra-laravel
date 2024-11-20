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
        $salePersonRoleId = Role::where('name', 'Purchase Person')->value('id');
        $purchasepersons = User::where('Role', $salePersonRoleId)->get();
        $products = Product::where('created_by', $user->id)
            ->orWhere('parent_user_id', $user->id)
            ->orWhere('parent_user_id', $user->parent_id)
            ->get();
        $uoms = Uom::all();
        $statuses = Status::all();
        $warehouses = Warehouse::where('created_by', $user->id)->get();
        // Pass all data to the view
        return view('purchases.create', compact('vendors', 'purchasepersons', 'products', 'uoms', 'warehouses','statuses'));
    }

    
    public function getPurchase($customOrderId)
    {
        $purchaseOrder = Purchase::where('custom_purchase_id', $customOrderId)->first();
    
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
    
        // Fetch all purchase items with product names and UOM details
        $purchaseItems = PurchaseItem::where('custom_purchase_id', $customOrderId)
            ->join('products', 'products.id', '=', 'purchase_items.product_id')
            ->leftJoin('uoms', 'uoms.id', '=', 'purchase_items.uom_id')
            ->select('purchase_items.*', 'products.product_name', 'uoms.abbreviation as uom_name')
            ->get();
    
        $purchaseItemsData = $purchaseItems->map(function ($item) {
            $unitPrice = (float)$item->unit_price;
            $quantity = (float)$item->quantity;
            $item->total_before_discount = $unitPrice * $quantity;
    
            // Calculate product-level discount
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
                'uom_name' => $item->uom_name ?: '-',
                'unit_price' => $unitPrice,
                'entry_warehouse' => $item->entry_warehouse,
                'discount_amount' => $item->discount_amount . $item->discount_type,
                'net_rate' => $item->net_rate,
                'amount' => $item->total_after_discount,
            ];
        });
    
        // Calculate gross amount
        $grossAmount = $purchaseItemsData->sum('amount');
    
        // Apply order-level discount
        $orderDiscountType = $purchaseOrder->discount_type;
        $orderDiscountAmount = (float)$purchaseOrder->discount_amount;
    
        $orderDiscount = $orderDiscountType === 'percentage'
            ? ($grossAmount * $orderDiscountAmount) / 100
            : $orderDiscountAmount;
    
        $grossAmountAfterOrderDiscount = $grossAmount - $orderDiscount;
    
        // Add other charges and calculate net total
        $otherCharges = (float)$purchaseOrder->other_charges;
        $netTotal = $grossAmountAfterOrderDiscount + $otherCharges;
    
        // Calculate remaining amount
        $paidAmount = (float)$purchaseOrder->paid;
        $remainingAmount = $netTotal - $paidAmount;
    
        return response()->json([
            'purchaseOrder' => $purchaseOrder,
            'purchaseItems' => $purchaseItemsData,
            'grossAmount' => $grossAmount,
            'orderDiscount' => $orderDiscount,
            'grossAmountAfterOrderDiscount' => $grossAmountAfterOrderDiscount,
            'otherCharges' => $otherCharges,
            'netTotal' => $netTotal,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
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
        $purchase->discount_amount = $request->discount_amount ?? 0;
        $purchase->purchase_date = $request->purchase_date;
        $purchase->custom_purchase_id = $purchaseNumber;
        $purchase->purchase_note = $request->purchase_note;
        $purchase->staff_note = $request->staff_note;
        $purchase->paid = $request->paid_amount ?: 0;  
        
        // Set discount type
        if ($request->discount_type == 'percentage') {
            $purchase->discount_type = $request->discount_type . '%'; // Append % for percentage
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
                    

                    
                    $inwardWarehouseId = empty($item['inward_warehouse_id']) ? 0 : $item['inward_warehouse_id'];
                    $discount_value = empty($item['discount_value']) ? 0 : $item['discount_value'];

                    DB::table('purchase_items')->insert([
                        'custom_purchase_id' => $purchaseNumber,
                        'product_id' => $item['product_id'],
                        'uom_id' => $item['uom_id'] !== null ? (int)$item['uom_id'] : 0,
                        'quantity' => $item['qty'],
                        'rate' => $item['rate'],
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

    // Get the last order for the current year and branch, ordered by custom_order_id (to get the latest order)
    $lastOrder = Purchase::whereYear('created_at', $year)
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


    

    public function customerStore(Request $request)
{
    // Validate the incoming request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('customers', 'email')
                ->where(function ($query) {
                    $query->where('created_by', auth()->id())
                          ->orWhere('parent_user_id', auth()->user()->parent_id);
                })
        ],
        'phone' => [
            'nullable',
            'string',
            'max:15',
            Rule::unique('customers', 'phone')
                ->where(function ($query) {
                    $query->where('created_by', auth()->id())
                          ->orWhere('parent_user_id', auth()->user()->parent_id);
                })
        ],
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
    ]);

    // Create the new customer in the database
    $customer = Customer::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Customer created successfully.',
        'customer_name' => $customer->name,  // Ensure this is being returned
        'customer_id' => $customer->id,      // Ensure this is being returned
    ]);
}

public function index(Request $request)
{
    try {
        $user = auth()->user();
        $userId = $user->id;
        $parentUserId = $user->parent_id;

        // Fetch purchases instead of orders
        $purchases = Purchase::whereIn('created_by', [$userId, $parentUserId])
                             ->with('supplier')  
                             ->orderBy('created_at', 'desc')
                             ->get();

        foreach ($purchases as $purchase) {
            $grossAmount = 0;
            $purchaseItems = PurchaseItem::where('custom_purchase_id', $purchase->custom_purchase_id)->get(); // Get matching purchase items

            foreach ($purchaseItems as $item) {
                $totalBeforeDiscount = $item->unit_price * $item->quantity;
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

            // Calculate remaining amount
            $remainingAmount = $netTotal - $purchase->paid;

            // Attach the calculated values to the purchase object
            $purchase->grossAmount = $grossAmount;
            $purchase->purchaseDiscount = $purchaseDiscount;
            $purchase->grossAmountAfterPurchaseDiscount = $grossAmountAfterPurchaseDiscount;
            $purchase->netTotal = $netTotal;
            $purchase->remainingAmount = $remainingAmount;
        }

        // Return the purchase listings view
        return view('purchases.index', compact('purchases'));
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
    \Log::info('Order update request data:', $request->all());

    try {
        // Start a database transaction
        DB::beginTransaction();
        $branchId = auth()->user()->branch_id ?? 1;

        // Check if the order exists by custom_order_id or any other unique identifier (like order_id)
        $existingOrder = Order::where('custom_order_id', $request->custom_order_id)->first();

        if (!$existingOrder) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        // Update the order details
        $existingOrder->customer_id = $request->customer_id;
        $existingOrder->sale_manager_id = $request->salesperson_id;
        $existingOrder->total_amount = $request->total_amount;
        $existingOrder->status = $request->order_status;
        $existingOrder->other_charges = $request->other_charges ?? 0;
        $existingOrder->discount_amount = $request->discount_amount ?? 0;
        $existingOrder->payment_method = $request->payment_method;
        $existingOrder->order_date = $request->order_date;
        $existingOrder->sale_note = $request->sale_note;
        $existingOrder->staff_note = $request->staff_note;

        if ($request->order_discount_type == 'percentage') {
            $existingOrder->discount_type = $request->order_discount_type . '%';  // Append % for percentage
        } else {
            $existingOrder->discount_type = '-';  // Use - for flat discount
        }

        $existingOrder->discount_amount = $request->order_discount;
        $existingOrder->other_charges = $request->other_charges;
        $existingOrder->paid = $request->paid_amount;

        $existingOrder->updated_by = auth()->id();  // Set the user updating the order
        $existingOrder->save();

        // Clear previous order items before updating (if needed)
        DB::table('order_items')->where('order_id', $existingOrder->id)->delete();

        // Check if orderData is provided
        if ($request->has('orderData')) {
            $orderData = json_decode($request->orderData, true); // Decode JSON string to array
            if (is_array($orderData)) {
                \Log::info('Order Data:', $orderData);

                // Insert updated order items
                foreach ($orderData as $item) {

                    $product = \DB::table('products')->where('id', $item['product_id'])->first();

                    if ($product) {
                        $costPrice = $product->cost;  
                    } else {
                        $costPrice = null; 
                    }
                  
                    DB::table('order_items')->insert([
                        'order_id' => $existingOrder->order_id,
                        'product_id' => $item['product_id'],
                        'uom_id' => $item['uomId'] !== null ? (int) $item['uomId'] : 0,
                        'quantity' => $item['qty'],
                        'unit_price' => $item['rate'],
                        'discount_type' => (strpos($item['discountType'], '%') !== false) ? '%' : '-',
                        'discount_amount' => $item['discountValue'],
                        'exit_warehouse' => $item['exit_warehouse'],
                        'custom_order_id' => $request->custom_order_id,
                        'cost_price' => $costPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                \Log::error('Invalid orderData format');
            }
        } else {
            \Log::error('Missing orderData');
        }

        // Commit transaction
        DB::commit();

        // Return a response indicating success
        return response()->json(['success' => true, 'message' => 'Order updated successfully.']);

    } catch (Exception $e) {
        // Rollback transaction if something goes wrong
        DB::rollBack();

        // Log the error with additional context
        \Log::error('Order update failed: ' . $e->getMessage(), [
            'request_data' => $request->all(),
            'exception' => $e
        ]);

        // Return a response indicating failure
        return response()->json(['success' => false, 'message' => 'There was an error updating the order. Please try again later.']);
    }
}







}
