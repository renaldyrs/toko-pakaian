<?php

use App\Http\Controllers\Api\ProductController;

Route::get('/products/{barcode}', [ProductController::class, 'show']);