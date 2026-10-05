<header class="admin-header">
    <div><span class="eyebrow">ESSENCE STUDIO / ADMIN</span><strong>@yield('title', 'Overview')</strong></div>
    <div class="admin-user"><span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        <div>
            <strong>{{ auth()->user()->name }}</strong><small>{{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}</small>
        </div>
    </div>
</header>
