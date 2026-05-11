# Discount & Offer Management System

An enterprise-grade Laravel 12 backend architecture for managing, validating, and sequentially applying stackable cart discounts and coupons.

---

## 🚀 Prerequisites
- PHP 8.2+
- Composer
- Docker (for full stack) or XAMPP (Local)
- MySQL / MariaDB
- Redis (Required for locking and caching)

---

## ⚙️ Installation & Setup

```bash
# 1. Clone the repository
git clone https://github.com/your-org/discount-system.git
cd discount-system

# 2. Install dependencies
composer install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# Configure your .env for MySQL and Redis
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# REDIS_HOST=127.0.0.1
# CACHE_DRIVER=redis

# 4. Database Setup
php artisan migrate:fresh --seed

# 5. Start Queue & Server
php artisan queue:work
php artisan serve
```

---

## 🏗️ Architecture Decisions

### 1. Why `BCMath`? (Floating Point Danger)
Never use PHP `floats` for money. `0.1 + 0.2` in standard floating-point binary results in `0.30000000000000004`. When scaled across thousands of orders and taxes, these micro-fractions cost businesses real money. This system stores everything in `cents` (integers) and exclusively uses string-based `BCMath` extensions to guarantee 100% precision.

### 2. Why the `Pipeline` Pattern? (Tax Ordering Compliance)
Checkout math is highly dependent on sequential ordering. You cannot apply tax, then a 10% discount, then free shipping. The Laravel Pipeline `Pipeline::send($cart)->through([...])` guarantees strict mathematical order:
1. Item discounts applied
2. Stacked order discounts applied
3. Taxes calculated **only on the discounted total**
4. Final grand total generated

### 3. Why `Atomic Increments`? (Race Condition Prevention)
If two users apply a coupon with 1 use left at the exact same millisecond, standard PHP reads (`$coupon->usage_count < limit`) will allow both through. We use database-level atomic row checking: `DB::table()->whereColumn('usage_count', '<', 'usage_limit')->increment()` to physically guarantee only one transaction commits.

### 4. Why `Redis Locks`? (Concurrent Checkout Safety)
A user might double-click the "Pay" button. We wrap the checkout controller in `Cache::lock('checkout_'.$userId, 30)` to grab a 30-second mutex lock, immediately rejecting concurrent duplicate attempts with a `429 Too Many Requests`.

### 5. Why the `Strategy` Pattern? (Open/Closed Principle)
Discounts come in many forms (Percentage, Fixed, BOGO, Free Shipping). Instead of a massive `if/else` block inside the controller, we abstract math into `DiscountStrategyInterface`. When marketing wants a new "Buy 2 Get 50% Off" logic, developers just create a new strategy class without touching existing, tested core code.

---

## 🌐 API Endpoint Matrix

All endpoints require the `Accept: application/json` header. Protected endpoints require `Authorization: Bearer <SanctumToken>`.

### Public / Auth
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/auth/login` | Returns Sanctum Token |
| `POST` | `/api/auth/register` | Registers new user |

### Offers & Coupons
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET`  | `/api/offers` | List all active public (sitewide) offers. |
| `POST` | `/api/coupon/validate` | Checks if code is valid/active. (60 req/min) |
| `POST` | `/api/coupon/apply` | Simulates cart and returns math breakdown. (60 req/min) |

**Example Request:** `POST /api/coupon/apply`
```json
{
  "code": "SUMMER20",
  "subtotal": 10000
}
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "original_subtotal": 10000,
    "original_subtotal_formatted": "$100.00",
    "discount_amount": 2000,
    "discount_amount_formatted": "-$20.00",
    "new_subtotal": 8000,
    "new_subtotal_formatted": "$80.00",
    "tax_amount": 640,
    "tax_amount_formatted": "$6.40",
    "grand_total": 8640,
    "grand_total_formatted": "$86.40"
  },
  "message": "Coupon applied successfully.",
  "errors": null
}
```

### Orders
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/checkout` | Processes pipeline, writes DB, queues job. |
| `GET`  | `/api/orders` | List user order history. |
| `GET`  | `/api/orders/{id}` | View specific order breakdown. |

---

## 🧪 Testing

The system is fully covered using **Pest PHP**.

```bash
# Run the entire test suite
php artisan test

# Run only isolated unit tests
php artisan test --testsuite=Unit

# Run Feature and Pipeline integration tests
php artisan test --testsuite=Feature
```

Tests cover:
- Exact BCMath calculations
- Coupon isolation states (expiry, limits, minimum spends)
- Pipeline mathematical stacking accuracy
- Concurrency simulation
