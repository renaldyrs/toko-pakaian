<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreProfileController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\ReturnController;

Route::get('/', function () {
    return view('auth.login');
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
    Route::get('/receipt/{id}', [CashierController::class, 'showReceipt'])->name('cashier.receipt');
    Route::get('/print/{id}', [CashierController::class, 'printReceipt'])->name('cashier.print');
});

Route::post('/cashier/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.addToCart');
Route::get('/barcode/{code}', [ProductController::class, 'generateBarcode'])->name('barcode.generate');
Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
});
Route::get('/reports/financial', [ReportController::class, 'financialReport'])->name('reports.financial');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});


Route::resource('users', UserController::class);
Route::get('/products/{id}/download-barcode', [ProductController::class, 'downloadBarcode'])->name('products.downloadBarcode');

Route::get('/products/find-by-code{code}', [ProductController::class, 'findByCode'])->name('products.find-by-code');

Route::get('/products/{barcode}', [ProductController::class, 'findByBarcode']);



Route::prefix('store-profile')->group(function () {
    Route::get('/edit', [StoreProfileController::class, 'edit'])->name('store-profile.edit');
    Route::put('/update', [StoreProfileController::class, 'update'])->name('store-profile.update');
});

// Route untuk halaman kasir
Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');

// Route untuk menyimpan transaksi
Route::post('/cashier', [CashierController::class, 'store'])->name('cashier.store');

// Route untuk menampilkan invoice
Route::get('/cashier/invoice/{id}', [CashierController::class, 'invoice'])->name('cashier.invoice');
Route::get('/cashier/print/{id}', [CashierController::class, 'print'])->name('cashier.print');

// routes/web.php
Route::resource('expenses', ExpenseController::class)->middleware('auth');

Route::prefix('financial-reports')->group(function () {
    Route::get('/', [FinancialReportController::class, 'index'])->name('financial-reports.index');
    Route::get('/export', [FinancialReportController::class, 'exportPdf'])->name('financial-reports.export');
});

Route::get('/products/print-barcodes/{id}', [ProductController::class, 'printBarcodes'])
    ->name('products.print-barcodes');

Route::prefix('returns')->group(function () {
    Route::get('/', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/create/{transaction}', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/{id}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
    Route::post('/{id}/reject', [ReturnController::class, 'reject'])->name('returns.reject');
});