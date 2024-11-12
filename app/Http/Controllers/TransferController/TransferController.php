<?php 
namespace App\Http\Controllers\TransferController;
use App\Http\Controllers\Controller; 
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\User; 
use App\Models\Transfer; 
use App\Models\Warehouse; 
use App\Models\Product; 



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class TransferController extends Controller
{
    public function index()
    {
        
         // Eager load the 'product', 'fromWarehouse', and 'toWarehouse' relationships
        $transfers = Transfer::with(['product', 'fromWarehouse', 'toWarehouse'])->get();
    
        
        // Pass the transfers to the view
        return view('transfers.index', compact('transfers'));
    }
    
        public function create()
{
    $user = auth()->user();

    // Retrieve warehouses created by the current user or their parent user
    $warehouses = Warehouse::where('created_by', $user->id)
        ->orWhere('created_by', $user->parent_user_id)
        ->get();

    // Retrieve products created by the current user or their parent user
    $products = Product::where(function($query) use ($user) {
        $query->where('created_by', $user->id)
              ->orWhere('created_by', $user->parent_user_id);
    })->get();

    return view('transfers.create', compact('warehouses', 'products'));
}


public function store(Request $request)
{
    
    $validatedData = $request->validate([
        'created_at' => 'required|date',
        'from_warehouse_id' => 'required|exists:warehouses,id',
        'to_warehouse_id' => 'required|exists:warehouses,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|numeric|min:1',
    ], [
        'created_at.required' => 'The transfer date field is required.',
        'from_warehouse_id.required' => 'The source warehouse id field is required.',
        'to_warehouse_id.required' => 'The destination warehouse id field is required.',
        'product_id.required' => 'The product id field is required.',
        'quantity.required' => 'The quantity field is required.',
        'quantity.numeric' => 'The quantity must be a number.',
        'quantity.min' => 'The quantity must be at least 1.',
    ]);

    $user = auth()->user();

    $product = Product::find($request->product_id);

    if (!$product) {
        return back()->withErrors(['product_id' => 'Product not found.']);
    }

    $date = Carbon::parse($request->created_at); // Ensures the date is in the correct format

    $transfer = new Transfer([
        'from_warehouse_id' => $request->from_warehouse_id,
        'to_warehouse_id' => $request->to_warehouse_id,
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'date' => $date->format('Y-m-d'), // Ensures the correct format for MySQL
        'created_by' => auth()->id(),
    ]);

    $transfer->save();

    return redirect()->route('transfers.index')->with('success', 'Transfer created successfully!');
}



    public function show(Transfer $transfer)
    {
        return view('transfers.show', compact('transfer'));
    }

    public function edit(Transfer $transfer)
    {
        $warehouses = Warehouse::all();
        return view('transfers.edit', compact('transfer', 'warehouses'));
    }

    public function update(Request $request, Transfer $transfer)
    {
        // Validate and update the transfer
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric',
        ]);

        $transfer->update([
            'from_warehouse_id' => $request->from_warehouse_id,
            'to_warehouse_id' => $request->to_warehouse_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('transfers.index')
                         ->with('success', 'Transfer updated successfully!');
    }

    public function destroy(Transfer $transfer)
    {
        // Delete the transfer
        $transfer->delete();

        return redirect()->route('transfers.index')
                         ->with('success', 'Transfer deleted successfully!');
    }
}


