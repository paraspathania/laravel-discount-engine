<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'subtotal',
        'discount_total',
        'tax_total',
        'grand_total',
        'status',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_phone',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        // All monetary fields are integers (cents)
        'subtotal'       => 'integer',
        'discount_total' => 'integer',
        'tax_total'      => 'integer',
        'grand_total'    => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The customer who placed this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All discounts applied to this order (via usage log).
     */
    public function discountUsages(): HasMany
    {
        return $this->hasMany(DiscountUsage::class);
    }

    /**
     * Items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ─── Status Helpers ───────────────────────────────────────────────────────

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isConfirmed(): bool  { return $this->status === 'confirmed'; }
    public function isCancelled(): bool  { return $this->status === 'cancelled'; }
    public function isDelivered(): bool  { return $this->status === 'delivered'; }

    // ─── Money Helpers ────────────────────────────────────────────────────────

    /**
     * Grand total formatted for display (e.g. "49.99").
     */
    public function getGrandTotalFormattedAttribute(): string
    {
        return number_format($this->grand_total / 100, 2);
    }

    public function getDiscountTotalFormattedAttribute(): string
    {
        return number_format($this->discount_total / 100, 2);
    }
}
