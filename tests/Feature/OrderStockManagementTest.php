<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'role' => 'customer',
        'email_verified_at' => now(),
    ]);

    $this->adminUser = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    $this->product = Product::create([
        'sku' => 'TEST-SKU-1',
        'name' => 'Test Product 1',
        'price' => 1000,
        'stock' => 10,
    ]);
});

it('deducts stock when order is placed successfully', function () {
    // 1. Put product in session cart
    session(['cart' => [
        $this->product->id => [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'qty' => 3,
        ]
    ]]);

    // 2. Checkout
    $response = $this->actingAs($this->user)
        ->post(route('user.checkout.process'), [
            'shipping_name' => 'John Doe',
            'shipping_address' => '123 Main Street',
            'shipping_city' => 'Delhi',
            'shipping_state' => 'Delhi',
            'shipping_postal_code' => '110001',
            'shipping_phone' => '9876543210',
            'terms' => 'on'
        ]);

    $response->assertRedirect();
    
    // Assert stock was decremented from 10 to 7
    $this->product->refresh();
    expect($this->product->stock)->toBe(7);
});

it('prevents checkout and throws error if stock is insufficient', function () {
    // 1. Put product in session cart with quantity greater than stock
    session(['cart' => [
        $this->product->id => [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'qty' => 12, // requested 12, only 10 in stock
        ]
    ]]);

    // 2. Checkout
    $response = $this->actingAs($this->user)
        ->post(route('user.checkout.process'), [
            'shipping_name' => 'John Doe',
            'shipping_address' => '123 Main Street',
            'shipping_city' => 'Delhi',
            'shipping_state' => 'Delhi',
            'shipping_postal_code' => '110001',
            'shipping_phone' => '9876543210',
            'terms' => 'on'
        ]);

    // Check we get redirected back to checkout review with error message
    $response->assertRedirect(route('user.checkout.index'));
    
    $this->product->refresh();
    expect($this->product->stock)->toBe(10); // unchanged
});

it('recovers stock when admin cancels or refunds an order', function () {
    // Setup an order
    $order = Order::create([
        'user_id' => $this->user->id,
        'subtotal' => 3000,
        'discount_total' => 0,
        'tax_total' => 240,
        'grand_total' => 3240,
        'status' => 'confirmed',
    ]);

    $item = OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'quantity' => 3,
        'unit_price' => 1000,
        'line_total' => 3000,
    ]);

    // Originally product has 10 stock
    $this->product->update(['stock' => 7]); // Simulate post-purchase stock level

    // Cancel order
    $response = $this->actingAs($this->adminUser)
        ->patch(route('admin.orders.updateStatus', $order), [
            'status' => 'cancelled'
        ]);

    $response->assertRedirect();
    
    // Assert stock returned to original 10
    $this->product->refresh();
    expect($this->product->stock)->toBe(10);
    expect($order->refresh()->status)->toBe('cancelled');
});

it('deducts stock again when admin reverts order from cancelled back to active status', function () {
    // Setup cancelled order
    $order = Order::create([
        'user_id' => $this->user->id,
        'subtotal' => 3000,
        'discount_total' => 0,
        'tax_total' => 240,
        'grand_total' => 3240,
        'status' => 'cancelled',
    ]);

    $item = OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'quantity' => 3,
        'unit_price' => 1000,
        'line_total' => 3000,
    ]);

    // Product currently has 10 stock
    $this->product->update(['stock' => 10]);

    // Revert cancelled order back to confirmed
    $response = $this->actingAs($this->adminUser)
        ->patch(route('admin.orders.updateStatus', $order), [
            'status' => 'confirmed'
        ]);

    $response->assertRedirect();
    
    // Assert stock was decremented back to 7
    $this->product->refresh();
    expect($this->product->stock)->toBe(7);
    expect($order->refresh()->status)->toBe('confirmed');
});

it('does not allow reverting order status back to active if there is insufficient stock', function () {
    // Setup cancelled order
    $order = Order::create([
        'user_id' => $this->user->id,
        'subtotal' => 3000,
        'discount_total' => 0,
        'tax_total' => 240,
        'grand_total' => 3240,
        'status' => 'cancelled',
    ]);

    $item = OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'quantity' => 12, // Requires 12 items
        'unit_price' => 1000,
        'line_total' => 12000,
    ]);

    // Product currently has 10 stock
    $this->product->update(['stock' => 10]);

    // Revert cancelled order back to confirmed (should fail)
    $response = $this->actingAs($this->adminUser)
        ->from(route('admin.orders.show', $order))
        ->patch(route('admin.orders.updateStatus', $order), [
            'status' => 'confirmed'
        ]);

    $response->assertRedirect(route('admin.orders.show', $order));
    $response->assertSessionHas('error');
    
    // Assert stock remained 10
    $this->product->refresh();
    expect($this->product->stock)->toBe(10);
    expect($order->refresh()->status)->toBe('cancelled'); // remains cancelled
});

it('displays shipping details on the admin order show page when they exist', function () {
    $order = Order::create([
        'user_id' => $this->user->id,
        'subtotal' => 1000,
        'discount_total' => 0,
        'tax_total' => 80,
        'grand_total' => 1080,
        'status' => 'pending',
        'shipping_name' => 'Alice Smith',
        'shipping_address' => '456 Oak Avenue',
        'shipping_city' => 'Mumbai',
        'shipping_state' => 'Maharashtra',
        'shipping_postal_code' => '400001',
        'shipping_phone' => '9123456789',
    ]);

    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.orders.show', $order));

    $response->assertStatus(200);
    $response->assertSee('Shipping Details');
    $response->assertSee('Alice Smith');
    $response->assertSee('456 Oak Avenue');
    $response->assertSee('Mumbai');
    $response->assertSee('Maharashtra');
    $response->assertSee('400001');
    $response->assertSee('9123456789');
});

it('renders the admin order show page without shipping details when they do not exist', function () {
    $order = Order::create([
        'user_id' => $this->user->id,
        'subtotal' => 1000,
        'discount_total' => 0,
        'tax_total' => 80,
        'grand_total' => 1080,
        'status' => 'pending',
        'shipping_name' => null,
        'shipping_address' => null,
        'shipping_city' => null,
        'shipping_state' => null,
        'shipping_postal_code' => null,
        'shipping_phone' => null,
    ]);

    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.orders.show', $order));

    $response->assertStatus(200);
    $response->assertDontSee('Shipping Details');
});
