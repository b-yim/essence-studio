@extends('layout.auth')

@section('title', 'Sign in')

@section('content')
    <span class="eyebrow">WELCOME BACK</span>
    <h2>Sign in to your account.</h2>
    <p class="auth-subtitle">Continue your fragrance journey with us.</p>

    <form method="post" action="{{ route('login.store') }}" class="stack-form">
        @csrf
        @include('auth.components.field', [
            'name' => 'email',
            'label' => 'Email address',
            'type' => 'email',
        ])
        @include('auth.components.field', [
            'name' => 'password',
            'label' => 'Password',
            'type' => 'password',
            'autocomplete' => 'current-password',
        ])
        <label class="checkbox-row"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <button class="button button-dark button-block" type="submit">Sign in ↗</button>
    </form>

    <p class="auth-switch">New here? <a href="{{ route('register') }}">Create an account</a></p>
@endsection
