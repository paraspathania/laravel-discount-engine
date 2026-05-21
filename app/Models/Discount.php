<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
        'priority',
        'starts_at',
        'ends_at',
        'usage_limit',
        'usage_count',
        'is_stackable',
        'minimum_order_value',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'value'        => 'integer',
        'priority'     => 'integer',
        'usage_limit'  => 'integer',
        'usage_count'  => 'integer',
        'is_stackable' => 'boolean',
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'minimum_order_value' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The coupons (redemption codes) attached to this discount, if any.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * All usage records for this discount (audit log).
     */
    public function usages(): HasMany
    {
        return $this->hasMany(DiscountUsage::class);
    }

    /**
     * Products this discount is scoped to.
     * Empty set = applies to ALL products (site-wide).
     */
    public function qualifiableProducts(): MorphToMany
    {
        return $this->morphToMany(
            Product::class,
            'qualifiable',
            'discount_qualifiers',
            'discount_id',
            'qualifiable_id'
        );
    }

    /**
     * Categories this discount is scoped to.
     * Empty set = applies to ALL categories.
     */
    public function qualifiableCategories(): MorphToMany
    {
        return $this->morphToMany(
            Category::class,
            'qualifiable',
            'discount_qualifiers',
            'discount_id',
            'qualifiable_id'
        );
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Only discounts currently within their valid date window.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
        })->where(function ($q) {
            $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
        });
    }

    /**
     * Only stackable discounts.
     */
    public function scopeStackable($query)
    {
        return $query->where('is_stackable', true);
    }

    /**
     * Ordered by priority ascending (lower number = applied first).
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Check if this discount has remaining uses available.
     */
    public function hasUsesRemaining(): bool
    {
        if (is_null($this->usage_limit)) {
            return true;
        }

        return $this->usage_count < $this->usage_limit;
    }

    /**
     * Check if this discount is within its validity window.
     */
    public function isActive(): bool
    {
        $now = now();

        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->lt($now)) {
            return false;
        }

        return true;
    }

    /**
     * True when this discount is site-wide (no qualifier constraints).
     */
    public function isSiteWide(): bool
    {
        return $this->qualifiableProducts()->doesntExist()
            && $this->qualifiableCategories()->doesntExist();
    }
}
