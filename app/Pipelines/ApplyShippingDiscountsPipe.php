<?php

namespace App\Pipelines;

use Closure;

class ApplyShippingDiscountsPipe
{
    /**
     * Modifies shipping cost last.
     */
    public function handle($cart, Closure $next)
    {
        $cart->shippingDiscountTotal = '0';
        $cart->appliedDiscounts = $cart->appliedDiscounts ?? [];

        // Base shipping cost comes from the cart payload (e.g. 1500 cents)
        $shippingCost = $cart->baseShippingCost ?? 0;
        $cart->finalShippingCost = $shippingCost;

        if ($shippingCost <= 0) {
            return $next($cart);
        }

        $activeCoupon = $cart->activeCouponDiscount ?? null;

        if ($activeCoupon && $activeCoupon->type === 'free_shipping') {
            // Apply full shipping discount
            $cart->shippingDiscountTotal = (string)$shippingCost;
            $cart->finalShippingCost = 0;

            $cart->appliedDiscounts[] = [
                'discount_id' => $activeCoupon->id,
                'name' => $activeCoupon->name,
                'saved_amount' => (int)$shippingCost,
            ];
        }

        return $next($cart);
    }
}
