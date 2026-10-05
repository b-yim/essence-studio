<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255'],
            'style' => ['nullable', 'string', 'max:100'],
            'season' => ['nullable', 'string', 'max:100'],
            'longevity' => ['nullable', 'string', 'max:100'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $query = Product::query()
            ->where('is_published', true)
            ->with(['category', 'variants']);

        $this->applyFilters($query, $filters);

        return view('user.catalog', [
            'products' => $query->latest()->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_published, 404);
        $product->load([
            'category',
            'variants' => fn ($query) => $query->where('is_active', true),
        ]);

        $related = Product::query()
            ->where('is_published', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->with('variants')
            ->take(3)
            ->get();

        return view('user.product', [
            'product' => $product,
            'related' => $related,
        ]);
    }

    /** @param array<string, mixed> $filters */
    private function applyFilters(Builder $query, array $filters): void
    {
        if ($search = $filters['q'] ?? null) {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $filters['category'] ?? null) {
            $query->whereHas('category', fn (Builder $query) => $query->where('slug', $category));
        }

        foreach (['style' => 'style', 'season' => 'best_seasons', 'longevity' => 'longevity'] as $filter => $column) {
            if ($value = $filters[$filter] ?? null) {
                $query->where($column, 'like', "%{$value}%");
            }
        }

        foreach (['min_price' => '>=', 'max_price' => '<='] as $filter => $operator) {
            if (! isset($filters[$filter])) {
                continue;
            }

            $priceCents = (int) round($filters[$filter] * 100);
            $query->whereHas('variants', function (Builder $query) use ($operator, $priceCents): void {
                $query->where('is_active', true)
                    ->whereRaw("COALESCE(sale_price_cents, price_cents) {$operator} ?", [$priceCents]);
            });
        }
    }
}
