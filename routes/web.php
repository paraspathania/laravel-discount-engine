<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Breeze auth routes are loaded via require __DIR__.'/auth.php' at the
| bottom of this file. All routes here use the 'web' middleware group
| (session, cookies, CSRF) automatically.
*/

// ── Public ────────────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ── Authenticated (any role) ───────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin Panel ───────────────────────────────────────────────────────────────
// Protected by: auth (must be logged in) + admin (role === 'admin')
// All routes prefixed /admin, named admin.*

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // ── Discounts ─────────────────────────────────────────────────────────
        Route::prefix('discounts')->name('discounts.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DiscountController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\DiscountController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\DiscountController::class, 'store'])->name('store');
            Route::get('/{discount}', [\App\Http\Controllers\Admin\DiscountController::class, 'show'])->name('show');
            Route::get('/{discount}/edit', [\App\Http\Controllers\Admin\DiscountController::class, 'edit'])->name('edit');
            Route::patch('/{discount}', [\App\Http\Controllers\Admin\DiscountController::class, 'update'])->name('update');
            Route::delete('/{discount}', [\App\Http\Controllers\Admin\DiscountController::class, 'destroy'])->name('destroy');
        });

        // ── Coupons ───────────────────────────────────────────────────────────
        Route::prefix('coupons')->name('coupons.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('store');
            Route::get('/{coupon}/edit', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('edit');
            Route::patch('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('update');
            Route::delete('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('destroy');
        });

        // ── Products ──────────────────────────────────────────────────────────
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
            Route::patch('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('destroy');
        });

        // ── Usage Reports ─────────────────────────────────────────────────────
        Route::get('/reports/usage', [\App\Http\Controllers\Admin\ReportController::class, 'usage'])->name('reports.usage');
    });

// ── Customer Storefront ───────────────────────────────────────────────────────
// Authenticated customers can browse discounts and apply coupons

Route::middleware(['auth'])->prefix('shop')->name('shop.')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
    Route::post('/coupon/apply', [\App\Http\Controllers\Customer\CouponController::class, 'apply'])->name('coupon.apply');
});

// ── Breeze Auth Routes ────────────────────────────────────────────────────────
require __DIR__.'/auth.php';
