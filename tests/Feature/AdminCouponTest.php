<?php

use App\Models\User;
use App\Models\Discount;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminUser = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
});

it('allows admin to view coupons page and lists coupons', function () {
    $discount = Discount::create([
        'name' => 'Winter Sale',
        'type' => 'percentage',
        'value' => 2000,
    ]);

    $coupon = Coupon::create([
        'code' => 'WINTER20',
        'discount_id' => $discount->id,
        'max_uses_per_user' => 2,
        'usage_count' => 0,
    ]);

    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.coupons.index'));

    $response->assertOk()
        ->assertSee('WINTER20')
        ->assertSee('Winter Sale');
});

it('allows admin to search coupons by code or discount name', function () {
    $discount1 = Discount::create(['name' => 'Target Discount One', 'type' => 'fixed_amount', 'value' => 500]);
    $discount2 = Discount::create(['name' => 'Target Discount Two', 'type' => 'fixed_amount', 'value' => 1000]);

    Coupon::create(['code' => 'CODEONE', 'discount_id' => $discount1->id]);
    Coupon::create(['code' => 'CODETWO', 'discount_id' => $discount2->id]);

    // Search by code
    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.coupons.index', ['search' => 'CODEONE']));

    $response->assertSee('CODEONE')
        ->assertDontSee('CODETWO');

    // Search by discount name
    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.coupons.index', ['search' => 'Target Discount Two']));

    $response->assertSee('CODETWO')
        ->assertDontSee('CODEONE');
});

it('allows admin to filter coupons by discount id', function () {
    $discount1 = Discount::create(['name' => 'Discount One', 'type' => 'fixed_amount', 'value' => 500]);
    $discount2 = Discount::create(['name' => 'Discount Two', 'type' => 'fixed_amount', 'value' => 1000]);

    Coupon::create(['code' => 'CODEONE', 'discount_id' => $discount1->id]);
    Coupon::create(['code' => 'CODETWO', 'discount_id' => $discount2->id]);

    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.coupons.index', ['discount_id' => $discount1->id]));

    $response->assertSee('CODEONE')
        ->assertDontSee('CODETWO');
});

it('allows admin to filter coupons by active/exhausted status', function () {
    $discount = Discount::create(['name' => 'General Discount', 'type' => 'fixed_amount', 'value' => 500]);

    // Active (usage_count < max_uses_per_user)
    Coupon::create(['code' => 'ACTIVECODE', 'discount_id' => $discount->id, 'max_uses_per_user' => 5, 'usage_count' => 2]);
    // Exhausted (usage_count >= max_uses_per_user)
    Coupon::create(['code' => 'EXHAUSTEDCODE', 'discount_id' => $discount->id, 'max_uses_per_user' => 2, 'usage_count' => 2]);

    // Filter Active
    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.coupons.index', ['status' => 'active']));

    $response->assertSee('ACTIVECODE')
        ->assertDontSee('EXHAUSTEDCODE');

    // Filter Exhausted
    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.coupons.index', ['status' => 'exhausted']));

    $response->assertSee('EXHAUSTEDCODE')
        ->assertDontSee('ACTIVECODE');
});

it('allows admin to delete/revoke a coupon code', function () {
    $discount = Discount::create(['name' => 'Discount', 'type' => 'fixed_amount', 'value' => 500]);
    $coupon = Coupon::create(['code' => 'DELETECODE', 'discount_id' => $discount->id]);

    $this->assertDatabaseHas('coupons', ['id' => $coupon->id]);

    $response = $this->actingAs($this->adminUser)
        ->delete(route('admin.coupons.destroy', $coupon));

    $response->assertRedirect();
    $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
});
