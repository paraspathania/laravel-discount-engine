<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use App\Models\Coupon;
use Carbon\Carbon;

class SampleDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Percentage Discount (20% Off)
        $save20 = Discount::create([
            'name' => 'Season Finale Sale',
            'type' => 'percentage',
            'value' => 2000, // 20.00%
            'priority' => 10,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonths(1),
            'is_stackable' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE20',
            'discount_id' => $save20->id,
            'max_uses_per_user' => 1,
        ]);

        // 2. Fixed Amount Discount (₹50 Off)
        $flat50 = Discount::create([
            'name' => 'First Order Special',
            'type' => 'fixed_amount',
            'value' => 5000, // ₹50.00
            'priority' => 20,
            'starts_at' => Carbon::now(),
            'is_stackable' => false,
        ]);

        Coupon::create([
            'code' => 'WELCOME50',
            'discount_id' => $flat50->id,
            'max_uses_per_user' => 1,
        ]);

        // 3. Free Shipping
        $freeShip = Discount::create([
            'name' => 'Flash Free Shipping',
            'type' => 'free_shipping',
            'value' => 0,
            'priority' => 30,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addDays(7),
            'is_stackable' => true,
        ]);

        Coupon::create([
            'code' => 'SHIPFREE',
            'discount_id' => $freeShip->id,
            'max_uses_per_user' => 1,
        ]);
        
        // 4. VIP Bulk Coupons
        $vipDiscount = Discount::create([
            'name' => 'VIP Member Exclusive',
            'type' => 'percentage',
            'value' => 3000, // 30%
            'priority' => 5,
            'starts_at' => Carbon::now(),
            'is_stackable' => true,
        ]);
        
        for($i = 1; $i <= 5; $i++) {
            Coupon::create([
                'code' => 'VIP-' . strtoupper(bin2hex(random_bytes(3))),
                'discount_id' => $vipDiscount->id,
                'max_uses_per_user' => 1,
            ]);
        }
    }
}
