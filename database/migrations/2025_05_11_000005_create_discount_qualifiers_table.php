<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Polymorphic pivot table that scopes discounts to specific Products or Categories.
     * qualifiable_type = 'App\Models\Product' | 'App\Models\Category'
     * qualifiable_id   = the related model's primary key
     *
     * Used via Discount::morphToMany(Product::class, 'qualifiable') etc.
     * An empty qualifier set means the discount applies site-wide.
     */
    public function up(): void
    {
        Schema::create('discount_qualifiers', function (Blueprint $table) {
            // No auto-increment id; composite key is sufficient
            $table->foreignId('discount_id')
                  ->constrained('discounts')
                  ->cascadeOnDelete();

            // Morph columns — Laravel convention for polymorphic relations
            $table->string('qualifiable_type');
            $table->unsignedBigInteger('qualifiable_id');

            // Composite primary key prevents duplicate qualifier entries
            $table->primary(['discount_id', 'qualifiable_type', 'qualifiable_id']);

            // Index for reverse lookup: "which discounts apply to this product?"
            $table->index(['qualifiable_type', 'qualifiable_id'], 'qualifiable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_qualifiers');
    }
};
