<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ALL monetary columns stored as INTEGER cents:
     *   subtotal      — cart total before any discount
     *   discount_total — total amount saved by discounts
     *   tax_total      — tax amount applied after discount
     *   grand_total    — final amount charged (subtotal - discount_total + tax_total)
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete(); // Prevent accidental user deletion with orders

            // All monetary values in cents
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('discount_total')->default(0);
            $table->unsignedInteger('tax_total')->default(0);
            $table->unsignedInteger('grand_total');

            $table->string('status')->default('pending');
            // Possible statuses: pending, confirmed, processing, shipped, delivered, cancelled, refunded

            $table->timestamps();

            // Index for user order history queries
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
