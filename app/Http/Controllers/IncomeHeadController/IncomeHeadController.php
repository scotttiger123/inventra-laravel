<?php

namespace App\Http\Controllers\IncomeHeadController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHead;

class IncomeHeadController extends Controller
{
    public function index()
    {
        $incomeHeads = PaymentHead::where('type', 'income')->get(); // Fetch all income heads
        return view('income-heads.index-head', compact('incomeHeads'));
    }


    // Show the form for creating a new income head
    public function create()
    {
        return view('income-heads.create-head');
    }

    // Store a newly created income head in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $userId = auth()->id();

        // Create the IncomeHead record and set the 'created_by' field
        PaymentHead::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => 'income',
            'created_by' => $userId,
            'updated_by' => $userId,
            'deleted_by' => $userId,
        ]);

        return redirect()->route('income-heads.index-head')->with('success', 'Income Head created successfully.');
    }

    // Show the form for editing the specified income head
    public function edit($id)
    {
        $incomeHead = PaymentHead::findOrFail($id);
        return view('income-heads.edit-head', compact('incomeHead'));
    }

    // Update the specified income head in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $incomeHead = PaymentHead::findOrFail($id);

        $userId = auth()->id();

        // Update the income head record
        $incomeHead->update([
            'name' => $request->name,
            'description' => $request->description,
            'updated_by' => $userId,
        ]);

        return redirect()->route('income-heads.index-head')->with('success', 'Income Head updated successfully.');
    }

    // Soft delete an income head
    public function destroy($id)
    {
        $incomeHead = PaymentHead::findOrFail($id);

        // Optionally set 'deleted_by' to the current user
        $incomeHead->deleted_by = auth()->id();
        $incomeHead->delete(); // This will soft delete the record

        return redirect()->route('income-heads.index-head')->with('success', 'Income Head deleted successfully.');
    }
}
