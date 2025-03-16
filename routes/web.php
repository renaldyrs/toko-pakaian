<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('suppliers', SupplierController::class)->middleware('auth');

Route::resource('products', ProductController::class)->middleware('auth');

Route::resource('categories', CategoryController::class)->middleware('auth');


Route::prefix('cashier')->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/', [CashierController::class, 'store'])->name('cashier.store');
    Route::get('/invoice/{id}', [CashierController::class, 'invoice'])->name('cashier.invoice');
    Route::get('/invoice/{id}/print', [CashierController::class, 'printInvoice'])->name('cashier.invoice.print');
    Route::get('/orders', [CashierController::class, 'orders'])->name('cashier.orders'); // Route untuk daftar pesanan
});
Route::post('/cashier/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.addToCart');
Route::get('/barcode/{code}', [ProductController::class, 'generateBarcode'])->name('barcode.generate');
Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
});