<?php

use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

  Route::get('/orders', [OrderController::class, 'index']);
  Route::get('/orders/{order}', [OrderController::class, 'show']);
});
