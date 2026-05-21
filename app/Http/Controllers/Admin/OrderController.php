<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', $search);
            });
        }

        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $orders = $query->paginate(20)->withQueryString();
        $statuses = self::STATUSES;

        // Compute summary metrics matching search + date filters
        $baseStatsQuery = Order::query();
        if ($search) {
            $baseStatsQuery->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', $search);
            });
        }
        if ($fromDate) {
            $baseStatsQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $baseStatsQuery->whereDate('created_at', '<=', $toDate);
        }

        $stats = [
            'total_orders'    => (clone $baseStatsQuery)->count(),
            'total_revenue'   => (clone $baseStatsQuery)->whereNotIn('status', ['cancelled', 'refunded'])->sum('grand_total'),
            'pending_count'   => (clone $baseStatsQuery)->where('status', 'pending')->count(),
            'delivered_count' => (clone $baseStatsQuery)->where('status', 'delivered')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statuses', 'stats'));
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

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('success', "Order #{$order->id} status is already " . ucfirst($newStatus) . '.');
        }

        try {
            DB::transaction(function () use ($order, $oldStatus, $newStatus) {
                // Ensure items are loaded
                $order->load('items.product');

                $isOldCancelledOrRefunded = in_array($oldStatus, ['cancelled', 'refunded']);
                $isNewCancelledOrRefunded = in_array($newStatus, ['cancelled', 'refunded']);

                if (!$isOldCancelledOrRefunded && $isNewCancelledOrRefunded) {
                    // Restore stock
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            DB::table('products')
                                ->where('id', $item->product_id)
                                ->increment('stock', $item->quantity);
                        }
                    }
                } elseif ($isOldCancelledOrRefunded && !$isNewCancelledOrRefunded) {
                    // Deduct stock again when moving back to active status
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $updated = DB::table('products')
                                ->where('id', $item->product_id)
                                ->where('stock', '>=', $item->quantity)
                                ->decrement('stock', $item->quantity);

                            if ($updated === 0) {
                                throw new \Exception("Cannot update order status. Product [{$item->product->name}] has insufficient stock (required: {$item->quantity}, available: {$item->product->stock}).");
                            }
                        }
                    }
                }

                $order->update(['status' => $newStatus]);
            });

            return back()->with('success', "Order #{$order->id} status updated to " . ucfirst($newStatus) . '.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
