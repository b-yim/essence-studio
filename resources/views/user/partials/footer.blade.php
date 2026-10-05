<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <a class="brand brand-light" href="{{ route('home') }}"><span class="brand-mark">e.</span><span>ESSENCE
                    <em>STUDIO</em></span></a>
            <p>Beautiful fragrance, made part of everyday life.</p>
        </div>
        <div>
            <strong>Explore</strong>
            <a href="{{ route('products.index') }}">All fragrances</a>
            @auth
                @unless (auth()->user()->isStaff())
                    <a href="{{ route('user.orders.index') }}">My orders</a>
                @endunless
            @endauth
        </div>
        <div>
            <strong>Essence Studio</strong>
            <p>Inspired scents with a character of their own.</p>
        </div>
    </div>
    <div class="container footer-bottom">© {{ date('Y') }} Essence Studio</div>
</footer>
