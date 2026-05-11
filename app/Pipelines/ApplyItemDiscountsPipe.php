<?php

namespace App\Pipelines;

use App\Strategies\DiscountStrategyFactory;
use Closure;

class ApplyItemDiscountsPipe
{
    /**
     * Apply item-level discounts first.
     * One discount per product line max.
     */
    public function handle($cart, Closure $next)
    {
        $cart->itemDiscountsTotal = '0';
        $cart->appliedDiscounts = $cart->appliedDiscounts ?? [];

        // Check if there is an active coupon discount from ValidateCouponPipe
        $discount = $cart->activeCouponDiscount ?? null;
        if (!$discount) {
            return $next($cart);
        }

        // Only process if it's an item-level discount logic (i.e. not order-level)
        // For this scenario, let's assume any non-sitewide discount or BOGO is item-level.
        // Or if the prompt implies we apply the discount directly to qualifying items.
        
        $isItemLevel = !$discount->isSiteWide() || $discount->type === 'bogo';

        if ($isItemLevel) {
            $qualifyingProductIds = $discount->qualifiableProducts()->pluck('products.id')->toArray();
            $qualifyingCategoryIds = $discount->qualifiableCategories()->pluck('categories.id')->toArray();

            $strategy = DiscountStrategyFactory::make($discount->type);

            foreach ($cart->items as $item) {
                // If item already has a discount, skip (one discount per product line max)
                if (isset($item->has_discount) && $item->has_discount) {
                    continue;
                }

                $qualifies = $discount->isSiteWide() 
                    || in_array($item->product->id, $qualifyingProductIds) 
                    || in_array($item->product->category_id, $qualifyingCategoryIds);

                if ($qualifies) {
                    // Item price is original price (cents). Let strategy calculate new price.
                    // For bogo, we pass all qualifying cart items array.
                    $newPrice = $strategy->apply(
                        $item->price, 
                        $discount->value, 
                        $discount->type === 'bogo' ? $cart->items : []
                    );

                    $saved = bcsub((string)$item->price, (string)$newPrice, 0);

                    if ($saved > 0) {
                        $cart->itemDiscountsTotal = bcadd($cart->itemDiscountsTotal, $saved, 0);
                        $item->discounted_price = clone $newPrice;
                        $item->has_discount = true;

                        // Track usage for finalization
                        $cart->appliedDiscounts[] = [
                            'discount_id' => $discount->id,
                            'saved_amount' => (int)$saved,
                        ];

                        // If BOGO, it analyzes all items, so we break after applying once
                        if ($discount->type === 'bogo') {
                            break;
                        }
                    }
                }
            }
        }

        return $next($cart);
    }
}
