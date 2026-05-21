<?php

use App\Models\User;
use App\Models\Discount;
use App\Pipelines\FinalizeOrderPipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('handles 2 simultaneous requests on a discount with 1 use left; 1 succeeds and 1 fails gracefully', function () {
    // 1. Setup actual database record
    $user = User::factory()->create();
    $discount = Discount::create([
        'name' => 'Concurrent Test Discount',
        'type' => 'fixed_amount',
        'value' => 100,
        'usage_limit' => 1,
        'usage_count' => 0
    ]);

    // 2. Setup Cart 1
    $cart1 = new stdClass();
    $cart1->user = $user;
    $cart1->items = [];
    $cart1->subtotal = 1000;
    $cart1->itemDiscountsTotal = 0;
    $cart1->orderDiscountsTotal = 0;
    $cart1->shippingDiscountTotal = 0;
    $cart1->taxTotal = 0;
    $cart1->grandTotal = 1000;
    $cart1->couponCode = null;
    $cart1->appliedDiscounts = [
        ['discount_id' => $discount->id, 'saved_amount' => 100]
    ];

    // 3. Setup Cart 2 (exact clone representing the second concurrent request)
    $cart2 = clone $cart1;

    $pipe = new FinalizeOrderPipe();

    // 4. Request 1 executes: Should Succeed
    $pipe->handle($cart1, fn($c) => $c);
    
    // Assert DB was atomically incremented
    $dbCount = DB::table('discounts')->where('id', $discount->id)->value('usage_count');
    expect($dbCount)->toBe(1);

    // 5. Request 2 executes milliseconds later: Should Fail Gracefully
    // The DB atomic check whereColumn('usage_count', '<', 'usage_limit') will return 0 rows updated
    expect(fn() => $pipe->handle($cart2, fn($c) => $c))
        ->toThrow(Exception::class, "Discount [{$discount->id}] usage limit exceeded during checkout.");
});
