<?php

namespace App\Http\Controllers\AccountController;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Payment;

class AccountController extends Controller
{
    
    public function index()
    {
        $accounts = Account::all(); 
        $totalAccounts = $accounts->count(); 

        return view('accounts.index', compact('accounts', 'totalAccounts'));
    }

    // Show the form for creating a new account
    public function create()
    {
        return view('accounts.create');
    }

    // Store a newly created account in storage
    public function store(Request $request)
    {
        $request->validate([
            'account_no' => 'required|string|max:255|unique:accounts,account_no',
            'name' => 'required|string|max:255',
            'initial_balance' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        Account::create($request->only('account_no', 'name', 'initial_balance', 'note'));

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    // Show the form for editing the specified account
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        return view('accounts.edit', compact('account'));
    }

    // Update the specified account in storage
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'account_no' => 'required|string|max:255|unique:accounts,account_no,' . $id,
                'name' => 'required|string|max:255',
                'initial_balance' => 'nullable|numeric|min:0',
                'note' => 'nullable|string|max:1000',
            ]);

            $account = Account::findOrFail($id);
            $account->update($request->only('account_no', 'name', 'initial_balance', 'note'));

            return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('accounts.index')->with('error', 'Failed to update account. Please try again.');
        }
    }

    // Remove the specified account from storage
    public function destroy($id)
    {
        try {
            $account = Account::findOrFail($id);
            $account->delete();
            return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('accounts.index')->with('error', 'Failed to delete account. Please try again.');
        }
    }

    public function show($id)
    {
        
        $account = Account::findOrFail($id);

        
        return view('accounts.index', compact('account'));
    }


    public function accountBalanceSheet()
    {
    $accounts = Account::with('payments')->get();

    $totalCredits = 0;
    $totalDebits = 0;
    $totalBalance = 0;

    foreach ($accounts as $account) {
        $credits = $account->payments->where('payment_type', 'credit')->sum('amount');
        $debits = $account->payments->where('payment_type', 'debit')->sum('amount');
        $balance = $account->initial_balance + $credits - $debits;

        $totalCredits += $credits;
        $totalDebits += $debits;
        $totalBalance += $balance;

        $account->calculated_balance = $balance; 
    }

    return view('accounts.balance-sheet', compact('accounts', 'totalCredits', 'totalDebits', 'totalBalance'));
    }

    public function accountBalanceSheetJSON()
        {
            $accounts = Account::with('payments')->get();

            $totalCredits = 0;
            $totalDebits = 0;
            $totalBalance = 0;

            $accountData = [];

            foreach ($accounts as $account) {
                $credits = $account->payments->where('payment_type', 'credit')->sum('amount');
                $debits = $account->payments->where('payment_type', 'debit')->sum('amount');
                $balance = $account->initial_balance + $credits - $debits;

                $totalCredits += $credits;
                $totalDebits += $debits;
                $totalBalance += $balance;

                // Adding account data with calculated balance
                $accountData[] = [
                    'account_no' => $account->account_no,
                    'name' => $account->name,
                    'initial_balance' => $account->initial_balance,
                    'total_credits' => $credits,
                    'total_debits' => $debits,
                    'calculated_balance' => $balance,
                ];
            }

            return response()->json([
                'accounts' => $accountData,
                'totalCredits' => $totalCredits,
                'totalDebits' => $totalDebits,
                'totalBalance' => $totalBalance,
            ]);
        }



        public function accountStatement(Request $request)
{
    $accountId = $request->input('account_id');
    $accounts = Account::all();
    $transactions = [];
    $selectedAccount = null;
    $totalCredit = 0;
    $totalDebit = 0;
    $initialBalance = 0;
    $currencySymbol = \DB::table('settings')->where('name', 'currency-symbol')->value('value'); // Fetch currency symbol from settings

    if ($accountId) {
        $selectedAccount = Account::find($accountId);

        if ($selectedAccount) {
            // Get the initial balance from the selected account
            $initialBalance = $selectedAccount->initial_balance;

            // Fetch the transactions for the account
            $transactions = Payment::where('account_id', $accountId)
                ->orderBy('payment_date', 'desc')
                ->get();

            // Calculate total credit and debit
            $totalCredit = Payment::where('account_id', $accountId)
                ->where('payment_type', 'credit') 
                ->sum('amount'); 

            $totalDebit = Payment::where('account_id', $accountId)
                ->where('payment_type', 'debit')
                ->sum('amount');
        }
    }

    return view('accounts.account-statement', compact('accounts', 'selectedAccount', 'transactions', 'totalCredit', 'totalDebit', 'initialBalance', 'currencySymbol'));
}

        
        

        
public function accountStatementJSON($accountId)
{
    $selectedAccount = Account::find($accountId);
    if (!$selectedAccount) {
        return response()->json(['error' => 'Account not found'], 404);
    }

    $accounts = Account::all();
    $totalCredit = 0;
    $totalDebit = 0;
    $initialBalance = $selectedAccount->initial_balance;

    $transactions = Payment::where('account_id', $accountId)
        ->orderBy('payment_date', 'desc')
        ->get();

    $totalCredit = Payment::where('account_id', $accountId)
        ->where('payment_type', 'credit')
        ->sum('amount');

    $totalDebit = Payment::where('account_id', $accountId)
        ->where('payment_type', 'debit')
        ->sum('amount');

    $totalBalance = $initialBalance + $totalCredit - $totalDebit;

    $response = [
        'accounts' => $accounts,
        'selectedAccount' => [
            'account_id' => $selectedAccount->id,
            'account_no' => $selectedAccount->account_no,
            'name' => $selectedAccount->name,
            'initial_balance' => $selectedAccount->initial_balance,
            'total_credits' => $totalCredit,
            'total_debits' => $totalDebit,
            'calculated_balance' => $totalBalance,
        ],
        'transactions' => $transactions,
        'totalCredits' => $totalCredit,
        'totalDebits' => $totalDebit,
        'totalBalance' => $totalBalance,
    ];

    return response()->json($response);
}


    

}