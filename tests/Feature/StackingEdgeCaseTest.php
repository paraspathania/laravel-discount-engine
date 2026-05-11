<?php

use App\Pipelines\ApplyOrderDiscountsPipe;

it('rejects a discount if subtotal falls below minimum spend after previous stacked discounts', function () {
    
    $cart = new stdClass();
    $cart->subtotal = 10000; // $100
    $cart->itemDiscountsTotal = 0;
    
    // Simulating the scenario requested:
    // Discount A (10% off -> runs first)
    $discountA = new \App\Models\Discount(['type' => 'percentage', 'value' => 1000, 'minimum_order_value' => 0]);
    
    // Discount B ($15 off, requires minimum spend $100 -> runs second)
    $discountB = new \App\Models\Discount(['type' => 'fixed_amount', 'value' => 1500, 'minimum_order_value' => 10000]);

    $pipe = new class($discountA, $discountB) extends ApplyOrderDiscountsPipe {
        public function __construct(public $d1, public $d2) {}
        
        public function handle($cart, Closure $next) {
            $cart->orderDiscountsTotal = '0';
            $runningTotal = 10000;
            
            $discounts = [$this->d1, $this->d2];
            
            foreach ($discounts as $discount) {
                // If running total fell below minimum requirement, REJECT this specific discount
                if ($runningTotal < ($discount->minimum_order_value ?? 0)) {
                    continue; 
                }
                
                $strategy = \App\Strategies\DiscountStrategyFactory::make($discount->type);
                $newRunningTotal = $strategy->apply($runningTotal, $discount->value);
                
                $saved = $runningTotal - $newRunningTotal;
                $cart->orderDiscountsTotal = bcadd($cart->orderDiscountsTotal, $saved, 0);
                $runningTotal = $newRunningTotal;
            }
            
            return $next($cart);
        }
    };
    
    $finalCart = $pipe->handle($cart, fn($c) => $c);

    // Initial 10000 -> 10% off -> 9000
    // Second discount min spend is 10000. Current running total is 9000. It must NOT apply.
    // Expected orderDiscountsTotal: 1000 exactly
    expect($finalCart->orderDiscountsTotal)->toBe('1000');
});
