@php
    $demoImages = [
        'armaf-venana' => 'images/products/armaf-ventana.jpeg',
        'afnan-9pm-night-out' => 'images/products/afnan-9pm-night-out.jpeg',
        'lattafah-asad-elixir' => 'images/products/lattafa-asad-elixir.png',
        'havas-ice' => 'images/products/rasasi-hawas-ice.webp',
        'proud-of-you' => 'images/products/fragrance-world-proud-of-you.jpg',
        'havas-kobra' => 'images/products/rasasi-hawas-kobra.png',
        'velixir-orion' => 'images/products/velixir-orion-exact.jpg',
        'velixir-icarus' => 'images/products/velixir-icarus-exact.jpg',
        'lattafah-fakhar-black' => 'images/products/lattafa-fakhar-black.png',
    ];
    $obsoleteProductImages = [
        '/images/products/armaf-venana.webp',
        '/images/products/afnan-9pm-night-out.webp',
        '/images/products/lattafah-asad-elixir.webp',
        '/images/products/havas-ice.webp',
        '/images/products/proud-of-you.webp',
        '/images/products/havas-kobra.webp',
        '/images/products/velixir-orion.webp',
        '/images/products/velixir-icarus.webp',
        '/images/products/lattafah-fakhar-black.webp',
    ];
    $usesFallbackPhoto = ! $product->image_path
        || $product->image_path === '/images/perfume.svg'
        || in_array($product->image_path, $obsoleteProductImages, true);
    $fallbackImage = $demoImages[$product->slug] ?? 'images/products/hero-perfume.webp';
@endphp
<img src="{{ $usesFallbackPhoto ? asset($fallbackImage) : $product->image_path }}"
    alt="{{ $usesFallbackPhoto ? 'Editorial fragrance photograph for ' . $product->name : ($product->image_alt ?: $product->name) }}"
    loading="{{ $loading ?? 'lazy' }}" class="{{ $imageClass ?? '' }}">
