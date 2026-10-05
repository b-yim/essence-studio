@extends('layout.admin')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="admin-page-heading">
        <div><a class="back-link" href="{{ route('admin.orders.index') }}">← Orders</a>
            <h1>Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p>Placed {{ $order->created_at->format('F j, Y') }} by {{ $order->customer_name }}</p>
        </div><span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
    </div>
    <div class="admin-columns">
        <div class="admin-panel">
            <h2>Order items</h2>
            @foreach ($order->items as $item)
                <div class="summary-row"><span><strong>{{ $item->product_name }}</strong><br><small>{{ $item->size }} ·
                            {{ $item->sku }} · Qty
                            {{ $item->quantity }}</small></span><strong>${{ number_format($item->line_total_cents / 100, 2) }}</strong>
                </div>
            @endforeach
            <div class="summary-row summary-total">
                <span>Total</span><strong>${{ number_format($order->total_cents / 100, 2) }}</strong>
            </div>
        </div>
        <div class="admin-side-stack">
            <div class="admin-panel">
                <h2>Order status</h2>
                <p class="muted">Online payments are not connected yet. Mark an order paid only after confirming payment
                    with the customer.</p>
                <form method="post" action="{{ route('admin.orders.update', $order) }}" class="stack-form">
                    @csrf @method('PATCH')
                    <div class="field"><label for="status">Status</label><select id="status" name="status">
                            @foreach ($availableStatuses as $value)
                                @php($label = str_replace('_', ' ', ucfirst($value)))
                                <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}
                                </option>
                            @endforeach
                        </select></div>
                    <button class="button button-dark" type="submit">Update status</button>
                </form>
            </div>
            <div class="admin-panel">
                <h2>Customer and delivery</h2>
                <p><strong>{{ $order->customer_name }}</strong><br>{{ $order->customer_email }}<br>{{ $order->phone }}
                </p>
                <p>{{ $order->shipping_address }}</p>
                @if ($order->notes)
                    <p><strong>Note:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
