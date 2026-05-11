<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'price', 'stock', 'category_id'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer', // always cents
        'stock' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Discounts scoped specifically to this product (via discount_qualifiers).
     */
    public function discounts(): MorphToMany
    {
        return $this->morphedByMany(
            Discount::class,
            'qualifiable',
            'discount_qualifiers',
            'qualifiable_id',
            'discount_id'
        );
    }

    // ─── Price Helpers ────────────────────────────────────────────────────────

    /**
     * Price formatted as a decimal string for display (e.g. "19.99").
     */
    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->price / 100, 2);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
