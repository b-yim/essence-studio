<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_published, 404);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:100'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $product, $validated): void {
            if (Review::query()->whereBelongsTo($request->user())->whereBelongsTo($product)->exists()) {
                throw ValidationException::withMessages([
                    'review' => 'You have already reviewed this fragrance.',
                ]);
            }

            $orderItem = OrderItem::query()
                ->whereHas('order', function (Builder $query) use ($request): void {
                    $query->whereBelongsTo($request->user())->where('status', 'delivered');
                })
                ->whereHas('productVariant', function (Builder $query) use ($product): void {
                    $query->whereBelongsTo($product);
                })
                ->whereDoesntHave('review')
                ->oldest('id')
                ->lockForUpdate()
                ->first();

            if (! $orderItem) {
                throw ValidationException::withMessages([
                    'review' => 'You can review this fragrance after your order has been delivered.',
                ]);
            }

            Review::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'order_item_id' => $orderItem->id,
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'body' => $validated['body'],
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->to(route('products.show', $product).'#reviews')
            ->with('success', 'Thank you. Your review is waiting for approval.');
    }
}
