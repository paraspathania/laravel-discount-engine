<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use App\Pipelines\ValidateCouponPipe;
use App\Pipelines\ApplyItemDiscountsPipe;
use App\Pipelines\ApplyOrderDiscountsPipe;
use App\Pipelines\CalculateTaxesPipe;
use App\Pipelines\ApplyShippingDiscountsPipe;
use App\Models\Product;
use Exception;
use stdClass;

class CouponController extends Controller
{
    /**
     * Builds a mock cart payload for pipeline execution from API JSON input.
     */
    private function buildCartPayload(Request $request)
    {
        $cart = new stdClass();
        $cart->user = $request->user();
        $cart->couponCode = $request->input('code');
        
        // Mock subtotal fallback
        $cart->subtotal = $request->input('subtotal', 10000); 
        $cart->baseShippingCost = 1500; 

        // Map mock items if provided from frontend
        $cart->items = [];
        $rawItems = $request->input('items', []);
        foreach ($rawItems as $rawItem) {
            $item = new stdClass();
            $item->product = Product::find($rawItem['product_id']) ?? new Product();
            $item->price = $rawItem['price'] ?? 5000;
            $cart->items[] = $item;
        }

        return $cart;
    }

    /**
     * Validate coupon without applying.
     * Uses Pipeline but only executes ValidateCouponPipe.
     */
    public function validateCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $cart = $this->buildCartPayload($request);

        try {
            Pipeline::send($cart)->through([ValidateCouponPipe::class])->thenReturn();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_valid' => true,
                    'coupon' => $cart->couponCode,
                    'discount_name' => $cart->activeCouponDiscount->name,
                ],
                'message' => 'Coupon is valid.',
                'errors' => null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Coupon validation failed.',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }

    /**
     * Apply coupon and simulate full discount breakdown.
     * Runs through all calculation pipes but skips FinalizeOrderPipe.
     */
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $cart = $this->buildCartPayload($request);

        try {
            // Run exactly the 5 logic pipes to get accurate totals
            $simulatedCart = Pipeline::send($cart)
                ->through([
                    ValidateCouponPipe::class,
                    ApplyItemDiscountsPipe::class,
                    ApplyOrderDiscountsPipe::class,
                    ApplyShippingDiscountsPipe::class,
                    CalculateTaxesPipe::class,
                ])
                ->thenReturn();

            $totalDiscounts = bcadd((string)$simulatedCart->itemDiscountsTotal, (string)$simulatedCart->orderDiscountsTotal, 0);
            $newSubtotal = max(0, bcsub((string)$simulatedCart->subtotal, $totalDiscounts, 0));

            return response()->json([
                'success' => true,
                'data' => [
                    'original_subtotal' => $simulatedCart->subtotal,
                    'original_subtotal_formatted' => '$' . number_format($simulatedCart->subtotal / 100, 2),
                    
                    'discount_amount' => (int)$totalDiscounts,
                    'discount_amount_formatted' => '-$' . number_format($totalDiscounts / 100, 2),
                    'discount_label' => $simulatedCart->activeCouponDiscount->name,
                    
                    'new_subtotal' => (int)$newSubtotal,
                    'new_subtotal_formatted' => '$' . number_format($newSubtotal / 100, 2),
                    
                    'tax_amount' => $simulatedCart->taxTotal,
                    'tax_amount_formatted' => '$' . number_format($simulatedCart->taxTotal / 100, 2),
                    
                    'grand_total' => $simulatedCart->grandTotal,
                    'grand_total_formatted' => '$' . number_format($simulatedCart->grandTotal / 100, 2),
                ],
                'message' => 'Coupon applied successfully.',
                'errors' => null
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to apply coupon.',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }
}
