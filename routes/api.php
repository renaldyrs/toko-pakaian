<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'findByBarcode']);