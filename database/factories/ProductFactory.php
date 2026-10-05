<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'slug' => fake()->unique()->slug(3),
            'name' => fake()->unique()->words(3, true),
            'brand' => fake()->company(),
            'description' => fake()->paragraph(),
            'style' => fake()->words(3, true),
            'opening_smell' => fake()->sentence(),
            'main_vibe' => fake()->sentence(),
            'character' => fake()->sentence(),
            'overall_smell' => fake()->sentence(),
            'best_seasons' => 'Spring and Summer',
            'use_cases' => 'Everyday',
            'longevity' => '8 hours',
            'projection' => 'Moderate',
            'image_path' => '/images/perfume.svg',
            'image_alt' => 'Perfume bottle illustration',
            'gallery' => [],
            'is_published' => true,
        ];
    }
}
