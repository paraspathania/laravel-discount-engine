<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ensure an Admin exists
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 2. Create Categories
        $electronics = Category::firstOrCreate(['name' => 'Electronics']);
        $clothing = Category::firstOrCreate(['name' => 'Clothing']);
        $home = Category::firstOrCreate(['name' => 'Home & Garden']);

        // 3. Create Demo Products
        $products = [
            ['name' => 'Wireless Headphones', 'sku' => 'ELEC-001', 'price' => 19900, 'stock' => 50, 'category_id' => $electronics->id],
            ['name' => '4K Smart TV', 'sku' => 'ELEC-002', 'price' => 79900, 'stock' => 15, 'category_id' => $electronics->id],
            ['name' => 'Mechanical Keyboard', 'sku' => 'ELEC-003', 'price' => 12900, 'stock' => 30, 'category_id' => $electronics->id],
            
            ['name' => 'Cotton T-Shirt', 'sku' => 'CLOT-001', 'price' => 2500, 'stock' => 100, 'category_id' => $clothing->id],
            ['name' => 'Denim Jeans', 'sku' => 'CLOT-002', 'price' => 6500, 'stock' => 80, 'category_id' => $clothing->id],
            ['name' => 'Winter Jacket', 'sku' => 'CLOT-003', 'price' => 15000, 'stock' => 40, 'category_id' => $clothing->id],
            
            ['name' => 'Coffee Maker', 'sku' => 'HOME-001', 'price' => 8500, 'stock' => 25, 'category_id' => $home->id],
            ['name' => 'Blender', 'sku' => 'HOME-002', 'price' => 4500, 'stock' => 60, 'category_id' => $home->id],
            ['name' => 'Vacuum Cleaner', 'sku' => 'HOME-003', 'price' => 25000, 'stock' => 20, 'category_id' => $home->id],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['sku' => $p['sku']], $p);
        }

        // 4. Create an Active Sitewide Offer
        Discount::firstOrCreate(
            ['name' => 'Grand Opening Sale'],
            [
                'type' => 'percentage',
                'value' => 1500, // 15%
                'priority' => 100,
                'is_stackable' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(30),
            ]
        );
    }
}
