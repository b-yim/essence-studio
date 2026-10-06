@extends('layout.store')
@section('title', 'The collection · Essence Studio')
@section('content')
    <section class="collection-heading container"><span class="eyebrow">Find your signature</span><h1>The <em>collection.</em></h1><p>A fragrance for every feeling. Explore the studio selection.</p></section>
    <section class="container catalog-section">
        <div class="catalog-toolbar"><span>{{ $products->total() }} fragrances</span><details class="filter-disclosure" @if (request()->hasAny(['q', 'category', 'style', 'season', 'longevity', 'min_price', 'max_price'])) open @endif><summary>Refine your selection @include('user.components.icon', ['name' => 'plus'])</summary>
            <form class="filter-form" action="{{ route('products.index') }}" method="get">
                <div class="field"><label for="q">Search</label><input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Name or brand" maxlength="100"></div>
                <div class="field"><label for="category">Collection</label><select id="category" name="category"><option value="">All collections</option>@foreach ($categories as $category)<option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>@endforeach</select></div>
                <div class="field"><label for="style">Inspired by</label><input id="style" name="style" value="{{ request('style') }}" placeholder="Fragrance style"></div>
                <div class="field"><label for="season">Season</label><input id="season" name="season" value="{{ request('season') }}" placeholder="e.g. Summer"></div>
                <div class="field"><label for="longevity">Longevity</label><input id="longevity" name="longevity" value="{{ request('longevity') }}" placeholder="e.g. 8 hours"></div>
                <div class="field"><label for="min_price">Minimum price ($)</label><input id="min_price" type="number" name="min_price" min="0" step="0.01" value="{{ request('min_price') }}"></div>
                <div class="field"><label for="max_price">Maximum price ($)</label><input id="max_price" type="number" name="max_price" min="0" step="0.01" value="{{ request('max_price') }}"></div>
                <div class="filter-actions"><button class="button button-dark" type="submit">Apply filters</button><a class="text-link" href="{{ route('products.index') }}">Clear all</a></div>
            </form>
        </details></div>
        <div class="product-grid catalog-grid">@forelse ($products as $product) @include('user.components.product-card', ['product' => $product]) @empty <div class="empty-state"><h2>No scents found.</h2><p>Try a different search or clear your filters.</p><a class="button button-dark" href="{{ route('products.index') }}">Explore all fragrances</a></div> @endforelse</div>
        @include('user.partials.pagination', ['paginator' => $products])
    </section>
@endsection
