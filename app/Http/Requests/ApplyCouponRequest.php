<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates a customer's coupon code submission at checkout.
 *
 * Validation layers:
 *  1. Format check  — regex ensures only alphanumeric + dash/underscore, 3–32 chars
 *  2. Existence check — code must exist in the coupons table
 *  3. Business rule checks are performed in CouponService (not here), e.g.:
 *     - Coupon linked discount is active
 *     - Per-user usage limit not exceeded
 *     - Global usage_limit not exceeded
 */
class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Must be authenticated (enforced by route middleware)
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Code format: uppercase/lowercase letters, digits, dashes, underscores
            // Length: 3–32 characters
            'code' => [
                'required',
                'string',
                'min:3',
                'max:32',
                'regex:/^[A-Za-z0-9_\-]+$/',
                'exists:coupons,code',  // Must match a real coupon record
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Please enter a coupon code.',
            'code.regex'    => 'Coupon codes may only contain letters, numbers, dashes, and underscores.',
            'code.min'      => 'Coupon code must be at least 3 characters.',
            'code.max'      => 'Coupon code must not exceed 32 characters.',
            'code.exists'   => 'This coupon code is invalid or does not exist.',
        ];
    }

    /**
     * Normalise the code to uppercase before validation.
     * Allows customers to enter "summer20" and match "SUMMER20".
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim($this->input('code', ''))),
        ]);
    }
}
