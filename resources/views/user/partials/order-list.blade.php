<div class="order-list">
    @forelse($orders as $order)
        <a class="order-row" href="{{ route('user.orders.show', $order) }}">
            <span><strong>Order
                    #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong><small>{{ $order->created_at->format('M j, Y') }}</small></span>
            <span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
            <strong>${{ number_format($order->total_cents / 100, 2) }}</strong>
            <span>↗</span>
        </a>
    @empty
        <div class="empty-state">No orders yet. Your next favourite scent is waiting.</div>
    @endforelse
</div>
