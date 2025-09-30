<?php

use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/payment/create/{order}', [PaymentController::class, 'create'])
  ->middleware('auth:sanctum');
