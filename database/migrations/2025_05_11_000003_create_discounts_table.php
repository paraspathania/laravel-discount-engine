<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * `type`     — discriminator for the Strategy pattern:
     *              'percentage', 'fixed_amount', 'bogo', 'free_shipping', etc.
     * `value`    — stored as INTEGER cents for fixed; as basis points (1% = 100) for percent.
     * `priority` — lower number = applied first when stacking.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Strategy discriminator — maps to a concrete DiscountStrategy class
            $table->string('type');

            // For percentage discounts: value in basis points (500 = 5%)
            // For fixed discounts: value in cents (1000 = $10.00)
            $table->unsignedInteger('value');

            // Lower priority number = evaluated/applied first
            $table->unsignedSmallInteger('priority')->default(100);

            // Validity window — nullable means always active
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // NULL means unlimited uses
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_count')->default(0);

            // Whether this discount can stack with others
            $table->boolean('is_stackable')->default(false);

            $table->timestamps();

            // Indexes for common filter queries
            $table->index('type');
            $table->index('priority');
            $table->index(['starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
