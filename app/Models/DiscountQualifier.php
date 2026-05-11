<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * Represents a single row in the discount_qualifiers polymorphic pivot table.
 * Using MorphPivot allows accessing this record directly if needed for auditing.
 */
class DiscountQualifier extends MorphPivot
{
    protected $table = 'discount_qualifiers';

    // No timestamps on this table
    public $timestamps = false;

    // Composite primary key — Laravel pivot doesn't need auto-increment id
    public $incrementing = false;

    protected $fillable = [
        'discount_id',
        'qualifiable_type',
        'qualifiable_id',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
}
