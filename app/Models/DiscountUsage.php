<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Immutable audit record of a single discount being applied to an order.
 * One order can have multiple DiscountUsage rows (one per applied discount).
 */
class DiscountUsage extends Model
{
    // Only created_at — no updated_at, this is an immutable audit log
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'order_id',
        'discount_id',
        'saved_amount',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'saved_amount' => 'integer', // cents
        'created_at'   => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The user who received this discount.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The order this discount was applied to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * The discount that was applied.
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    // ─── Money Helper ─────────────────────────────────────────────────────────

    public function getSavedAmountFormattedAttribute(): string
    {
        return number_format($this->saved_amount / 100, 2);
    }
}
