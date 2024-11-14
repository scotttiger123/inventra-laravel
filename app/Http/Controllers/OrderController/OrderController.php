<?php 
namespace App\Http\Controllers\OrderController;
use App\Http\Controllers\Controller; 
use App\Models\Order;
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
                            'product_id' => '12',
                            'quantity' => $item['qty'],
                            'unit_price' => $item['rate'],
                            'discount_type' => $item['discountType'],
                            'discount_amount' => $item['discountValue'],
                            'exit_warehouse' => $item['exit_warehouse'],
                            
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
    $prefix = 'ORD-' . $branchId;

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
    return $prefix . '-' . $year . '-' . $newNumber;
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
 



}
