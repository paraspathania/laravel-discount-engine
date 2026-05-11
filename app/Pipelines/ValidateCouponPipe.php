<?php

namespace App\Pipelines;

use App\Models\Coupon;
use App\Models\Discount;
use Closure;
use Exception;

class ValidateCouponPipe
{
    /**
     * @param object $cart Expected to have $cart->couponCode, $cart->user, $cart->items, $cart->subtotal
     */
    public function handle($cart, Closure $next)
    {
        if (empty($cart->couponCode)) {
            return $next($cart); // Skip if no coupon code provided
        }

        // 1. Check coupon exists
        $coupon = Coupon::where('code', $cart->couponCode)->first();
        if (!$coupon) {
            throw new Exception("Invalid coupon code.");
        }

        $discount = $coupon->discount;
        if (!$discount) {
            throw new Exception("Coupon is not linked to a valid discount.");
        }

        // Attach discount to the cart for downstream pipes
        $cart->activeCouponDiscount = $discount;

        // 2. Check active (date window)
        if (!$discount->isActive()) {
            throw new Exception("This coupon is expired or not yet active.");
        }

        // 3. Check global usage_count < usage_limit
        if (!$discount->hasUsesRemaining()) {
            throw new Exception("This coupon has reached its global usage limit.");
        }

        // 4. Check user personal usage limit
        if (!$coupon->isUsableByUser($cart->user->id)) {
            throw new Exception("You have exceeded your personal usage limit for this coupon.");
        }

        // 5. Check cart meets minimum order value (assuming dynamic attribute or 0 fallback)
        $minOrderValue = $discount->minimum_order_value ?? 0;
        if ($cart->subtotal < $minOrderValue) {
            throw new Exception("Order subtotal does not meet the minimum requirement for this coupon.");
        }

        // 6. Check cart contains qualifying products/categories
        if (!$discount->isSiteWide()) {
            $hasQualifyingItem = false;

            $qualifyingProductIds = $discount->qualifiableProducts()->pluck('products.id')->toArray();
            $qualifyingCategoryIds = $discount->qualifiableCategories()->pluck('categories.id')->toArray();

            foreach ($cart->items as $item) {
                // $item->product is assumed to be an App\Models\Product instance
                if (in_array($item->product->id, $qualifyingProductIds)) {
                    $hasQualifyingItem = true;
                    break;
                }
                if (in_array($item->product->category_id, $qualifyingCategoryIds)) {
                    $hasQualifyingItem = true;
                    break;
                }
            }

            if (!$hasQualifyingItem) {
                throw new Exception("Your cart does not contain any qualifying items for this coupon.");
            }
        }

        return $next($cart);
    }
}
