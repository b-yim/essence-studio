@extends('layout.auth')
@section('auth-content')
    <span class="eyebrow">Your personal studio</span><h1>Welcome <em>back.</em></h1><p class="auth-subtitle">Sign in to your bag, your orders, and your next discovery.</p>
    <form method="post" action="{{ route('login.store') }}" class="stack-form">@csrf
        @include('auth.components.field', ['name' => 'email', 'label' => 'Email address', 'type' => 'email'])
        @include('auth.components.field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'autocomplete' => 'current-password'])
        <label class="checkbox-row"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Keep me signed in</label>
        <button class="button button-dark button-block" type="submit">Sign in @include('user.components.icon', ['name' => 'arrow'])</button>
    </form><p class="auth-switch">New to the studio? <a href="{{ route('register') }}">Create an account</a></p>
@endsection
