<?php 

namespace App\Http\Controllers\CurrencyController ;

use App\Http\Controllers\Controller; 
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    // Display a listing of the currencies
    public function index()
    {
        $currencies = Currency::all();
        return view('currencies.index', compact('currencies'));
    }

    // Show the form for creating a new currency
    public function create()
    {
        return view('currencies.create');
    }

    // Store a newly created currency in storage
    public function store(Request $request)
{
    $request->validate([
        'currency_name' => 'required|string|max:255',
        'currency_code' => 'required|string|max:10|unique:currencies,code',
        'currency_symbol' => 'nullable|string|max:10',
    ]);

    Currency::create([
        'name' => $request->currency_name,
        'code' => $request->currency_code,
        'symbol' => $request->currency_symbol,
    ]);

    return redirect()->route('currencies.index')
                     ->with('success', 'Currency created successfully!');
}


    // Display the specified currency
    public function show(Currency $currency)
    {
        return view('currencies.show', compact('currency'));
    }

    // Show the form for editing the specified currency
    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

     // Handle the update request
     public function update(Request $request, $id)
     {
         // Validate the request
         $request->validate([
             'currency_name' => 'required|string|max:255',
             'currency_code' => 'required|string|max:10|unique:currencies,code,' . $id,
             'currency_symbol' => 'nullable|string|max:10',
         ]);
 
         // Find the currency by its ID
         $currency = Currency::findOrFail($id);
 
         // Update the currency data
         $currency->update([
             'name' => $request->currency_name,
             'code' => $request->currency_code,
             'symbol' => $request->currency_symbol,
         ]);
 
         // Redirect back to the currency list with success message
         return redirect()->route('currencies.index')
                          ->with('success', 'Currency updated successfully!');
     }
 
    // Remove the specified currency from storage
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currencies.index')
                         ->with('success', 'Currency  deleted successfully!');
    }

    // Restore a soft-deleted currency
    public function restore($id)
    {
        $currency = Currency::withTrashed()->findOrFail($id);
        $currency->restore();

        return redirect()->route('currencies.index')
                         ->with('success', 'Currency restored successfully!');
    }

    // Permanently delete a soft-deleted currency
    public function forceDelete($id)
    {
        $currency = Currency::withTrashed()->findOrFail($id);
        $currency->forceDelete();

        return redirect()->route('currencies.index')
                         ->with('success', 'Currency permanently deleted successfully!');
    }
}