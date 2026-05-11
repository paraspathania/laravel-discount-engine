<?php

use App\Strategies\PercentageDiscountStrategy;

it('calculates exactly 10 percent of 10000 cents as 1000 using bcmath', function () {
    $strategy = new PercentageDiscountStrategy();
    
    // 1000 basis points = 10%
    // 10000 cents = $100.00
    $newPrice = $strategy->apply(10000, 1000);
    
    // Expected new price is 9000 cents ($90.00)
    expect($newPrice)->toBe(9000);
});

it('never lets the price go below 0', function () {
    $strategy = new PercentageDiscountStrategy();
    
    // 20000 basis points = 200% off
    $newPrice = $strategy->apply(500, 20000);
    
    expect($newPrice)->toBe(0);
});
