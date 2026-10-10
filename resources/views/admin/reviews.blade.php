@extends('layout.admin')
@section('title', 'Reviews')
@section('content')
    <div class="admin-page-heading">
        <div>
            <span class="eyebrow">Customer voices</span>
            <h1>Reviews</h1>
            <p>Approve verified reviews before they appear in the store.</p>
        </div>
    </div>

    <section class="admin-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Customer</th><th>Product</th><th>Review</th><th>Status</th><th>Moderation</th></tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td><strong>{{ $review->user->name }}</strong><br><small>{{ $review->user->email }}</small></td>
                            <td><a class="table-action" href="{{ route('products.show', $review->product) }}#reviews">{{ $review->product->name }} ↗</a><br><small>Order #{{ str_pad((string) $review->orderItem->order_id, 5, '0', STR_PAD_LEFT) }}</small></td>
                            <td class="review-table-copy"><span class="admin-review-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>@if ($review->title)<strong>{{ $review->title }}</strong>@endif<p>{{ $review->body }}</p></td>
                            <td><span class="status-pill">{{ ucfirst($review->status) }}</span></td>
                            <td>
                                <form method="post" action="{{ route('admin.reviews.update', $review) }}" class="review-moderation-form">
                                    @csrf
                                    @method('patch')
                                    <label class="sr-only" for="review-status-{{ $review->id }}">Review status</label>
                                    <select id="review-status-{{ $review->id }}" name="status">
                                        @foreach ($statuses as $value => $label)<option value="{{ $value }}" @selected($review->status === $value)>{{ $label }}</option>@endforeach
                                    </select>
                                    <button class="button button-dark button-small" type="submit">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">No customer reviews yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('user.partials.pagination', ['paginator' => $reviews])
    </section>
@endsection
