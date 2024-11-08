<?php 

namespace App\Http\Controllers\CustomerController;

use App\Http\Controllers\Controller; 
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')
                    ->where(function ($query) use ($request) {
                        $query->where('created_by', auth()->id())
                              ->orWhere('parent_user_id', auth()->user()->parent_id);
                    })
                ],
                'phone' => [
                    'nullable',
                    'string',
                    'max:15',
                    Rule::unique('customers', 'phone')
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

        // Create a new customer instance
        $customer = new Customer();
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->city = $request->input('city');
        $customer->po_box = $request->input('po_box');
        $customer->tax_number = $request->input('tax_number');
        $customer->initial_balance = $request->input('initial_balance');
        $customer->discount_type = $request->input('discount_type');
        $customer->discount_value = $request->input('discount_value');
        
        $customer->created_by = auth()->id(); 
        $customer->parent_user_id = auth()->user()->parent_id;

        try {
            $customer->save();
            return redirect()->route('customers.create')->with('success', 'Customer added successfully!');
        } catch (QueryException $e) {
            Log::error('Error saving customer: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }

    public function index()
    {
        $customers = Customer::with('creator')->get(); 
        return view('customers.index', compact('customers'));
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->delete();
            return response()->json(['success' => true, 'message' => 'Customer deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Customer not found.']);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id . ',id,parent_user_id,' . auth()->user()->parent_id . ',created_by,' . auth()->id(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'po_box' => 'nullable|string|max:10',
            'tax_number' => 'nullable|string|max:20',
            'initial_balance' => 'nullable|numeric',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount_value' => 'nullable|numeric'
        ]);

        // Update the customer instance
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->city = $request->input('city');
        $customer->po_box = $request->input('po_box');
        $customer->tax_number = $request->input('tax_number');
        $customer->initial_balance = $request->input('initial_balance');
        $customer->discount_type = $request->input('discount_type');
        $customer->discount_value = $request->input('discount_value');

        $customer->updated_by = auth()->id();

        try {
            $customer->save();
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
        } catch (QueryException $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }
}
