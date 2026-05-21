<?php

namespace App\Pipelines;

use App\Models\Discount;
use App\Strategies\DiscountStrategyFactory;
use Closure;

class ApplyOrderDiscountsPipe
{
    /**
     * Apply order-level discounts to running total.
     * Sequentially stacks them, sorted by priority DESC, then created_at ASC.
     */
    public function handle($cart, Closure $next)
    {
        $cart->orderDiscountsTotal = '0';
        $cart->appliedDiscounts = $cart->appliedDiscounts ?? [];

        // Current subtotal after item-level discounts
        $runningTotal = bcsub((string)$cart->subtotal, (string)$cart->itemDiscountsTotal, 0);

        // Retrieve the active coupon if it's order-level
        $activeCouponDiscount = $cart->activeCouponDiscount ?? null;

        // Fetch other active, stackable, order-level (sitewide) auto-discounts.
        // Prompt asked to sort by priority DESC, created_at ASC.
        $autoDiscounts = Discount::active()
            ->stackable()
            ->orderByDesc('priority')
            ->orderBy('created_at', 'asc')
            ->get()
            ->filter(fn($d) => $d->isSiteWide() && $d->type !== 'free_shipping');

        // Merge active coupon into stack if it's sitewide and not already applied
        $discountsToApply = collect();
        if ($activeCouponDiscount && $activeCouponDiscount->isSiteWide() && $activeCouponDiscount->type !== 'free_shipping') {
            $discountsToApply->push($activeCouponDiscount);
        }
        
        foreach ($autoDiscounts as $auto) {
            // Avoid applying the exact same discount twice if it was the coupon
            if (!$discountsToApply->contains('id', $auto->id)) {
                $discountsToApply->push($auto);
            }
        }

        // Sequential stacking using BCMath on the running total
        foreach ($discountsToApply as $discount) {
            if ($runningTotal <= 0) {
                break; // Stop if order is already free
            }

            $strategy = DiscountStrategyFactory::make($discount->type);
            $newRunningTotal = $strategy->apply((int)$runningTotal, $discount->value, []);

            $saved = bcsub((string)$runningTotal, (string)$newRunningTotal, 0);

            if ($saved > 0) {
                $cart->orderDiscountsTotal = bcadd($cart->orderDiscountsTotal, $saved, 0);
                $runningTotal = $newRunningTotal;

                $cart->appliedDiscounts[] = [
                    'discount_id' => $discount->id,
                    'name' => $discount->name,
                    'saved_amount' => (int)$saved,
                ];
            }
        }

        return $next($cart);
    }
}
