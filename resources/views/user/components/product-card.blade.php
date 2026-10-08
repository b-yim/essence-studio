@php($variant = $product->variants->where('is_active', true)->sortBy(fn ($item) => $item->effectivePriceCents())->first())
<article class="product-card">
    <a class="product-card-image" href="{{ route('products.show', $product) }}">
        @include('user.components.product-image', ['product' => $product])
        @if (! $variant || $variant->stock_quantity === 0)<span class="product-tag">Unavailable</span>@elseif ($variant->sale_price_cents)<span class="product-tag">Special price</span>@endif
        <span class="product-discover">View scent @include('user.components.icon', ['name' => 'arrow'])</span>
    </a>
    <div class="product-card-copy">
        <div class="product-card-heading"><div><span class="product-category">{{ $product->category->name }} · {{ $product->brand }}</span><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3></div><span class="price">@if ($variant)${{ number_format($variant->effectivePriceCents() / 100, 2) }}@else Soon @endif</span></div>
        <p>{{ $product->style ?: $product->brand }}</p>
        <div class="product-card-bottom"><span>{{ $variant?->size ?: 'Signature edition' }} @if ($variant?->sale_price_cents)<del>${{ number_format($variant->price_cents / 100, 2) }}</del>@endif</span><a href="{{ route('products.show', $product) }}">Discover <span aria-hidden="true">↗</span></a></div>
    </div>
</article>
