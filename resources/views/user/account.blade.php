@extends('layout.account')
@section('title', 'My account · Essence Studio')
@section('account-content')
    <header class="account-heading"><span class="eyebrow">Make yourself at home</span><h1>Hello, <em>{{ auth()->user()->name }}.</em></h1><p>Your fragrance collection starts here.</p></header>
    <div class="account-overview"><div class="profile-panel"><span class="eyebrow">Your details</span><h3>{{ auth()->user()->name }}</h3><p>{{ auth()->user()->email }}</p></div><a class="account-discovery" href="{{ route('products.index') }}"><span class="eyebrow">Something new awaits</span><h3>Find your next<br><em>signature.</em></h3><span>Explore the collection ↗</span></a></div>
    <div class="section-heading account-section-heading"><h2>Recent orders</h2><a class="text-link" href="{{ route('user.orders.index') }}">View all ↗</a></div>
    @include('user.partials.order-list', ['orders' => $orders])
@endsection
