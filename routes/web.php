<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController\InvoiceController;
use App\Http\Controllers\ProductController\ProductController;
use App\Http\Controllers\AuthController\LoginController;
use App\Http\Controllers\CustomerController\CustomerController;
use App\Http\Controllers\SupplierController\SupplierController;
use App\Http\Controllers\UserController\UserController;
use App\Http\Controllers\PaymentController\PaymentController;




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

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');


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




//});
