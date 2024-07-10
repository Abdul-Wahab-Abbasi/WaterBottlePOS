<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\inventoryController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\billingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/POS/createOrder', [POSController::class, 'createOrderUser'])->middleware(['auth', 'verified'])->name('POS.createOrder');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

Route::get('/POS/index', [POSController::class, 'index'])->name('POS.index');
Route::get('/POS/createOrder', [POSController::class, 'createOrderUser'])->name('POS.createOrder');
Route::post('/POS', [POSController::class, 'addOrder'])->name('POS.addOrder');
Route::delete('POS/index/{order}', [POSController::class, 'destroy'])->name('orders.destroy');
Route::put('/POS/update/{order}', [POSController::class, 'updateOrder'])->name('orders.update');
Route::post('/POS/quik-update', [POSController::class, 'quikUpdate'])->name('POS.quik-update');


Route::get('/invoice/invoiceTemp', [invoiceController::class, 'invoiceTemp'])->name('invoice.invoiceTemp');
Route::post('/invoice/invoiceTemp', [invoiceController::class, 'invoiceTempStore'])->name('invoice.invoiceTemp');
Route::put('invoiceTemp/{id}', [invoiceController::class, 'update'])->name('invoiceTemp.update');
Route::get('/invoice/createInvoice/{id}', [InvoiceController::class, 'showInvoicePreview'])->name('invoice.createInvoice.show');
Route::post('invoice/store-invoice-record', [InvoiceController::class, 'storeInvoiceRecord'])->name('invoice.store-invoice-record');


Route::get('/billing', [billingController::class, 'index'])->name('Billing.index');
Route::get('/billing.createBill', [billingController::class, 'show'])->name('Billing.createBill');
Route::get('/billing.manageBills', [billingController::class, 'manage'])->name('Billing.manageBills');
Route::post('/billing.show', [billingController::class, 'show'])->name('Billing.show');
Route::post('/billing.addBill', [billingController::class, 'addBill'])->name('Billing.addBill');
Route::post('/billing.update', [billingController::class, 'update'])->name('Billing.update');
Route::delete('billing/{bill}', [billingController::class, 'destroy'])->name('Billing.destroy');
Route::post('billing/update-status', [billingController::class, 'updateStatus'])->name('billing.updateStatus');

});


require __DIR__.'/auth.php';
