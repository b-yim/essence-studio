<aside class="admin-sidebar">
    <a class="brand brand-light" href="{{ route('admin.dashboard') }}"><span class="brand-mark">e.</span><span>ESSENCE
            <em>STUDIO</em></span></a>
    <span class="sidebar-label">WORKSPACE</span>
    <nav aria-label="Admin navigation">
        <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>◫ <span>Overview</span></a>
        <a href="{{ route('admin.hero-slides.index') }}" @class(['active' => request()->routeIs('admin.hero-slides.*')])>▣ <span>Hero slides</span></a>
        <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>◈ <span>Products</span></a>
        <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>▦ <span>Categories</span></a>
        <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>▤ <span>Orders</span></a>
        @if (auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.accounts.index') }}" @class(['active' => request()->routeIs('admin.accounts.*')])>♙ <span>Admin accounts</span></a>
        @endif
    </nav>
    <div class="sidebar-bottom">
        <a href="{{ route('home') }}">← View storefront</a>
        <form method="post" action="{{ route('logout') }}">@csrf<button type="submit">Sign out</button></form>
    </div>
</aside>
