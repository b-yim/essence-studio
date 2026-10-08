<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0808">
    <title>@yield('title', 'Essence Studio')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/index.css') }}">
    @vite('resources/js/app.js')
</head>
<body class="storefront">
    <a class="skip-link" href="#main-content">Skip to content</a>
    @include('user.partials.header')
    <main id="main-content">
        @include('user.partials.messages')
        @yield('content')
    </main>
    @include('user.partials.footer')
</body>
</html>
