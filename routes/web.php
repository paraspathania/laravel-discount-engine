<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Main Controllers
use App\Http\Controllers\HomeController;

// User Controllers
use App\Http\Controllers\User\UserOfferController;
use App\Http\Controllers\User\UserProductController;
use App\Http\Controllers\User\UserCartController;
use App\Http\Controllers\User\UserOrderController;

/*
|--------------------------------------------------------------------------
| Public Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return app(\App\Http\Controllers\User\UserDashboardController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/offers', [UserOfferController::class, 'index'])->name('user.offers.index');
Route::get('/offers/{offer}', [UserOfferController::class, 'show'])->name('user.offers.show');

Route::get('/products', [UserProductController::class, 'index'])->name('user.products.index');
Route::get('/products/{product}', [UserProductController::class, 'show'])->name('user.products.show');

// Cart (AJAX) - Guest access allowed
Route::get('/cart', [\App\Http\Controllers\User\CartController::class, 'index'])->name('user.cart.index');
Route::post('/cart/add', [\App\Http\Controllers\User\CartController::class, 'add'])->name('user.cart.add');
Route::post('/cart/remove', [\App\Http\Controllers\User\CartController::class, 'remove'])->name('user.cart.remove');
Route::post('/cart/update', [\App\Http\Controllers\User\CartController::class, 'update'])->name('user.cart.update');
Route::post('/cart/apply-coupon', [\App\Http\Controllers\User\CartController::class, 'applyCoupon'])->name('user.cart.coupon.apply');
Route::post('/cart/remove-coupon', [\App\Http\Controllers\User\CartController::class, 'removeCoupon'])->name('user.cart.coupon.remove');

/*
|--------------------------------------------------------------------------
| Authenticated User Shopping Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout
    Route::get('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'index'])->name('user.checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'process'])->name('user.checkout.process');
    
    // Order History
    Route::get('/orders', [\App\Http\Controllers\User\UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\User\UserOrderController::class, 'show'])->name('user.orders.show');
    Route::get('/orders/{order}/confirmation', [\App\Http\Controllers\User\UserOrderController::class, 'confirmation'])->name('user.orders.confirmation');

    /*
    |--------------------------------------------------------------------------
    | Admin Portal (Triple-Guarded: auth, verified, admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard (real data via controller)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Discount Management (full resource)
        Route::resource('discounts', AdminDiscountController::class);

        // Coupon Management
        Route::get('coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
        Route::post('coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
        Route::delete('coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

        // Product Management (full resource)
        Route::resource('products', AdminProductController::class);

        // Order Management
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Analytics
        Route::get('analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/export', [AdminAnalyticsController::class, 'export'])->name('analytics.export');
    });
});

require __DIR__.'/auth.php';
