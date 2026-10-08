@extends('layout.store')
@section('title', request()->routeIs('register') ? 'Create account · Essence Studio' : 'Sign in · Essence Studio')
@section('content')
    <section class="auth-layout">
        <div class="auth-art"><img src="{{ asset('images/products/story-perfume.webp') }}" alt="Perfume bottle nestled among purple flowers and green leaves" width="1400" height="1750"><div><span class="eyebrow">Your own little ritual</span><h2>A world of scent.<br><em>A space for you.</em></h2></div></div>
        <div class="auth-main"><div class="auth-card"><a class="back-link" href="{{ route('products.index') }}">← Back to the collection</a><div class="auth-tabs"><a href="{{ route('login') }}" @class(['active' => request()->routeIs('login')])>Sign in</a><a href="{{ route('register') }}" @class(['active' => request()->routeIs('register')])>Create account</a></div>@yield('auth-content')</div></div>
    </section>
@endsection
