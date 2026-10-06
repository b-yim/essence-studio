@extends('layout.auth')
@section('auth-content')
    <span class="eyebrow">A scent of your own</span><h1>Come <em>on in.</em></h1><p class="auth-subtitle">Create an account to build your bag and keep track of every order.</p>
    <form method="post" action="{{ route('register.store') }}" class="stack-form">@csrf
        @include('auth.components.field', ['name' => 'name', 'label' => 'Full name', 'autocomplete' => 'name'])
        @include('auth.components.field', ['name' => 'email', 'label' => 'Email address', 'type' => 'email'])
        <div class="form-grid">@include('auth.components.field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'autocomplete' => 'new-password']) @include('auth.components.field', ['name' => 'password_confirmation', 'label' => 'Confirm password', 'type' => 'password', 'autocomplete' => 'new-password'])</div>
        <button class="button button-dark button-block" type="submit">Create account @include('user.components.icon', ['name' => 'arrow'])</button>
    </form><p class="auth-switch">Already a member? <a href="{{ route('login') }}">Sign in</a></p>
@endsection
