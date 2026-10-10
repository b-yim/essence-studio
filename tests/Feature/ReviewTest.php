<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_submitting_a_review(): void
    {
        $product = Product::factory()->create();

        $this->post(route('user.reviews.store', $product), [
            'rating' => 5,
            'body' => 'A beautifully balanced fragrance.',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_can_submit_a_pending_review_for_a_delivered_purchase(): void
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create();
        $orderItem = $this->createOrderItem($customer, $product, 'delivered');

        $this->actingAs($customer)
            ->post(route('user.reviews.store', $product), [
                'rating' => 5,
                'title' => 'Clean and confident',
                'body' => 'Fresh at first and beautifully woody after an hour.',
                'user_id' => User::factory()->create()->id,
                'status' => 'approved',
            ])
            ->assertRedirect(route('products.show', $product).'#reviews')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'product_id' => $product->id,
            'user_id' => $customer->id,
            'order_item_id' => $orderItem->id,
            'rating' => 5,
            'title' => 'Clean and confident',
            'status' => 'pending',
            'published_at' => null,
        ]);
    }

    public function test_customer_cannot_review_a_purchase_before_delivery(): void
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create();
        $this->createOrderItem($customer, $product, 'shipped');

        $this->actingAs($customer)
            ->post(route('user.reviews.store', $product), [
                'rating' => 4,
                'body' => 'I should not be able to submit this yet.',
            ])
            ->assertSessionHasErrors([
                'review' => 'You can review this fragrance after your order has been delivered.',
            ]);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_cannot_review_another_customers_purchase(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $product = Product::factory()->create();
        $this->createOrderItem($otherCustomer, $product, 'delivered');

        $this->actingAs($customer)
            ->post(route('user.reviews.store', $product), [
                'rating' => 4,
                'body' => 'I should not be able to review another order.',
            ])
            ->assertSessionHasErrors('review');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_review_rating_and_body_are_validated(): void
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create();
        $this->createOrderItem($customer, $product, 'delivered');

        $this->actingAs($customer)
            ->post(route('user.reviews.store', $product), [
                'rating' => 6,
                'body' => 'Short',
            ])
            ->assertSessionHasErrors(['rating', 'body']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_can_review_each_product_only_once(): void
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create();
        $this->createOrderItem($customer, $product, 'delivered');
        $this->createOrderItem($customer, $product, 'delivered');

        $payload = [
            'rating' => 5,
            'body' => 'A complete review with enough detail.',
        ];

        $this->actingAs($customer)->post(route('user.reviews.store', $product), $payload);

        $this->post(route('user.reviews.store', $product), $payload)
            ->assertSessionHasErrors([
                'review' => 'You have already reviewed this fragrance.',
            ]);

        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_product_page_shows_only_approved_reviews_and_rating_summary(): void
    {
        $product = Product::factory()->create();
        $fiveStarCustomer = User::factory()->create(['name' => 'Five Star']);
        $fourStarCustomer = User::factory()->create(['name' => 'Four Star']);
        $pendingCustomer = User::factory()->create(['name' => 'Pending Star']);
        $dangerousBody = '<script>alert("review")</script> Lasts all day and dries down smoothly.';

        Review::factory()
            ->for($product)
            ->for($fiveStarCustomer)
            ->for($this->createOrderItem($fiveStarCustomer, $product, 'delivered'))
            ->approved()
            ->create(['rating' => 5, 'body' => $dangerousBody]);
        Review::factory()
            ->for($product)
            ->for($fourStarCustomer)
            ->for($this->createOrderItem($fourStarCustomer, $product, 'delivered'))
            ->approved()
            ->create(['rating' => 4, 'body' => 'Warm, polished, and easy to wear.']);
        Review::factory()
            ->for($product)
            ->for($pendingCustomer)
            ->for($this->createOrderItem($pendingCustomer, $product, 'delivered'))
            ->create(['rating' => 1, 'body' => 'This pending review must stay private.']);

        $this->get(route('products.show', $product))
            ->assertSee('4.5')
            ->assertSee('2 verified reviews')
            ->assertSee('Five Star')
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>', false)
            ->assertDontSee('This pending review must stay private.');
    }

    public function test_admin_can_approve_a_review_for_publication(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create();
        $product = Product::factory()->create();
        $orderItem = $this->createOrderItem($customer, $product, 'delivered');
        $review = Review::factory()
            ->for($product)
            ->for($customer)
            ->for($orderItem)
            ->create(['body' => 'A pending review ready for moderation.']);

        $this->actingAs($admin)
            ->patch(route('admin.reviews.update', $review), ['status' => 'approved'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $review->refresh();
        $this->assertSame('approved', $review->status);
        $this->assertNotNull($review->published_at);

        $this->get(route('products.show', $product))
            ->assertSee('A pending review ready for moderation.');
    }

    public function test_customer_cannot_access_review_moderation(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('admin.reviews.index'))
            ->assertForbidden();
    }

    private function createOrderItem(User $customer, Product $product, string $status): OrderItem
    {
        $variant = ProductVariant::factory()->for($product)->create();
        $order = Order::factory()->for($customer)->create(['status' => $status]);

        return OrderItem::factory()
            ->for($order)
            ->for($variant, 'productVariant')
            ->create([
                'product_name' => $product->name,
                'sku' => $variant->sku,
                'size' => $variant->size,
            ]);
    }
}
