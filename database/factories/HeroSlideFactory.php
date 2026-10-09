<?php

namespace Database\Factories;

use App\Models\HeroSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeroSlide>
 */
class HeroSlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kicker' => fake()->sentence(3),
            'title' => fake()->sentence(4),
            'image_path' => 'images/essence-editorial.webp',
            'image_url' => null,
            'image_alt' => fake()->sentence(),
            'image_position' => 'center',
            'sort_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
