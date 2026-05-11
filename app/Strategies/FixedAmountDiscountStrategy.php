<?php

namespace App\Strategies;

class FixedAmountDiscountStrategy implements DiscountStrategyInterface
{
    /**
     * Apply a fixed flat-rate discount in cents.
     */
    public function apply(int $price, int $discountValue, array $cartItems = []): int
    {
        if ($price <= 0) {
            return 0;
        }

        // Simple strict subtraction using BCMath
        $newPrice = bcsub((string)$price, (string)$discountValue, 0);

        // Never let the price go below 0 cents
        return max(0, (int)$newPrice);
    }

    public function validateConfiguration(array $parameters): bool
    {
        // Fixed discount should be at least 1 cent
        if (!isset($parameters['value'])) {
            return false;
        }
        
        return (int)$parameters['value'] >= 1;
    }
}
