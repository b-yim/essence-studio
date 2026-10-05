<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('brand');
            $table->text('description');
            $table->string('style')->nullable();
            $table->text('opening_smell')->nullable();
            $table->text('main_vibe')->nullable();
            $table->text('character')->nullable();
            $table->text('overall_smell')->nullable();
            $table->string('best_seasons')->nullable();
            $table->string('use_cases')->nullable();
            $table->string('longevity')->nullable();
            $table->string('projection')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
