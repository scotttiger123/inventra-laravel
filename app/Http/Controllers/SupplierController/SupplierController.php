<?php 

namespace App\Http\Controllers\SupplierController;

use App\Http\Controllers\Controller; 
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers', 'email')
                    ->where(function ($query) use ($request) {
                        $query->where('created_by', auth()->id())
                              ->orWhere('parent_user_id', auth()->user()->parent_id);
                    })
        ],
        'phone' => [
            'nullable',
            'string',
            'max:15',
            Rule::unique('suppliers', 'phone')
                ->where(function ($query) use ($request) {
                    $query->where('created_by', auth()->id())
                        ->orWhere('parent_user_id', auth()->user()->parent_id);
                })
        ],
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'po_box' => 'nullable|string|max:10',
            'tax_number' => 'nullable|string|max:20',
            'initial_balance' => 'nullable|numeric',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric'
        ]);

        // Create a new supplier instance
        $supplier = new Supplier();
        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->address = $request->input('address');
        $supplier->city = $request->input('city');
        $supplier->po_box = $request->input('po_box');
        $supplier->tax_number = $request->input('tax_number');
        $supplier->initial_balance = $request->input('initial_balance');
        $supplier->discount_type = $request->input('discount_type');
        $supplier->discount_value = $request->input('discount_value');
        
        $supplier->created_by = auth()->id(); 
        $supplier->parent_user_id = auth()->user()->parent_id;

        try {
            $supplier->save();
            return redirect()->route('suppliers.create')->with('success', 'Supplier added successfully!');
        } catch (QueryException $e) {
            Log::error('Error saving supplier: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }

    public function index()
    {
        $suppliers = Supplier::with('creator')->get(); 
        return view('suppliers.index', compact('suppliers'));
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if ($supplier) {
            $supplier->delete();
            return response()->json(['success' => true, 'message' => 'Supplier deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Supplier not found.']);
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id . ',id,parent_user_id,' . auth()->user()->parent_id . ',created_by,' . auth()->id(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'po_box' => 'nullable|string|max:10',
            'tax_number' => 'nullable|string|max:20',
            'initial_balance' => 'nullable|numeric',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric'
        ]);

        // Update the supplier instance
        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->address = $request->input('address');
        $supplier->city = $request->input('city');
        $supplier->po_box = $request->input('po_box');
        $supplier->tax_number = $request->input('tax_number');
        $supplier->initial_balance = $request->input('initial_balance');
        $supplier->discount_type = $request->input('discount_type');
        $supplier->discount_value = $request->input('discount_value');

        $supplier->updated_by = auth()->id();

        try {
            $supplier->save();
            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
        } catch (QueryException $e) {
            Log::error('Error updating supplier: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }
}