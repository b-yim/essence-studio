<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAndCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_must_sign_in_before_adding_to_the_cart(): void
    {
        $variant = ProductVariant::factory()->create();

        $this->post(route('user.cart.store'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_duplicate_variants_merge_and_stock_is_enforced(): void
    {
        $customer = User::factory()->create();
        $variant = ProductVariant::factory()->create(['stock_quantity' => 3]);

        $this->actingAs($customer)
            ->post(route('user.cart.store'), [
                'product_variant_id' => $variant->id,
                'quantity' => 1,
            ])
            ->assertRedirect(route('user.cart.index'));

        $this->post(route('user.cart.store'), [
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ])->assertRedirect(route('user.cart.index'));

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $customer->id,
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $this->post(route('user.cart.store'), [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ])->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_customer_cannot_change_another_customers_cart(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $item = CartItem::create([
            'user_id' => $owner->id,
            'product_variant_id' => ProductVariant::factory()->create()->id,
            'quantity' => 1,
        ]);

        $this->actingAs($other)
            ->patch(route('user.cart.update', $item), ['quantity' => 2])
            ->assertNotFound();

        $this->assertDatabaseHas('cart_items', ['id' => $item->id, 'quantity' => 1]);
    }

    public function test_checkout_creates_an_order_with_a_price_snapshot(): void
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Cedar Light']);
        $variant = ProductVariant::factory()
            ->for($product)
            ->create(['price_cents' => 3500, 'stock_quantity' => 5]);

        CartItem::create([
            'user_id' => $customer->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->actingAs($customer)
            ->post(route('user.checkout.store'), [
                'customer_name' => 'A Customer',
                'customer_email' => 'customer@example.com',
                'phone' => '012345678',
                'shipping_address' => '123 Studio Street, Phnom Penh',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'status' => 'pending_payment',
            'total_cents' => 7000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Cedar Light',
            'unit_price_cents' => 3500,
            'quantity' => 2,
            'line_total_cents' => 7000,
        ]);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_customer_cart_and_checkout_pages_render(): void
    {
        $customer = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        CartItem::create([
            'user_id' => $customer->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->actingAs($customer)
            ->get(route('user.cart.index'))
            ->assertOk()
            ->assertSee($variant->product->name);

        $this->get(route('user.checkout.create'))
            ->assertOk()
            ->assertSee('pending payment');
    }

    public function test_marking_an_order_paid_reduces_stock_once(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create();
        $variant = ProductVariant::factory()->create(['stock_quantity' => 5]);
        $order = $customer->orders()->create([
            'status' => 'pending_payment',
            'currency' => 'USD',
            'total_cents' => 7000,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'phone' => '012345678',
            'shipping_address' => '123 Studio Street',
        ]);

        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'sku' => $variant->sku,
            'size' => $variant->size,
            'unit_price_cents' => 3500,
            'quantity' => 2,
            'line_total_cents' => 7000,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), ['status' => 'paid'])
            ->assertRedirect();

        $this->patch(route('admin.orders.update', $order), ['status' => 'paid'])
            ->assertRedirect();

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 3,
        ]);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
    }

    public function test_admin_cannot_confirm_an_order_without_available_stock(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create();
        $variant = ProductVariant::factory()->create(['stock_quantity' => 1]);
        $order = $customer->orders()->create([
            'status' => 'pending_payment',
            'currency' => 'USD',
            'total_cents' => 7000,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'phone' => '012345678',
            'shipping_address' => '123 Studio Street',
        ]);

        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'sku' => $variant->sku,
            'size' => $variant->size,
            'unit_price_cents' => 3500,
            'quantity' => 2,
            'line_total_cents' => 7000,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), ['status' => 'paid'])
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending_payment',
        ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 1,
        ]);

        $this->patch(route('admin.orders.update', $order), ['status' => 'shipped'])
            ->assertSessionHasErrors('status');
    }
}
