<?php

namespace App\Strategies;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class DiscountStrategyFactory
{
    /**
     * Resolve the concrete strategy class from the database 'type' string.
     * Leverages Laravel's Service Container for automatic dependency injection
     * if the strategies themselves eventually need injected services.
     */
    public static function make(string $type): DiscountStrategyInterface
    {
        return match ($type) {
            'percentage'   => App::make(PercentageDiscountStrategy::class),
            'fixed_amount' => App::make(FixedAmountDiscountStrategy::class),
            'bogo'         => App::make(BuyOneGetOneStrategy::class),
            
            // Note: 'free_shipping' was mentioned in the Request validation.
            // If you add a FreeShippingStrategy later, map it here:
            // 'free_shipping' => App::make(FreeShippingStrategy::class),
            
            default => throw new InvalidArgumentException("Unknown discount strategy type: [{$type}]")
        };
    }
}
