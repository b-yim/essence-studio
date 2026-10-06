<div class="order-list">
    @forelse ($orders as $order)
        <a class="order-row" href="{{ route('user.orders.show', $order) }}"><span class="order-icon">@include('user.components.icon', ['name' => 'package'])</span><span><strong>Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong><small>{{ $order->created_at->format('M j, Y') }}</small></span><span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span><strong>${{ number_format($order->total_cents / 100, 2) }}</strong>@include('user.components.icon', ['name' => 'arrow'])</a>
    @empty
        <div class="empty-state">@include('user.components.icon', ['name' => 'package'])<h2>Your first discovery awaits.</h2><p>Your orders will appear here after you check out.</p><a class="button button-dark" href="{{ route('products.index') }}">Explore the collection</a></div>
    @endforelse
</div>
