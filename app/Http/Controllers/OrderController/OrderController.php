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
use App\Models\Tax;
use App\Models\Status;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\User; 
use App\Models\Payment; 
use App\Models\PaymentMethod;
use App\Models\Account;
use App\Models\Salesman; 
use App\Models\Setting;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;


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
    
        
        $products = Product::where('created_by', $user->id) 
            ->orWhere('parent_user_id', $user->id)
            ->orWhere('parent_user_id', $user->parent_id) 
            ->get();

            $uoms = Uom::all();  
            $taxes = Tax::all(); 
            $statuses = Status::all();
            $warehouses = Warehouse::all();
            $salespersons = Salesman::all();
    
        return view('orders.create', compact('customers', 'salespersons', 'products', 'statuses','uoms','taxes','warehouses'));

        
    }
    
    public function createPOS()
    {
        $user = auth()->user();
        $currencySymbol = \App\Models\Setting::where('name', 'currency-symbol')->value('value');
    
        // Get customers created by the current user or their parent
        $customers = Customer::where('created_by', $user->id)
            ->orWhere('parent_user_id', $user->id)
            ->orWhere('parent_user_id', $user->parent_id)
            ->get();
    
        
        $products = Product::all();
        $uoms = Uom::all();  
        $taxes = Tax::all(); 
        $statuses = Status::all();
        $categories = Category::all();
        $warehouses = Warehouse::all();
        $salespersons = Salesman::all();
        
    
        return view('orders.create-pos', compact(
            'customers', 
            'categories', 
            'salespersons', 
            'products', 
            'statuses', 
            'uoms', 
            'taxes', 
            'currencySymbol', 
            'warehouses'
        ));
    }
    



    





    public function getInvoice($customOrderId)
    {
        $order = Order::where('custom_order_id', $customOrderId)
        ->leftJoin('statuses', 'statuses.id', '=', 'orders.status') 
        ->select('orders.*', 'statuses.status_name')
        ->first();
        
        


        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }
    
        $customer = $order->customer;
    
        if (!$customer) {
            return response()->json(['error' => 'Customer not found.'], 404);
        }

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
        
    

                                    
        // Fetch the sales manager associated with this order (if any)
        $salesManager = User::where('id', $order->sale_manager_id)->first();
        $salesManagerName = $salesManager ? $salesManager->name : '';
        $order->sales_manager_name = $salesManagerName;

    
        // Fetch all order items with product names and UOM details
        $orderItems = OrderItem::where('custom_order_id', $customOrderId)
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('uoms', 'uoms.id', '=', 'order_items.uom_id')
            ->leftJoin('warehouses', 'warehouses.id', '=', 'order_items.exit_warehouse')
            ->select(
                'order_items.*',
                'products.product_name',
                'uoms.abbreviation as uom_name',
                'warehouses.name as warehouse_name'
            )
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
    
            $uomName = !empty($item->uom_name) ? $item->uom_name : '';
    
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'uom_id' => $item->uom_id,
                'uom_name' => $uomName,
                'unit_price' => $item->unit_price,
                'exit_warehouse' => $item->exit_warehouse,
                'warehouse_name' => $item->warehouse_name,
                'discount_amount' => $item->discount_amount,
                'discount_type' => $item->discount_type,
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
        
        $taxRate = $order->tax_rate; 
        $taxAmount = 0;
    
        if ($taxRate) {
            $taxAmount = ($netTotal * $taxRate) / 100;
        }
    
        $netTotalWithTax = $netTotal + $taxAmount; // net total with order tax
    

        $payments = Payment::where('invoice_id', $customOrderId) 
                        ->sum('amount');


        // Calculate remaining amount
        $paidAmount = (float)$payments;
        $remainingAmount = $netTotalWithTax  - $paidAmount;
        


        return response()->json([

            'order' => $order,
            'currencySymbol' => $currencySymbol,
            'orderItems' => $orderItemsData,
            'grossAmount' => $grossAmount,
            'orderDiscount' => $orderDiscount,
            'grossAmountAfterOrderDiscount' => $grossAmountAfterOrderDiscount,
            'otherCharges' => $otherCharges,
            'netTotal' => $netTotal,
            'taxRate' => $taxRate,
            'netTotalWithTax' => $netTotalWithTax,
            'taxAmount' => $taxAmount,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
            'companyName' => $companyName,
            'companyAddress' => $companyAddress,
            'companyPhone' => $companyPhone,
            'companyEmail' => $companyEmail,
            
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
            $order->status = $request->status_id ?? 0;
            $order->other_charges = $request->other_charges ?? 0;
            $order->discount_amount = $request->discount_amount ?? 0;
            $order->tax_rate = $request->tax_rate ?? 0;
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


                    // Retrieve the product's cost_price from the products table
                    $product = \DB::table('products')->where('id', $item['product_id'])->first();

                    if ($product) {
                        $costPrice = $product->cost;  
                    } else {
                        $costPrice = null; 
                    }



                        DB::table('order_items')->insert([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'uom_id' => $item['uomId'] !== null ? (int) $item['uomId'] : 0,

                            'quantity' => $item['qty'],
                            'unit_price' => $item['rate'],
                            'discount_type' => (strpos($item['discountType'], '%') !== false) ? '%' : '-',
                            'discount_amount' => $item['discountValue'],
                            'exit_warehouse' => $item['exit_warehouse_id'] ?? 0,
                            'custom_order_id' => $orderNumber,
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
            
            


            if ($request->paid_amount > 0) {

                DB::table('payments')->insert([
                    'payable_type' => 'customer', 
                    'payment_head' => 1, 
                    'payable_id' => $request->customer_id, 
                    'payment_date' => $request->order_date, 
                    'invoice_id' => $orderNumber, 
                    'amount' => $request->paid_amount, 
                    'payment_method' => $request->payment_method, 
                    'account_id' => 1, 
                    'payment_type' => "credit", 
                    'note' => 'Payment made at the time of order creation', 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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





    public function storePos(Request $request)
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
            $order->status = $request->status_id ?? 0;
            $order->other_charges = $request->other_charges ?? 0;
            $order->discount_amount = $request->discount_amount ?? 0;
            $order->tax_rate = $request->tax_rate ?? 0;
            $order->payment_method = $request->payment_method;
            $order->order_date = $request->order_date ?? now();
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


                    // Retrieve the product's cost_price from the products table
                    $product = \DB::table('products')->where('id', $item['product_id'])->first();

                    if ($product) {
                        $costPrice = $product->cost;  
                    } else {
                        $costPrice = null; 
                    }

                        DB::table('order_items')->insert([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['qty'],
                            'unit_price' => $item['rate'],
                            'custom_order_id' => $orderNumber,
                            'cost_price' => $costPrice, 
                            'exit_warehouse' => $item['warehouse_id'] === null ?? 0 ,
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
            
            
            if ($request->paid_amount > 0) {
                DB::table('payments')->insert([
                    'payable_type' => 'customer', 
                    'payment_head' => 1, 
                    'payable_id' => $request->customer_id, 
                    'payment_date' => $request->order_date ?? now(),
                    'invoice_id' => $orderNumber, 
                    'amount' => $request->paid_amount, 
                    'payment_method' => $request->payment_method, 
                    'account_id' => 1, 
                    'payment_type' => "credit", 
                    'note' => 'Payment made at the time of order creation', 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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

        $lastOrder = Order::whereYear('created_at', $year)
                      ->where('branch_id', $branchId)
                      ->orderBy('custom_order_id', 'desc')
                      ->first();
    
        if ($lastOrder) {
        $lastNumber = (int) substr($lastOrder->custom_order_id, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
        $newNumber = '001';
        }
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

    $validated['created_by'] = auth()->id();  
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

        $totalGrossAmount = 0;
        $totalOrderDiscount = 0;
        $totalNetAmount = 0;
        $totalPaid = 0;
        $totalAmountDue = 0;
        $totalNetReturnAmount = 0;
        $totalProductCost = 0; 
        $totalNetProfit = 0; 

        $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value');

        $startDate = $request->input('start_date', Carbon::now()->subDays(29)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $filterRemainingAmount = $request->input('remaining_amount_filter', 'all'); 

        $ordersQuery = Order::select('orders.*', 'statuses.status_name')
            ->leftJoin('statuses', 'orders.status', '=', 'statuses.id')
            ->whereIn('orders.created_by', [$userId, $parentUserId])
            ->whereNull('orders.deleted_at')
            ->with('customer')
            ->orderBy('orders.created_at', 'desc');

        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        $orders = $ordersQuery->get();

        foreach ($orders as $order) {
            $grossAmount = 0;
            $orderItems = OrderItem::where('custom_order_id', $order->custom_order_id)->get();
            $orderProductCost = 0; // Initialize product cost for this order

            foreach ($orderItems as $item) {
                $totalBeforeDiscount = $item->unit_price * $item->quantity;
                $discountAmount = $item->discount_amount;

                if ($item->discount_type == '%') {
                    $discountAmountCalculated = ($totalBeforeDiscount * $discountAmount) / 100;
                } else {
                    $discountAmountCalculated = $discountAmount;
                }

                // Calculate product cost for each item
                $orderProductCost += $item->cost_price * $item->quantity;

                $grossAmount += $totalBeforeDiscount - $discountAmountCalculated;
            }

            $orderDiscount = 0;
            if ($order->discount_type == '%') {
                $orderDiscount = ($grossAmount * $order->discount_amount) / 100;
            } else {
                $orderDiscount = $order->discount_amount;
            }

            $grossAmountAfterOrderDiscount = $grossAmount - $orderDiscount;
            $netTotal = $grossAmountAfterOrderDiscount + $order->other_charges;

            $taxRate = $order->tax_rate;
            $taxAmount = 0;
            if ($taxRate) {
                $taxAmount = ($netTotal * $taxRate) / 100;
            }
            $netTotalWithTax = $netTotal + $taxAmount;

            
            

            $payments = Payment::where('invoice_id', $order->custom_order_id)
                ->sum('amount');

            $remainingAmount = $netTotalWithTax - $payments;

            if ($order->status_name === 'Return') {
                $totalGrossAmount -= $grossAmount;
                $totalOrderDiscount -= $orderDiscount;
                
                $totalPaid -= $payments;
                $totalAmountDue -= $remainingAmount;
                $totalNetReturnAmount += $netTotalWithTax;
                $netProfit = $netTotal - $orderProductCost; 
                $totalNetProfit -= $netProfit;
                
            } else {
                $totalGrossAmount += $grossAmount;
                $totalOrderDiscount += $orderDiscount;
                $totalNetAmount += $netTotalWithTax;
                $totalPaid += $payments;
                $totalAmountDue += $remainingAmount;
                $netProfit = $netTotal - $orderProductCost; 
                $totalNetProfit += $netProfit;
            }

            // Store order calculations in the order object
            $order->grossAmount = $grossAmount;
            $order->orderDiscount = $orderDiscount;
            $order->grossAmountAfterOrderDiscount = $grossAmountAfterOrderDiscount;
            $order->netTotal = $netTotalWithTax;
            $order->remainingAmount = $remainingAmount;
            $order->ordersPaidAmount = $payments;
            $order->netProfit = $netProfit; // Store net profit for this order
            $order->statusDisplay = $order->status_name;
        }

        // Apply remaining amount filter
        if ($filterRemainingAmount === 'due') {
            $orders = $orders->filter(function ($order) {
                return $order->remainingAmount > 0;
            });
        } elseif ($filterRemainingAmount === 'paid') {
            $orders = $orders->filter(function ($order) {
                return $order->remainingAmount <= 0;
            });
        }


        $topSellingProducts = \DB::table('order_items')
        ->selectRaw('product_id, SUM(quantity) as total_sold')
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get()
        ->map(function ($item) {
            $product = Product::find($item->product_id);
    
            
            if ($product) {
                return [
                    'product_name' => $product->product_name,
                    'total_sold' => $item->total_sold,
                    'image_path' => $product->image_path,
                ];
            }
    
            
        });
    

        $accounts = \DB::table('accounts')
            ->whereNull('deleted_at')
            ->pluck('name', 'id');
            $paymentMethods = PaymentMethod::all(); 
            
        return view('orders.index', compact(
            'orders',
            'totalGrossAmount',
            'totalOrderDiscount',
            'totalNetAmount',
            'totalNetReturnAmount',
            'totalPaid',
            'totalAmountDue',
            'currencySymbol',
            'totalNetProfit', 
            'accounts',
            'paymentMethods',
            'topSellingProducts'
        ));
    } catch (Exception $e) {
        \Log::error('Failed to fetch orders: ' . $e->getMessage());
        return redirect()->back()->withErrors('Failed to fetch orders. Please try again later.');
    }
}



public function edit($customOrderId)
{
    
    $order = Order::where('custom_order_id', $customOrderId)->first();

    if (!$order) {
        return redirect()->route('orders.index')->with('error', 'Order not found.');
    }

    $customers = Customer::all();

    
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
        $orderNumber = $request->custom_order_id;
        // Update the order details
        $existingOrder->customer_id = $request->customer_id;
        $existingOrder->sale_manager_id = $request->salesperson_id;
        $existingOrder->total_amount = $request->total_amount;
        $existingOrder->status = $request->status_id;
        $existingOrder->other_charges = $request->other_charges ?? 0;
        $existingOrder->discount_amount = $request->discount_amount ?? 0;
        $existingOrder->tax_rate = $request->tax_rate ?? 0;
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

        $existingOrder->updated_by = auth()->id();  
        $existingOrder->save();

        
        DB::table('order_items')->where('custom_order_id', $existingOrder->custom_order_id)->delete();

        
        if ($request->has('orderData')) {
            $orderData = json_decode($request->orderData, true); 
            if (is_array($orderData)) {
                \Log::info('Order Data:', $orderData);

                
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
                        'exit_warehouse'  => $item['exit_warehouse_id'] ?? 0,
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

        
        if ($request->paid_amount > 0) {
            
            DB::table('payments')
                ->where('invoice_id', $orderNumber) 
                ->delete();
        
            
            DB::table('payments')->insert([
                'payable_type' => 'customer',
                'payment_head' => 1, // Cash
                'payable_id' => $request->customer_id,
                'payment_date' => $request->order_date ?? now(),
                'invoice_id' => $orderNumber,
                'amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'account_id' => 1, // Default sale account
                'payment_type' => "debit",
                'note' => 'Payment updated during order update',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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


public function destroy($customOrderId)
{
    try {
        $order = Order::where('custom_order_id', $customOrderId)->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

      
        OrderItem::where('custom_order_id', $customOrderId)->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    } catch (\Exception $e) {
        \Log::error('Error deleting order: ' . $e->getMessage());
        return redirect()->route('orders.index')->with('error', 'Failed to delete order. Please try again later.');
    }
}





}
