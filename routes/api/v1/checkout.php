<?php

use App\Http\Controllers\Api\V1\CheckoutController;
use Illuminate\Support\Facades\Route;


Route::post('/checkout', [CheckoutController::class, 'store'])
  ->middleware('auth:sanctum');
