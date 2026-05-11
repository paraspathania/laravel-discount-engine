<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\Cache;
use stdClass;
use Exception;

class UserOrderController extends Controller
{
    /**
     * Checkout Page
     * GET /checkout
     */
    public function checkout(Request $request)
    {
        // Re-use cart building logic for display purposes
        $cartData = session()->get('cart', []);
        if (empty($cartData)) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        // Just calling an internal method or duplicating the build logic for safety
        $cart = new stdClass();
        $cart->user = $request->user();
        $cart->couponCode = session()->get('coupon_code');
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
            return redirect()->route('user.cart.index')->with('error', $e->getMessage());
        }

        return view('user.checkout.index', compact('finalCart'));
    }

    /**
     * Process Checkout
     * POST /checkout
     */
    public function process(Request $request)
    {
        $userId = $request->user()->id;
        $lock = Cache::lock('web_checkout_' . $userId, 30);
        
        if (!$lock->get()) {
            return back()->with('error', 'Please wait. Your checkout is processing.');
        }

        try {
            $cartData = session()->get('cart', []);
            if (empty($cartData)) {
                throw new Exception('Your cart is empty.');
            }

            $cart = new stdClass();
            $cart->user = $request->user();
            $cart->couponCode = session()->get('coupon_code');
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

            // Run FULL pipeline including FinalizeOrderPipe
            $finalCart = Pipeline::send($cart)
                ->through([
                    \App\Pipelines\ValidateCouponPipe::class,
                    \App\Pipelines\ApplyItemDiscountsPipe::class,
                    \App\Pipelines\ApplyOrderDiscountsPipe::class,
                    \App\Pipelines\ApplyShippingDiscountsPipe::class,
                    \App\Pipelines\CalculateTaxesPipe::class,
                    \App\Pipelines\FinalizeOrderPipe::class,
                ])->thenReturn();

            // Clear session cart
            session()->forget(['cart', 'coupon_code']);

            return redirect()->route('user.orders.show', $finalCart->createdOrder->id)
                ->with('success', 'Order placed successfully!');

        } catch (Exception $e) {
            return back()->with('error', 'Checkout Failed: ' . $e->getMessage());
        } finally {
            $lock->release();
        }
    }

    /**
     * My Orders List
     * GET /orders
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->latest()->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Order Detail Page
     * GET /orders/{id}
     */
    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }
        
        return view('user.orders.show', compact('order'));
    }
}
