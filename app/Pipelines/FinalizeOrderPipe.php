<?php

namespace App\Pipelines;

use App\Models\Order;
use App\Models\DiscountUsage;
use App\Models\Coupon;
use App\Models\Discount;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;

class FinalizeOrderPipe
{
    /**
     * Write to orders table.
     * Write to discount_usage table.
     * Atomic increments for usage counts.
     */
    public function handle($cart, Closure $next)
    {
        DB::beginTransaction();
        try {
            // 1. Write to orders table
            $totalDiscounts = bcadd((string)$cart->itemDiscountsTotal, (string)$cart->orderDiscountsTotal, 0);
            $totalDiscounts = bcadd($totalDiscounts, (string)$cart->shippingDiscountTotal, 0);

            $order = Order::create([
                'user_id' => $cart->user->id,
                'subtotal' => $cart->subtotal,
                'discount_total' => (int)$totalDiscounts,
                'tax_total' => $cart->taxTotal,
                'grand_total' => $cart->grandTotal,
                'status' => 'confirmed',
            ]);

            $cart->createdOrder = $order;

            // 2. Write to discount_usage and atomicaly increment
            foreach ($cart->appliedDiscounts as $usage) {
                // Write audit log
                DiscountUsage::create([
                    'user_id' => $cart->user->id,
                    'order_id' => $order->id,
                    'discount_id' => $usage['discount_id'],
                    'saved_amount' => $usage['saved_amount'],
                ]);

                // Atomic increment for Discount usage
                $affectedDiscount = Discount::where('id', $usage['discount_id'])
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereColumn('usage_count', '<', 'usage_limit');
                    })
                    ->increment('usage_count');

                if (!$affectedDiscount) {
                    throw new Exception("Discount [{$usage['discount_id']}] usage limit exceeded during checkout.");
                }

                // Atomic increment for Coupon usage (if this discount is tied to the cart's coupon)
                if (!empty($cart->couponCode)) {
                    $coupon = Coupon::where('code', $cart->couponCode)
                        ->where('discount_id', $usage['discount_id'])
                        ->first();
                        
                    if ($coupon) {
                        // User-specific checks happened in ValidateCouponPipe.
                        // Here we just increment the global coupon usage count safely.
                        $coupon->increment('usage_count');
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw clone $e;
        }

        return $next($cart);
    }
}
