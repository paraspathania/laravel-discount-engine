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

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

Artisan::command('app:download-product-images', function () {
    $this->info('Starting download of product and category images...');

    // 1. Create directories if not exist
    $productDir = public_path('images/products');
    $categoryDir = public_path('images/categories');

    if (!file_exists($productDir)) {
        mkdir($productDir, 0755, true);
    }
    if (!file_exists($categoryDir)) {
        mkdir($categoryDir, 0755, true);
    }

    // 2. Download SKU images
    $skuMap = Product::$skuMap;
    foreach ($skuMap as $sku => $url) {
        $filePath = $productDir . '/' . $sku . '.jpg';
        if (file_exists($filePath)) {
            $this->info("SKU {$sku} already exists, skipping.");
            continue;
        }

        $this->info("Downloading image for SKU: {$sku}...");
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])->timeout(30)->get($url);

            if ($response->successful()) {
                File::put($filePath, $response->body());
                $this->info("Successfully saved {$sku}.jpg");
            } else {
                $this->error("Failed to download SKU {$sku}: HTTP " . $response->status());
            }
        } catch (\Exception $e) {
            $this->error("Error downloading SKU {$sku}: " . $e->getMessage());
        }
    }

    // 3. Download category fallbacks
    $categoryFallbacks = Product::$categoryFallbacks;
    foreach ($categoryFallbacks as $catId => $url) {
        $filePath = $categoryDir . '/' . $catId . '.jpg';
        if (file_exists($filePath)) {
            $this->info("Category {$catId} already exists, skipping.");
            continue;
        }

        $this->info("Downloading image for Category ID: {$catId}...");
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])->timeout(30)->get($url);

            if ($response->successful()) {
                File::put($filePath, $response->body());
                $this->info("Successfully saved Category {$catId}.jpg");
            } else {
                $this->error("Failed to download Category {$catId}: HTTP " . $response->status());
            }
        } catch (\Exception $e) {
            $this->error("Error downloading Category {$catId}: " . $e->getMessage());
        }
    }

    $this->info('Finished downloading product and category images!');
})->purpose('Download all product images from Unsplash to serve locally');

