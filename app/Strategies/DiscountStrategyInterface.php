<?php

namespace App\Strategies;

interface DiscountStrategyInterface
{
    /**
     * Apply the discount logic and return the new calculated price (in cents).
     *
     * @param int $price The original price or subtotal (in cents).
     * @param int $discountValue The discount value (cents for fixed, basis points for percentage).
     * @param array $cartItems Optional array of cart items (needed for strategies like BOGO).
     * @return int The new discounted price (must not be less than 0).
     */
    public function apply(int $price, int $discountValue, array $cartItems = []): int;

    /**
     * Validate if the given parameters are valid for this specific strategy.
     *
     * @param array $parameters Validation parameters.
     * @return bool
     */
    public function validateConfiguration(array $parameters): bool;
}
