<footer class="site-footer">
    <div class="container footer-top">
        <div class="footer-intro"><span class="eyebrow">An everyday ritual</span><h2>A lasting<br><em>impression.</em></h2><p>Fragrances with character. Moments made personal.</p></div>
        <nav aria-label="Footer shop"><span class="footer-label">Explore</span><a href="{{ route('products.index') }}">All fragrances</a><a href="{{ route('home') }}#collections">The collections</a><a href="{{ route('home') }}#our-story">Our story</a></nav>
        <nav aria-label="Footer account"><span class="footer-label">Your studio</span>
            @auth
                @if (auth()->user()->isStaff())
                    <a href="{{ route('admin.dashboard') }}">Store dashboard</a>
                @else
                    <a href="{{ route('user.account') }}">My account</a><a href="{{ route('user.orders.index') }}">My orders</a><a href="{{ route('user.cart.index') }}">Shopping bag</a>
                @endif
            @else
                <a href="{{ route('login') }}">Sign in</a><a href="{{ route('register') }}">Create an account</a>
            @endauth
        </nav>
    </div>
    <div class="container footer-signature" aria-hidden="true">essence studio</div>
    <div class="container footer-bottom"><span>© {{ date('Y') }} Essence Studio</span><span>Considered scents. Everyday luxury.</span><a href="#main-content">Back to top ↑</a></div>
</footer>
