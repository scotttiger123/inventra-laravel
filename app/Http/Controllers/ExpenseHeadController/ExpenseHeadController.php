<?php

namespace App\Http\Controllers\ExpenseHeadController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHead;

class ExpenseHeadController extends Controller
{
        public function index()
            {
                $expenseHeads = PaymentHead::all(); // Fetch all expenses
                return view('expenses-heads.index-head', compact('expenseHeads'));
            }



        public function create()
        {
            return view('expenses-heads.create-head');
        }


        
        

        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
        
        
            $userId = auth()->id(); 
        
            // Create the ExpenseHead record and set the 'created_by' field
            PaymentHead::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $userId,  
                'updated_by' => $userId,  
                'deleted_by' => $userId,  
            ]);
        
            return redirect()->route('expenses-heads.index-head')->with('success', 'Expense Head created successfully.');
        }
        
        public function edit($id)
        {
            $expenseHead = PaymentHead::findOrFail($id);
            return view('expenses-heads.edit-head', compact('expenseHead'));
        }
        
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
        
            $expenseHead = PaymentHead::findOrFail($id);
        
            $userId = auth()->id();
        
            // Update the expense head record
            $expenseHead->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_by' => $userId,  // Set the updated_by field
            ]);
        
            return redirect()->route('expenses-heads.index-head')->with('success', 'Expense Head updated successfully.');
        }

            
    // Soft delete an Expense Head
    public function destroy($id)
    {
        $expenseHead = PaymentHead::findOrFail($id);

        // Optionally set 'deleted_by' to the current user
        $expenseHead->deleted_by = auth()->id();
        $expenseHead->delete(); // This will soft delete the record

        return redirect()->route('expenses-heads.index-head')->with('success', 'Expense Head deleted successfully.');
    }

}
