<?php

use App\Strategies\FixedAmountDiscountStrategy;

it('subtracts 500 cents off 1000 cents to equal 500', function () {
    $strategy = new FixedAmountDiscountStrategy();
    
    $newPrice = $strategy->apply(1000, 500);
    
    expect($newPrice)->toBe(500);
});

it('subtracts 1500 cents off 1000 cents and returns 0 instead of negative', function () {
    $strategy = new FixedAmountDiscountStrategy();
    
    $newPrice = $strategy->apply(1000, 1500);
    
    expect($newPrice)->toBe(0);
});
