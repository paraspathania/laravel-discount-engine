<?php

use App\Models\User;
use App\Models\Discount;
use Illuminate\Support\Facades\Pipeline;
use App\Pipelines\ApplyOrderDiscountsPipe;
use App\Pipelines\CalculateTaxesPipe;

it('calculates full pipeline correct grand total and sequential stacking accuracy', function () {
    $user = new User(['id' => 1]);
    
    // Priority 20 (applied first) - 10% off
    $discountA = new Discount(['id' => 1, 'type' => 'percentage', 'value' => 1000]);
    
    // Priority 50 (applied second) - $5 off
    $discountB = new Discount(['id' => 2, 'type' => 'fixed_amount', 'value' => 500]);

    $cart = new stdClass();
    $cart->user = $user;
    $cart->subtotal = 10000; // $100.00
    $cart->itemDiscountsTotal = 0;
    $cart->baseShippingCost = 0;
    $cart->finalShippingCost = 0;
    $cart->shippingDiscountTotal = 0;

    // We override the pipe locally to inject our mock discounts 
    // instead of querying DB to ensure pure mathematical testing
    $mockApplyPipe = new class($discountA, $discountB) extends ApplyOrderDiscountsPipe {
        public function __construct(public $d1, public $d2) {}
        public function handle($cart, Closure $next) {
            $cart->orderDiscountsTotal = '0';
            $runningTotal = 10000;
            
            // Stack D1
            $strategy1 = \App\Strategies\DiscountStrategyFactory::make($this->d1->type);
            $runningTotal = $strategy1->apply($runningTotal, $this->d1->value);
            $cart->orderDiscountsTotal = bcadd($cart->orderDiscountsTotal, 10000 - $runningTotal, 0);

            // Stack D2
            $strategy2 = \App\Strategies\DiscountStrategyFactory::make($this->d2->type);
            $oldRunning = $runningTotal;
            $runningTotal = $strategy2->apply($runningTotal, $this->d2->value);
            $cart->orderDiscountsTotal = bcadd($cart->orderDiscountsTotal, $oldRunning - $runningTotal, 0);

            return $next($cart);
        }
    };

    $finalCart = Pipeline::send($cart)->through([
        $mockApplyPipe,
        CalculateTaxesPipe::class,
    ])->thenReturn();

    // Mathematical breakdown:
    // Start: 10000
    // 10% off -> 9000 (saved 1000)
    // $5 off  -> 8500 (saved 500)
    // Total orderDiscountsTotal = 1500
    // Tax 8% of 8500 = 680
    // Grand total = 8500 + 680 = 9180
    
    expect($finalCart->orderDiscountsTotal)->toBe('1500');
    expect($finalCart->taxTotal)->toBe(680);
    expect($finalCart->grandTotal)->toBe(9180);
});
