<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class UserOfferController extends Controller
{
    /**
     * Display a listing of the public offers.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $offersQuery = Discount::active()->with(['qualifiableProducts', 'qualifiableCategories', 'coupons']);
        
        if ($filter === 'percentage') {
            $offersQuery->where('type', 'percentage');
        } elseif ($filter === 'fixed') {
            $offersQuery->where('type', 'fixed_amount');
        } elseif ($filter === 'coupon') {
            $offersQuery->has('coupons');
        }

        $offers = $offersQuery->latest()->paginate(12)->withQueryString();

        return view('user.offers.index', compact('offers', 'filter'));
    }

    /**
     * Display the specific offer details.
     */
    public function show(Discount $offer)
    {
        // Abort if offer is not active or has ended
        if ($offer->ends_at && $offer->ends_at->isPast()) {
            abort(404, 'This offer has expired.');
        }

        $offer->load(['qualifiableProducts', 'qualifiableCategories', 'coupons']);
        
        return view('user.offers.show', compact('offer'));
    }
}
