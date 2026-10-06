<header class="site-header">
    <div class="container header-inner">
        @if (request()->routeIs('user.checkout.*'))
            <a class="utility-link" href="{{ route('user.cart.index') }}">← Back to bag</a>
        @else
            <div class="header-discovery">
                <details class="mobile-menu header-popover">
                    <summary aria-label="Open navigation">@include('user.components.icon', ['name' => 'menu'])</summary>
                    <nav class="popover-panel mobile-navigation" aria-label="Mobile navigation">
                        <a href="{{ route('products.index') }}">All fragrances</a>
                        <a href="{{ route('home') }}#collections">Collections</a>
                        <a href="{{ route('home') }}#our-story">Our story</a>
                    </nav>
                </details>
                <nav class="primary-nav" aria-label="Main navigation">
                    <a href="{{ route('products.index') }}" @class(['active' => request()->routeIs('products.*')])>Shop</a>
                    <a href="{{ route('home') }}#collections">Collections</a>
                    <a href="{{ route('home') }}#our-story">Our story</a>
                </nav>
            </div>
        @endif

        <a class="wordmark" href="{{ route('home') }}" aria-label="Essence Studio home">essence<span>STUDIO</span></a>

        @if (request()->routeIs('user.checkout.*'))
            <span class="checkout-header-note">Order checkout</span>
        @else
            <div class="header-utilities" role="group" aria-label="Shopping and account">
                <details class="header-popover search-popover">
                    <summary aria-label="Search fragrances">@include('user.components.icon', ['name' => 'search'])</summary>
                    <div class="popover-panel">
                        <form action="{{ route('products.index') }}" method="get" class="header-search">
                            <label for="header-search">Find your next fragrance</label>
                            <div class="search-input"><input id="header-search" type="search" name="q" placeholder="Name, brand or scent…" maxlength="100"><button aria-label="Submit search">@include('user.components.icon', ['name' => 'arrow'])</button></div>
                        </form>
                    </div>
                </details>
                <details class="header-popover account-popover">
                    <summary aria-label="{{ auth()->check() ? 'Account menu' : 'Sign in or create account' }}">@include('user.components.icon', ['name' => 'user'])<span class="utility-label">{{ auth()->check() ? 'Account' : 'Sign in' }}</span></summary>
                    <div class="popover-panel account-menu">
                        @guest
                            <span class="eyebrow">Your personal collection</span>
                            <h3>Welcome to the studio.</h3>
                            <p>Sign in to keep your bag and orders in one place.</p>
                            <a class="button button-dark button-block" href="{{ route('login') }}">Sign in</a>
                            <a class="account-create" href="{{ route('register') }}">Create an account →</a>
                        @else
                            <span class="eyebrow">Welcome back</span>
                            <h3>{{ auth()->user()->name }}</h3>
                            @if (auth()->user()->isStaff())
                                <a href="{{ route('admin.dashboard') }}">Manage the store ↗</a>
                            @else
                                <a href="{{ route('user.account') }}">Account overview →</a>
                                <a href="{{ route('user.orders.index') }}">My orders →</a>
                            @endif
                            <form action="{{ route('logout') }}" method="post">@csrf<button class="text-button" type="submit">Sign out</button></form>
                        @endguest
                    </div>
                </details>
                @if (! auth()->check() || ! auth()->user()->isStaff())
                    <a class="bag-link" href="{{ auth()->check() ? route('user.cart.index') : route('login') }}" aria-label="{{ auth()->check() ? 'Shopping bag' : 'Sign in to view your bag' }}">@include('user.components.icon', ['name' => 'bag'])<span class="utility-label">Bag</span></a>
                @endif
            </div>
        @endif
    </div>
</header>
