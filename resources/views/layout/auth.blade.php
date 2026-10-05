<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · Essence Studio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-body">
    <div class="auth-shell">
        @include('auth.partials.intro')
        <main class="auth-main">
            <a class="back-link" href="{{ route('home') }}">← Back to the shop</a>
            <div class="auth-card">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
