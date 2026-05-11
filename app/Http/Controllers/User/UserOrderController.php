<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    /**
     * My Orders list
     */
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Single Order detail view
     */
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        
        // Eager load items and discount history
        $order->load(['items.product', 'discountUsages.discount']);
        
        return view('user.orders.show', compact('order'));
    }

    /**
     * Post-checkout confirmation success page
     */
    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('user.orders.confirmation', compact('order'));
    }
}
