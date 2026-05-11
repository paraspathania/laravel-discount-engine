<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_id',
        'usage_count',
        'max_uses_per_user',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'usage_count'      => 'integer',
        'max_uses_per_user' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The discount this coupon code activates.
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Check how many times a specific user has used this coupon.
     */
    public function usageCountByUser(int $userId): int
    {
        return DiscountUsage::where('discount_id', $this->discount_id)
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Check if this user can still use this coupon.
     */
    public function isUsableByUser(int $userId): bool
    {
        if (is_null($this->max_uses_per_user)) {
            return true;
        }

        return $this->usageCountByUser($userId) < $this->max_uses_per_user;
    }
}
