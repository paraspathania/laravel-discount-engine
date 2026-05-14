<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get recent orders
        $recentOrders = $user->orders()->with('items')->latest()->take(5)->get();
        
        // Get active coupons the user hasn't exhausted
        // Note: In a real large-scale app, we might want to only show global coupons, 
        // or specifically assigned ones, but for now we'll show any active coupon.
        // We filter out any coupons that have reached their max usage limit for this user.
        $coupons = Coupon::whereHas('discount', function ($query) {
                $query->active();
            })
            ->with('discount')
            ->get()
            ->filter(function ($coupon) use ($user) {
                return $coupon->isUsableByUser($user->id);
            })
            ->take(12);

        return view('user.dashboard.index', compact('recentOrders', 'coupons'));
    }
}
