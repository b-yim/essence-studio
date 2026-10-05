@extends('layout.admin')

@section('title', 'Orders')

@section('content')
    <div class="admin-page-heading">
        <div><span class="eyebrow">FULFILMENT</span>
            <h1>Orders</h1>
            <p>Track each order from placement through delivery.</p>
        </div>
    </div>
    <div class="admin-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->created_at->format('M j, Y') }}</td>
                            <td><span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></td>
                            <td>${{ number_format($order->total_cents / 100, 2) }}</td>
                            <td><a class="table-action" href="{{ route('admin.orders.show', $order) }}">View ↗</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-empty">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('user.partials.pagination', ['paginator' => $orders])
    </div>
@endsection
