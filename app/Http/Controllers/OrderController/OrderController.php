<?php 
namespace App\Http\Controllers\OrderController;
use App\Http\Controllers\Controller; 
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;


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
    
        return view('orders.create', compact('customers', 'salespersons', 'products'));
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
    
            // Create the order record
            $order = new Order();
            $order->customer_id = $request->customer_id;
            $order->sale_manager_id = $request->salesperson_id;
            $order->total_amount = $request->total_amount;
            $order->status = $request->status;
            $order->other_charges = $request->other_charges ?? 0;
            $order->discount_amount = $request->discount_amount ?? 0;
            $order->payment_method = $request->payment_method;
            $order->order_date = $request->order_date;
            $order->created_by = auth()->id(); // Assuming the user is authenticated
            $order->updated_by = auth()->id();
            $order->save();
    
            // Process and store order items (assuming the request contains 'order_items')
            if ($request->has('order_items') && is_array($request->order_items)) {
                foreach ($request->order_items as $item) {
                    $order->items()->create([
                        'product' => $item['product'],
                        'qty' => $item['qty'],
                        'rate' => $item['rate'],
                        'discount' => $item['discount'] ?? 0,
                        'net_rate' => $item['netRate'] ?? 0,
                        'amount' => $item['amount'],
                    ]);
                }
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
            return response()->json(['success' => false, 'message' => 'There was an error creating the order. Please try again later.'], 500);
        }
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
