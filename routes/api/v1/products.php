<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)->group(function () {
  Route::get('/products', 'index');
  Route::get('/products/{product}', 'show');
});
