<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FinalizeOrderJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Layer 3 — Unique Jobs with Laravel Horizon
     * Ensure only one finalization job can run for a specific order.
     * Returns the order ID as the unique identifier.
     */
    public function uniqueId(): string
    {
        return (string) $this->orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Execute heavy finalization tasks here
        // E.g., Generating PDF invoices, sending order confirmation emails, 
        // pinging warehouse APIs, clearing user cart sessions.
        
        Log::info("Running heavy background finalization for Order ID: {$this->orderId}");
    }
}
