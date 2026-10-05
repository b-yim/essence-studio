@extends('layout.store')

@section('title', 'Essence Studio · Fragrance for every day')

@section('content')
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <span class="eyebrow eyebrow-light">THE ART OF EVERYDAY FRAGRANCE</span>
                <h1>Your signature scent <em>starts here.</em></h1>
                <p>Discover considered fragrances with beautiful character, lasting performance, and a price that feels just
                    right.</p>
                <div class="hero-actions">
                    <a class="button button-cream" href="{{ route('products.index') }}">Explore the collection
                        <span>↗</span></a>
                    <span>Scents worth remembering.</span>
                </div>
            </div>
            <div class="hero-art" aria-hidden="true">
                <div class="hero-orbit hero-orbit-one"></div>
                <div class="hero-orbit hero-orbit-two"></div>
                <img src="{{ asset('images/perfume.svg') }}" alt="">
                <span class="hero-caption">A new way to wear your mood</span>
            </div>
        </div>
    </section>

    <section class="value-strip">
        <div class="container value-grid">
            <p><strong>01</strong> Distinctive scent stories</p>
            <p><strong>02</strong> Everyday confidence</p>
            <p><strong>03</strong> Accessible luxury</p>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="container category-links">
            <span class="eyebrow">SHOP BY FEELING</span>
            <div>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}">
                        <span>{{ $category->name }}</span>
                        <small>{{ $category->products_count }} scents ↗</small>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">CURATED FOR YOU</span>
                <h2>Find your next favourite.</h2>
            </div>
            <a class="link-arrow" href="{{ route('products.index') }}">Shop all fragrances ↗</a>
        </div>
        <div class="product-grid">
            @forelse($featured as $product)
                @include('user.components.product-card', ['product' => $product])
            @empty
                <div class="empty-state">Our first fragrances are on their way. Check back soon.</div>
            @endforelse
        </div>
    </section>

    <section class="story-band">
        <div class="container story-grid">
            <div class="story-symbol">✳</div>
            <div>
                <span class="eyebrow">THE ESSENCE STUDIO EDIT</span>
                <h2>Good fragrance should feel effortless.</h2>
                <p>From fresh mornings to unforgettable evenings, discover a scent that feels like you.</p>
                <a class="link-arrow" href="{{ route('products.index') }}">Discover your scent ↗</a>
            </div>
        </div>
    </section>
@endsection
