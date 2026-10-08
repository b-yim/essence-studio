@extends('layout.store')
@section('title', 'Our story · Essence Studio')
@section('content')
    <section class="story-page-hero container">
        <div class="story-page-intro">
            <span class="eyebrow">Our story / Essence Studio</span>
            <h1>Smell remarkable.<br><em>Spend sensibly.</em></h1>
            <p>We make it easier to discover fragrances that feel premium, perform beautifully, and still make sense for an everyday budget.</p>
            <a class="button button-light" href="#our-point-of-view">Discover what drives us @include('user.components.icon', ['name' => 'arrow'])</a>
            <div class="story-page-facts"><span><strong>01</strong> value without compromise</span><span><strong>02</strong> performance that lasts</span></div>
        </div>
        <div class="story-page-media">
            <img src="{{ asset('images/products/story-perfume.webp') }}" alt="Perfume bottle nestled among purple flowers and green leaves" fetchpriority="high" width="1400" height="1750">
            <span>VALUE / PERFORMANCE / CONFIDENCE</span>
        </div>
    </section>

    <section class="story-statement" id="our-point-of-view">
        <div class="container">
            <span class="eyebrow">Why we exist</span>
            <p class="story-statement-copy">A great fragrance should earn compliments—not put pressure on <em>your budget.</em></p>
            <div class="story-statement-note"><span>Fair, accessible prices</span><span>Strong everyday performance</span><span>Scents for real life</span></div>
        </div>
    </section>

    <section class="story-values container">
        <div class="section-heading"><div><span class="eyebrow">Vision, mission, promise</span><h2>Good scent should<br><em>not cost a fortune.</em></h2></div><p>Everything we choose must balance price, character, wearability, and performance.</p></div>
        <div class="story-values-grid">
            <article><span>01 / OUR VISION</span><h3>Premium feeling.<br>Within reach.</h3><p>We want everyone to enjoy a fragrance that feels special, builds confidence, and fits comfortably into everyday life.</p></article>
            <article><span>02 / OUR MISSION</span><h3>Find the value.<br>Keep the quality.</h3><p>We curate affordable perfumes with memorable character, dependable longevity, and projection that performs beyond the price.</p></article>
            <article><span>03 / OUR PROMISE</span><h3>Clear choices.<br>Honest value.</h3><p>We focus on scents worth wearing and explain them simply, so you can buy with confidence instead of paying for hype.</p></article>
        </div>
    </section>

    <section class="story-curation container">
        <div class="story-curation-images">
            <img src="{{ asset('images/products/armaf-ventana.jpeg') }}" alt="Armaf Ventana fragrance in warm sunlight" loading="lazy" width="335" height="597">
            <img src="{{ asset('images/products/afnan-9pm-night-out.jpeg') }}" alt="Afnan 9PM Night Out fragrance on black fabric" loading="lazy" width="482" height="636">
        </div>
        <div class="story-curation-copy"><span class="eyebrow">How we choose</span><h2>Performance<br><em>meets value.</em></h2><p>We look for fragrances that give you more for your money: enjoyable openings, a clear personality, reliable longevity, and enough presence to be noticed.</p><p>Then we organize them around real moments—school, work, fresh starts, warm evenings, and nights out—so choosing the right scent feels simple.</p><a class="button button-dark" href="{{ route('products.index') }}">Explore the collection @include('user.components.icon', ['name' => 'arrow'])</a></div>
    </section>
@endsection
