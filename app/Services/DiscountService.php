<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Support\Facades\Cache;

class DiscountService
{
    /**
     * Retrieve all active discounts, highly cached for performance.
     * Caches the database query for 5 minutes (300 seconds).
     * Automatically invalidated by DiscountObserver on CRUD operations.
     */
    public function getActiveDiscounts()
    {
        return Cache::remember('active_discounts', 300, function () {
            // Eager load polymorphic relationships to prevent N+1 queries during checkout
            return Discount::active()
                ->with(['qualifiableProducts', 'qualifiableCategories'])
                ->get();
        });
    }
}
