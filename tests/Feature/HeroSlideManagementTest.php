<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSlideManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_an_uploaded_hero_slide(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $image = UploadedFile::fake()->image('midnight.jpg', 1600, 1200)->size(800);

        $this->actingAs($admin)
            ->from(route('admin.hero-slides.index'))
            ->post(route('admin.hero-slides.store'), [
                'kicker' => 'The midnight edit',
                'title' => 'Dark. Smooth. Magnetic.',
                'image_alt' => 'A dark perfume bottle on black silk',
                'image_position' => 'right',
                'sort_order' => 40,
                'is_active' => '1',
                'image' => $image,
                'image_path' => 'images/should-not-be-used.webp',
            ])
            ->assertRedirect(route('admin.hero-slides.index'))
            ->assertSessionHas('success', 'Hero slide added.');

        $heroSlide = HeroSlide::query()->where('title', 'Dark. Smooth. Magnetic.')->firstOrFail();

        $this->assertSame('hero-slides/'.$image->hashName(), $heroSlide->image_path);
        $this->assertSame(40, $heroSlide->sort_order);
        $this->assertTrue($heroSlide->is_active);
        Storage::disk('public')->assertExists($heroSlide->image_path);
    }

    public function test_admin_can_add_a_hero_slide_from_an_image_url(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from(route('admin.hero-slides.index'))
            ->post(route('admin.hero-slides.store'), [
                'kicker' => 'The linked edit',
                'title' => 'Remote. Bright. Ready.',
                'image_alt' => 'A perfume bottle from a remote image',
                'image_position' => 'center',
                'sort_order' => 45,
                'is_active' => '1',
                'image_url' => 'https://images.example.com/hero.webp',
            ])
            ->assertRedirect(route('admin.hero-slides.index'))
            ->assertSessionHas('success', 'Hero slide added.');

        $this->assertDatabaseHas('hero_slides', [
            'title' => 'Remote. Bright. Ready.',
            'image_path' => null,
            'image_url' => 'https://images.example.com/hero.webp',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('https://images.example.com/hero.webp', false);
    }

    public function test_admin_can_replace_an_uploaded_image_and_update_slide_details(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('hero-slides/old.jpg', 'old image');
        $admin = User::factory()->admin()->create();
        $heroSlide = HeroSlide::factory()->create(['image_path' => 'hero-slides/old.jpg']);
        $replacement = UploadedFile::fake()->image('replacement.webp', 1600, 1200)->size(900);

        $this->actingAs($admin)
            ->from(route('admin.hero-slides.index'))
            ->patch(route('admin.hero-slides.update', $heroSlide), [
                'kicker' => 'A brighter edit',
                'title' => 'Fresh. Clean. Electric.',
                'image_alt' => 'A blue bottle in bright light',
                'image_position' => 'top',
                'sort_order' => 5,
                'is_active' => '1',
                'image' => $replacement,
            ])
            ->assertRedirect(route('admin.hero-slides.index'))
            ->assertSessionHas('success', 'Hero slide updated.');

        $heroSlide->refresh();

        $this->assertSame('Fresh. Clean. Electric.', $heroSlide->title);
        $this->assertSame('top', $heroSlide->image_position);
        Storage::disk('public')->assertExists($heroSlide->image_path);
        Storage::disk('public')->assertMissing('hero-slides/old.jpg');
    }

    public function test_replacing_an_uploaded_hero_image_with_a_url_deletes_the_stored_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('hero-slides/old.jpg', 'old image');
        $admin = User::factory()->admin()->create();
        $heroSlide = HeroSlide::factory()->create(['image_path' => 'hero-slides/old.jpg']);

        $this->actingAs($admin)
            ->from(route('admin.hero-slides.index'))
            ->patch(route('admin.hero-slides.update', $heroSlide), [
                'kicker' => $heroSlide->kicker,
                'title' => $heroSlide->title,
                'image_alt' => $heroSlide->image_alt,
                'image_position' => $heroSlide->image_position,
                'sort_order' => $heroSlide->sort_order,
                'is_active' => '1',
                'image_url' => 'https://images.example.com/replacement.webp',
            ])
            ->assertRedirect(route('admin.hero-slides.index'));

        $heroSlide->refresh();

        $this->assertNull($heroSlide->image_path);
        $this->assertSame('https://images.example.com/replacement.webp', $heroSlide->image_url);
        Storage::disk('public')->assertMissing('hero-slides/old.jpg');
    }

    public function test_only_staff_can_manage_hero_slides(): void
    {
        $customer = User::factory()->create();

        $this->get(route('admin.hero-slides.index'))
            ->assertRedirect(route('login'));

        $this->actingAs($customer)
            ->get(route('admin.hero-slides.index'))
            ->assertForbidden();
    }

    public function test_storefront_displays_only_active_slides_in_the_configured_order(): void
    {
        HeroSlide::query()->delete();
        HeroSlide::factory()->create([
            'title' => 'Second active slide',
            'sort_order' => 20,
        ]);
        HeroSlide::factory()->create([
            'title' => 'Hidden slide',
            'sort_order' => 1,
            'is_active' => false,
        ]);
        HeroSlide::factory()->create([
            'title' => 'First active slide',
            'sort_order' => 10,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['First active slide', 'Second active slide'])
            ->assertDontSee('Hidden slide');
    }

    public function test_storefront_escapes_managed_slide_content(): void
    {
        HeroSlide::query()->delete();
        $dangerousContent = '<script>alert("hero")</script>';
        HeroSlide::factory()->create([
            'kicker' => $dangerousContent,
            'title' => $dangerousContent,
            'image_alt' => $dangerousContent,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($dangerousContent)
            ->assertDontSee($dangerousContent, false);
    }

    public function test_non_image_upload_is_rejected_without_creating_a_slide(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $slideCount = HeroSlide::query()->count();

        $this->actingAs($admin)
            ->from(route('admin.hero-slides.index'))
            ->post(route('admin.hero-slides.store'), [
                'kicker' => 'Invalid upload',
                'title' => 'This should not be saved',
                'image_alt' => 'Invalid file',
                'image_position' => 'center',
                'sort_order' => 50,
                'is_active' => '1',
                'image' => UploadedFile::fake()->create('not-an-image.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('admin.hero-slides.index'))
            ->assertSessionHasErrors(['image' => 'The image field must be an image.']);

        $this->assertSame($slideCount, HeroSlide::query()->count());
        Storage::disk('public')->assertEmpty();
    }

    public function test_admin_can_delete_a_slide_and_its_uploaded_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('hero-slides/deleted.jpg', 'deleted image');
        $admin = User::factory()->admin()->create();
        $heroSlide = HeroSlide::factory()->create(['image_path' => 'hero-slides/deleted.jpg']);

        $this->actingAs($admin)
            ->from(route('admin.hero-slides.index'))
            ->delete(route('admin.hero-slides.destroy', $heroSlide))
            ->assertRedirect(route('admin.hero-slides.index'))
            ->assertSessionHas('success', 'Hero slide deleted.');

        $this->assertModelMissing($heroSlide);
        Storage::disk('public')->assertMissing('hero-slides/deleted.jpg');
    }

    public function test_last_active_slide_cannot_be_hidden_or_deleted(): void
    {
        HeroSlide::query()->delete();
        $admin = User::factory()->admin()->create();
        $heroSlide = HeroSlide::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.hero-slides.index'))
            ->patch(route('admin.hero-slides.update', $heroSlide), [
                'kicker' => $heroSlide->kicker,
                'title' => $heroSlide->title,
                'image_alt' => $heroSlide->image_alt,
                'image_position' => $heroSlide->image_position,
                'sort_order' => $heroSlide->sort_order,
            ])
            ->assertRedirect(route('admin.hero-slides.index'))
            ->assertSessionHasErrors(['is_active' => 'At least one hero slide must remain visible.']);

        $this->delete(route('admin.hero-slides.destroy', $heroSlide))
            ->assertRedirect(route('admin.hero-slides.index'))
            ->assertSessionHasErrors(['hero_slide' => 'At least one hero slide must remain visible.']);

        $this->assertModelExists($heroSlide);
        $this->assertTrue($heroSlide->fresh()->is_active);
    }
}
