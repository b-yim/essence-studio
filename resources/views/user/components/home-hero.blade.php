<section class="campaign">
    <div class="container campaign-inner">
        <div class="campaign-copy">
            <span class="eyebrow">Perfume, edited for now — 2026</span>
            <h1>Leave a trace.<br><em>Make it yours.</em></h1>
            <p>Fresh signatures, magnetic nights, and warm skin scents. Nine fragrances chosen for every version of you.</p>
            <a class="button button-light" href="{{ route('products.index') }}">
                Shop the collection @include('user.components.icon', ['name' => 'arrow'])
            </a>
        </div>

        <div class="campaign-visual" data-hero-slider aria-label="Featured fragrance edits"
            aria-roledescription="carousel">
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
                <div>
                    <small data-hero-kicker>{{ $heroSlides->first()->kicker }}</small>
                    <strong data-hero-title>{{ $heroSlides->first()->title }}</strong>
                </div>
                <div class="campaign-card-meta">
                    <span data-hero-count>
                        01 / {{ str_pad((string) $heroSlides->count(), 2, '0', STR_PAD_LEFT) }}
                    </span>
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

        <div class="campaign-bottom">
            <span>ESSENCE STUDIO / CURATED FRAGRANCE</span>
            <a href="#the-edit">Explore the edit ↓</a>
        </div>
    </div>
</section>
