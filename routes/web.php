<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController\DashboardController;;
use App\Http\Controllers\OrderController\OrderController;
use App\Http\Controllers\PurchaseController\PurchaseController;
use App\Http\Controllers\ProductController\ProductController;
use App\Http\Controllers\AuthController\LoginController;
use App\Http\Controllers\CustomerController\CustomerController;
use App\Http\Controllers\SupplierController\SupplierController;
use App\Http\Controllers\UserController\UserController;
use App\Http\Controllers\PaymentController\PaymentController;
use App\Http\Controllers\RoleController\RoleController;
use App\Http\Controllers\WarehouseController\WarehouseController;
use App\Http\Controllers\TransferController\TransferController;
use App\Http\Controllers\CurrencyController\CurrencyController;
use App\Http\Controllers\ExpenseHeadController\ExpenseHeadController;
use App\Http\Controllers\IncomeHeadController\IncomeHeadController;
use App\Http\Controllers\ExpenseController\ExpenseController;
use App\Http\Controllers\IncomeController\IncomeController;
use App\Http\Controllers\TaxController\TaxController;
use App\Http\Controllers\SettingController\SettingController;
use App\Http\Controllers\BrandController\BrandController;
use App\Http\Controllers\CategoryController\CategoryController;
use App\Http\Controllers\AccountController\AccountController;


// Route::get('welcome', function () {
//     return view('welcome');
// })->name('welcome');



// Route for auth
Route::get('/', [LoginController::class,'index'])->name('login');
Route::get('/login', [LoginController::class, 'index']);
Route::get('forgot', [LoginController::class,'forgot'])->name('forgot');
Route::get('/reset-password-mail-token/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm']);
Route::get('/candidate-reset-password-mail-token/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordFormCandidate']);
Route::post('login', [LoginController::class,'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->group(function () { 

Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
Route::get('/profit-loss', [DashboardController::class, 'profitLossView'])->name('profit.loss');



Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::get('/orders/create-pos', [OrderController::class, 'createPOS'])->name('orders.create-pos');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/orders-pos', [OrderController::class, 'storePos'])->name('orders.store-pos');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/order/{order}/edit', [OrderController::class, 'edit'])->name('order.edit');
Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::post('/customer/store', [OrderController::class, 'customerStore'])->name('customer.store');
Route::get('get-invoice/{orderId}', [OrderController::class, 'getInvoice']);


// Route for listing all purchases 

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::post('/supplier/store', [PurchaseController::class, 'customerStore'])->name('supplier.store');
    Route::get('get-purchase-invoice/{purchaseId}', [PurchaseController::class, 'getPurchase']);
    Route::put('/purchases/{order}', [PurchaseController::class, 'update'])->name('purchase.update');
    Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchase.destroy');




    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/get-product-details/{code}', [ProductController::class, 'getProductDetails']);
    Route::get('/load-products', [ProductController::class, 'loadProducts'])->name('products.load');
    Route::get('/stock-report-view', [ProductController::class, 'stockReport'])->name('stock-report-view');
    Route::get('/products/{product}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stockHistory');
    Route::get('/reports/product-sold-report', [ProductController::class, 'productSoldReport'])->name('reports.product-sold-report');
    Route::get('product-sold-report-pdf', [ProductController::class, 'productSoldReportPDF'])->name('product-sold-report-pdf');
    Route::get('/reports/product-purchased-report', [ProductController::class, 'productPurchasedReport'])->name('reports.product-purchased-report');
    Route::get('product-purchased-report-pdf', [ProductController::class, 'productPurchasedReportPDF'])->name('product-purchased-report-pdf');
    Route::get('/product-quantity-alerts-json', [ProductController::class, 'quantityAlerts'])->name('product.quantity.alerts.json');
    Route::get('/product-quantity-alerts', [ProductController::class, 'quantityAlertsIndex'])->name('product.quantity.alerts.index');





   
    // Customer routes 
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customer-ledger', [CustomerController::class, 'ledger'])->name('customer-ledger.index');
    Route::get('/customer-ledger-pdf', [CustomerController::class, 'ledgerPDF'])->name('customer-ledger-pdf');
    

    // Supplier routes
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/supplier-ledger', [SupplierController::class, 'ledger'])->name('supplier-ledger.index');
    Route::get('/supplier-ledger-pdf', [SupplierController::class, 'ledgerPDF'])->name('supplier-ledger-pdf');

    // User routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); 
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); 
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store'); 
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); 
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); 
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); 
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); 

    

    // Payment routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments/store-using-sale', [PaymentController::class, 'storeUsingSale'])->name('payments.storeUsingSale');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/payments/get-payable-options/{head}', [PaymentController::class, 'getPayableOptions'])->name('payments.getPayableOptions');
    Route::get('/get-payment-details/{voucherId}', [PaymentController::class, 'getPaymentDetails']);
    Route::get('/payments/view/{orderId}', [PaymentController::class, 'viewPayments']);
    Route::get('/download-sample-excel', [PaymentController::class, 'downloadSampleExcel'])->name('download-sample-excel');


    // Role routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');  // Display a listing of roles
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');  // Show form to create a new role
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');  // Store a newly created role
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');  // Display a specific role
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');  // Show form to edit a role
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');  // Update a specific role
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');  // Delete a specific role
    Route::get('roles/{role}/permissions', [RoleController::class, 'showPermissions'])->name('roles.permissions');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');

    
    // Warehouse routes
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index'); 
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create'); 
    Route::post('/warehouses/store', [WarehouseController::class, 'store'])->name('warehouses.store'); 
    Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show'); 
    Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit'); 
    Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update'); 
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy'); 
    Route::get('/warehouse/warehouse-report', [WarehouseController::class, 'warehouseReport'])->name('reports.warehouse-report');
    Route::get('/warehouse/warehouse-report-pdf', [WarehouseController::class, 'warehouseReportPDF'])->name('reports.warehouse-report-pdf');

    // Transfer routes
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index'); // List all transfers
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create'); // Show form to create a transfer
    Route::post('/transfers/store', [TransferController::class, 'store'])->name('transfers.store'); // Store a new transfer
    Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show'); // Show a specific transfer
    Route::get('/transfers/{transfer}/edit', [TransferController::class, 'edit'])->name('transfers.edit'); // Show form to edit a transfer
    Route::put('/transfers/{transfer}', [TransferController::class, 'update'])->name('transfers.update'); // Update a specific transfer
    Route::delete('/transfers/{transfer}', [TransferController::class, 'destroy'])->name('transfers.destroy'); // Delete a transfer




    Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index'); // List all currencies
    Route::get('/currencies/create', [CurrencyController::class, 'create'])->name('currencies.create'); // Show form to create a currency
    Route::post('/currencies', [CurrencyController::class, 'store'])->name('currencies.store'); // Store a new currency
    Route::get('/currencies/{currency}', [CurrencyController::class, 'show'])->name('currencies.show'); // Show a specific currency
    Route::get('/currencies/{currency}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit'); // Show form to edit a currency
    Route::put('/currencies/{currency}', [CurrencyController::class, 'update'])->name('currencies.update'); // Update a specific currency
    Route::delete('/currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy'); // Delete a currency



    
    Route::get('/expenses-head', [ExpenseHeadController::class, 'index'])->name('expenses-heads.index-head'); // List all expenses
    Route::get('/expenses-head/create', [ExpenseHeadController::class, 'create'])->name('expenses-heads.create-head'); // Show form to create an expense
    Route::post('/expenses-head', [ExpenseHeadController::class, 'store'])->name('expenses-heads.store-head'); // Store a new expense
    Route::get('/expenses-head/{expense}', [ExpenseHeadController::class, 'show'])->name('expenses-heads.show'); // Show a specific expense
    Route::get('/expenses-head/{expense}/edit', [ExpenseHeadController::class, 'edit'])->name('expenses-heads.edit-head'); // Show form to edit an expense
    Route::put('/expenses-head/{expense}', [ExpenseHeadController::class, 'update'])->name('expenses-heads.update-head'); // Update a specific expense
    Route::delete('/expenses-head/{expense}', [ExpenseHeadController::class, 'destroy'])->name('expenses-heads.destroy-head'); // Delete an expense


    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index'); 
    Route::get('/expensesPDF', [ExpenseController::class, 'indexPDF'])->name('expenses.indexPDF'); 
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit'); // Show form to edit an expense
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update'); // Update a specific expense
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy'); // Delete an expense
    



    Route::get('/income-head', [IncomeHeadController::class, 'index'])->name('income-heads.index-head'); 
    Route::get('/income-head/create', [IncomeHeadController::class, 'create'])->name('income-heads.create-head'); 
    Route::post('/income-head', [IncomeHeadController::class, 'store'])->name('income-heads.store-head'); 
    Route::get('/income-head/{income}', [IncomeHeadController::class, 'show'])->name('income-heads.show'); 
    Route::get('/income-head/{income}/edit', [IncomeHeadController::class, 'edit'])->name('income-heads.edit-head'); 
    Route::put('/income-head/{income}', [IncomeHeadController::class, 'update'])->name('income-heads.update-head'); 
    Route::delete('/income-head/{income}', [IncomeHeadController::class, 'destroy'])->name('income-heads.destroy-head'); // Delete an income head


    Route::get('/income', [IncomeController::class, 'index'])->name('income.index'); 
    Route::get('/incomePDF', [IncomeController::class, 'indexPDF'])->name('income.indexPDF'); 
    Route::get('/income/create', [IncomeController::class, 'create'])->name('income.create'); 
    Route::post('/income/store', [IncomeController::class, 'store'])->name('income.store'); 
    Route::get('/income/{income}/edit', [IncomeController::class, 'edit'])->name('income.edit'); 
    Route::put('/income/{income}', [IncomeController::class, 'update'])->name('income.update'); 
    Route::delete('/income/{income}', [IncomeController::class, 'destroy'])->name('income.destroy');


    Route::get('/tax', [TaxController::class, 'index'])->name('tax.index');
    Route::get('/tax/create', [TaxController::class, 'create'])->name('tax.create');
    Route::post('/tax/store', [TaxController::class, 'store'])->name('tax.store');
    Route::get('/tax/{tax}/edit', [TaxController::class, 'edit'])->name('tax.edit');
    Route::put('/tax/{tax}', [TaxController::class, 'update'])->name('tax.update');
    Route::delete('/tax/{tax}', [TaxController::class, 'destroy'])->name('tax.destroy');



    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
    Route::post('/settings/store', [SettingController::class, 'store'])->name('settings.store');
    Route::get('/settings/{setting}/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');


    Route::resource('brand', BrandController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('accounts', AccountController::class);
    Route::get('/account-balance-sheet', [AccountController::class, 'accountBalanceSheet'])->name('balanceSheet.index');
    Route::get('/account-balance-sheetJSON', [AccountController::class, 'accountBalanceSheetJSON'])->name('balanceSheet.indexJSON');
    Route::get('/account-statement', [AccountController::class, 'accountStatement'])->name('accountStatement.index');
    Route::get('/account-statementJSON/{accountId}', [AccountController::class, 'accountStatementJSON'])->name('accountStatement.indexJSON');


    
});

