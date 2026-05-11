<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\Cache;
use stdClass;
use Exception;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the checkout review page
     */
    public function index(Request $request)
    {
        $cartData = $this->cartService->getItems();
        if (empty($cartData)) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        try {
            $cart = $this->buildRawCart($request);
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
            return redirect()->route('user.cart.index')->with('error', $e->getMessage());
        }

        return view('user.checkout.index', compact('finalCart'));
    }

    /**
     * Process the order securely with Redis locking
     */
    public function process(Request $request)
    {
        $cartData = $this->cartService->getItems();
        if (empty($cartData)) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'terms' => 'accepted'
        ]);

        $userId = auth()->id();
        
        // Concurrency Protection Layer 2: Redis Distributed Locking
        $lock = Cache::lock('checkout_' . $userId, 30);

        if (!$lock->get()) {
            return back()->with('error', 'Please wait, your previous checkout is still processing.');
        }

        try {
            $cart = $this->buildRawCart($request);
            
            // Run full pipeline including FinalizeOrderPipe to execute DB operations
            $finalCart = Pipeline::send($cart)
                ->through([
                    \App\Pipelines\ValidateCouponPipe::class,
                    \App\Pipelines\ApplyItemDiscountsPipe::class,
                    \App\Pipelines\ApplyOrderDiscountsPipe::class,
                    \App\Pipelines\ApplyShippingDiscountsPipe::class,
                    \App\Pipelines\CalculateTaxesPipe::class,
                    \App\Pipelines\FinalizeOrderPipe::class, // Triggers Layer 1 & Layer 3 protection
                ])->thenReturn();

            // Checkout successful, wipe session cart
            $this->cartService->clear();
            session()->forget('coupon_code');

            return redirect()->route('user.orders.confirmation', $finalCart->order_id);
            
        } catch (Exception $e) {
            return redirect()->route('user.checkout.index')->with('error', $e->getMessage());
        } finally {
            $lock->release();
        }
    }

    /**
     * Hydrate raw cart object for Pipeline processing
     */
    private function buildRawCart(Request $request)
    {
        $cartData = $this->cartService->getItems();
        $couponCode = session()->get('coupon_code', null);
        
        $cart = new stdClass();
        $cart->user = $request->user();
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
        
        return $cart;
    }
}
