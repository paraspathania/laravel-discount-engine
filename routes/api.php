<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Registered with the 'api' middleware group automatically by bootstrap/app.php.
| All routes here are prefixed with /api and use throttle:api by default.
|
| Authentication: Laravel Sanctum (token-based for mobile/SPA clients).
|   - Issue tokens via POST /api/auth/login
|   - Revoke via POST /api/auth/logout
|   - All protected routes require: Authorization: Bearer {token}
*/

// ── Auth (Public — no token required) ─────────────────────────────────────────

Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('register');
});

// ── Authenticated API Routes (Sanctum token required) ─────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    // ── Current User ──────────────────────────────────────────────────────────
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.auth.logout');

    // ── Discounts (read-only for customers) ────────────────────────────────────
    Route::prefix('discounts')->name('api.discounts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\DiscountController::class, 'index'])->name('index');
        Route::get('/{discount}', [\App\Http\Controllers\Api\DiscountController::class, 'show'])->name('show');
    });

    // ── Coupon Application ────────────────────────────────────────────────────
    Route::post('/coupons/apply', [\App\Http\Controllers\Api\CouponController::class, 'apply'])->name('api.coupons.apply');

    // ── Orders ────────────────────────────────────────────────────────────────
    Route::prefix('orders')->name('api.orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\OrderController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Api\OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [\App\Http\Controllers\Api\OrderController::class, 'show'])->name('show');
    });

    // ── Admin API (auth:sanctum + admin role) ─────────────────────────────────
    // Double-guarded: valid Sanctum token AND role === 'admin'
    Route::middleware('admin')->prefix('admin')->name('api.admin.')->group(function () {

        // Discounts CRUD
        Route::apiResource('discounts', \App\Http\Controllers\Api\Admin\DiscountController::class)
            ->names('api.admin.discounts');

        // Coupons CRUD
        Route::apiResource('coupons', \App\Http\Controllers\Api\Admin\CouponController::class)
            ->names('api.admin.coupons');

        // Usage reports
        Route::get('/reports/usage', [\App\Http\Controllers\Api\Admin\ReportController::class, 'usage'])
            ->name('reports.usage');
    });
});
