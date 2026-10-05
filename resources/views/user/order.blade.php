@extends('layout.store')

@section('title', 'Order #' . $order->id . ' · Essence Studio')

@section('content')
    <section class="page-heading container"><span class="eyebrow">YOUR ACCOUNT</span>
        <h1>Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
        <p>Placed {{ $order->created_at->format('F j, Y') }} · {{ str_replace('_', ' ', ucfirst($order->status)) }}</p>
    </section>
    <div class="container order-detail-layout">
        <div class="panel">
            <h2>Items</h2>
            @foreach ($order->items as $item)
                <div class="summary-row"><span>{{ $item->product_name }} · {{ $item->size }} ×
                        {{ $item->quantity }}</span><strong>${{ number_format($item->line_total_cents / 100, 2) }}</strong>
                </div>
            @endforeach
            <div class="summary-row summary-total">
                <span>Total</span><strong>${{ number_format($order->total_cents / 100, 2) }}</strong>
            </div>
        </div>
        <aside class="panel">
            <h2>Shipping details</h2>
            <p>{{ $order->customer_name }}<br>{{ $order->customer_email }}<br>{{ $order->phone }}</p>
            <p>{{ $order->shipping_address }}</p>
        </aside>
    </div>
@endsection
