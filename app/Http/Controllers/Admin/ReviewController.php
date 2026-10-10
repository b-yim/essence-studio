<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.reviews', [
            'reviews' => Review::query()
                ->with(['product:id,name,slug', 'user:id,name,email', 'orderItem:id,order_id'])
                ->latest()
                ->paginate(20),
            'statuses' => Review::STATUSES,
        ]);
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Review::STATUSES))],
        ]);

        $review->update([
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'approved'
                ? ($review->published_at ?? now())
                : null,
        ]);

        return back()->with('success', 'Review status updated.');
    }
}
