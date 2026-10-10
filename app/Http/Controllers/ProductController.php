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

    public function show(Request $request, Product $product): View
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

        $reviewSummary = $product->reviews()
            ->approved()
            ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
            ->selectRaw('SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5_count')
            ->selectRaw('SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4_count')
            ->selectRaw('SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3_count')
            ->selectRaw('SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2_count')
            ->selectRaw('SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1_count')
            ->first();
        $totalReviews = (int) $reviewSummary->total_reviews;
        $ratingBreakdown = collect(range(5, 1))->mapWithKeys(function (int $rating) use ($reviewSummary, $totalReviews): array {
            $count = (int) $reviewSummary->{"rating_{$rating}_count"};

            return [$rating => [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? (int) round(($count / $totalReviews) * 100) : 0,
            ]];
        });
        $reviews = $product->reviews()
            ->approved()
            ->with('user:id,name')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(6, ['*'], 'reviews')
            ->withQueryString()
            ->fragment('reviews');

        $customerReview = null;
        $canReview = false;

        if ($request->user()?->role === 'customer') {
            $customerReview = $product->reviews()->whereBelongsTo($request->user())->latest()->first();
            $canReview = ! $customerReview && $request->user()->orders()
                ->where('status', 'delivered')
                ->whereHas('items.productVariant', fn (Builder $query): Builder => $query->whereBelongsTo($product))
                ->exists();
        }

        return view('user.product', [
            'product' => $product,
            'related' => $related,
            'reviews' => $reviews,
            'averageRating' => $totalReviews > 0 ? round((float) $reviewSummary->average_rating, 1) : 0.0,
            'totalReviews' => $totalReviews,
            'ratingBreakdown' => $ratingBreakdown,
            'customerReview' => $customerReview,
            'canReview' => $canReview,
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
