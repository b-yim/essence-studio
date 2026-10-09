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
            </div>
            <div class="campaign-visual" data-hero-slider aria-label="Featured fragrance edits" aria-roledescription="carousel">
                <div class="campaign-slides">
                    @foreach ($heroSlides as $heroSlide)
                        <div @class(['campaign-slide', 'is-active' => $loop->first]) data-hero-slide
                            data-kicker="{{ $heroSlide->kicker }}" data-title="{{ $heroSlide->title }}"
                            @if (! $loop->first) aria-hidden="true" @endif>
                            <img class="campaign-image campaign-image-position-{{ $heroSlide->image_position }}"
                                src="{{ $heroSlide->imageUrl() }}" alt="{{ $heroSlide->image_alt }}"
                                @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                        </div>
                    @endforeach
                </div>
                <span class="campaign-stamp">NEW<br>EDIT / <span data-hero-number>01</span></span>
                <div class="campaign-card" aria-live="polite">
                    <div><small data-hero-kicker>{{ $heroSlides->first()->kicker }}</small><strong data-hero-title>{{ $heroSlides->first()->title }}</strong></div>
                    <div class="campaign-card-meta">
                        <span data-hero-count>01 / {{ str_pad((string) $heroSlides->count(), 2, '0', STR_PAD_LEFT) }}</span>
                        @if ($heroSlides->count() > 1)
                            <div class="campaign-slider-dots" aria-label="Choose a hero image">
                                @foreach ($heroSlides as $heroSlide)
                                    <button @class(['is-active' => $loop->first]) type="button" data-hero-dot
                                        aria-label="Show hero image {{ $loop->iteration }}"
                                        @if ($loop->first) aria-current="true" @endif></button>
                                @endforeach
                            </div>
                            <div class="campaign-slider-controls">
                                <button type="button" data-hero-previous aria-label="Show previous hero image">←</button>
                                <button type="button" data-hero-next aria-label="Show next hero image">→</button>
                            </div>
                        @endif
                    </div>
                </div>
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
    <section class="scent-finder container" aria-labelledby="scent-finder-title">
        <div class="scent-finder-media">
            <img src="{{ asset('images/essence-editorial.webp') }}" alt="Golden perfume bottle illuminated by warm afternoon light" loading="lazy" width="1024" height="1536">
            <span>CURATED FOR YOU / 01</span>
        </div>
        <div class="scent-finder-copy">
            <span class="eyebrow">A personal edit</span>
            <h2 id="scent-finder-title">Find your <em>scent.</em></h2>
            <p>Three quick questions. One fragrance direction shaped around how you want to feel.</p>
            <div class="scent-finder-meta" aria-label="Quiz details"><span><strong>03</strong> questions</span><span><strong>01</strong> minute</span></div>
            <a class="button button-light" href="{{ route('products.index') }}" data-scent-quiz-open aria-haspopup="dialog">Take the scent quiz @include('user.components.icon', ['name' => 'arrow'])</a>
        </div>
    </section>
    <dialog class="scent-quiz" data-scent-quiz aria-labelledby="scent-quiz-title" data-shop-url="{{ route('products.index') }}">
        <form class="scent-quiz-form" data-scent-quiz-form>
            <header class="scent-quiz-header">
                <div><span class="eyebrow">Your scent profile</span><span class="scent-quiz-count" data-scent-quiz-count>01 / 03</span></div>
                <button class="scent-quiz-close" type="button" data-scent-quiz-close aria-label="Close scent quiz">×</button>
                <span class="scent-quiz-progress" aria-hidden="true"><span data-scent-quiz-progress></span></span>
            </header>
            <div class="scent-quiz-step" data-scent-quiz-step>
                <fieldset>
                    <legend id="scent-quiz-title">How do you want to <em>feel?</em></legend>
                    <p>Choose the energy you want your fragrance to carry.</p>
                    <div class="scent-quiz-options">
                        <label><input type="radio" name="feeling" value="fresh" data-profile="fresh"><span><strong>Fresh</strong><small>Bright and effortless</small></span></label>
                        <label><input type="radio" name="feeling" value="warm" data-profile="warm"><span><strong>Warm</strong><small>Soft and comforting</small></span></label>
                        <label><input type="radio" name="feeling" value="bold" data-profile="bold"><span><strong>Bold</strong><small>Magnetic and noticed</small></span></label>
                    </div>
                </fieldset>
            </div>
            <div class="scent-quiz-step" data-scent-quiz-step hidden>
                <fieldset>
                    <legend>Where will you wear <em>it?</em></legend>
                    <p>Think about the moment you want this scent to belong to.</p>
                    <div class="scent-quiz-options">
                        <label><input type="radio" name="moment" value="everyday" data-profile="fresh"><span><strong>Every day</strong><small>Easy and versatile</small></span></label>
                        <label><input type="radio" name="moment" value="work" data-profile="warm"><span><strong>Work mode</strong><small>Polished and composed</small></span></label>
                        <label><input type="radio" name="moment" value="evening" data-profile="bold"><span><strong>After dark</strong><small>Rich and memorable</small></span></label>
                    </div>
                </fieldset>
            </div>
            <div class="scent-quiz-step" data-scent-quiz-step hidden>
                <fieldset>
                    <legend>Which notes pull you <em>in?</em></legend>
                    <p>Trust your instinct—there is no wrong answer.</p>
                    <div class="scent-quiz-options">
                        <label><input type="radio" name="notes" value="citrus" data-profile="fresh"><span><strong>Citrus</strong><small>Cool, crisp and clean</small></span></label>
                        <label><input type="radio" name="notes" value="vanilla" data-profile="warm"><span><strong>Vanilla</strong><small>Creamy, smooth warmth</small></span></label>
                        <label><input type="radio" name="notes" value="spice" data-profile="bold"><span><strong>Spice</strong><small>Deep, smoky intensity</small></span></label>
                    </div>
                </fieldset>
            </div>
            <div class="scent-quiz-result" data-scent-quiz-result hidden aria-live="polite">
                <span class="eyebrow">Your fragrance direction</span>
                <h3 data-scent-result-title tabindex="-1"></h3>
                <p data-scent-result-copy></p>
                <a class="button button-dark" href="{{ route('products.index') }}" data-scent-result-link>Meet your matches @include('user.components.icon', ['name' => 'arrow'])</a>
                <button class="text-button" type="button" data-scent-quiz-restart>Start again</button>
            </div>
            <footer class="scent-quiz-actions" data-scent-quiz-actions>
                <button class="text-button" type="button" data-scent-quiz-back disabled>Back</button>
                <button class="button button-dark" type="button" data-scent-quiz-next disabled>Continue @include('user.components.icon', ['name' => 'arrow'])</button>
            </footer>
        </form>
    </dialog>
    <section class="story-section container" id="our-story">
        <div class="story-image"><img src="{{ asset('images/products/story-perfume.webp') }}" alt="Perfume bottle nestled among purple flowers and green leaves" loading="lazy" width="1400" height="1750"><span>THE ART OF EVERYDAY FRAGRANCE</span></div>
        <div class="story-copy"><span class="eyebrow">Inside the studio</span><h2>Premium feeling.<br><em>Within reach.</em></h2><p>We believe smelling remarkable should not mean overspending. Our edit focuses on affordable fragrances with personality and dependable performance.</p><p>Clear choices, honest value, and scents made for the moments you actually live.</p><a class="text-link" href="{{ route('story') }}">Read our story ↗</a></div>
    </section>
@endsection
