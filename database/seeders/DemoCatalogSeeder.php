<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'fresh-aquatic'],
            [
                'name' => 'Fresh & Aquatic',
                'description' => 'Bright, clean fragrances for everyday wear.',
            ],
        );

        $product = Product::firstOrCreate(
            ['slug' => 'fw-imaginari'],
            [
                'category_id' => $category->id,
                'name' => 'FW Imaginari',
                'brand' => 'Fragrance World',
                'description' => 'An affordable fresh fragrance with a refined, uplifting character and strong performance.',
                'style' => 'Inspired by Louis Vuitton Imagination',
                'opening_smell' => 'A bright burst of Calabrian bergamot, citron, and sweet Sicilian orange.',
                'main_vibe' => 'Fresh, soapy, aquatic, and deeply uplifting.',
                'character' => 'Clean, modern, sophisticated, and effortlessly luxurious.',
                'overall_smell' => 'Black tea, spicy ginger, and rich ambroxan settle into a smooth, comforting, fresh aura.',
                'best_seasons' => 'Spring and Summer',
                'use_cases' => 'School, Work, Signature Scent, Casual Day Out',
                'longevity' => '8–10 hours on skin',
                'projection' => 'Strong for the first 2 hours, then a moderate, steady bubble.',
                'image_path' => '/images/perfume.svg',
                'image_alt' => 'Illustration of a clear FW Imaginari perfume bottle',
                'gallery' => [],
                'is_published' => true,
            ],
        );

        $product->variants()->firstOrCreate(
            ['sku' => 'FW-IMAGINARI-100'],
            [
                'size' => '100 ml',
                'currency' => 'USD',
                'price_cents' => 3500,
                'stock_quantity' => 25,
                'is_active' => true,
            ],
        );

        $warmCategory = Category::firstOrCreate(
            ['slug' => 'warm-amber'],
            [
                'name' => 'Warm & Amber',
                'description' => 'Comforting scents with depth and warmth.',
            ],
        );

        $floralCategory = Category::firstOrCreate(
            ['slug' => 'soft-floral'],
            [
                'name' => 'Soft Florals',
                'description' => 'Modern florals with an easy, elegant feel.',
            ],
        );

        $samples = [
            [
                'slug' => 'cedar-afterglow',
                'name' => 'Cedar Afterglow',
                'category_id' => $warmCategory->id,
                'description' => 'A warm, polished fragrance for evenings that linger.',
                'style' => 'Smooth woods and amber',
                'opening_smell' => 'A gentle sparkle of pink pepper and cardamom.',
                'main_vibe' => 'Warm, calm, and confident.',
                'character' => 'Understated and elegant.',
                'overall_smell' => 'Cedarwood settles into a soft amber and musk base.',
                'best_seasons' => 'Autumn and Winter',
                'use_cases' => 'Evenings, Dinner, Special Occasions',
                'longevity' => '7–9 hours on skin',
                'projection' => 'Moderate with a warm scent trail.',
                'sku' => 'ES-CEDAR-100',
                'price_cents' => 3900,
            ],
            [
                'slug' => 'petal-morning',
                'name' => 'Petal Morning',
                'category_id' => $floralCategory->id,
                'description' => 'A clean floral fragrance that brightens ordinary mornings.',
                'style' => 'Fresh modern floral',
                'opening_smell' => 'Pear, bergamot, and soft rose petals.',
                'main_vibe' => 'Airy, graceful, and optimistic.',
                'character' => 'Fresh and softly feminine.',
                'overall_smell' => 'White flowers and skin musk create a gentle finish.',
                'best_seasons' => 'Spring and Summer',
                'use_cases' => 'Work, Brunch, Casual Day Out',
                'longevity' => '6–8 hours on skin',
                'projection' => 'Soft to moderate.',
                'sku' => 'ES-PETAL-100',
                'price_cents' => 3200,
            ],
            [
                'slug' => 'santal-dusk',
                'name' => 'Santal Dusk',
                'category_id' => $warmCategory->id,
                'description' => 'Creamy sandalwood and spice for a quietly magnetic finish.',
                'style' => 'Creamy sandalwood',
                'opening_smell' => 'Spiced citrus and a touch of violet.',
                'main_vibe' => 'Relaxed, smooth, and intimate.',
                'character' => 'Modern and genderless.',
                'overall_smell' => 'Sandalwood, amber, and musk blend into a soft trail.',
                'best_seasons' => 'Autumn and Winter',
                'use_cases' => 'Date Night, Work, Signature Scent',
                'longevity' => '8–10 hours on skin',
                'projection' => 'Moderate for the first few hours.',
                'sku' => 'ES-SANTAL-100',
                'price_cents' => 4400,
            ],
        ];

        foreach ($samples as $sample) {
            $sampleProduct = Product::firstOrCreate(
                ['slug' => $sample['slug']],
                [
                    'category_id' => $sample['category_id'],
                    'name' => $sample['name'],
                    'brand' => 'Essence Studio',
                    'description' => $sample['description'],
                    'style' => $sample['style'],
                    'opening_smell' => $sample['opening_smell'],
                    'main_vibe' => $sample['main_vibe'],
                    'character' => $sample['character'],
                    'overall_smell' => $sample['overall_smell'],
                    'best_seasons' => $sample['best_seasons'],
                    'use_cases' => $sample['use_cases'],
                    'longevity' => $sample['longevity'],
                    'projection' => $sample['projection'],
                    'image_path' => '/images/perfume.svg',
                    'image_alt' => $sample['name'].' perfume bottle illustration',
                    'gallery' => [],
                    'is_published' => true,
                ],
            );

            $sampleProduct->variants()->firstOrCreate(
                ['sku' => $sample['sku']],
                [
                    'size' => '100 ml',
                    'currency' => 'USD',
                    'price_cents' => $sample['price_cents'],
                    'stock_quantity' => 18,
                    'is_active' => true,
                ],
            );
        }
    }
}
