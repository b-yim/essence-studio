<section class="featured-section section container" id="the-edit">
    <div class="section-heading">
        <div>
            <span class="eyebrow">The studio selection</span>
            <h2>Meet your next <em>signature.</em></h2>
        </div>
        <a class="text-link" href="{{ route('products.index') }}">Shop all fragrances ↗</a>
    </div>

    <div class="product-grid">
        @forelse ($featured as $product)
            @include('user.components.product-card', ['product' => $product])
        @empty
            <div class="empty-state">
                <h3>A new collection is coming.</h3>
                <p>Our first fragrances will be here soon.</p>
            </div>
        @endforelse
    </div>
</section>
