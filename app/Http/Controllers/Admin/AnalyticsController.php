<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountUsage;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $query = DiscountUsage::with(['order.user', 'discount'])->latest();

        // Date range filter
        if ($from = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Specific discount filter
        if ($discountId = $request->input('discount_id')) {
            $query->where('discount_id', $discountId);
        }

        $usages = $query->paginate(25)->withQueryString();

        // Summary stats for the filtered period
        $summaryQuery = DiscountUsage::query();
        if ($from) $summaryQuery->whereDate('created_at', '>=', $from);
        if ($to)   $summaryQuery->whereDate('created_at', '<=', $to);
        if ($discountId) $summaryQuery->where('discount_id', $discountId);

        $summary = [
            'total_redemptions' => $summaryQuery->count(),
            'total_saved'       => $summaryQuery->sum('saved_amount'),
        ];

        // Top performing discounts (filtered)
        $topQuery = DiscountUsage::select('discount_id', DB::raw('COUNT(*) as uses'), DB::raw('SUM(saved_amount) as total_saved'))
            ->groupBy('discount_id')
            ->orderByDesc('uses')
            ->with('discount')
            ->limit(5);
        if ($from) $topQuery->whereDate('created_at', '>=', $from);
        if ($to)   $topQuery->whereDate('created_at', '<=', $to);
        $topDiscounts = $topQuery->get();

        // All discounts for the filter dropdown
        $allDiscounts = Discount::orderBy('name')->get();

        return view('admin.analytics.index', compact('usages', 'summary', 'topDiscounts', 'allDiscounts'));
    }

    public function export(Request $request)
    {
        $query = DiscountUsage::with(['order.user', 'discount'])->latest();

        if ($from = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', $to);
        }
        if ($discountId = $request->input('discount_id')) {
            $query->where('discount_id', $discountId);
        }

        $usages = $query->get();

        $csv = "Date,Customer,Order ID,Discount Rule,Amount Saved\n";
        foreach ($usages as $u) {
            $csv .= implode(',', [
                $u->created_at->format('Y-m-d H:i'),
                '"' . ($u->order->user->email ?? 'Guest') . '"',
                '#' . $u->order_id,
                '"' . ($u->discount->name ?? 'N/A') . '"',
                number_format($u->saved_amount / 100, 2),
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="discount_analytics_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
