<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Customer\CheckoutController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Sanctum Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Step 8: Public Offers
    Route::get('/offers', [DiscountController::class, 'index']);

    // Step 8: Coupon Endpoints (Rate Limited 60 per minute)
    Route::middleware('throttle:60,1')->group(function () {
        Route::post('/coupon/validate', [CouponController::class, 'validateCoupon']);
        Route::post('/coupon/apply', [CouponController::class, 'apply']);
    });

    // Step 8: Checkout Pipeline
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    
    // Step 8: Order Summary
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Admin API Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Double-guarded routes handled by other controllers
        Route::apiResource('discounts', \App\Http\Controllers\Api\Admin\DiscountController::class);
        Route::apiResource('coupons', \App\Http\Controllers\Api\Admin\CouponController::class);
    });
});
