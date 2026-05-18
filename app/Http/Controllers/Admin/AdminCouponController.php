<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCouponController extends Controller
{
    public function index()
    {
        $activeDiscounts = Discount::active()->orderBy('name')->get();
        $coupons = Coupon::with('discount')->latest()->paginate(50);
        
        return view('admin.coupons.index', compact('activeDiscounts', 'coupons'));
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
