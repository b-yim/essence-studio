<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('kicker', 100);
            $table->string('title', 150);
            $table->string('image_path', 2048)->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->string('image_alt');
            $table->string('image_position', 20)->default('center');
            $table->unsignedSmallInteger('sort_order')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        $now = now();

        DB::table('hero_slides')->insert([
            [
                'kicker' => 'The after-dark edit',
                'title' => 'Rich. Smoky. Unforgettable.',
                'image_path' => 'images/products/hero-perfume.webp',
                'image_url' => null,
                'image_alt' => 'Amber perfume bottle surrounded by charcoal and smoke',
                'image_position' => 'center',
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kicker' => 'The blue-hour edit',
                'title' => 'Cool. Polished. Electric.',
                'image_path' => 'images/essence-hero-sapphire.webp',
                'image_url' => null,
                'image_alt' => 'Sapphire blue perfume bottle resting on dark silk',
                'image_position' => 'right',
                'sort_order' => 20,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kicker' => 'The golden-hour edit',
                'title' => 'Warm. Luminous. Effortless.',
                'image_path' => 'images/essence-editorial.webp',
                'image_url' => null,
                'image_alt' => 'Golden perfume bottle illuminated by warm afternoon light',
                'image_position' => 'bottom',
                'sort_order' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
