<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountUsage;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Stat Cards ---
        $stats = [
            'active_discounts' => Discount::active()->count(),
            'coupons_used'     => \App\Models\DiscountUsage::whereDate('created_at', today())->count(),
            'total_savings'    => \App\Models\DiscountUsage::sum('saved_amount'),   // cents
            'orders_today'     => Order::whereDate('created_at', today())->count(),
        ];

        // --- Recent Redemptions (last 10) ---
        $recentRedemptions = DiscountUsage::with(['order.user', 'discount'])
            ->latest()
            ->limit(10)
            ->get();

        // --- Top Performing Discounts ---
        $topDiscounts = Discount::withCount('usages')
            ->withSum('usages', 'saved_amount')
            ->having('usages_count', '>', 0)
            ->orderByDesc('usages_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRedemptions', 'topDiscounts'));
    }
}
