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
//Route::get('/reset-password-mail-token/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm']);
//Route::get('/candidate-reset-password-mail-token/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordFormCandidate']);
Route::post('login', [LoginController::class,'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->group(function () { 

Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
Route::get('/profit-loss', [DashboardController::class, 'profitLossView'])->name('profit.loss');


        Route::middleware(['permission:view_sale_orders'])->group(function () {
            Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
            
            
        });

        Route::middleware(['permission:create_sale_orders'])->group(function () {
            Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
            Route::get('/orders/create-pos', [OrderController::class, 'createPOS'])->name('orders.create-pos');
            Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
            Route::post('/orders-pos', [OrderController::class, 'storePos'])->name('orders.store-pos');
            Route::post('/customer/store', [OrderController::class, 'customerStore'])->name('customer.store');
        });

        Route::middleware(['permission:edit_sales_orders'])->group(function () {
            Route::get('/order/{order}/edit', [OrderController::class, 'edit'])->name('order.edit');
            Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        });

        Route::middleware(['permission:delete_sale_orders'])->group(function () {
            Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        });

        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('get-invoice/{orderId}', [OrderController::class, 'getInvoice']);
    
        // Route for listing all purchases 

        Route::middleware(['permission:view_purchases'])->group(function () {
            Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
            Route::get('get-purchase-invoice/{purchaseId}', [PurchaseController::class, 'getPurchase']);
        });

        Route::middleware(['permission:create_purchases'])->group(function () {
            Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
            Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
            Route::post('/supplier/store', [PurchaseController::class, 'customerStore'])->name('supplier.store');
        });

        Route::middleware(['permission:edit_purchases'])->group(function () {
            Route::put('/purchases/{order}', [PurchaseController::class, 'update'])->name('purchase.update');
        });

        Route::middleware(['permission:delete_purchases'])->group(function () {
            Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchase.destroy');
        });






    Route::middleware(['permission:view_products'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        
    });
    
    Route::middleware(['permission:create_products'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    });
    
    Route::middleware(['permission:edit_products'])->group(function () {
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    });
    
    Route::middleware(['permission:delete_products'])->group(function () {
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

   
    Route::get('/get-product-details/{code}', [ProductController::class, 'getProductDetails']);
    Route::get('/load-products', [ProductController::class, 'loadProducts'])->name('products.load');
    
    Route::middleware(['permission:view_stock_report'])->group(function () {
        Route::get('/stock-report-view', [ProductController::class, 'stockReport'])->name('stock-report-view');
    });    
    Route::middleware(['permission:view_product_sold_report'])->group(function () {
        Route::get('/reports/product-sold-report', [ProductController::class, 'productSoldReport'])->name('reports.product-sold-report');
        Route::get('product-sold-report-pdf', [ProductController::class, 'productSoldReportPDF'])->name('product-sold-report-pdf');
    });    
    Route::middleware(['permission:view_product_purchased_report'])->group(function () {
        Route::get('/reports/product-purchased-report', [ProductController::class, 'productPurchasedReport'])->name('reports.product-purchased-report');
        Route::get('product-purchased-report-pdf', [ProductController::class, 'productPurchasedReportPDF'])->name('product-purchased-report-pdf');
    }); 
    Route::middleware(['permission:view_product_quantity_alerts'])->group(function () {   
        Route::get('/product-quantity-alerts-json', [ProductController::class, 'quantityAlerts'])->name('product.quantity.alerts.json');
        Route::get('/product-quantity-alerts', [ProductController::class, 'quantityAlertsIndex'])->name('product.quantity.alerts.index');
    }); 
    Route::get('/download-sample-product-csv', [ProductController::class, 'downloadSampleProductCsv'])->name('download-sample-product-csv');





   
    Route::middleware(['permission:create_customers'])->group(function () { 
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    }); 

    Route::middleware(['permission:view_customers'])->group(function () { 
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });     
    
    Route::middleware(['permission:edit_customers'])->group(function () { 
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    });    
    
    Route::middleware(['permission:delete_customers'])->group(function () { 
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });     

    Route::middleware(['permission:view_customer_ledger'])->group(function () {

        Route::get('/customer-ledger', [CustomerController::class, 'ledger'])->name('customer-ledger.index');
        Route::get('/customer-ledger-pdf', [CustomerController::class, 'ledgerPDF'])->name('customer-ledger-pdf');
    });

    // Supplier routes
    Route::middleware(['permission:create_vendors'])->group(function () {
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    });
    
    Route::middleware(['permission:view_vendors'])->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    });
    
    Route::middleware(['permission:edit_vendors'])->group(function () {
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    });
    
    Route::middleware(['permission:delete_vendors'])->group(function () {
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });
    
    
    Route::middleware(['permission:view_supplier_ledger'])->group(function () {

        Route::get('/supplier-ledger', [SupplierController::class, 'ledger'])->name('supplier-ledger.index');
        Route::get('/supplier-ledger-pdf', [SupplierController::class, 'ledgerPDF'])->name('supplier-ledger-pdf');
    });    
    
    Route::middleware(['permission:view_users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index'); 
        
    });
    
    Route::middleware(['permission:create_users'])->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); 
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store'); 
    });
    
    Route::middleware(['permission:edit_users'])->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); 
    });
    
    Route::middleware(['permission:delete_users'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); 
    });
    

    

    // Payment routes
    Route::middleware(['permission:view_payments'])->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });
    
    Route::middleware(['permission:create_payments'])->group(function () {
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::post('/payments/store-using-sale', [PaymentController::class, 'storeUsingSale'])->name('payments.storeUsingSale');
    });
    
    Route::middleware(['permission:edit_payments'])->group(function () {
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    });
    
    Route::middleware(['permission:delete_payments'])->group(function () {
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });
    
    Route::get('/payments/get-payable-options/{head}', [PaymentController::class, 'getPayableOptions'])->name('payments.getPayableOptions');
    Route::get('/get-payment-details/{voucherId}', [PaymentController::class, 'getPaymentDetails']);
    Route::get('/payments/view/{orderId}', [PaymentController::class, 'viewPayments']);
    Route::get('/download-sample-excel', [PaymentController::class, 'downloadSampleExcel'])->name('download-sample-excel');


    // Role routes
    Route::middleware(['permission:view_roles'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('roles/{role}/permissions', [RoleController::class, 'showPermissions'])->name('roles.permissions');
    });
    
    Route::middleware(['permission:create_roles'])->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    });
    
    Route::middleware(['permission:edit_roles'])->group(function () {
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
    });
    
    Route::middleware(['permission:delete_roles'])->group(function () {
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
    

    
    // Warehouse routes
    
        
        Route::middleware(['permission:view_warehouse'])->group(function () {
            Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
            Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
        });
        
        Route::middleware(['permission:create_warehouse'])->group(function () {
            Route::get('/warehouse/create', [WarehouseController::class, 'create'])->name('warehouse.create');
            Route::post('/warehouses/store', [WarehouseController::class, 'store'])->name('warehouses.store');
        });
        
        Route::middleware(['permission:edit_warehouse'])->group(function () {
            Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
            Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
        });
        
        Route::middleware(['permission:delete_warehouse'])->group(function () {
            Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
        });
      
        Route::get('/warehouse/warehouse-report', [WarehouseController::class, 'warehouseReport'])->name('reports.warehouse-report');
        Route::get('/warehouse/warehouse-report-pdf', [WarehouseController::class, 'warehouseReportPDF'])->name('reports.warehouse-report-pdf');
    
    

    // Transfer routes
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index'); 
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create'); 
    Route::post('/transfers/store', [TransferController::class, 'store'])->name('transfers.store'); 
    Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show'); 
    Route::get('/transfers/{transfer}/edit', [TransferController::class, 'edit'])->name('transfers.edit'); 
    Route::put('/transfers/{transfer}', [TransferController::class, 'update'])->name('transfers.update'); 
    Route::delete('/transfers/{transfer}', [TransferController::class, 'destroy'])->name('transfers.destroy');




    


    
    Route::get('/expenses-head', [ExpenseHeadController::class, 'index'])->name('expenses-heads.index-head'); 
    Route::get('/expenses-head/create', [ExpenseHeadController::class, 'create'])->name('expenses-heads.create-head'); 
    Route::post('/expenses-head', [ExpenseHeadController::class, 'store'])->name('expenses-heads.store-head'); 
    Route::get('/expenses-head/{expense}', [ExpenseHeadController::class, 'show'])->name('expenses-heads.show'); 
    Route::get('/expenses-head/{expense}/edit', [ExpenseHeadController::class, 'edit'])->name('expenses-heads.edit-head'); 
    Route::put('/expenses-head/{expense}', [ExpenseHeadController::class, 'update'])->name('expenses-heads.update-head'); 
    Route::delete('/expenses-head/{expense}', [ExpenseHeadController::class, 'destroy'])->name('expenses-heads.destroy-head'); 


    Route::middleware(['permission:view_expenses'])->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index'); 
        Route::get('/expensesPDF', [ExpenseController::class, 'indexPDF'])->name('expenses.indexPDF'); 
    });
    
    Route::middleware(['permission:create_expenses'])->group(function () {
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create'); 
        Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store'); 
    });
    
    Route::middleware(['permission:edit_expenses'])->group(function () {
        Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit'); 
        Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update'); 
    });
    
    Route::middleware(['permission:delete_expenses'])->group(function () {
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy'); 
    });
    



    Route::get('/income-head', [IncomeHeadController::class, 'index'])->name('income-heads.index-head'); 
    Route::get('/income-head/create', [IncomeHeadController::class, 'create'])->name('income-heads.create-head'); 
    Route::post('/income-head', [IncomeHeadController::class, 'store'])->name('income-heads.store-head'); 
    Route::get('/income-head/{income}', [IncomeHeadController::class, 'show'])->name('income-heads.show'); 
    Route::get('/income-head/{income}/edit', [IncomeHeadController::class, 'edit'])->name('income-heads.edit-head'); 
    Route::put('/income-head/{income}', [IncomeHeadController::class, 'update'])->name('income-heads.update-head'); 
    Route::delete('/income-head/{income}', [IncomeHeadController::class, 'destroy'])->name('income-heads.destroy-head'); // Delete an income head


    Route::middleware(['permission:view_incomes '])->group(function () {
        Route::get('/income', [IncomeController::class, 'index'])->name('income.index'); 
        Route::get('/incomePDF', [IncomeController::class, 'indexPDF'])->name('income.indexPDF'); 
    });
    
    Route::middleware(['permission:create_incomes'])->group(function () {
        Route::get('/income/create', [IncomeController::class, 'create'])->name('income.create'); 
        Route::post('/income/store', [IncomeController::class, 'store'])->name('income.store'); 
    });
    
    Route::middleware(['permission:edit_incomes'])->group(function () {
        Route::get('/income/{income}/edit', [IncomeController::class, 'edit'])->name('income.edit'); 
        Route::put('/income/{income}', [IncomeController::class, 'update'])->name('income.update'); 
    });
    
    Route::middleware(['permission:delete_incomes'])->group(function () {
        Route::delete('/income/{income}', [IncomeController::class, 'destroy'])->name('income.destroy'); 
    });
    

    Route::middleware(['permission:taxes'])->group(function () {
        Route::get('/tax', [TaxController::class, 'index'])->name('tax.index');
        Route::get('/tax/create', [TaxController::class, 'create'])->name('tax.create');
        Route::post('/tax/store', [TaxController::class, 'store'])->name('tax.store');
        Route::get('/tax/{tax}/edit', [TaxController::class, 'edit'])->name('tax.edit');
        Route::put('/tax/{tax}', [TaxController::class, 'update'])->name('tax.update');
        Route::delete('/tax/{tax}', [TaxController::class, 'destroy'])->name('tax.destroy');
    });    

    Route::middleware(['permission:general_settings'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
        Route::post('/settings/store', [SettingController::class, 'store'])->name('settings.store');
        Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');
    }); 

    Route::middleware(['permission:currencies'])->group(function () {
        Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index'); 
        Route::get('/currencies/create', [CurrencyController::class, 'create'])->name('currencies.create'); 
        Route::post('/currencies', [CurrencyController::class, 'store'])->name('currencies.store'); 
        Route::get('/currencies/{currency}', [CurrencyController::class, 'show'])->name('currencies.show'); 
        Route::get('/currencies/{currency}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit'); 
        Route::put('/currencies/{currency}', [CurrencyController::class, 'update'])->name('currencies.update'); 
        Route::delete('/currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy'); 
    });    

    Route::resource('brand', BrandController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('accounts', AccountController::class);

    
    Route::get('/account-balance-sheet', [AccountController::class, 'accountBalanceSheet'])->name('balanceSheet.index');
    Route::get('/account-balance-sheetJSON', [AccountController::class, 'accountBalanceSheetJSON'])->name('balanceSheet.indexJSON');
    Route::get('/account-statement', [AccountController::class, 'accountStatement'])->name('accountStatement.index');
    Route::get('/account-statementJSON/{accountId}', [AccountController::class, 'accountStatementJSON'])->name('accountStatement.indexJSON');


    
});

