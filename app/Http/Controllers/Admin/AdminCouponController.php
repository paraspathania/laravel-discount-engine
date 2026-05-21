<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCouponController extends Controller
{
    public function index(Request $request)
    {
        $activeDiscounts = Discount::active()->orderBy('name')->get();
        
        $search = $request->input('search');
        $discountId = $request->input('discount_id');
        $status = $request->input('status');

        $coupons = Coupon::with('discount')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                      ->orWhereHas('discount', function ($dq) use ($search) {
                          $dq->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($discountId, function ($query, $discountId) {
                $query->where('discount_id', $discountId);
            })
            ->when($status, function ($query, $status) {
                if ($status === 'exhausted') {
                    $query->whereNotNull('max_uses_per_user')
                          ->whereColumn('usage_count', '>=', 'max_uses_per_user');
                } elseif ($status === 'active') {
                    $query->where(function ($q) {
                        $q->whereNull('max_uses_per_user')
                          ->orWhereColumn('usage_count', '<', 'max_uses_per_user');
                    });
                }
            })
            ->latest()
            ->paginate(50)
            ->withQueryString();
        
        return view('admin.coupons.index', compact('activeDiscounts', 'coupons'));
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon code deleted successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'discount_id' => 'required|exists:discounts,id',
            'quantity' => 'required|integer|min:1|max:1000',
            'prefix' => 'nullable|string|max:10',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->input('quantity', 1);
        $prefix = strtoupper($request->input('prefix', ''));
        $maxUses = $request->input('max_uses');

        $couponsToInsert = [];
        for ($i = 0; $i < $quantity; $i++) {
            $code = $prefix . strtoupper(Str::random(8));
            $couponsToInsert[] = [
                'discount_id' => $request->discount_id,
                'code' => $code,
                'max_uses_per_user' => $maxUses,
                'usage_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Coupon::insert($couponsToInsert);

        return back()->with('success', "Successfully generated {$quantity} new coupon codes!");
    }
}
