<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'price', 'stock', 'category_id'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer', // always cents
        'stock' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['price_formatted', 'image_url'];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Discounts scoped specifically to this product (via discount_qualifiers).
     */
    public function discounts(): MorphToMany
    {
        return $this->morphedByMany(
            Discount::class,
            'qualifiable',
            'discount_qualifiers',
            'qualifiable_id',
            'discount_id'
        );
    }

    // ─── Price Helpers ────────────────────────────────────────────────────────

    /**
     * Price formatted as a decimal string for display (e.g. "19.99").
     */
    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->price / 100, 2);
    }

    /**
     * @var array<string, string>
     */
    public static array $skuMap = [
        // Electronics
        'ELEC-001' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60', // Wireless Headphones
        'ELEC-002' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=500&auto=format&fit=crop&q=60', // 4K Smart TV
        'ELEC-003' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&auto=format&fit=crop&q=60', // Mechanical Keyboard
        'ELEC-004' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&auto=format&fit=crop&q=60', // Smartphone Pro Max
        'ELEC-005' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&auto=format&fit=crop&q=60', // Noise-Cancelling Earbuds
        'ELEC-006' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=500&auto=format&fit=crop&q=60', // Wireless Gaming Mouse
        'ELEC-007' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&auto=format&fit=crop&q=60', // 27" 4K Monitor
        'ELEC-008' => 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=500&auto=format&fit=crop&q=60', // USB-C Hub
        'ELEC-009' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500&auto=format&fit=crop&q=60', // Bluetooth Speaker
        'ELEC-010' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=500&auto=format&fit=crop&q=60', // Smart Watch
        'ELEC-011' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=500&auto=format&fit=crop&q=60', // Laptop
        'ELEC-012' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=500&auto=format&fit=crop&q=60', // Webcam
        'ELEC-013' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=500&auto=format&fit=crop&q=60', // Power Bank
        'ELEC-014' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&auto=format&fit=crop&q=60', // Smart Home Hub
        'ELEC-015' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&auto=format&fit=crop&q=60', // Tablet
        'ELE-40'   => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&auto=format&fit=crop&q=60', // Mobile

        // Clothing
        'CLOT-001' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500&auto=format&fit=crop&q=60', // Cotton T-Shirt
        'CLOT-002' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&auto=format&fit=crop&q=60', // Denim Jeans
        'CLOT-003' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500&auto=format&fit=crop&q=60', // Winter Jacket
        'CLOT-004' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=500&auto=format&fit=crop&q=60', // Oxford Shirt
        'CLOT-005' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&auto=format&fit=crop&q=60', // Hoodie
        'CLOT-006' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=500&auto=format&fit=crop&q=60', // Track Pants
        'CLOT-007' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=500&auto=format&fit=crop&q=60', // Summer Dress
        'CLOT-008' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&auto=format&fit=crop&q=60', // Sneakers
        'CLOT-009' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=500&auto=format&fit=crop&q=60', // Overcoat
        'CLOT-010' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=500&auto=format&fit=crop&q=60', // Polo Pack

        // Home & Garden
        'HOME-001' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=500&auto=format&fit=crop&q=60', // French Press
        'HOME-002' => 'https://images.unsplash.com/photo-1578643463396-0997cb5328c1?w=500&auto=format&fit=crop&q=60', // Blender
        'HOME-003' => 'https://images.unsplash.com/photo-1558317374-067fb5f30001?w=500&auto=format&fit=crop&q=60', // Robot Vacuum
        'HOME-004' => 'https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?w=500&auto=format&fit=crop&q=60', // Bamboo Chopping Board
        'HOME-005' => 'https://images.unsplash.com/photo-1585776245991-cf89dd7fc73a?w=500&auto=format&fit=crop&q=60', // Air Purifier
        'HOME-006' => 'https://images.unsplash.com/photo-1599940824399-b87987ceb72a?w=500&auto=format&fit=crop&q=60', // Cookware Set
        'HOME-007' => 'https://images.unsplash.com/photo-1565814329452-e1efa11c5b89?w=500&auto=format&fit=crop&q=60', // Smart LED Bulb
        'HOME-008' => 'https://images.unsplash.com/photo-1578643463396-0997cb5328c1?w=500&auto=format&fit=crop&q=60', // Electric Kettle
        'HOME-009' => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=500&auto=format&fit=crop&q=60', // Pillow
        'HOME-010' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=500&auto=format&fit=crop&q=60', // Plant Pot

        // Sports & Fitness
        'SPRT-001' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=500&auto=format&fit=crop&q=60', // Yoga Mat
        'SPRT-002' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=500&auto=format&fit=crop&q=60', // Resistance Bands
        'SPRT-003' => 'https://images.unsplash.com/photo-1638536532686-d610adfc8e5c?w=500&auto=format&fit=crop&q=60', // Dumbbell
        'SPRT-004' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=60', // Running Shoes
        'SPRT-005' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=500&auto=format&fit=crop&q=60', // Helmet
        'SPRT-006' => 'https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=500&auto=format&fit=crop&q=60', // Jump Rope
        'SPRT-007' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=500&auto=format&fit=crop&q=60', // Gym Gloves
        'SPRT-008' => 'https://images.unsplash.com/photo-1593079831268-3381b0db4a77?w=500&auto=format&fit=crop&q=60', // Shaker Bottle

        // Beauty & Personal Care
        'BEAU-001' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=500&auto=format&fit=crop&q=60', // Serum
        'BEAU-002' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=500&auto=format&fit=crop&q=60', // Shampoo
        'BEAU-003' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=500&auto=format&fit=crop&q=60', // Cleansing Brush
        'BEAU-004' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=500&auto=format&fit=crop&q=60', // Sunscreen
        'BEAU-005' => 'https://images.unsplash.com/photo-1621607512214-68297480165e?w=500&auto=format&fit=crop&q=60', // Beard Kit
        'BEAU-006' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=500&auto=format&fit=crop&q=60', // Moisturizer

        // Books & Stationery
        'BOOK-001' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&auto=format&fit=crop&q=60', // Clean Code
        'BOOK-002' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&auto=format&fit=crop&q=60', // Atomic Habits
        'BOOK-003' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?w=500&auto=format&fit=crop&q=60', // Journal
        'BOOK-004' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=500&auto=format&fit=crop&q=60', // Fountain Pen
        'BOOK-005' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?w=500&auto=format&fit=crop&q=60', // Sticky Notes

        // Food & Beverages
        'FOOD-001' => 'https://images.unsplash.com/photo-1597481499750-3e6b22637e12?w=500&auto=format&fit=crop&q=60', // Green Tea
        'FOOD-002' => 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=500&auto=format&fit=crop&q=60', // Chocolate
        'FOOD-003' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=500&auto=format&fit=crop&q=60', // Honey
        'FOOD-004' => 'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=500&auto=format&fit=crop&q=60', // Oats
        'FOOD-005' => 'https://images.unsplash.com/photo-1579758629938-03607ccdbaba?w=500&auto=format&fit=crop&q=60', // Whey Protein

        // Toys & Games
        'TOYS-001' => 'https://images.unsplash.com/photo-1566647387313-9fda80664848?w=500&auto=format&fit=crop&q=60', // LEGO
        'TOYS-002' => 'https://images.unsplash.com/photo-1594787318286-3d835c1d207f?w=500&auto=format&fit=crop&q=60', // RC Car
        'TOYS-003' => 'https://images.unsplash.com/photo-1529699211952-734e80c4d42b?w=500&auto=format&fit=crop&q=60', // Chess Set
        'TOYS-004' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=500&auto=format&fit=crop&q=60', // Puzzle
        'TOYS-005' => 'https://images.unsplash.com/photo-1559893088-c0787ebfc084?w=500&auto=format&fit=crop&q=60', // Action Figures
    ];

    /**
     * @var array<int, string>
     */
    public static array $categoryFallbacks = [
        1 => 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=500&auto=format&fit=crop&q=60', // Electronics
        2 => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&auto=format&fit=crop&q=60', // Clothing
        3 => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=500&auto=format&fit=crop&q=60', // Home & Garden
        4 => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=500&auto=format&fit=crop&q=60', // Sports & Fitness
        5 => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&auto=format&fit=crop&q=60', // Beauty & Personal Care
        6 => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=500&auto=format&fit=crop&q=60', // Books & Stationery
        7 => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&auto=format&fit=crop&q=60', // Food & Beverages
        8 => 'https://images.unsplash.com/photo-1531525645387-7f14be1bdbbd?w=500&auto=format&fit=crop&q=60', // Toys & Games
    ];

    /**
     * Get dynamic high-resolution product image URL.
     */
    public function getImageUrlAttribute(): string
    {
        $localPath = 'images/products/' . $this->sku . '.jpg';
        if (file_exists(public_path($localPath))) {
            return asset($localPath);
        }

        $localCategoryPath = 'images/categories/' . $this->category_id . '.jpg';
        if (file_exists(public_path($localCategoryPath))) {
            return asset($localCategoryPath);
        }

        if (isset(self::$skuMap[$this->sku])) {
            return self::$skuMap[$this->sku];
        }

        return self::$categoryFallbacks[$this->category_id] ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=60';
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}

