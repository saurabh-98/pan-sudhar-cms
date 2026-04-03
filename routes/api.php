<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;

/* =========================
   PUBLIC ROUTES
========================= */

Route::post('/payment/webhook', [CheckoutController::class, 'webhook']);
Route::post('/payment/create-order', [CheckoutController::class, 'createOrder']);
Route::post('/payment/verify', [CheckoutController::class, 'verifyPayment']);


/* =========================
   AUTH ROUTES
========================= */

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/order/place', [OrderController::class, 'placeOrder']);
    Route::get('/orders', [OrderController::class, 'myOrders']);

});