@extends('layout.store')

@section('title', $product->name . ' · Essence Studio')

@section('content')
    <div class="container breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}">Shop</a>
        <span>/</span>
        <span>{{ $product->name }}</span>
    </div>

    <section class="container detail-grid">
        <div class="detail-media">
            <div class="detail-main-image">
                <img src="{{ $product->image_path ?: asset('images/perfume.svg') }}"
                    alt="{{ $product->image_alt ?: $product->name }}">
            </div>
            @if ($product->gallery)
                <div class="gallery-grid">
                    @foreach ($product->gallery as $image)
                        <img src="{{ $image }}" alt="{{ $product->name }} alternate view {{ $loop->iteration }}"
                            loading="lazy">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="detail-copy">
            <span class="eyebrow">{{ $product->category->name }} · {{ $product->brand }}</span>
            <h1>{{ $product->name }}</h1>
            <p class="detail-style">{{ $product->style }}</p>
            <p class="detail-description">{{ $product->description }}</p>

            @if ($product->variants->isNotEmpty())
                <form method="post" action="{{ route('user.cart.store') }}" class="purchase-panel">
                    @csrf
                    <label for="product_variant_id">Choose your size</label>
                    <select id="product_variant_id" name="product_variant_id" required>
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}" @disabled($variant->stock_quantity === 0)>
                                {{ $variant->size }} · ${{ number_format($variant->effectivePriceCents() / 100, 2) }}
                                @if ($variant->stock_quantity === 0)
                                    — Sold out
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <label for="quantity">Quantity</label>
                    <input id="quantity" name="quantity" type="number" min="1" max="99" value="1"
                        required>
                    @auth
                        @if (auth()->user()->role === 'customer')
                            <button class="button button-dark button-block" type="submit" @disabled($product->variants->every(fn($variant) => $variant->stock_quantity === 0))>Add to
                                bag ↗</button>
                        @else
                            <p class="muted">Shopping is available for customer accounts.</p>
                        @endif
                    @else
                        <a class="button button-dark button-block" href="{{ route('login') }}">Sign in to add to bag ↗</a>
                        <p class="muted small-text">You need an account before adding a fragrance to your bag.</p>
                    @endauth
                </form>
            @else
                <div class="notice">This fragrance is currently unavailable.</div>
            @endif
        </div>
    </section>

    <section class="container section scent-section">
        <div class="section-heading">
            <div><span class="eyebrow">THE SCENT PROFILE</span>
                <h2>Get to know this fragrance.</h2>
            </div>
        </div>
        <div class="scent-grid">
            @foreach ([
            'Opening smell' => $product->opening_smell,
            'Main vibe' => $product->main_vibe,
            'Character' => $product->character,
            'Overall smell' => $product->overall_smell,
            'Best seasons' => $product->best_seasons,
            'Wear it for' => $product->use_cases,
            'Longevity' => $product->longevity,
            'Projection' => $product->projection,
        ] as $label => $value)
                @if ($value)
                    <div class="scent-item"><span>{{ $label }}</span>
                        <p>{{ $value }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="container section">
            <div class="section-heading">
                <div><span class="eyebrow">KEEP EXPLORING</span>
                    <h2>You may also love.</h2>
                </div>
            </div>
            <div class="product-grid product-grid-three">
                @foreach ($related as $relatedProduct)
                    @include('user.components.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
    @endif
@endsection
