<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders),
            'message' => 'Orders retrieved successfully.',
            'errors' => null
        ]);
    }

    public function show(Request $request, Order $order)
    {
        // Security check
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Unauthorized access to order.',
                'errors' => 'Forbidden'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order),
            'message' => 'Order details retrieved.',
            'errors' => null
        ]);
    }
}
