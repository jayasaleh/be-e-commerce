<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth.apikey')->group(function () {
  require base_path('routes/api/v1/auth.php');
  require base_path('routes/api/v1/product.php');
  require base_path('routes/api/v1/checkout.php');
  require base_path('routes/api/v1/payment.php');
});
