@extends('layout.store')

@section('title', 'Shop fragrances · Essence Studio')

@section('content')
    <section class="page-heading container">
        <span class="eyebrow">THE COLLECTION</span>
        <h1>Fragrance, your way.</h1>
        <p>Explore fresh, expressive scents for every season and every mood.</p>
    </section>

    <div class="container catalog-layout">
        <aside class="filter-panel">
            <div class="filter-heading">
                <h2>Filter by</h2><a href="{{ route('products.index') }}">Clear all</a>
            </div>
            <form action="{{ route('products.index') }}" method="get">
                <label for="q">Search</label>
                <input id="q" name="q" type="search" value="{{ request('q') }}"
                    placeholder="Search fragrances">

                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>

                <label for="style">Inspired by</label>
                <input id="style" name="style" value="{{ request('style') }}" placeholder="A fragrance style">

                <label for="season">Season</label>
                <input id="season" name="season" value="{{ request('season') }}" placeholder="e.g. Summer">

                <label for="longevity">Longevity</label>
                <input id="longevity" name="longevity" value="{{ request('longevity') }}" placeholder="e.g. 8 hours">

                <div class="form-grid">
                    <div><label for="min_price">Min price</label><input id="min_price" name="min_price" type="number"
                            min="0" step="0.01" value="{{ request('min_price') }}" placeholder="$"></div>
                    <div><label for="max_price">Max price</label><input id="max_price" name="max_price" type="number"
                            min="0" step="0.01" value="{{ request('max_price') }}" placeholder="$"></div>
                </div>

                <button class="button button-dark button-block" type="submit">Apply filters</button>
            </form>
        </aside>

        <section class="catalog-results">
            <div class="results-heading"><span>{{ $products->total() }} fragrances</span><span>Made to be worn and
                    remembered</span></div>
            <div class="product-grid product-grid-three">
                @forelse($products as $product)
                    @include('user.components.product-card', ['product' => $product])
                @empty
                    <div class="empty-state">No fragrances match these filters. Try a broader search.</div>
                @endforelse
            </div>
            @include('user.partials.pagination', ['paginator' => $products])
        </section>
    </div>
@endsection
