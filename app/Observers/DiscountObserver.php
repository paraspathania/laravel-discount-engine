<?php

namespace App\Observers;

use App\Models\Discount;
use Illuminate\Support\Facades\Cache;

class DiscountObserver
{
    /**
     * Handle the Discount "saved" event (created or updated).
     */
    public function saved(Discount $discount): void
    {
        Cache::forget('active_discounts');
    }

    /**
     * Handle the Discount "deleted" event.
     */
    public function deleted(Discount $discount): void
    {
        Cache::forget('active_discounts');
    }
    
    /**
     * Handle the Discount "restored" event.
     */
    public function restored(Discount $discount): void
    {
        Cache::forget('active_discounts');
    }
}
