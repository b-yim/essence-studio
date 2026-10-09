<?php

namespace App\Models;

use Database\Factories\HeroSlideFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'kicker',
    'title',
    'image_path',
    'image_url',
    'image_alt',
    'image_position',
    'sort_order',
    'is_active',
])]
class HeroSlide extends Model
{
    /** @use HasFactory<HeroSlideFactory> */
    use HasFactory;

    public const IMAGE_POSITIONS = [
        'center' => 'Center',
        'top' => 'Top',
        'bottom' => 'Bottom',
        'left' => 'Left',
        'right' => 'Right',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function imageUrl(): string
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        if (! $this->image_path) {
            return asset('images/products/hero-perfume.webp');
        }

        if (Str::startsWith($this->image_path, '/')) {
            return $this->image_path;
        }

        if (Str::startsWith($this->image_path, 'images/')) {
            return asset($this->image_path);
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function usesUploadedImage(): bool
    {
        return filled($this->image_path)
            && ! Str::startsWith($this->image_path, ['/', 'images/']);
    }
}
