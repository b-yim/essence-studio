<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_product_with_an_uploaded_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('product.jpg', 1200, 1500)->size(700);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Uploaded Scent',
                'brand' => 'Essence Studio',
                'description' => 'A fragrance with an uploaded product image.',
                'image_alt' => 'A bottle of Uploaded Scent',
                'image_upload' => $image,
                'is_published' => '1',
                'sku' => 'UPLOADED-100',
                'size' => '100 ml',
                'price' => '45.00',
                'stock_quantity' => 5,
            ])
            ->assertRedirect();

        $product = Product::query()->where('slug', 'uploaded-scent')->firstOrFail();

        $this->assertSame('products/'.$image->hashName(), $product->image_path);
        Storage::disk('public')->assertExists($product->image_path);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee(Storage::disk('public')->url($product->image_path), false);
    }

    public function test_uploaded_product_image_can_replace_a_linked_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'image_path' => 'https://images.example.com/original.webp',
        ]);
        $image = UploadedFile::fake()->image('replacement.webp', 1200, 1500)->size(700);

        $this->actingAs($admin)
            ->patch(route('admin.products.update', $product), $this->productUpdatePayload($product, [
                'image_upload' => $image,
            ]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Product updated.');

        $product->refresh();

        $this->assertSame('products/'.$image->hashName(), $product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_product_url_can_replace_an_upload_and_deletes_the_stored_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/old.jpg', 'old image');
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['image_path' => 'products/old.jpg']);

        $this->actingAs($admin)
            ->patch(route('admin.products.update', $product), $this->productUpdatePayload($product, [
                'image_path' => 'https://images.example.com/replacement.webp',
            ]))
            ->assertRedirect();

        $this->assertSame('https://images.example.com/replacement.webp', $product->fresh()->image_path);
        Storage::disk('public')->assertMissing('products/old.jpg');
    }

    public function test_non_image_product_upload_is_rejected(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)
            ->patch(route('admin.products.update', $product), $this->productUpdatePayload($product, [
                'image_upload' => UploadedFile::fake()->create('catalog.pdf', 100, 'application/pdf'),
            ]))
            ->assertSessionHasErrors(['image_upload' => 'The image upload field must be an image.']);

        $this->assertSame('/images/perfume.svg', $product->fresh()->image_path);
        Storage::disk('public')->assertEmpty();
    }

    public function test_deleting_a_product_removes_its_uploaded_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/deleted.jpg', 'deleted image');
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['image_path' => 'products/deleted.jpg']);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertModelMissing($product);
        Storage::disk('public')->assertMissing('products/deleted.jpg');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function productUpdatePayload(Product $product, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $product->category_id,
            'name' => $product->name,
            'brand' => $product->brand,
            'description' => $product->description,
            'image_alt' => $product->image_alt,
            'is_published' => '1',
        ], $overrides);
    }
}
