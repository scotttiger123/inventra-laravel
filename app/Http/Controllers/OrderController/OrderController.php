<?php 
namespace App\Http\Controllers\OrderController;
use App\Http\Controllers\Controller; 
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\User; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;


class OrderController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        // Get customers created by the current user or their parent
        $customers = Customer::where('created_by', $user->id)
        ->orWhere('created_by', $user->parent_id)
        ->get();

        // Fetch sale managers for additional dropdown if needed
        $saleManagers = User::where('role', 'sale_manager')->get();

        return view('orders.create', compact('customers', 'saleManagers'));
    }


  
       
        public function store(Request $request)
        {
            \Log::info('Order creation request data:', $request->all());
    
            // // Validate the incoming data
            // $validated = $request->validate([
            //     'customer_id' => 'required|exists:customers,id',
            //     'sale_manager_id' => 'required|exists:users,id',
            //     'amount' => 'required|numeric',
            //     'status' => 'required|string|in:pending,completed,cancelled',
            //     'other_charges' => 'nullable|numeric',
            //     'total_discount' => 'nullable|numeric',
            //     'payment_method' => 'required|string|in:credit,debit',
            //     'order_date' => 'required|date',
            //     'note' => 'nullable|string',
            // ]);
    
            try {
                // Start a database transaction
                DB::beginTransaction();
    
                // Create the order record
                $order = new Order();
                $order->customer_id = $request->customer_id;
                $order->sale_manager_id = $request->sale_manager_id;
                $order->total_amount = $request->total_amount;
                $order->status = $request->status;
                $order->other_charges = $request->other_charges ?? 0;
                $order->discount_amount = $request->discount_amount ?? 0;
                $order->payment_method = $request->payment_method;
                $order->order_date = $request->order_date;
                
                $order->created_by = auth()->id(); // Assuming the user is authenticated
                $order->updated_by = auth()->id();
                $order->save();
    
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
    
    



}
