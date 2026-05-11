<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\Cache;
use App\Pipelines\ValidateCouponPipe;
use App\Pipelines\ApplyItemDiscountsPipe;
use App\Pipelines\ApplyOrderDiscountsPipe;
use App\Pipelines\ApplyShippingDiscountsPipe;
use App\Pipelines\CalculateTaxesPipe;
use App\Pipelines\FinalizeOrderPipe;
use Exception;
use stdClass;

class CheckoutController extends Controller
{
    /**
     * Executes the checkout process using the Pipeline pattern.
     * Incorporates Layer 2: Redis Distributed Locking to prevent double-clicks.
     */
    public function checkout(Request $request)
    {
        $userId = $request->user()->id;

        // Layer 2 — Redis Distributed Locking
        // Prevents the same user from initiating checkout concurrently (e.g. double-click)
        $lock = Cache::lock('checkout_' . $userId, 30);
        
        if (!$lock->get()) {
            abort(429, 'Please try again. Your previous checkout request is still processing.');
        }

        try {
            // 1. Build the Cart Payload Object
            $cart = new stdClass();
            $cart->user = $request->user();
            $cart->couponCode = $request->input('coupon_code');
            
            // Mocking cart items & subtotal
            $cart->items = []; 
            $cart->subtotal = 0; 
            $cart->baseShippingCost = 1500; 

            // 2. Wire and execute the Pipeline
            $finalCart = Pipeline::send($cart)
                ->through([
                    ValidateCouponPipe::class,
                    ApplyItemDiscountsPipe::class,
                    ApplyOrderDiscountsPipe::class,
                    ApplyShippingDiscountsPipe::class,
                    CalculateTaxesPipe::class,
                    FinalizeOrderPipe::class, // Contains Layer 1 & 3 concurrency controls
                ])
                ->thenReturn();

            return response()->json([
                'message' => 'Order placed successfully!',
                'order_id' => $finalCart->createdOrder->id,
                'grand_total' => $finalCart->createdOrder->grand_total_formatted,
                'discount_total' => $finalCart->createdOrder->discount_total_formatted,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Checkout failed.',
                'error' => $e->getMessage()
            ], 422);
        } finally {
            // Guarantee lock is released even if exceptions occur
            $lock->release();
        }
    }
}
