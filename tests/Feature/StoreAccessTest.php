<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\DemoCatalogSeeder;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_browse_a_published_product(): void
    {
        $product = Product::factory()->create(['name' => 'Morning Cedar']);
        ProductVariant::factory()->for($product)->create();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Morning Cedar');

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Morning Cedar');
    }

    public function test_registration_always_creates_a_customer(): void
    {
        $this->post(route('register.store'), [
            'name' => 'A New Customer',
            'email' => 'customer@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'role' => 'super_admin',
        ])->assertRedirect(route('user.account'));

        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_roles_cannot_cross_into_the_other_area(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('user.cart.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.accounts.index'))
            ->assertForbidden();
    }

    public function test_only_the_super_admin_can_create_an_admin_account(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->post(route('admin.accounts.store'), [
                'name' => 'Store Manager',
                'email' => 'manager@example.com',
                'password' => 'SecurePassword123!',
                'password_confirmation' => 'SecurePassword123!',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'manager@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_created_product_appears_on_the_storefront(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Velvet Dawn',
                'brand' => 'Essence Studio',
                'description' => 'A soft floral scent for every day.',
                'style' => 'Inspired by a modern floral',
                'is_published' => '1',
                'sku' => 'ES-VELVET-100',
                'size' => '100 ml',
                'price' => '42.00',
                'stock_quantity' => 10,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('products', [
            'slug' => 'velvet-dawn',
            'is_published' => true,
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Velvet Dawn')
            ->assertSee('$42.00');

        $this->get(route('products.show', Product::where('slug', 'velvet-dawn')->firstOrFail()))
            ->assertOk()
            ->assertSee('A soft floral scent for every day.');
    }

    public function test_draft_products_are_not_public(): void
    {
        $product = Product::factory()->create(['is_published' => false]);

        $this->get(route('products.show', $product))->assertNotFound();

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee($product->name);
    }

    public function test_admin_edits_to_product_and_sizes_are_visible_in_the_shop(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create();
        ProductVariant::factory()->for($product)->create();

        $this->actingAs($admin)
            ->patch(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'name' => 'Citrus Hours',
                'brand' => 'Essence Studio',
                'description' => 'A bright everyday fragrance.',
                'opening_smell' => 'Bergamot and lemon',
                'best_seasons' => 'Summer',
                'is_published' => '1',
            ])
            ->assertRedirect();

        $product->refresh();

        $this->post(route('admin.variants.store', $product), [
            'sku' => 'ES-CITRUS-50',
            'size' => '50 ml',
            'price' => '25.00',
            'sale_price' => '22.00',
            'stock_quantity' => 12,
            'is_active' => '1',
        ])->assertRedirect();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Citrus Hours')
            ->assertSee('Bergamot and lemon')
            ->assertSee('50 ml')
            ->assertSee('$22.00');

        $this->assertDatabaseHas('product_variants', [
            'sku' => 'ES-CITRUS-50',
            'sale_price_cents' => 2200,
            'stock_quantity' => 12,
        ]);
    }

    public function test_auth_and_admin_management_pages_render(): void
    {
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();

        $superAdmin = User::factory()->superAdmin()->create();
        $product = Product::factory()->create();
        ProductVariant::factory()->for($product)->create();

        $this->actingAs($superAdmin);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.products.index'))->assertOk();
        $this->get(route('admin.products.create'))->assertOk();
        $this->get(route('admin.products.edit', $product))->assertOk();
        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.orders.index'))->assertOk();
        $this->get(route('admin.accounts.index'))->assertOk();
    }

    public function test_demo_catalog_seeder_displays_products_on_the_storefront(): void
    {
        $this->seed(DemoCatalogSeeder::class);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('FW Imaginari')
            ->assertSee('Cedar Afterglow');

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Petal Morning')
            ->assertSee('Santal Dusk');

        $this->assertDatabaseCount('products', 4);
        $this->assertDatabaseCount('categories', 3);
    }

    public function test_super_admin_seeder_uses_configured_credentials(): void
    {
        config()->set('essence.super_admin.email', 'owner@test.example');
        config()->set('essence.super_admin.password', 'SecureSeedPassword123!');

        $this->seed(SuperAdminSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'owner@test.example',
            'role' => 'super_admin',
        ]);
    }
}
