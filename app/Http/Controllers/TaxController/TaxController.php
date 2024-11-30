<?php


namespace App\Http\Controllers\TaxController;

use App\Http\Controllers\Controller;
use App\Models\PaymentHead;
use App\Models\Tax;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaxController extends Controller
{
        
    public function index()
    {
        $taxes = Tax::all(); // Fetch all tax records
        $totalTaxes = $taxes->count(); // Count total tax records
        
    
        return view('taxes.index', compact('taxes', 'totalTaxes'));
    }
    


    public function create()
    {
        
        return view('taxes.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:taxes,name',
        'rate' => 'required|numeric|min:0',
    ]);

    Tax::create($request->only('name', 'rate'));

    return redirect()->route('tax.index')->with('success', 'Tax created successfully.');
}

    
    public function edit($id)
    {
        $tax = Tax::findOrFail($id);
        return view('taxes.edit', compact('tax'));
    }

    
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'rate' => 'required|numeric|min:0',
            ]);
    
            $tax = Tax::findOrFail($id);
            $tax->update([
                'name' => $request->name,
                'rate' => $request->rate,
            ]);
    
            return redirect()->route('tax.index')->with('success', 'Tax record updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tax.index')->with('error', 'Failed to update tax record. Please try again.');
        }
    }
    

    public function destroy($id)
{
    try {
        $tax = Tax::findOrFail($id);
        $tax->delete();
        return redirect()->route('tax.index')->with('success', 'Tax record deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('tax.index')->with('error', 'Failed to delete tax record. Please try again.');
    }
}

}
