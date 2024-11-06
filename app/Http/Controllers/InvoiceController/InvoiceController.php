<?php 
namespace App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Controller; 
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function create()
    {
        // Return the view to create a new invoice
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        // Create the invoice
        Invoice::create([
            'user_id' => Auth::id(), // Assuming the user is logged in
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        // Redirect or respond with a success message
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
    }
}
