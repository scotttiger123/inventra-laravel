<?php 

namespace App\Http\Controllers\WarehouseController ;

use App\Http\Controllers\Controller; 
use App\Models\WareHouse;
use App\Models\OrderItem;
use App\Models\PurchaseItem;

use App\Models\Order;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    public function index()
    {
        $user = auth()->user();

    // Retrieve warehouses created by the current user or their parent user
        $warehouses = Warehouse::where('created_by', $user->id)
        ->orWhere('created_by', $user->parent_user_id)
        ->get();

        return view('warehouses.index', compact('warehouses'));
    }

    // Show the form for creating a new warehouse
    public function create()
    {
        // Return the view for creating a new warehouse
        return view('warehouses.create');
    }

    // Store a newly created warehouse in storage
    public function store(Request $request)
    {
    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        // Add more fields as needed
    ]);

    // Get the authenticated user ID
    $userId = auth()->id();

    // Add the created_by field to the data
    $data = $request->all();
    $data['created_by'] = $userId;

    // Create a new warehouse with the validated data
    Warehouse::create($data);

    // Redirect with success message
    return redirect()->route('warehouses.index')
                     ->with('success', 'Warehouse created successfully!');
    }


    // Display the specified warehouse
    public function show(Warehouse $warehouse)
    {
        // Return the view with the warehouse data
        return view('warehouses.show', compact('warehouse'));
    }

    // Show the form for editing the specified warehouse
    public function edit(Warehouse $warehouse)
    {
        // Return the edit view with the warehouse data
        return view('warehouses.edit', compact('warehouse'));
    }

    // Update the specified warehouse in storage
    public function update(Request $request, Warehouse $warehouse)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            // Add more fields as needed
        ]);

        // Update the warehouse
        $warehouse->update($request->all());

        // Redirect with success message
        return redirect()->route('warehouses.index')
                         ->with('success', 'Warehouse updated successfully!');
    }

    public function destroy($id)
    {
        // Find the warehouse by ID
        $warehouse = Warehouse::find($id);

        // Check if the warehouse exists
        if ($warehouse) {
            // Perform the soft delete
            $warehouse->delete();
            
            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Warehouse deleted successfully!'
            ]);
        }

        // Return error response if warehouse not found
        return response()->json([
            'success' => false,
            'message' => 'Warehouse not found.'
        ]);
    }

    // Restore a soft-deleted warehouse
    public function restore($id)
    {
        $warehouse = Warehouse::withTrashed()->findOrFail($id);
        $warehouse->restore();

        return redirect()->route('warehouses.index')
                         ->with('success', 'Warehouse restored successfully!');
    }

    // Permanently delete a soft-deleted warehouse
    public function forceDelete($id)
    {
        $warehouse = Warehouse::withTrashed()->findOrFail($id);
        $warehouse->forceDelete();

        return redirect()->route('warehouses.index')
                         ->with('success', 'Warehouse permanently deleted successfully!');
    }

            







    public function warehouseReport(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');
    
        // Fetch total purchases
        $purchases = DB::table('products')
            ->leftJoin('purchase_items', 'products.id', '=', 'purchase_items.product_id')
            ->when($warehouseId, function ($query, $warehouseId) {
                return $query->where('purchase_items.inward_warehouse_id', $warehouseId);
            })
            ->select(
                'products.id as product_id',
                'products.product_name as product_name',
                DB::raw('SUM(IFNULL(purchase_items.quantity, 0)) as purchased_quantity'),
                DB::raw('SUM(IFNULL(purchase_items.quantity * purchase_items.rate, 0)) as purchased_amount')
            )
            ->groupBy('products.id', 'products.product_name')
            ->get();
    
        // Fetch total sales
        $sales = DB::table('products')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->when($warehouseId, function ($query, $warehouseId) {
                return $query->where('order_items.exit_warehouse', $warehouseId);
            })
            ->select(
                'products.id as product_id',
                'products.product_name as product_name',
                DB::raw('SUM(IFNULL(order_items.quantity, 0)) as sold_quantity'),
                DB::raw('SUM(IFNULL(order_items.quantity * order_items.unit_price, 0)) as sold_amount')
            )
            ->groupBy('products.id', 'products.product_name')
            ->get();
    
        // Fetch Transfer In quantity (to the warehouse)
        $transferIn = DB::table('products')
            ->leftJoin('transfers', 'products.id', '=', 'transfers.product_id')
            ->when($warehouseId, function ($query, $warehouseId) {
                return $query->where('transfers.to_warehouse_id', $warehouseId);
            })
            ->select(
                'products.id as product_id',
                DB::raw('SUM(IFNULL(transfers.quantity, 0)) as transfer_in_quantity')
            )
            ->groupBy('products.id')
            ->get();
    
        // Fetch Transfer Out quantity (from the warehouse)
        $transferOut = DB::table('products')
            ->leftJoin('transfers', 'products.id', '=', 'transfers.product_id')
            ->when($warehouseId, function ($query, $warehouseId) {
                return $query->where('transfers.from_warehouse_id', $warehouseId);
            })
            ->select(
                'products.id as product_id',
                DB::raw('SUM(IFNULL(transfers.quantity, 0)) as transfer_out_quantity')
            )
            ->groupBy('products.id')
            ->get();
    
        // Merge all data (purchases, sales, transfers)
        $productQuantities = $purchases->map(function ($purchase) use ($sales, $transferIn, $transferOut) {
            $sale = $sales->firstWhere('product_id', $purchase->product_id);
            $in = $transferIn->firstWhere('product_id', $purchase->product_id);
            $out = $transferOut->firstWhere('product_id', $purchase->product_id);
    
            $sold_quantity = $sale ? $sale->sold_quantity : 0;
            $transfer_in_quantity = $in ? $in->transfer_in_quantity : 0;
            $transfer_out_quantity = $out ? $out->transfer_out_quantity : 0;
    
            // Calculate remaining quantity
            $remaining_quantity = $purchase->purchased_quantity 
                                 + $transfer_in_quantity 
                                 - $sold_quantity 
                                 - $transfer_out_quantity;
    
            return [
                'product_name' => $purchase->product_name,
                'purchased_quantity' => $purchase->purchased_quantity,
                'purchased_amount' => $purchase->purchased_amount,
                'sold_quantity' => $sold_quantity,
                'sold_amount' => $sale ? $sale->sold_amount : 0,
                'transfer_in_quantity' => $transfer_in_quantity,
                'transfer_out_quantity' => $transfer_out_quantity,
                'remaining_quantity' => $remaining_quantity,
            ];
        });
    
        // Fetch all warehouses
        $warehouses = Warehouse::all();
    
        // Return the view with the fetched data
        return view('warehouses.warehouse-report', compact('productQuantities', 'warehouses'));
    }
    



    
    
}