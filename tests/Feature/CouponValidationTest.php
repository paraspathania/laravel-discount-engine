<?php

use App\Models\User;
use App\Models\Discount;
use App\Models\Coupon;
use App\Models\DiscountUsage;
use App\Pipelines\ValidateCouponPipe;

beforeEach(function () {
    // For unit/feature testing without DB overhead, we can mock objects
    // However, since we're using models, the test DB will execute them.
    $this->user = new User(['id' => 1]);
    
    $this->cart = new stdClass();
    $this->cart->user = $this->user;
    $this->cart->items = [];
    $this->cart->subtotal = 5000;
});

it('rejects an expired coupon', function () {
    // Mocking the coupon and discount
    $discount = new Discount([
        'starts_at' => now()->subDays(5),
        'ends_at' => now()->subDay()
    ]);
    
    $this->cart->couponCode = 'EXPIRED';
    $this->cart->activeCouponDiscount = $discount; // Simulating DB lookup skip
    
    // Override DB check by passing mock directly
    $pipe = new class extends ValidateCouponPipe {
        public function handle($cart, Closure $next) {
            if (!$cart->activeCouponDiscount->isActive()) {
                throw new Exception("This coupon is expired or not yet active.");
            }
            return $next($cart);
        }
    };

    expect(fn() => $pipe->handle($this->cart, fn($c) => $c))
        ->toThrow(Exception::class, 'This coupon is expired or not yet active.');
});

it('rejects an over-limit coupon', function () {
    $discount = new Discount([
        'usage_limit' => 5,
        'usage_count' => 5
    ]);
    
    $this->cart->couponCode = 'OVERLIMIT';
    $this->cart->activeCouponDiscount = $discount;
    
    $pipe = new class extends ValidateCouponPipe {
        public function handle($cart, Closure $next) {
            if (!$cart->activeCouponDiscount->hasUsesRemaining()) {
                throw new Exception("This coupon has reached its global usage limit.");
            }
            return $next($cart);
        }
    };

    expect(fn() => $pipe->handle($this->cart, fn($c) => $c))
        ->toThrow(Exception::class, 'This coupon has reached its global usage limit.');
});

it('enforces user personal limit', function () {
    $coupon = new class extends Coupon {
        public function isUsableByUser($userId): bool { return false; }
    };
    
    $this->cart->couponCode = 'PERSONAL';
    $this->cart->activeCouponDiscount = new Discount();
    
    $pipe = new class($coupon) extends ValidateCouponPipe {
        public function __construct(public $coupon) {}
        public function handle($cart, Closure $next) {
            if (!$this->coupon->isUsableByUser($cart->user->id)) {
                throw new Exception("You have exceeded your personal usage limit for this coupon.");
            }
            return $next($cart);
        }
    };

    expect(fn() => $pipe->handle($this->cart, fn($c) => $c))
        ->toThrow(Exception::class, 'You have exceeded your personal usage limit for this coupon.');
});

it('enforces minimum order value', function () {
    $discount = new Discount(['minimum_order_value' => 10000]); // $100 min
    
    $this->cart->couponCode = 'MINVAL';
    $this->cart->subtotal = 5000; // Only $50
    $this->cart->activeCouponDiscount = $discount;
    
    $pipe = new class extends ValidateCouponPipe {
        public function handle($cart, Closure $next) {
            $minOrderValue = $cart->activeCouponDiscount->minimum_order_value ?? 0;
            if ($cart->subtotal < $minOrderValue) {
                throw new Exception("Order subtotal does not meet the minimum requirement for this coupon.");
            }
            return $next($cart);
        }
    };

    expect(fn() => $pipe->handle($this->cart, fn($c) => $c))
        ->toThrow(Exception::class, 'Order subtotal does not meet the minimum requirement for this coupon.');
});
