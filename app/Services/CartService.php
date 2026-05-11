<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $sessionKey = 'cart';

    public function add($productId, $quantity = 1)
    {
        $cart = Session::get($this->sessionKey, []);
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $quantity;
        } else {
            $product = Product::find($productId);
            if ($product) {
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $quantity,
                ];
            }
        }
        Session::put($this->sessionKey, $cart);
    }

    public function remove($productId)
    {
        $cart = Session::get($this->sessionKey, []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        Session::put($this->sessionKey, $cart);
    }

    public function update($productId, $quantity)
    {
        $cart = Session::get($this->sessionKey, []);
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['qty'] = $quantity;
            }
        }
        Session::put($this->sessionKey, $cart);
    }

    public function getItems()
    {
        return Session::get($this->sessionKey, []);
    }

    public function getSubtotal()
    {
        $cart = Session::get($this->sessionKey, []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }

    public function clear()
    {
        Session::forget($this->sessionKey);
    }
}
