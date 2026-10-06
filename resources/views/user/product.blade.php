@extends('layout.store')
@section('title', $product->name . ' · Essence Studio')
@section('content')
    @php($selectedVariant = $product->variants->firstWhere('id', (int) old('product_variant_id')) ?? $product->variants->first(fn ($variant) => $variant->stock_quantity > 0) ?? $product->variants->first())
    <nav class="container breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><a href="{{ route('products.index') }}">The collection</a><span>/</span><span>{{ $product->name }}</span></nav>
    <section class="container product-detail" data-product-detail>
        <div class="detail-media"><div class="detail-main-image">@include('user.components.product-image', ['product' => $product, 'loading' => 'eager', 'imageClass' => 'detail-image'])</div>
            @if (! $product->image_path || $product->image_path === '/images/perfume.svg')<p class="image-caption">Studio illustration · Product packaging may vary</p>@endif
            @if ($product->gallery)<div class="gallery-grid">@foreach ($product->gallery as $image)<a href="{{ $image }}" target="_blank" rel="noopener"><img src="{{ $image }}" alt="{{ $product->name }} alternate view {{ $loop->iteration }}" loading="lazy"></a>@endforeach</div>@endif
        </div>
        <div class="detail-copy"><span class="eyebrow">{{ $product->category->name }} / {{ $product->brand }}</span><h1>{{ $product->name }}</h1><p class="detail-style">{{ $product->style }}</p>
            <div class="detail-price-row"><span class="detail-price" data-variant-price>@if ($selectedVariant)${{ number_format($selectedVariant->effectivePriceCents() / 100, 2) }}@else Coming soon @endif</span><span class="stock-copy" data-stock-status>{{ $selectedVariant && $selectedVariant->stock_quantity > 0 ? 'Available to order' : 'Currently unavailable' }}</span></div>
            <p class="detail-description">{{ $product->description }}</p>
            @if ($product->variants->isNotEmpty())
                <form method="post" action="{{ route('user.cart.store') }}" class="purchase-panel">@csrf
                    <fieldset class="variant-fieldset"><legend>Select your size</legend><div class="variant-options">@foreach ($product->variants as $variant)<label class="variant-option"><input type="radio" name="product_variant_id" value="{{ $variant->id }}" data-price="${{ number_format($variant->effectivePriceCents() / 100, 2) }}" data-stock="{{ $variant->stock_quantity }}" @checked($selectedVariant?->id === $variant->id) @disabled($variant->stock_quantity === 0) required><span><strong>{{ $variant->size }}</strong><small>${{ number_format($variant->effectivePriceCents() / 100, 2) }}{{ $variant->stock_quantity === 0 ? ' · Sold out' : '' }}</small></span></label>@endforeach</div></fieldset>
                    <label class="quantity-label" for="quantity">Quantity</label><div class="purchase-actions"><div class="quantity-control" data-quantity><button type="button" data-step="-1" aria-label="Decrease quantity" hidden>−</button><input id="quantity" name="quantity" type="number" min="1" max="{{ min(99, max(1, $selectedVariant?->stock_quantity ?? 1)) }}" value="{{ old('quantity', 1) }}" required><button type="button" data-step="1" aria-label="Increase quantity" hidden>+</button></div>
                    @auth
                        @if (auth()->user()->role === 'customer')<button class="button button-dark" type="submit" @disabled($product->variants->every(fn ($variant) => $variant->stock_quantity === 0))>Add to bag @include('user.components.icon', ['name' => 'bag'])</button>@else<p class="muted">Shopping is available for customer accounts.</p>@endif
                    @else
                        <a class="button button-dark" href="{{ route('login') }}">Sign in to shop @include('user.components.icon', ['name' => 'arrow'])</a>
                    @endauth</div>
                </form>
            @else <p class="notice">This fragrance is currently unavailable.</p> @endif
            <div class="scent-accordions"><details open><summary>The scent story <span>+</span></summary><dl>@foreach (['Opening notes' => $product->opening_smell, 'The feeling' => $product->main_vibe, 'Character' => $product->character, 'Overall scent' => $product->overall_smell] as $label => $value) @if ($value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endif @endforeach</dl></details><details><summary>When to wear it <span>+</span></summary><dl>@foreach (['Seasons' => $product->best_seasons, 'Occasions' => $product->use_cases, 'Longevity' => $product->longevity, 'Projection' => $product->projection] as $label => $value) @if ($value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endif @endforeach</dl></details></div>
        </div>
    </section>
    @if ($related->isNotEmpty())<section class="container section related-section"><div class="section-heading"><div><span class="eyebrow">Stay a little longer</span><h2>You may also <em>love.</em></h2></div></div><div class="product-grid">@foreach ($related as $relatedProduct) @include('user.components.product-card', ['product' => $relatedProduct]) @endforeach</div></section>@endif
@endsection
