@extends('layout.store')

@section('title', 'My account · Essence Studio')

@section('content')
    <section class="page-heading container">
        <span class="eyebrow">YOUR SPACE</span>
        <h1>Welcome back, {{ auth()->user()->name }}.</h1>
        <p>Your fragrance journey, all in one place.</p>
    </section>

    <div class="container account-grid">
        <a class="account-tile" href="{{ route('user.cart.index') }}"><span>01</span>
            <h2>Your bag</h2>
            <p>Review the fragrances you have saved.</p><strong>View bag ↗</strong>
        </a>
        <a class="account-tile" href="{{ route('user.orders.index') }}"><span>02</span>
            <h2>Your orders</h2>
            <p>Follow every order from checkout to delivery.</p><strong>View orders ↗</strong>
        </a>
        <a class="account-tile" href="{{ route('products.index') }}"><span>03</span>
            <h2>Explore scents</h2>
            <p>Find another fragrance to make your own.</p><strong>Shop now ↗</strong>
        </a>
    </div>

    <section class="container section compact-section">
        <div class="section-heading">
            <div><span class="eyebrow">RECENT ACTIVITY</span>
                <h2>Latest orders</h2>
            </div><a class="link-arrow" href="{{ route('user.orders.index') }}">View all ↗</a>
        </div>
        @include('user.partials.order-list', ['orders' => $orders])
    </section>
@endsection
