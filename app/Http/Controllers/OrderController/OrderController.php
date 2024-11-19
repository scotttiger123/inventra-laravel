<?php 
namespace App\Http\Controllers\OrderController;
use App\Http\Controllers\Controller; 
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\Uom;


use App\Models\User; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;


class OrderController extends Controller
{
    

    public function create()
    {
        $user = auth()->user();
    
        // Get customers created by the current user or their parent
        $customers = Customer::where('created_by', $user->id)
            ->orWhere('parent_user_id', $user->id)
            ->orWhere('parent_user_id', $user->parent_id)
            ->get();
    
            $salePersonRoleId = Role::where('name', 'Sale Person')->value('id');

            $salespersons = User::where('Role', $salePersonRoleId)->get();

    
        // Fetch products that belong to the current user or their parent
        $products = Product::where('created_by', $user->id) // Products created by the current user
            ->orWhere('parent_user_id', $user->id)
            ->orWhere('parent_user_id', $user->parent_id) // Products created by the parent of the current user
            ->get();

            $uoms = Uom::all();  // Assuming the model for Uom is correctly defined

    
        return view('orders.create', compact('customers', 'salespersons', 'products', 'uoms'));

        
    }
    

    public function createPOS()
    {
        $user = auth()->user();

        // Get customers created by the current user or their parent
        $customers = Customer::where('created_by', $user->id)
        ->orWhere('created_by', $user->parent_id)
        ->get();

        // Fetch sale managers for additional dropdown if needed
        $saleManagers = User::where('role', 'sale_manager')->get();

        return view('orders.create-pos', compact('customers', 'saleManagers'));
    }



    





    public function getInvoice($customOrderId)
    {
        $order = Order::where('custom_order_id', $customOrderId)->first();
    
        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }
    
        $customer = $order->customer;
    
        if (!$customer) {
            return response()->json(['error' => 'Customer not found.'], 404);
        }



                                    
        // Fetch the sales manager associated with this order (if any)
        $salesManager = User::where('id', $order->sale_manager_id)->first();
        $salesManagerName = $salesManager ? $salesManager->name : '';
        $order->sales_manager_name = $salesManagerName;

    
        // Fetch all order items with product names and UOM details
        $orderItems = OrderItem::where('custom_order_id', $customOrderId)
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('uoms', 'uoms.id', '=', 'order_items.uom_id')
            ->select('order_items.*', 'products.product_name', 'uoms.abbreviation as uom_name')
            ->get();
    
        $orderItemsData = $orderItems->map(function ($item) {
            $item->total_before_discount = (float)$item->unit_price * (int)$item->quantity;
    
            // Calculate product-level discount
            if (strpos($item->discount_type, '%') !== false) {
                $discountPercentage = rtrim($item->discount_amount, '%');
                $item->discount_amount_calculated = ($item->total_before_discount * $discountPercentage) / 100;
            } else {
                $item->discount_amount_calculated = (float)$item->discount_amount;
            }
    
            $item->net_rate = $item->unit_price - $item->discount_amount_calculated / $item->quantity;
            $item->total_after_discount = $item->total_before_discount - $item->discount_amount_calculated;
    
            $uomName = !empty($item->uom_name) ? $item->uom_name : '-';
    
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'uom_id' => $item->uom_id,
                'uom_name' => $uomName,
                'unit_price' => $item->unit_price,
                'exit_warehouse' => $item->exit_warehouse,
                'discount_amount' => $item->discount_amount . $item->discount_type,
                'net_rate' => $item->net_rate,
                'amount' => $item->total_after_discount,
            ];
        });
    
        // Calculate gross amount
        $grossAmount = $orderItemsData->sum('amount');
    
        // Apply order-level discount
        $orderDiscountType = $order->discount_type; // e.g., "flat" or "percentage"
        $orderDiscountAmount = (float)$order->discount_amount;
    
        if ($orderDiscountType === 'percentage') {
            $orderDiscount = ($grossAmount * $orderDiscountAmount) / 100;
        } else {
            $orderDiscount = $orderDiscountAmount;
        }
    
        $grossAmountAfterOrderDiscount = $grossAmount - $orderDiscount;
    
        // Add other charges and calculate net total
        $otherCharges = (float)$order->other_charges;
        $netTotal = $grossAmountAfterOrderDiscount + $otherCharges;
    
        // Calculate remaining amount
        $paidAmount = (float)$order->paid;
        $remainingAmount = $netTotal - $paidAmount;
        


        return response()->json([
            'order' => $order,
            
            'orderItems' => $orderItemsData,
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
        \Log::info('Order creation request data:', $request->all());
    
        try {
            // Start a database transaction
            DB::beginTransaction();
            $branchId = auth()->user()->branch_id ?? 1;
            
            if ($request->has('custom_order_id') && $request->custom_order_id) {
                $orderNumber = $request->custom_order_id;
                $existingOrder = Order::where('custom_order_id', $orderNumber)->first();
                if ($existingOrder) {
                    return response()->json(['success' => false, 'message' => 'Custom Order ID already exists.']);
                }
            } else {
                $orderNumber = $this->generateOrderNumber($branchId);
            }

             
            // Create the order record
            $order = new Order();
            $order->customer_id = $request->customer_id;
            $order->sale_manager_id = $request->salesperson_id;
            $order->total_amount = $request->total_amount;
            $order->status = $request->order_status;
            $order->other_charges = $request->other_charges ?? 0;
            $order->discount_amount = $request->discount_amount ?? 0;
            $order->payment_method = $request->payment_method;
            $order->order_date = $request->order_date;
            $order->custom_order_id = $orderNumber;
            $order->sale_note = $request->sale_note;
            $order->staff_note = $request->staff_note;
            
            
            if ($request->order_discount_type == 'percentage') {
                $order->discount_type = $request->order_discount_type . '%';  // Append % for percentage
            } else {
                $order->discount_type = '-';  // Use - for flat discount
            }

            $order->discount_amount = $request->order_discount;
            $order->other_charges = $request->other_charges;
            $order->paid = $request->paid_amount;
            
            $order->branch_id = auth()->user()->branch_id;
             
            
            $order->created_by = auth()->id(); // Assuming the user is authenticated
            $order->updated_by = auth()->id();
            $order->save();
            if ($request->has('orderData')) {
                $orderData = json_decode($request->orderData, true); // Decode JSON string to array
                if (is_array($orderData)) {
                    \Log::info('Order Data:', $orderData);
                    foreach ($orderData as $item) {
                        DB::table('order_items')->insert([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'uom_id' => $item['uomId'] !== null ? (int) $item['uomId'] : 0,

                            'quantity' => $item['qty'],
                            'unit_price' => $item['rate'],
                            'discount_type' => (strpos($item['discountType'], '%') !== false) ? '%' : '-',
                            'discount_amount' => $item['discountValue'],
                            'exit_warehouse' => $item['exit_warehouse'],
                            'custom_order_id' => $orderNumber,
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
            return response()->json(['success' => true, 'message' => 'Order created successfully.']);
    
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();
    
            // Log the error with additional context
            \Log::error('Order creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);
    
            // Return a response indicating failure
            return response()->json(['success' => false, 'message' => 'There was an error creating the order. Please try again later.']);
        }
    }

    
    private function generateOrderNumber($branchId)
{
    $year = date('Y');
    $prefix = $branchId;

    // Get the last order for the current year and branch, ordered by custom_order_id (to get the latest order)
    $lastOrder = Order::whereYear('created_at', $year)
                      ->where('branch_id', $branchId)
                      ->orderBy('custom_order_id', 'desc')
                      ->first();

    // If no orders exist for the current year and branch, start with '001'
    if ($lastOrder) {
        // Extract the numeric part from the last order's custom_order_id
        $lastNumber = (int) substr($lastOrder->custom_order_id, -3);
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

        $orders = Order::whereIn('created_by', [$userId, $parentUserId])
                       ->with('customer')
                       ->orderBy('created_at', 'desc')
                       ->get();
        foreach ($orders as $order) {
            $grossAmount = 0;
            $orderItems = OrderItem::where('custom_order_id', $order->custom_order_id)->get(); // Get matching order items

            foreach ($orderItems as $item) {
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

            // Apply order-level discount (percentage or flat)
            $orderDiscount = 0;
            if ($order->discount_type == '%') {
                $orderDiscount = ($grossAmount * $order->discount_amount) / 100; // Calculate discount as a percentage
            } else {
                $orderDiscount = $order->discount_amount; // Flat discount amount
            }

            // Calculate gross amount after order-level discount
            $grossAmountAfterOrderDiscount = $grossAmount - $orderDiscount;

            // Add other charges to calculate the net total
            $netTotal = $grossAmountAfterOrderDiscount + $order->other_charges;

            // Calculate remaining amount
            $remainingAmount = $netTotal - $order->paid;

            // Attach the calculated values to the order object
            $order->grossAmount = $grossAmount;
            $order->orderDiscount = $orderDiscount;
            $order->grossAmountAfterOrderDiscount = $grossAmountAfterOrderDiscount;
            $order->netTotal = $netTotal;
            $order->remainingAmount = $remainingAmount;
        }

        return view('orders.index', compact('orders'));
    } catch (Exception $e) {
        \Log::error('Failed to fetch orders: ' . $e->getMessage());
        return redirect()->back()->withErrors('Failed to fetch orders. Please try again later.');
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
        DB::table('order_items')->where('custom_order_id', $existingOrder->id)->delete();

        // Check if orderData is provided
        if ($request->has('orderData')) {
            $orderData = json_decode($request->orderData, true); // Decode JSON string to array
            if (is_array($orderData)) {
                \Log::info('Order Data:', $orderData);

                // Insert updated order items
                foreach ($orderData as $item) {
                    DB::table('order_items')->insert([
                        'order_id' => $existingOrder->id,
                        'product_id' => $item['product_id'],
                        'uom_id' => $item['uomId'] !== null ? (int) $item['uomId'] : 0,
                        'quantity' => $item['qty'],
                        'unit_price' => $item['rate'],
                        'discount_type' => (strpos($item['discountType'], '%') !== false) ? '%' : '-',
                        'discount_amount' => $item['discountValue'],
                        'exit_warehouse' => $item['exit_warehouse'],
                        'custom_order_id' => $request->custom_order_id,
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
