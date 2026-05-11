<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Immutable audit log — records every discount applied to every order.
     * No `updated_at` since usage records should never be modified after creation.
     * Used for:
     *   - Per-user usage limit enforcement (max_uses_per_user)
     *   - Global usage counting (usage_count on discounts/coupons)
     *   - Reporting: savings by discount, customer, time period
     */
    public function up(): void
    {
        Schema::create('discount_usage', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete(); // Remove usage record if order is cancelled/deleted

            $table->foreignId('discount_id')
                  ->constrained('discounts')
                  ->restrictOnDelete(); // Keep discount records for historical reporting

            // Amount saved by this specific discount on this order, in cents
            $table->unsignedInteger('saved_amount');

            // Immutable audit timestamp — no updated_at
            $table->timestamp('created_at')->useCurrent();

            // Compound indexes for usage limit enforcement queries
            $table->index('discount_id');
            $table->index('user_id');
            $table->index('order_id');
            $table->index(['user_id', 'discount_id'], 'user_discount_usage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_usage');
    }
};
