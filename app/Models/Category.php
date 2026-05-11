<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id'];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Parent category (null for root-level categories).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Direct child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Recursive children (all descendants at any depth).
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Products belonging to this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Discounts that are scoped to this category (via discount_qualifiers).
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

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }
}
