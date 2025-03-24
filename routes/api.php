<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Models\Product;

Route::get('/products', function(Request $request) {
    return Product::where('barcode', $request->barcode)->first();
});