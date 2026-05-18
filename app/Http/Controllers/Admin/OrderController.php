<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private const STATUSES = [
        'pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded',
    ];

    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('id', $search);
        }

        $orders   = $query->paginate(20)->withQueryString();
        $statuses = self::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'discountUsages.discount']);
        $statuses = self::STATUSES;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', "Order #{$order->id} status updated to " . ucfirst($request->status) . '.');
    }
}
