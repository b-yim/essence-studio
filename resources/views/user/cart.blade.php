@extends('layout.store')
@section('title', 'Your bag · Essence Studio')
@section('content')
    <header class="container page-heading"><span class="eyebrow">Your personal selection</span><h1>The shopping <em>bag.</em></h1><p>{{ $items->sum('quantity') }} items, chosen by you.</p></header>
    <div class="container cart-layout">
        <section class="cart-items" aria-label="Bag items">@forelse ($items as $item)
            <article class="cart-item"><a class="cart-image" href="{{ route('products.show', $item->variant->product) }}">@include('user.components.product-image', ['product' => $item->variant->product])</a><div class="cart-item-copy"><span class="eyebrow">{{ $item->variant->product->brand }}</span><h2><a href="{{ route('products.show', $item->variant->product) }}">{{ $item->variant->product->name }}</a></h2><p>{{ $item->variant->size }} · ${{ number_format($item->variant->effectivePriceCents() / 100, 2) }} each</p>
                <div class="cart-controls"><form method="post" action="{{ route('user.cart.update', $item) }}">@csrf @method('PATCH')<label class="sr-only" for="quantity-{{ $item->id }}">Quantity for {{ $item->variant->product->name }}</label><div class="quantity-control" data-quantity><button type="button" data-step="-1" aria-label="Decrease quantity" hidden>−</button><input id="quantity-{{ $item->id }}" name="quantity" type="number" min="1" max="{{ max(1, min($item->variant->stock_quantity, 99)) }}" value="{{ $item->quantity }}" required><button type="button" data-step="1" aria-label="Increase quantity" hidden>+</button></div><button class="text-button" type="submit">Update</button></form><form method="post" action="{{ route('user.cart.destroy', $item) }}">@csrf @method('DELETE')<button class="text-button muted" type="submit">Remove</button></form></div></div><strong class="line-total">${{ number_format(($item->variant->effectivePriceCents() * $item->quantity) / 100, 2) }}</strong></article>
        @empty
            <div class="empty-state">@include('user.components.icon', ['name' => 'bag'])<h2>A little room for <em>discovery.</em></h2><p>Your bag is empty. Find a fragrance that feels like you.</p><a class="button button-dark" href="{{ route('products.index') }}">Explore the collection</a></div>
        @endforelse
        @if ($items->isNotEmpty())<a class="back-link" href="{{ route('products.index') }}">← Continue discovering</a>@endif</section>
        @if ($items->isNotEmpty())<aside class="summary-card"><span class="eyebrow">The finishing touch</span><h2>Your summary</h2><div class="summary-row"><span>Subtotal</span><strong>${{ number_format($subtotal / 100, 2) }}</strong></div><p>Review your contact and delivery details at checkout.</p><a class="button button-dark button-block" href="{{ route('user.checkout.create') }}">Proceed to checkout @include('user.components.icon', ['name' => 'arrow'])</a><span class="summary-caption">One step closer to your next signature.</span></aside>@endif
    </div>
@endsection
