<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'slug',
    'name',
    'brand',
    'description',
    'style',
    'opening_smell',
    'main_vibe',
    'character',
    'overall_smell',
    'best_seasons',
    'use_cases',
    'longevity',
    'projection',
    'image_path',
    'image_alt',
    'gallery',
    'is_published',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['gallery' => 'array', 'is_published' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
