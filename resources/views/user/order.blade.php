@extends('layout.account')
@section('title', 'Order #' . $order->id . ' · Essence Studio')
@section('account-content')
    <a class="back-link" href="{{ route('user.orders.index') }}">← All orders</a>
    <header class="account-heading"><span class="eyebrow">Placed {{ $order->created_at->format('F j, Y') }}</span><h1>Order <em>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</em></h1><span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></header>
    <div class="order-detail-layout"><section class="panel"><span class="eyebrow">Your selection</span><h2>Order details</h2>@foreach ($order->items as $item)<div class="summary-row"><span><strong>{{ $item->product_name }}</strong><small>{{ $item->size }} · Quantity {{ $item->quantity }}</small></span><strong>${{ number_format($item->line_total_cents / 100, 2) }}</strong></div>@endforeach<div class="summary-row summary-total"><span>Total</span><strong>${{ number_format($order->total_cents / 100, 2) }}</strong></div></section>
    <aside class="panel"><span class="eyebrow">On its way to</span><h2>Delivery details</h2><p><strong>{{ $order->customer_name }}</strong><br>{{ $order->customer_email }}<br>{{ $order->phone }}</p><p class="address">{{ $order->shipping_address }}</p>@if ($order->notes)<p><strong>Delivery notes</strong><br>{{ $order->notes }}</p>@endif</aside></div>
@endsection
