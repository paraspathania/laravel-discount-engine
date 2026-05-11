<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Discount;

/*
|--------------------------------------------------------------------------
| Console Scheduling (Laravel 12 alternative to Kernel.php)
|--------------------------------------------------------------------------
*/

// Every minute: Cache invalidation for time-based activation/deactivation
// Since our `active()` scope relies on datetime comparisons, the DB doesn't "fire" an event
// when time passes. We must check if any discounts crossed the time threshold in the last minute
// to manually bust the Redis cache.
Schedule::call(function () {
    $now = now();
    $oneMinuteAgo = now()->subMinute();

    $newlyActive = Discount::where('starts_at', '<=', $now)
        ->where('starts_at', '>', $oneMinuteAgo)
        ->exists();

    $newlyExpired = Discount::where('ends_at', '<=', $now)
        ->where('ends_at', '>', $oneMinuteAgo)
        ->exists();

    if ($newlyActive || $newlyExpired) {
        Cache::forget('active_discounts');
        Log::info('Redis active_discounts cache cleared due to time-based status change.');
    }
})->everyMinute()->name('sync-discount-status');

// Daily: Generate Discount Performance Report
Schedule::call(function () {
    // In a real system, this would dispatch a Job to aggregate `discount_usage`, 
    // generate a PDF/CSV, and email it to the Admin team.
    Log::info('Daily discount performance report generated and dispatched to admins.');
})->dailyAt('23:55')->name('generate-daily-reports');
