<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => 'pending_payment',
            'payment_provider' => null,
            'payment_reference' => null,
            'currency' => 'USD',
            'total_cents' => 3500,
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'phone' => fake()->numerify('0#########'),
            'shipping_address' => fake()->address(),
            'notes' => null,
        ];
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => 'delivered']);
    }
}
