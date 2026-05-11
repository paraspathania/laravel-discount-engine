<?php

namespace App\Strategies;

class BuyOneGetOneStrategy implements DiscountStrategyInterface
{
    /**
     * Analyze cart items to apply "Buy 1 Get 1 Free" logic.
     * We pair up eligible items, sort them by price, and discount the cheaper one.
     */
    public function apply(int $price, int $discountValue, array $cartItems = []): int
    {
        // If there are less than 2 items, BOGO doesn't apply; return original price.
        if (count($cartItems) < 2) {
            return max(0, $price);
        }

        // Extract prices and sort them descending
        $itemPrices = array_column($cartItems, 'price');
        rsort($itemPrices);

        $discountAmount = '0';

        // For every pair (2 items), the second (cheaper/equal) one is free
        for ($i = 0; $i < count($itemPrices); $i++) {
            // Every odd index is the "Get 1 Free" item (since 0-indexed)
            if ($i % 2 !== 0) {
                // Add the price of the free item to our total discount
                $discountAmount = bcadd($discountAmount, (string)$itemPrices[$i], 0);
            }
        }

        // Subtract the total BOGO discount from the cart subtotal
        $newPrice = bcsub((string)$price, $discountAmount, 0);

        return max(0, (int)$newPrice);
    }

    public function validateConfiguration(array $parameters): bool
    {
        // BOGO doesn't strictly need a 'value', but we can ensure the cart setup is valid
        return true;
    }
}
