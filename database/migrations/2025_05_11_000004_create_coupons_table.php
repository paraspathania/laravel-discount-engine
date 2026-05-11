<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Coupons are the user-facing redemption codes that link to a Discount.
     * max_uses_per_user — NULL means a single user can use it unlimited times.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // The redemption code entered by the customer (e.g. "SUMMER20")
            $table->string('code')->unique();

            $table->foreignId('discount_id')
                  ->constrained('discounts')
                  ->cascadeOnDelete();

            $table->unsignedInteger('usage_count')->default(0);

            // NULL = unlimited per-user usage
            $table->unsignedInteger('max_uses_per_user')->nullable();

            $table->timestamps();

            // Fast lookup during checkout validation
            $table->index('code');
            $table->index('discount_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
