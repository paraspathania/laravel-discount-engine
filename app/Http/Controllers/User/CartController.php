<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use stdClass;
use Exception;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Render the initial cart page
     */
    public function index(Request $request)
    {
        $finalCart = $this->getCalculatedCart($request);
        return view('user.cart.index', compact('finalCart'));
    }

    /**
     * AJAX: Add to Cart
     */
    public function add(Request $request)
    {
        $this->cartService->add($request->product_id, $request->qty ?? 1);
        return response()->json($this->getCalculatedCart($request));
    }

    /**
     * AJAX: Update Quantity
     */
    public function update(Request $request)
    {
        $this->cartService->update($request->product_id, $request->qty);
        return response()->json($this->getCalculatedCart($request));
    }

    /**
     * AJAX: Remove Item
     */
    public function remove(Request $request)
    {
        $this->cartService->remove($request->product_id);
        return response()->json($this->getCalculatedCart($request));
    }

    /**
     * AJAX: Apply Coupon Code
     */
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        session()->put('coupon_code', strtoupper($request->code));
        
        $cart = $this->getCalculatedCart($request);
        
        if (isset($cart->couponError)) {
            return response()->json([
                'success' => false, 
                'message' => $cart->couponError, 
                'cart' => $cart
            ]);
        }
        
        return response()->json([
            'success' => true, 
            'message' => 'Coupon applied successfully!', 
            'cart' => $cart
        ]);
    }

    /**
     * AJAX: Remove Coupon Code
     */
    public function removeCoupon(Request $request)
    {
        session()->forget('coupon_code');
        return response()->json([
            'success' => true, 
            'cart' => $this->getCalculatedCart($request)
        ]);
    }

    /**
     * Builds the final cart object passing through the simulation pipeline
     */
    private function getCalculatedCart(Request $request)
    {
        $cartData = $this->cartService->getItems();
        $couponCode = session()->get('coupon_code', null);
        
        $cart = new stdClass();
        $cart->user = auth()->user();
        $cart->couponCode = $couponCode;
        $cart->subtotal = 0;
        $cart->baseShippingCost = 1500;
        $cart->items = [];
        $cart->itemCount = 0;
        
        foreach ($cartData as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $item = new stdClass();
                $item->product = $product;
                $item->qty = $details['qty'];
                $item->price = $product->price * $details['qty'];
                $cart->subtotal += $item->price;
                $cart->items[] = $item;
                $cart->itemCount += $details['qty'];
            }
        }
        
        $finalCart = $cart;

        if (count($cart->items) > 0) {
            try {
                $finalCart = Pipeline::send($cart)
                    ->through([
                        \App\Pipelines\ValidateCouponPipe::class,
                        \App\Pipelines\ApplyItemDiscountsPipe::class,
                        \App\Pipelines\ApplyOrderDiscountsPipe::class,
                        \App\Pipelines\ApplyShippingDiscountsPipe::class,
                        \App\Pipelines\CalculateTaxesPipe::class,
                    ])->thenReturn();

            } catch (Exception $e) {
                session()->forget('coupon_code');
                $finalCart->couponError = $e->getMessage();
                $finalCart->couponCode = null; // Clear from final cart
                
                // Fallback math
                $finalCart->itemDiscountsTotal = 0;
                $finalCart->orderDiscountsTotal = 0;
                $finalCart->taxTotal = (int)($cart->subtotal * 0.08);
                $finalCart->finalShippingCost = 1500;
                $finalCart->grandTotal = $cart->subtotal + $finalCart->taxTotal + 1500;
            }
        } else {
            $finalCart->itemDiscountsTotal = 0;
            $finalCart->orderDiscountsTotal = 0;
            $finalCart->taxTotal = 0;
            $finalCart->finalShippingCost = 0;
            $finalCart->grandTotal = 0;
        }

        // Format monetary values for JSON/JS frontend
        $finalCart->subtotalFormatted = number_format($finalCart->subtotal / 100, 2);
        $finalCart->discountsFormatted = number_format(($finalCart->itemDiscountsTotal + $finalCart->orderDiscountsTotal) / 100, 2);
        $finalCart->taxFormatted = number_format($finalCart->taxTotal / 100, 2);
        $finalCart->shippingFormatted = number_format($finalCart->finalShippingCost / 100, 2);
        $finalCart->baseShippingFormatted = number_format($finalCart->baseShippingCost / 100, 2);
        $finalCart->grandTotalFormatted = number_format($finalCart->grandTotal / 100, 2);
        
        // Also map items to include formatted line totals for JS
        foreach ($finalCart->items as $i) {
            $i->unitPriceFormatted = number_format($i->product->price / 100, 2);
            $i->lineTotalFormatted = number_format($i->price / 100, 2);
        }

        return $finalCart;
    }
}
