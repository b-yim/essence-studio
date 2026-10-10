<section id="reviews" class="container product-reviews" aria-labelledby="reviews-title">
    <div class="reviews-heading">
        <span class="eyebrow">From the community</span>
        <h2 id="reviews-title">Rating &amp; <em>reviews.</em></h2>
    </div>

    <div class="reviews-overview">
        <div class="review-score" aria-label="Average rating {{ number_format($averageRating, 1) }} out of 5">
            <strong>{{ number_format($averageRating, 1) }}</strong>
            <span>/5</span>
            <small>{{ $totalReviews }} {{ Str::plural('verified review', $totalReviews) }}</small>
        </div>

        <div class="rating-breakdown" aria-label="Rating distribution">
            @foreach ($ratingBreakdown as $rating => $distribution)
                <div class="rating-row">
                    <span><b aria-hidden="true">★</b> {{ $rating }}</span>
                    <span class="rating-track" aria-hidden="true"><i style="width: {{ $distribution['percentage'] }}%"></i></span>
                    <small>{{ $distribution['count'] }}</small>
                </div>
            @endforeach
        </div>

        <div class="review-action-card">
            <span class="eyebrow">Review this product</span>
            <h3>Share your scent story.</h3>
            @guest
                <p>Sign in to review a fragrance you have received.</p>
                <a class="button button-outline" href="{{ route('login') }}">Sign in to review</a>
            @else
                @if (auth()->user()->role !== 'customer')
                    <p>Reviews are available to customer accounts.</p>
                @elseif ($customerReview)
                    <p>Your review is <strong>{{ $customerReview->status }}</strong>{{ $customerReview->status === 'pending' ? ' and waiting for approval' : '' }}.</p>
                @elseif ($canReview)
                    <details class="review-form-disclosure" @if ($errors->hasAny(['rating', 'title', 'body', 'review'])) open @endif>
                        <summary class="button button-outline">Write a customer review</summary>
                        <form method="post" action="{{ route('user.reviews.store', $product) }}" class="review-form">
                            @csrf
                            <fieldset class="review-rating-fieldset">
                                <legend>Your rating</legend>
                                <div class="review-rating-options">
                                    @for ($rating = 1; $rating <= 5; $rating++)
                                        <label>
                                            <input type="radio" name="rating" value="{{ $rating }}" @checked((int) old('rating') === $rating) required>
                                            <span aria-hidden="true">★</span>
                                            <span class="sr-only">{{ $rating }} {{ Str::plural('star', $rating) }}</span>
                                        </label>
                                    @endfor
                                </div>
                            </fieldset>
                            <div class="field">
                                <label for="review-title">Review title <small>(optional)</small></label>
                                <input id="review-title" name="title" type="text" maxlength="100" value="{{ old('title') }}" placeholder="A memorable first impression">
                            </div>
                            <div class="field">
                                <label for="review-body">Your review</label>
                                <textarea id="review-body" name="body" minlength="10" maxlength="2000" required placeholder="How did it smell, perform, and make you feel?">{{ old('body') }}</textarea>
                            </div>
                            <button class="button button-dark" type="submit">Submit review</button>
                        </form>
                    </details>
                @else
                    <p>You can write a review after an order containing this fragrance is delivered.</p>
                @endif
            @endguest
        </div>
    </div>

    @if ($reviews->isNotEmpty())
        <div class="review-card-grid">
            @foreach ($reviews as $review)
                <article class="review-card">
                    <header>
                        <span class="reviewer-avatar" aria-hidden="true">{{ Str::upper(Str::substr($review->user->name, 0, 1)) }}</span>
                        <span>
                            <strong>{{ $review->user->name }}</strong>
                            <small>Verified purchase</small>
                        </span>
                        <time datetime="{{ $review->published_at->toDateString() }}">{{ $review->published_at->diffForHumans() }}</time>
                    </header>
                    <div class="review-stars" aria-label="{{ $review->rating }} out of 5 stars">
                        @for ($star = 1; $star <= 5; $star++)<span aria-hidden="true">{{ $star <= $review->rating ? '★' : '☆' }}</span>@endfor
                    </div>
                    @if ($review->title)<h3>{{ $review->title }}</h3>@endif
                    <p>{{ $review->body }}</p>
                </article>
            @endforeach
        </div>
        @include('user.partials.pagination', ['paginator' => $reviews])
    @else
        <div class="reviews-empty">
            <p>No reviews yet. A verified customer can be the first to share their scent story.</p>
        </div>
    @endif
</section>
