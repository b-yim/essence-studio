@extends('layout.store')

@section('title', 'Checkout · Essence Studio')

@section('content')
    <section class="page-heading container">
        <span class="eyebrow">ALMOST THERE</span>
        <h1>Checkout.</h1>
        <p>Tell us where your fragrance should go.</p>
    </section>

    <form class="container checkout-layout" method="post" action="{{ route('user.checkout.store') }}">
        @csrf
        <div class="panel checkout-form">
            <h2>Contact and delivery</h2>
            <div class="form-grid">
                <div class="field"><label for="customer_name">Full name</label><input id="customer_name" name="customer_name"
                        value="{{ old('customer_name', auth()->user()->name) }}" required></div>
                <div class="field"><label for="customer_email">Email</label><input id="customer_email"
                        name="customer_email" type="email" value="{{ old('customer_email', auth()->user()->email) }}"
                        required></div>
            </div>
            <div class="field"><label for="phone">Phone number</label><input id="phone" name="phone"
                    value="{{ old('phone') }}" required></div>
            <div class="field"><label for="shipping_address">Shipping address</label>
                <textarea id="shipping_address" name="shipping_address" rows="4" required>{{ old('shipping_address') }}</textarea>
            </div>
            <div class="field"><label for="notes">Delivery notes <span class="muted">(optional)</span></label>
                <textarea id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>
        </div>

        <aside class="summary-card checkout-summary">
            <h2>Your order</h2>
            @foreach ($items as $item)
                <div class="summary-row"><span>{{ $item->variant->product->name }} · {{ $item->variant->size }} ×
                        {{ $item->quantity }}</span><strong>${{ number_format(($item->variant->effectivePriceCents() * $item->quantity) / 100, 2) }}</strong>
                </div>
            @endforeach
            <div class="summary-row summary-total">
                <span>Subtotal</span><strong>${{ number_format($subtotal / 100, 2) }}</strong>
            </div>
            <p class="checkout-note">Online payment is not available yet. Your order will be marked as pending payment until
                the store confirms it.</p>
            <button class="button button-dark button-block" type="submit">Place order ↗</button>
            <a class="text-link" href="{{ route('user.cart.index') }}">Back to bag</a>
        </aside>
    </form>
@endsection
