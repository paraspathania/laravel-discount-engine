<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Price stored as INTEGER in cents to avoid floating-point rounding issues.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');

            // Stored in cents (e.g. 1999 = $19.99) — never use DECIMAL for money
            $table->unsignedInteger('price');

            $table->unsignedInteger('stock')->default(0);

            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();

            $table->timestamps();

            // Index for category-filtered product listings
            $table->index('category_id');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
