<?php

namespace App\Pipelines;

use Closure;

class CalculateTaxesPipe
{
    /**
     * Calculate tax AFTER all discounts applied.
     * Uses BCMath for precision.
     * Tax on discounted total NOT original total.
     */
    public function handle($cart, Closure $next)
    {
        $taxRate = '0.08'; // Example 8% flat tax rate, could be dynamic

        // Total discounts so far = item + order discounts
        $totalProductDiscounts = bcadd((string)$cart->itemDiscountsTotal, (string)$cart->orderDiscountsTotal, 0);

        // Taxable total = Subtotal - total product discounts
        $taxableAmount = bcsub((string)$cart->subtotal, $totalProductDiscounts, 0);
        $taxableAmount = max(0, (int)$taxableAmount); // Ensure no negative taxable amount

        // Calculate exact tax in cents
        $tax = bcmul((string)$taxableAmount, $taxRate, 0);
        
        $cart->taxTotal = (int)$tax;

        // Calculate Grand Total
        // Grand Total = Taxable Amount + Tax + Final Shipping Cost
        $grandTotal = bcadd((string)$taxableAmount, (string)$cart->taxTotal, 0);
        $grandTotal = bcadd($grandTotal, (string)$cart->finalShippingCost, 0);

        $cart->grandTotal = (int)$grandTotal;

        return $next($cart);
    }
}
