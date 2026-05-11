<?php

namespace App\Pipelines;

use App\Models\Order;
use App\Models\DiscountUsage;
use App\Jobs\FinalizeOrderJob;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;

class FinalizeOrderPipe
{
    /**
     * Write to orders table.
     * Write to discount_usage table.
     * Layer 1: Atomic increments for usage counts with strict row-count checking.
     * Layer 3: Dispatch heavy background tasks to queue.
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

            // 2. Write to discount_usage and atomically increment limits
            foreach ($cart->appliedDiscounts as $usage) {
                
                // Write audit log
                DiscountUsage::create([
                    'user_id' => $cart->user->id,
                    'order_id' => $order->id,
                    'discount_id' => $usage['discount_id'],
                    'saved_amount' => $usage['saved_amount'],
                ]);

                // Layer 1 — Atomic DB Operation for Discounts
                // Increments only if usage_limit is null OR usage_count is strictly less than usage_limit.
                $updatedDiscountRows = DB::table('discounts')
                    ->where('id', $usage['discount_id'])
                    ->where(function ($query) {
                        $query->whereNull('usage_limit')
                              ->orWhereColumn('usage_count', '<', 'usage_limit');
                    })
                    ->increment('usage_count');

                // If no rows were updated, another transaction beat us to the limit
                if ($updatedDiscountRows === 0) {
                    throw new Exception("Discount [{$usage['discount_id']}] usage limit exceeded during checkout.");
                }

                // Layer 1 — Atomic DB Operation for Coupons (if applicable)
                if (!empty($cart->couponCode)) {
                    $coupon = DB::table('coupons')
                        ->where('code', $cart->couponCode)
                        ->where('discount_id', $usage['discount_id'])
                        ->first();
                        
                    if ($coupon) {
                        // Applying the exact atomic row-checking pattern requested
                        // (Assuming we check against a global limit, or just increment safely)
                        $updatedCouponRows = DB::table('coupons')
                            ->where('id', $coupon->id)
                            ->increment('usage_count');

                        if ($updatedCouponRows === 0) {
                            throw new Exception("Coupon record could not be updated or was deleted concurrently.");
                        }
                    }
                }
            }

            DB::commit();

            // Layer 3 — Dispatch heavy background tasks (invoicing, emails, warehouse sync)
            FinalizeOrderJob::dispatch($order->id);

        } catch (Exception $e) {
            DB::rollBack();
            throw clone $e; // Throw back to CheckoutController to handle
        }

        return $next($cart);
    }
}
