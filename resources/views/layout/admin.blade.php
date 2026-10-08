<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Overview') · Essence Studio Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/btn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/cart-checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/collections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/product-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/story.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/utility.css') }}">
    @vite('resources/js/app.js')
</head>

<body class="admin-body">
    <div class="admin-shell">
        @include('admin.partials.sidebar')
        <div class="admin-main">
            @include('admin.partials.header')
            <main class="admin-content">
                @include('admin.partials.messages')
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
