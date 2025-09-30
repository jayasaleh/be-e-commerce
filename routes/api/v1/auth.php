<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
  Route::post('/register', 'register');
  Route::post('/login', 'login');
  Route::post('/logout', 'logout');
  Route::get('/users', 'index');
});
