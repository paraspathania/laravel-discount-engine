<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        $newSubtotal = $this->subtotal - $this->discount_total;
        
        return [
            'id' => $this->id,
            'status' => $this->status,
            'breakdown' => [
                'original_subtotal' => $this->subtotal,
                'original_subtotal_formatted' => '$' . number_format($this->subtotal / 100, 2),
                
                'discount_amount' => $this->discount_total,
                'discount_amount_formatted' => '-$' . number_format($this->discount_total / 100, 2),
                
                'new_subtotal' => $newSubtotal,
                'new_subtotal_formatted' => '$' . number_format($newSubtotal / 100, 2),
                
                'tax_amount' => $this->tax_total,
                'tax_amount_formatted' => '$' . number_format($this->tax_total / 100, 2),
                
                'grand_total' => $this->grand_total,
                'grand_total_formatted' => '$' . number_format($this->grand_total / 100, 2),
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
