<?php

namespace App\Strategies;

class PercentageDiscountStrategy implements DiscountStrategyInterface
{
    /**
     * Apply a percentage discount using basis points.
     * 10000 basis points = 100%. 
     * E.g., $discountValue = 500 means 5%.
     */
    public function apply(int $price, int $discountValue, array $cartItems = []): int
    {
        if ($price <= 0) {
            return 0;
        }

        // Convert basis points to a decimal multiplier.
        // e.g., 500 / 10000 = 0.0500
        $multiplier = bcdiv((string)$discountValue, '10000', 4);
        
        // Calculate the exact discount amount in cents
        $discountAmount = bcmul((string)$price, $multiplier, 0);

        // Subtract the discount from the original price
        $newPrice = bcsub((string)$price, $discountAmount, 0);

        return max(0, (int)$newPrice);
    }

    public function validateConfiguration(array $parameters): bool
    {
        // Must be between 1 and 10000 basis points (0.01% to 100%)
        if (!isset($parameters['value'])) {
            return false;
        }
        
        $val = (int)$parameters['value'];
        return $val >= 1 && $val <= 10000;
    }
}
