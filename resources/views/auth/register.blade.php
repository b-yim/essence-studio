@extends('layout.auth')

@section('title', 'Create account')

@section('content')
    <span class="eyebrow">JOIN ESSENCE STUDIO</span>
    <h2>Make it yours.</h2>
    <p class="auth-subtitle">Create your account to save a bag and place an order.</p>

    <form method="post" action="{{ route('register.store') }}" class="stack-form">
        @csrf
        @include('auth.components.field', [
            'name' => 'name',
            'label' => 'Full name',
            'autocomplete' => 'name',
        ])
        @include('auth.components.field', [
            'name' => 'email',
            'label' => 'Email address',
            'type' => 'email',
        ])
        @include('auth.components.field', [
            'name' => 'password',
            'label' => 'Password',
            'type' => 'password',
            'autocomplete' => 'new-password',
        ])
        @include('auth.components.field', [
            'name' => 'password_confirmation',
            'label' => 'Confirm password',
            'type' => 'password',
            'autocomplete' => 'new-password',
        ])
        <button class="button button-dark button-block" type="submit">Create account ↗</button>
    </form>

    <p class="auth-switch">Already a member? <a href="{{ route('login') }}">Sign in</a></p>
@endsection
