@php
    $moods = [
        ['name' => 'Night Out', 'mark' => 'NO', 'copy' => 'Sweet, magnetic scents made for plans after dark.', 'url' => route('products.index', ['category' => 'night-out'])],
        ['name' => 'Fresh Start', 'mark' => 'FR', 'copy' => 'Cool citrus, clean air, and an easy kind of energy.', 'url' => route('products.index', ['category' => 'fresh-aquatic'])],
        ['name' => 'School Days', 'mark' => 'SC', 'copy' => 'Bright, relaxed fragrances that never feel too loud.', 'url' => route('products.index', ['style' => 'Citrus'])],
        ['name' => 'Work Mode', 'mark' => 'WK', 'copy' => 'Polished everyday scents with a confident, quiet trail.', 'url' => route('products.index', ['style' => 'Aromatic'])],
        ['name' => 'Warm Amber', 'mark' => 'WA', 'copy' => 'Vanilla, woods, and slow warmth for the evening.', 'url' => route('products.index', ['category' => 'warm-spiced'])],
    ];
@endphp

<section class="collection-section" id="collections">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Choose by mood</span>
                <h2>Follow your <em>feeling.</em></h2>
            </div>
            <p>Pick the moment first.<br>We will find the scent.</p>
        </div>

        <div class="mood-grid">
            @foreach ($moods as $mood)
                <a class="mood-card mood-tone-{{ $loop->index }}" href="{{ $mood['url'] }}">
                    <span class="mood-index">0{{ $loop->iteration }} / YOUR MOMENT</span>
                    <span class="mood-mark" aria-hidden="true">{{ $mood['mark'] }}</span>
                    <div class="mood-copy">
                        <h3>{{ $mood['name'] }}</h3>
                        <p>{{ $mood['copy'] }}</p>
                        <span class="mood-link">Explore this mood <span aria-hidden="true">↗</span></span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
