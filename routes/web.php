<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController\OrderController;
use App\Http\Controllers\ProductController\ProductController;
use App\Http\Controllers\AuthController\LoginController;
use App\Http\Controllers\CustomerController\CustomerController;
use App\Http\Controllers\SupplierController\SupplierController;
use App\Http\Controllers\UserController\UserController;
use App\Http\Controllers\PaymentController\PaymentController;
use App\Http\Controllers\RoleController\RoleController;
use App\Http\Controllers\WarehouseController\WarehouseController;
use App\Http\Controllers\TransferController\TransferController;





Route::get('welcome', function () {
    return view('welcome');
})->name('welcome');

// Route for auth
Route::get('/', [LoginController::class,'index'])->name('login');
Route::get('/login', [LoginController::class, 'index']);
Route::get('forgot', [LoginController::class,'forgot'])->name('forgot');
Route::get('/reset-password-mail-token/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm']);
Route::get('/candidate-reset-password-mail-token/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordFormCandidate']);
Route::post('login', [LoginController::class,'login'])->name('login');



// Route for listing all invoices

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::get('/orders/create-pos', [OrderController::class, 'createPOS'])->name('orders.create-pos');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::post('/customer/store', [OrderController::class, 'customerStore'])->name('customer.store');
Route::get('get-invoice/{orderId}', [OrderController::class, 'getInvoice']);

//Route::middleware(['auth'])->group(function () {

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');


   
    // Customer routes 
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Supplier routes
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

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
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/payments/get-payable-options/{head}', [PaymentController::class, 'getPayableOptions'])->name('payments.getPayableOptions');


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
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index'); // List all warehouses
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create'); // Show form to create a warehouse
    Route::post('/warehouses/store', [WarehouseController::class, 'store'])->name('warehouses.store'); // Store a new warehouse
    Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show'); // Show a specific warehouse
    Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit'); // Show form to edit a warehouse
    Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update'); // Update a specific warehouse
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy'); // Soft delete a warehouse


    // Transfer routes
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index'); // List all transfers
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create'); // Show form to create a transfer
    Route::post('/transfers/store', [TransferController::class, 'store'])->name('transfers.store'); // Store a new transfer
    Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show'); // Show a specific transfer
    Route::get('/transfers/{transfer}/edit', [TransferController::class, 'edit'])->name('transfers.edit'); // Show form to edit a transfer
    Route::put('/transfers/{transfer}', [TransferController::class, 'update'])->name('transfers.update'); // Update a specific transfer
    Route::delete('/transfers/{transfer}', [TransferController::class, 'destroy'])->name('transfers.destroy'); // Delete a transfer


//});
