<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
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
     */
    public function checkout(Request $request)
    {
        // 1. Build the Cart Payload Object
        // In a real app, items would be loaded from DB/Session.
        $cart = new stdClass();
        $cart->user = $request->user();
        $cart->couponCode = $request->input('coupon_code');
        
        // Mocking cart items & subtotal for demonstration
        $cart->items = []; // Should be populated with actual Product models & prices
        $cart->subtotal = 0; // Sum of $item->price
        $cart->baseShippingCost = 1500; // $15.00 shipping

        try {
            // 2. Wire and execute the Pipeline
            $finalCart = Pipeline::send($cart)
                ->through([
                    ValidateCouponPipe::class,
                    ApplyItemDiscountsPipe::class,
                    ApplyOrderDiscountsPipe::class,
                    ApplyShippingDiscountsPipe::class,
                    CalculateTaxesPipe::class,
                    FinalizeOrderPipe::class,
                ])
                ->thenReturn();

            return response()->json([
                'message' => 'Order placed successfully!',
                'order_id' => $finalCart->createdOrder->id,
                'grand_total' => $finalCart->createdOrder->grand_total_formatted,
                'discount_total' => $finalCart->createdOrder->discount_total_formatted,
            ]);

        } catch (Exception $e) {
            // If any pipe throws an exception, the pipeline aborts cleanly
            return response()->json([
                'message' => 'Checkout failed.',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
