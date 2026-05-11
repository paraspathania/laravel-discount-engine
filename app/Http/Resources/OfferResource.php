<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            // Format basis points for percentage, or cents for fixed
            'value_formatted' => $this->type === 'percentage' 
                ? ($this->value / 100) . '%' 
                : '$' . number_format($this->value / 100, 2),
            'priority' => $this->priority,
            'starts_at' => $this->starts_at ? $this->starts_at->toIso8601String() : null,
            'ends_at' => $this->ends_at ? $this->ends_at->toIso8601String() : null,
        ];
    }
}
