@extends('layout.store')
@section('title', 'Essence Studio · A scent of your own')
@section('content')
    <section class="campaign">
        <div class="container campaign-inner">
            <div class="campaign-copy">
                <span class="eyebrow">Perfume, edited for now — 2026</span>
                <h1>Leave a trace.<br><em>Make it yours.</em></h1>
                <p>Fresh signatures, magnetic nights, and warm skin scents. Nine fragrances chosen for every version of you.</p>
                <a class="button button-light" href="{{ route('products.index') }}">Shop the collection @include('user.components.icon', ['name' => 'arrow'])</a>
                <div class="campaign-notes" aria-label="Collection highlights"><span><strong>09</strong> curated scents</span><span><strong>05</strong> scent moods</span></div>
            </div>
            <div class="campaign-visual">
                <img class="campaign-image" src="{{ asset('images/products/hero-perfume.webp') }}" alt="Amber perfume bottle surrounded by charcoal and smoke" fetchpriority="high" width="1800" height="2250">
                <span class="campaign-stamp">NEW<br>EDIT / 01</span>
                <div class="campaign-card"><div><small>The after-dark edit</small><strong>Rich. Smoky. Unforgettable.</strong></div><span>01 / 09</span></div>
            </div>
            <div class="campaign-bottom"><span>ESSENCE STUDIO / CURATED FRAGRANCE</span><a href="#the-edit">Explore the edit ↓</a></div>
        </div>
    </section>
    <div class="brand-note container"><span>Selected with intention.</span><span>Worn with feeling.</span><span>Remembered long after.</span></div>
    <section class="featured-section section container" id="the-edit">
        <div class="section-heading"><div><span class="eyebrow">The studio selection</span><h2>Meet your next <em>signature.</em></h2></div><a class="text-link" href="{{ route('products.index') }}">Shop all fragrances ↗</a></div>
        <div class="product-grid">@forelse ($featured as $product) @include('user.components.product-card', ['product' => $product]) @empty <div class="empty-state"><h3>A new collection is coming.</h3><p>Our first fragrances will be here soon.</p></div> @endforelse</div>
    </section>
    @php
        $moods = [
            ['name' => 'Night Out', 'mark' => 'NO', 'copy' => 'Sweet, magnetic scents made for plans after dark.', 'url' => route('products.index', ['category' => 'night-out'])],
            ['name' => 'Fresh Start', 'mark' => 'FR', 'copy' => 'Cool citrus, clean air, and an easy kind of energy.', 'url' => route('products.index', ['category' => 'fresh-aquatic'])],
            ['name' => 'School Days', 'mark' => 'SC', 'copy' => 'Bright, relaxed fragrances that never feel too loud.', 'url' => route('products.index', ['style' => 'Citrus'])],
            ['name' => 'Work Mode', 'mark' => 'WK', 'copy' => 'Polished everyday scents with a confident, quiet trail.', 'url' => route('products.index', ['style' => 'Aromatic'])],
            ['name' => 'Warm Amber', 'mark' => 'WA', 'copy' => 'Vanilla, woods, and slow warmth for the evening.', 'url' => route('products.index', ['category' => 'warm-spiced'])],
        ];
    @endphp
    <section class="collection-section" id="collections"><div class="container">
        <div class="section-heading"><div><span class="eyebrow">Choose by mood</span><h2>Follow your <em>feeling.</em></h2></div><p>Pick the moment first.<br>We will find the scent.</p></div>
        <div class="mood-grid">
            @foreach ($moods as $mood)
                <a class="mood-card mood-tone-{{ $loop->index }}" href="{{ $mood['url'] }}">
                    <span class="mood-index">0{{ $loop->iteration }} / YOUR MOMENT</span>
                    <span class="mood-mark" aria-hidden="true">{{ $mood['mark'] }}</span>
                    <div class="mood-copy"><h3>{{ $mood['name'] }}</h3><p>{{ $mood['copy'] }}</p><span class="mood-link">Explore this mood <span aria-hidden="true">↗</span></span></div>
                </a>
            @endforeach
        </div>
    </div></section>
    <section class="story-section container" id="our-story">
        <div class="story-image"><img src="{{ asset('images/products/story-perfume.webp') }}" alt="Perfume bottle nestled among purple flowers and green leaves" loading="lazy" width="1400" height="1750"><span>THE ART OF EVERYDAY FRAGRANCE</span></div>
        <div class="story-copy"><span class="eyebrow">Inside the studio</span><h2>Premium feeling.<br><em>Within reach.</em></h2><p>We believe smelling remarkable should not mean overspending. Our edit focuses on affordable fragrances with personality and dependable performance.</p><p>Clear choices, honest value, and scents made for the moments you actually live.</p><a class="text-link" href="{{ route('story') }}">Read our story ↗</a></div>
    </section>
@endsection
