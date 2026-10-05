<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#213b32">
    <title>@yield('title', 'Essence Studio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('user.partials.header')

    <main id="main-content">
        @include('user.partials.messages')
        @yield('content')
    </main>

    @include('user.partials.footer')
</body>

</html>
