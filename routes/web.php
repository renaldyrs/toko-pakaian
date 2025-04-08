<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    SupplierController,
    CategoryController,
    CashierController,
    DashboardController,
    ReportController,
    TransactionController,
    InventoryController,
    ProfileController,
    UserController,
    StoreProfileController,
    ExpenseController,
    FinancialReportController,
    ReturnController
};

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Suppliers
Route::resource('suppliers', SupplierController::class)->middleware('auth');

// Products
Route::resource('products', ProductController::class)->middleware('auth');
Route::get('/products/print-barcodes/{id}', [ProductController::class, 'printBarcodes'])->name('products.print-barcodes');
Route::get('/products/{id}/download-barcode', [ProductController::class, 'downloadBarcode'])->name('products.downloadBarcode');
Route::get('/products/find-by-code{code}', [ProductController::class, 'findByCode'])->name('products.find-by-code');
Route::get('/products/{barcode}', [ProductController::class, 'findByBarcode']);
Route::get('/barcode/{code}', [ProductController::class, 'generateBarcode'])->name('barcode.generate');

// Categories
Route::resource('categories', CategoryController::class)->middleware('auth');

// Cashier
Route::prefix('cashier')->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('/', [CashierController::class, 'store'])->name('cashier.store');
    Route::get('/invoice/{id}', [CashierController::class, 'invoice'])->name('cashier.invoice');
    Route::get('/invoice/{id}/print', [CashierController::class, 'printInvoice'])->name('cashier.invoice.print');
    Route::get('/orders', [CashierController::class, 'orders'])->name('cashier.orders');
    Route::get('/receipt/{id}', [CashierController::class, 'showReceipt'])->name('cashier.receipt');
    Route::get('/print/{id}', [CashierController::class, 'printReceipt'])->name('cashier.print');
    Route::post('/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.addToCart');
});

// Reports
Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
    Route::get('/laporan-pesanan', [ReportController::class, 'laporanPesanan'])->name('reports.laporan-pesanan');
});

// Financial Reports
Route::prefix('financial-reports')->group(function () {
    Route::get('/', [FinancialReportController::class, 'index'])->name('financial-reports.index');
    Route::get('/export', [FinancialReportController::class, 'exportPdf'])->name('financial-reports.export');
    Route::get('/laporan-keuangan', [FinancialReportController::class, 'laporanKeuangan'])->name('financial-reports.laporan-keuangan');
});

// Store Profile
Route::prefix('store-profile')->group(function () {
    Route::get('/edit', [StoreProfileController::class, 'edit'])->name('store-profile.edit');
    Route::put('/update', [StoreProfileController::class, 'update'])->name('store-profile.update');
});

// Expenses
Route::resource('expenses', ExpenseController::class)->middleware('auth');

// Returns
Route::prefix('returns')->group(function () {
    Route::get('/', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/create/{transaction}', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/{id}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
    Route::post('/{id}/reject', [ReturnController::class, 'reject'])->name('returns.reject');
});

// Profile
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

// Users
Route::resource('users', UserController::class);