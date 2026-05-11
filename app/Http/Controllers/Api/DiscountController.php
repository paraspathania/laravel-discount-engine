<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Http\Resources\OfferResource;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * List active public offers (sitewide auto-discounts).
     */
    public function index(Request $request)
    {
        $offers = Discount::active()
            ->stackable()
            ->whereNull('usage_limit') // Optionally only show unlimited public offers
            ->get()
            ->filter(fn($d) => $d->isSiteWide()); // Only public sitewide

        return response()->json([
            'success' => true,
            'data' => OfferResource::collection($offers),
            'message' => 'Active public offers retrieved.',
            'errors' => null
        ]);
    }
}
