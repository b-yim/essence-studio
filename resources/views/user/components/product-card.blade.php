@php
    $variant = $product->variants->where('is_active', true)->sortBy(fn($item) => $item->effectivePriceCents())->first();
@endphp

<article class="product-card">
    <a class="product-card-image" href="{{ route('products.show', $product) }}">
        <img src="{{ $product->image_path ?: asset('images/perfume.svg') }}"
            alt="{{ $product->image_alt ?: $product->name }}" loading="lazy">
        @if ($variant && $variant->stock_quantity === 0)
            <span class="product-tag">Sold out</span>
        @elseif($variant?->sale_price_cents)
            <span class="product-tag">Special price</span>
        @endif
    </a>
    <div class="product-card-copy">
        <span class="eyebrow">{{ $product->category->name }}</span>
        <a class="product-name" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
        <p>{{ $product->style ?: $product->brand }}</p>
        <span class="stock-copy">
            {{ $variant && $variant->stock_quantity > 0 ? 'In stock' : 'Currently unavailable' }}
        </span>
        <div class="product-card-bottom">
            <span class="price">
                @if ($variant)
                    ${{ number_format($variant->effectivePriceCents() / 100, 2) }}
                    @if ($variant->sale_price_cents)
                        <del>${{ number_format($variant->price_cents / 100, 2) }}</del>
                    @endif
                @else
                    Coming soon
                @endif
            </span>
            <a class="circle-link" href="{{ route('products.show', $product) }}"
                aria-label="View {{ $product->name }}">↗</a>
        </div>
    </div>
</article>
