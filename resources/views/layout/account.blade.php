@extends('layout.store')
@section('content')
    <div class="container account-layout">
        <aside class="account-sidebar"><span class="eyebrow">Your studio</span><h2>My account</h2><p>{{ auth()->user()->name }}</p><nav aria-label="Account navigation"><a href="{{ route('user.account') }}" @class(['active' => request()->routeIs('user.account')])>Overview <span>↗</span></a><a href="{{ route('user.orders.index') }}" @class(['active' => request()->routeIs('user.orders.*')])>My orders <span>↗</span></a><a href="{{ route('user.cart.index') }}">Shopping bag <span>↗</span></a></nav><form action="{{ route('logout') }}" method="post">@csrf<button class="text-button" type="submit">Sign out</button></form></aside>
        <div class="account-content">@yield('account-content')</div>
    </div>
@endsection

