@extends('layout.store')
@section('title', 'Essence Studio · A scent of your own')
@section('content')
    <section class="campaign">
        <img class="campaign-image" src="{{ asset('images/essence-hero-sapphire.webp') }}" alt="Sapphire glass perfume on midnight silk" fetchpriority="high" width="1536" height="1024">
        <div class="container campaign-inner">
            <div class="campaign-copy"><span class="eyebrow">The fragrance collection — No. 01</span><h1>A scent.<br>A feeling.<br><em>Entirely you.</em></h1><p>Discover fragrances for the moments you want to keep.</p><a class="button button-light" href="{{ route('products.index') }}">Discover the collection @include('user.components.icon', ['name' => 'arrow'])</a></div>
            <div class="campaign-bottom"><span>ESSENCE STUDIO / EAU DE PARFUM</span><a href="#the-edit">Explore the edit ↓</a></div>
        </div>
    </section>
    <div class="brand-note container"><span>Selected with intention.</span><span>Worn with feeling.</span><span>Remembered long after.</span></div>
    <section class="section container" id="the-edit">
        <div class="section-heading"><div><span class="eyebrow">The studio selection</span><h2>Meet your next <em>signature.</em></h2></div><a class="text-link" href="{{ route('products.index') }}">Shop all fragrances ↗</a></div>
        <div class="product-grid">@forelse ($featured as $product) @include('user.components.product-card', ['product' => $product]) @empty <div class="empty-state"><h3>A new collection is coming.</h3><p>Our first fragrances will be here soon.</p></div> @endforelse</div>
    </section>
    @if ($categories->isNotEmpty())
        <section class="collection-section" id="collections"><div class="container">
            <div class="section-heading"><div><span class="eyebrow">A world of fragrance</span><h2>Follow your <em>feeling.</em></h2></div><p>From a fresh beginning<br>to a warm afterglow.</p></div>
            <div class="collection-grid">@foreach ($categories as $category)<a class="collection-tile collection-tone-{{ $loop->index % 3 }}" href="{{ route('products.index', ['category' => $category->slug]) }}"><span class="collection-number">0{{ $loop->iteration }} / THE COLLECTION</span><span class="collection-decoration" aria-hidden="true">✳</span><div><h3>{{ $category->name }}</h3><span>{{ $category->products_count }} fragrances <span aria-hidden="true">↗</span></span></div></a>@endforeach</div>
        </div></section>
    @endif
    <section class="story-section container" id="our-story">
        <div class="story-image"><img src="{{ asset('images/essence-editorial.webp') }}" alt="Fragrance in warm afternoon light" loading="lazy" width="1024" height="1536"><span>THE ART OF EVERYDAY FRAGRANCE</span></div>
        <div class="story-copy"><span class="eyebrow">Inside the studio</span><h2>Some things<br>are better <em>felt.</em></h2><p>A fragrance can take you somewhere. Back to a moment, into a mood, or a little closer to yourself.</p><p>We bring together expressive scents with beautiful character, so finding your signature feels like a discovery.</p><a class="text-link" href="{{ route('products.index') }}">Find a scent of your own ↗</a></div>
    </section>
@endsection
