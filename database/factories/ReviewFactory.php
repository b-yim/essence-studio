<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'order_item_id' => function (array $attributes): int {
                $product = Product::query()->findOrFail($attributes['product_id']);
                $user = User::query()->findOrFail($attributes['user_id']);
                $variant = ProductVariant::factory()->for($product)->create();
                $order = Order::factory()->for($user)->delivered()->create();

                return OrderItem::factory()
                    ->for($order)
                    ->for($variant, 'productVariant')
                    ->create([
                        'product_name' => $product->name,
                        'sku' => $variant->sku,
                        'size' => $variant->size,
                    ])->id;
            },
            'rating' => fake()->numberBetween(3, 5),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraph(),
            'status' => 'pending',
            'published_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'approved',
            'published_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'rejected',
            'published_at' => null,
        ]);
    }
}
