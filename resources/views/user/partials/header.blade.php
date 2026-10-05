<div class="announcement">A little luxury for every day · Thoughtful scents, accessible prices</div>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Essence Studio home">
            <span class="brand-mark">e.</span>
            <span>ESSENCE <em>STUDIO</em></span>
        </a>

        <nav class="primary-nav" aria-label="Main navigation">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('products.index') }}" @class(['active' => request()->routeIs('products.*')])>Shop fragrances</a>
            @auth
                @if (auth()->user()->isStaff())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @else
                    <a href="{{ route('user.account') }}">My account</a>
                    <a href="{{ route('user.cart.index') }}">Bag</a>
                @endif
            @else
                <a href="{{ route('login') }}">Sign in</a>
            @endauth
        </nav>

        @guest
            <a class="button button-small button-outline header-action" href="{{ route('register') }}">Create account</a>
        @else
            <form class="header-action" method="post" action="{{ route('logout') }}">
                @csrf
                <button class="text-button" type="submit">Sign out</button>
            </form>
        @endguest
    </div>
</header>
