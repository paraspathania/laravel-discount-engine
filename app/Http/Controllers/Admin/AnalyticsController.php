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

        // Average saved calculations
        $summary['avg_saved'] = $summary['total_redemptions'] > 0 
            ? ($summary['total_saved'] / $summary['total_redemptions']) 
            : 0;

        // Top performing discounts (filtered)
        $topQuery = DiscountUsage::select('discount_id', DB::raw('COUNT(*) as uses'), DB::raw('SUM(saved_amount) as total_saved'))
            ->groupBy('discount_id')
            ->orderByDesc('uses')
            ->with('discount')
            ->limit(5);
        if ($from) $topQuery->whereDate('created_at', '>=', $from);
        if ($to)   $topQuery->whereDate('created_at', '<=', $to);
        $topDiscounts = $topQuery->get();

        // Daily redemption trend (filtered) grouped by date
        $trendQuery = DiscountUsage::select(
            DB::raw("DATE(created_at) as date_label"),
            DB::raw("COUNT(*) as uses"),
            DB::raw("SUM(saved_amount) as total_saved")
        )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy("date_label", "asc");

        if ($from) $trendQuery->whereDate('created_at', '>=', $from);
        if ($to)   $trendQuery->whereDate('created_at', '<=', $to);
        if ($discountId) $trendQuery->where('discount_id', $discountId);

        $trendData = $trendQuery->get();

        // Prepare trend data for the chart views
        $chartLabels = $trendData->pluck('date_label')->toArray();
        $chartUses   = $trendData->pluck('uses')->toArray();
        $chartSaved  = $trendData->map(fn($t) => $t->total_saved / 100)->toArray();

        // All discounts for the filter dropdown
        $allDiscounts = Discount::orderBy('name')->get();

        return view('admin.analytics.index', compact(
            'usages', 
            'summary', 
            'topDiscounts', 
            'allDiscounts', 
            'chartLabels', 
            'chartUses', 
            'chartSaved'
        ));
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

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="discount_analytics_' . now()->format('Y-m-d') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() use ($usages) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Customer', 'Order ID', 'Discount Rule', 'Amount Saved (INR)']);

            foreach ($usages as $u) {
                fputcsv($file, [
                    $u->created_at->format('Y-m-d H:i'),
                    $u->order->user->email ?? 'Guest',
                    '#' . $u->order_id,
                    $u->discount->name ?? 'N/A',
                    number_format($u->saved_amount / 100, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
