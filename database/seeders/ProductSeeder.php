<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ── Categories ────────────────────────────────────────────────
        $electronics  = Category::firstOrCreate(['name' => 'Electronics']);
        $clothing     = Category::firstOrCreate(['name' => 'Clothing']);
        $home         = Category::firstOrCreate(['name' => 'Home & Garden']);
        $sports       = Category::firstOrCreate(['name' => 'Sports & Fitness']);
        $beauty       = Category::firstOrCreate(['name' => 'Beauty & Personal Care']);
        $books        = Category::firstOrCreate(['name' => 'Books & Stationery']);
        $food         = Category::firstOrCreate(['name' => 'Food & Beverages']);
        $toys         = Category::firstOrCreate(['name' => 'Toys & Games']);

        // ── Products (price in cents) ─────────────────────────────────
        $products = [

            // ── Electronics (existing + new) ──────────────────────────
            ['sku' => 'ELEC-001', 'name' => 'Wireless Headphones',       'price' =>  19900, 'stock' =>  50, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-002', 'name' => '4K Smart TV 55"',           'price' =>  79900, 'stock' =>  15, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-003', 'name' => 'Mechanical Keyboard',       'price' =>  12900, 'stock' =>  30, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-004', 'name' => 'Smartphone Pro Max',        'price' => 109900, 'stock' =>  25, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-005', 'name' => 'Noise-Cancelling Earbuds',  'price' =>  15900, 'stock' =>  60, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-006', 'name' => 'Wireless Gaming Mouse',     'price' =>   6900, 'stock' =>  45, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-007', 'name' => '27" 4K Monitor',            'price' =>  45900, 'stock' =>  12, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-008', 'name' => 'USB-C Hub 7-in-1',          'price' =>   3500, 'stock' => 100, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-009', 'name' => 'Portable Bluetooth Speaker','price' =>   9900, 'stock' =>  40, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-010', 'name' => 'Smart Watch Series X',      'price' =>  29900, 'stock' =>  35, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-011', 'name' => 'Laptop Ultrabook 14"',      'price' =>  89900, 'stock' =>  10, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-012', 'name' => 'Webcam 4K HD',              'price' =>   7900, 'stock' =>  55, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-013', 'name' => 'Portable Power Bank 20000mAh', 'price' => 4500, 'stock' => 80, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-014', 'name' => 'Smart Home Hub',            'price' =>  11900, 'stock' =>  20, 'category_id' => $electronics->id],
            ['sku' => 'ELEC-015', 'name' => 'Tablet 10.5" 128GB',        'price' =>  34900, 'stock' =>  18, 'category_id' => $electronics->id],

            // ── Clothing ──────────────────────────────────────────────
            ['sku' => 'CLOT-001', 'name' => 'Classic Cotton T-Shirt',    'price' =>   2500, 'stock' => 100, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-002', 'name' => 'Slim Fit Denim Jeans',      'price' =>   6500, 'stock' =>  80, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-003', 'name' => 'Premium Winter Jacket',     'price' =>  15000, 'stock' =>  40, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-004', 'name' => 'Formal Oxford Shirt',       'price' =>   4500, 'stock' =>  70, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-005', 'name' => 'Casual Hoodie',             'price' =>   5500, 'stock' =>  90, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-006', 'name' => 'Athletic Track Pants',      'price' =>   3800, 'stock' =>  85, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-007', 'name' => 'Floral Summer Dress',       'price' =>   5000, 'stock' =>  60, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-008', 'name' => 'Leather Sneakers',          'price' =>   8900, 'stock' =>  55, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-009', 'name' => 'Wool Blend Overcoat',       'price' =>  22000, 'stock' =>  25, 'category_id' => $clothing->id],
            ['sku' => 'CLOT-010', 'name' => 'Polo T-Shirt (Pack of 2)',  'price' =>   4200, 'stock' => 120, 'category_id' => $clothing->id],

            // ── Home & Garden ─────────────────────────────────────────
            ['sku' => 'HOME-001', 'name' => 'French Press Coffee Maker', 'price' =>   8500, 'stock' =>  25, 'category_id' => $home->id],
            ['sku' => 'HOME-002', 'name' => 'High-Speed Blender',        'price' =>   4500, 'stock' =>  60, 'category_id' => $home->id],
            ['sku' => 'HOME-003', 'name' => 'Robot Vacuum Cleaner',      'price' =>  25000, 'stock' =>  20, 'category_id' => $home->id],
            ['sku' => 'HOME-004', 'name' => 'Bamboo Chopping Board Set', 'price' =>   1900, 'stock' => 150, 'category_id' => $home->id],
            ['sku' => 'HOME-005', 'name' => 'Air Purifier HEPA 360',     'price' =>  18500, 'stock' =>  22, 'category_id' => $home->id],
            ['sku' => 'HOME-006', 'name' => 'Stainless Steel Cookware Set', 'price' => 12500, 'stock' => 30, 'category_id' => $home->id],
            ['sku' => 'HOME-007', 'name' => 'Smart LED Bulb (4-pack)',   'price' =>   3200, 'stock' =>  90, 'category_id' => $home->id],
            ['sku' => 'HOME-008', 'name' => 'Electric Kettle 1.7L',      'price' =>   2800, 'stock' =>  75, 'category_id' => $home->id],
            ['sku' => 'HOME-009', 'name' => 'Memory Foam Pillow',        'price' =>   5500, 'stock' =>  45, 'category_id' => $home->id],
            ['sku' => 'HOME-010', 'name' => 'Indoor Plant Pot Set',      'price' =>   2100, 'stock' => 110, 'category_id' => $home->id],

            // ── Sports & Fitness ──────────────────────────────────────
            ['sku' => 'SPRT-001', 'name' => 'Yoga Mat Anti-Slip 6mm',    'price' =>   3200, 'stock' =>  80, 'category_id' => $sports->id],
            ['sku' => 'SPRT-002', 'name' => 'Resistance Bands Set (5)',  'price' =>   1800, 'stock' => 150, 'category_id' => $sports->id],
            ['sku' => 'SPRT-003', 'name' => 'Adjustable Dumbbell 20kg',  'price' =>  14500, 'stock' =>  20, 'category_id' => $sports->id],
            ['sku' => 'SPRT-004', 'name' => 'Running Shoes Pro',         'price' =>  11900, 'stock' =>  50, 'category_id' => $sports->id],
            ['sku' => 'SPRT-005', 'name' => 'Cycling Helmet MTB',        'price' =>   7500, 'stock' =>  35, 'category_id' => $sports->id],
            ['sku' => 'SPRT-006', 'name' => 'Jump Rope Speed Rope',      'price' =>    900, 'stock' => 200, 'category_id' => $sports->id],
            ['sku' => 'SPRT-007', 'name' => 'Gym Gloves (Pair)',         'price' =>   1500, 'stock' => 120, 'category_id' => $sports->id],
            ['sku' => 'SPRT-008', 'name' => 'Protein Shaker Bottle',     'price' =>    800, 'stock' => 180, 'category_id' => $sports->id],

            // ── Beauty & Personal Care ────────────────────────────────
            ['sku' => 'BEAU-001', 'name' => 'Vitamin C Face Serum 30ml', 'price' =>   3500, 'stock' =>  70, 'category_id' => $beauty->id],
            ['sku' => 'BEAU-002', 'name' => 'Organic Shampoo & Conditioner Set', 'price' => 2800, 'stock' => 90, 'category_id' => $beauty->id],
            ['sku' => 'BEAU-003', 'name' => 'Electric Facial Cleansing Brush', 'price' => 5900, 'stock' => 40, 'category_id' => $beauty->id],
            ['sku' => 'BEAU-004', 'name' => 'SPF 50 Sunscreen 100ml',   'price' =>   1800, 'stock' => 120, 'category_id' => $beauty->id],
            ['sku' => 'BEAU-005', 'name' => 'Beard Grooming Kit',        'price' =>   4500, 'stock' =>  55, 'category_id' => $beauty->id],
            ['sku' => 'BEAU-006', 'name' => 'Rose Hip Moisturizer 50ml', 'price' =>   2900, 'stock' =>  65, 'category_id' => $beauty->id],

            // ── Books & Stationery ────────────────────────────────────
            ['sku' => 'BOOK-001', 'name' => 'The Art of Clean Code',     'price' =>   1200, 'stock' => 100, 'category_id' => $books->id],
            ['sku' => 'BOOK-002', 'name' => 'Atomic Habits (Hardcover)', 'price' =>   1600, 'stock' =>  80, 'category_id' => $books->id],
            ['sku' => 'BOOK-003', 'name' => 'Leather-bound Journal A5',  'price' =>   1400, 'stock' => 130, 'category_id' => $books->id],
            ['sku' => 'BOOK-004', 'name' => 'Fountain Pen Set Gold Nib', 'price' =>   4800, 'stock' =>  35, 'category_id' => $books->id],
            ['sku' => 'BOOK-005', 'name' => 'Sticky Notes Mega Pack',    'price' =>    500, 'stock' => 300, 'category_id' => $books->id],

            // ── Food & Beverages ──────────────────────────────────────
            ['sku' => 'FOOD-001', 'name' => 'Premium Green Tea (100 bags)', 'price' => 1500, 'stock' => 150, 'category_id' => $food->id],
            ['sku' => 'FOOD-002', 'name' => 'Dark Chocolate 70% (500g)', 'price' =>   2200, 'stock' =>  90, 'category_id' => $food->id],
            ['sku' => 'FOOD-003', 'name' => 'Raw Honey Jar 500g',        'price' =>   2800, 'stock' =>  75, 'category_id' => $food->id],
            ['sku' => 'FOOD-004', 'name' => 'Organic Oats 1kg',          'price' =>    900, 'stock' => 200, 'category_id' => $food->id],
            ['sku' => 'FOOD-005', 'name' => 'Whey Protein Vanilla 1kg',  'price' =>  4500,  'stock' =>  60, 'category_id' => $food->id],

            // ── Toys & Games ──────────────────────────────────────────
            ['sku' => 'TOYS-001', 'name' => 'LEGO Creator 3-in-1 Set',   'price' =>  5500,  'stock' =>  40, 'category_id' => $toys->id],
            ['sku' => 'TOYS-002', 'name' => 'Remote Control Car 4WD',    'price' =>  6900,  'stock' =>  30, 'category_id' => $toys->id],
            ['sku' => 'TOYS-003', 'name' => 'Chess Set Wooden Premium',   'price' =>  3200,  'stock' =>  55, 'category_id' => $toys->id],
            ['sku' => 'TOYS-004', 'name' => 'Puzzle 1000 Pieces (World Map)', 'price' => 1800, 'stock' => 85, 'category_id' => $toys->id],
            ['sku' => 'TOYS-005', 'name' => 'Action Figure Collection Set', 'price' => 2500, 'stock' => 70, 'category_id' => $toys->id],
        ];

        foreach ($products as $product) {
            $product['price'] = $product['price'] * 100;
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }

        $this->command->info('✅ ' . count($products) . ' products seeded across 8 categories.');
    }
}
