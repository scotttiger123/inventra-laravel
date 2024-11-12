<?php 

namespace App\Http\Controllers\WarehouseController ;

use App\Http\Controllers\Controller; 
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
}