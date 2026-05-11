<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates the creation of a new Discount.
 *
 * Discount value encoding:
 *   type = 'percentage' → value is basis points (1–10000, i.e. 0.01%–100%)
 *   type = 'fixed_amount' → value is cents (minimum 1 cent)
 *
 * Date rules:
 *   starts_at can be any future or current datetime (nullable = always active)
 *   ends_at must be after starts_at
 */
class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Gate checked at route level (admin middleware) — allow all here
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Human-readable label for this discount
            'name' => ['required', 'string', 'max:255'],

            // Strategy discriminator: must match a known type
            'type' => [
                'required',
                'string',
                Rule::in(['percentage', 'fixed_amount', 'free_shipping', 'bogo']),
            ],

            // Integer value:
            //   percentage  → basis points, max 10000 (= 100%)
            //   fixed/other → cents, min 1
            'value' => ['required', 'integer', 'min:1', 'max:10000000'],

            // Lower number = applied first; default 100 in DB
            'priority' => ['sometimes', 'integer', 'min:1', 'max:9999'],

            // Validity window — both nullable (null = no restriction)
            'starts_at' => ['nullable', 'date', 'after_or_equal:today'],
            'ends_at'   => ['nullable', 'date', 'after:starts_at'],

            // NULL = unlimited; integer >= 1 if set
            'usage_limit' => ['nullable', 'integer', 'min:1'],

            // Whether this discount can stack with others
            'is_stackable' => ['sometimes', 'boolean'],

            // Optional: scope to specific product/category IDs
            'product_ids'  => ['sometimes', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],

            'category_ids'  => ['sometimes', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.in'          => 'Discount type must be one of: percentage, fixed_amount, free_shipping, bogo.',
            'value.min'        => 'Discount value must be at least 1 (1 cent or 1 basis point).',
            'ends_at.after'    => 'The end date must be after the start date.',
            'starts_at.after_or_equal' => 'The start date cannot be in the past.',
        ];
    }

    /**
     * Prepare the data for validation.
     * Cast boolean field from string (HTML form submit sends "1"/"0").
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_stackable')) {
            $this->merge([
                'is_stackable' => filter_var($this->is_stackable, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
