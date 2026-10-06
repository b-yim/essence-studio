@php($isIllustration = ! $product->image_path || $product->image_path === '/images/perfume.svg')
<img src="{{ $isIllustration ? asset('images/essence-bottle.webp') : $product->image_path }}"
    alt="{{ $isIllustration ? 'Fragrance illustration for ' . $product->name : ($product->image_alt ?: $product->name) }}"
    loading="{{ $loading ?? 'lazy' }}" class="{{ $imageClass ?? '' }}">

