<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::query()
            ->where('is_published', true)
            ->with('variants')
            ->take(3)
            ->get()
            ->each(function (Product $product): void {
                $variant = $product->variants->first();

                if (! $variant) {
                    return;
                }

                $user = User::factory()->create();
                $order = Order::factory()->for($user)->delivered()->create();
                $orderItem = OrderItem::factory()
                    ->for($order)
                    ->for($variant, 'productVariant')
                    ->create([
                        'product_name' => $product->name,
                        'sku' => $variant->sku,
                        'size' => $variant->size,
                    ]);

                Review::factory()
                    ->for($product)
                    ->for($user)
                    ->for($orderItem)
                    ->approved()
                    ->create();
            });
    }
}
