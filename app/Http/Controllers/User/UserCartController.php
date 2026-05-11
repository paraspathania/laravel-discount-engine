<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use stdClass;
use Exception;

class UserCartController extends Controller
{
    /**
     * Shopping Cart Page
     * GET /cart
     */
    public function index(Request $request)
    {
        $cartData = session()->get('cart', []);
        $couponCode = session()->get('coupon_code', null);
        
        $cart = new stdClass();
        $cart->user = $request->user();
        $cart->couponCode = $couponCode;
        $cart->subtotal = 0;
        $cart->baseShippingCost = 1500;
        $cart->items = [];
        
        foreach ($cartData as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $item = new stdClass();
                $item->product = $product;
                $item->qty = $details['qty'];
                $item->price = $product->price * $details['qty'];
                $cart->subtotal += $item->price;
                $cart->items[] = $item;
            }
        }
        
        $finalCart = $cart;

        if (count($cart->items) > 0) {
            try {
                // Pass through simulation pipeline (omitting FinalizeOrderPipe)
                $finalCart = Pipeline::send($cart)
                    ->through([
                        \App\Pipelines\ValidateCouponPipe::class,
                        \App\Pipelines\ApplyItemDiscountsPipe::class,
                        \App\Pipelines\ApplyOrderDiscountsPipe::class,
                        \App\Pipelines\ApplyShippingDiscountsPipe::class,
                        \App\Pipelines\CalculateTaxesPipe::class,
                    ])->thenReturn();

            } catch (Exception $e) {
                // Invalid coupon triggers fallback
                session()->forget('coupon_code');
                session()->flash('error', $e->getMessage());
                
                // Manually calculate tax on raw subtotal as fallback
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

        return view('user.cart.index', compact('finalCart', 'cartData'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('user.cart.index')->with('success', 'Product added to cart!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        session()->put('coupon_code', strtoupper($request->code));
        return redirect()->route('user.cart.index')->with('success', 'Coupon applied!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');
        return redirect()->route('user.cart.index')->with('success', 'Coupon removed.');
    }
}
