@extends('layout.store')

@section('title', 'Your bag · Essence Studio')

@section('content')
    <section class="page-heading container">
        <span class="eyebrow">YOUR SELECTION</span>
        <h1>Your bag.</h1>
        <p>Good choices deserve a second look.</p>
    </section>

    <div class="container cart-layout">
        <div class="cart-items">
            @forelse($items as $item)
                <article class="cart-item">
                    <img src="{{ $item->variant->product->image_path ?: asset('images/perfume.svg') }}"
                        alt="{{ $item->variant->product->image_alt ?: $item->variant->product->name }}">
                    <div class="cart-item-copy">
                        <span class="eyebrow">{{ $item->variant->product->brand }}</span>
                        <h2><a
                                href="{{ route('products.show', $item->variant->product) }}">{{ $item->variant->product->name }}</a>
                        </h2>
                        <p>{{ $item->variant->size }} · {{ $item->variant->sku }}</p>
                        <strong>${{ number_format($item->variant->effectivePriceCents() / 100, 2) }}</strong>
                        <div class="cart-controls">
                            <form method="post" action="{{ route('user.cart.update', $item) }}">
                                @csrf
                                @method('PATCH')
                                <label class="sr-only" for="quantity-{{ $item->id }}">Quantity</label>
                                <input id="quantity-{{ $item->id }}" name="quantity" type="number" min="1"
                                    max="{{ min($item->variant->stock_quantity, 99) }}" value="{{ $item->quantity }}">
                                <button class="text-button" type="submit">Update</button>
                            </form>
                            <form method="post" action="{{ route('user.cart.destroy', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-button text-danger" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                    <strong
                        class="line-total">${{ number_format(($item->variant->effectivePriceCents() * $item->quantity) / 100, 2) }}</strong>
                </article>
            @empty
                <div class="empty-state">
                    <h2>Your bag is waiting.</h2>
                    <p>Discover a fragrance that feels like you.</p><a class="button button-dark"
                        href="{{ route('products.index') }}">Explore fragrances</a>
                </div>
            @endforelse
        </div>

        @if ($items->isNotEmpty())
            <aside class="summary-card">
                <h2>Order summary</h2>
                <div class="summary-row"><span>Subtotal</span><strong>${{ number_format($subtotal / 100, 2) }}</strong>
                </div>
                <p>Shipping and payment details are confirmed at checkout.</p>
                <a class="button button-dark button-block" href="{{ route('user.checkout.create') }}">Continue to checkout
                    ↗</a>
                <a class="text-link" href="{{ route('products.index') }}">Continue shopping</a>
            </aside>
        @endif
    </div>
@endsection
