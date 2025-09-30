<?php

use App\Http\Controllers\Api\V1\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/payment-webhook', [PaymentWebhookController::class, 'handle']);
