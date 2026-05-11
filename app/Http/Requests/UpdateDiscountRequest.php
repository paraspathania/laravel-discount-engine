<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates partial or full updates to an existing Discount.
 *
 * Key difference from StoreDiscountRequest:
 *  - All fields use 'sometimes' — only validated if present in the request.
 *  - starts_at no longer requires after_or_equal:today (admin may correct past dates).
 *  - ends_at validated as after:starts_at if either is provided.
 */
class UpdateDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],

            'type' => [
                'sometimes',
                'string',
                Rule::in(['percentage', 'fixed_amount', 'free_shipping', 'bogo']),
            ],

            'value' => ['sometimes', 'integer', 'min:1', 'max:10000000'],

            'priority' => ['sometimes', 'integer', 'min:1', 'max:9999'],

            // On update, allow setting past dates (e.g. admin correcting a typo)
            'starts_at' => ['sometimes', 'nullable', 'date'],

            // ends_at must still be after starts_at (use incoming or current value)
            'ends_at' => [
                'sometimes',
                'nullable',
                'date',
                // If starts_at is in request use it; else fall back to existing record
                function ($attribute, $value, $fail) {
                    $startsAt = $this->input('starts_at')
                        ?? optional($this->route('discount'))->starts_at?->toDateTimeString();

                    if ($startsAt && $value && strtotime($value) <= strtotime($startsAt)) {
                        $fail('The end date must be after the start date.');
                    }
                },
            ],

            'usage_limit' => ['sometimes', 'nullable', 'integer', 'min:1'],

            'is_stackable' => ['sometimes', 'boolean'],

            // Qualifier arrays — replaces all existing qualifiers on update
            'product_ids'   => ['sometimes', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],

            'category_ids'   => ['sometimes', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.in'       => 'Discount type must be one of: percentage, fixed_amount, free_shipping, bogo.',
            'value.min'     => 'Discount value must be at least 1.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_stackable')) {
            $this->merge([
                'is_stackable' => filter_var($this->is_stackable, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
