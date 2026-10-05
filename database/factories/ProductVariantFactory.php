<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
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
            'sku' => strtoupper(fake()->unique()->bothify('ES-????-####')),
            'size' => '100 ml',
            'currency' => 'USD',
            'price_cents' => 3500,
            'sale_price_cents' => null,
            'stock_quantity' => 20,
            'is_active' => true,
        ];
    }
}
