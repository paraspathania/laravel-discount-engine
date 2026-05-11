<?php

use App\Strategies\DiscountStrategyFactory;
use App\Strategies\PercentageDiscountStrategy;
use App\Strategies\FixedAmountDiscountStrategy;
use App\Strategies\BuyOneGetOneStrategy;

it('returns correct strategy class per type', function () {
    expect(DiscountStrategyFactory::make('percentage'))
        ->toBeInstanceOf(PercentageDiscountStrategy::class);
        
    expect(DiscountStrategyFactory::make('fixed_amount'))
        ->toBeInstanceOf(FixedAmountDiscountStrategy::class);
        
    expect(DiscountStrategyFactory::make('bogo'))
        ->toBeInstanceOf(BuyOneGetOneStrategy::class);
});

it('throws exception on invalid type', function () {
    DiscountStrategyFactory::make('invalid_type');
})->throws(InvalidArgumentException::class);
