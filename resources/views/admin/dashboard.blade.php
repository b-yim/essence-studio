@extends('layout.admin')

@section('title', 'Overview')

@section('content')
    <div class="admin-page-heading">
        <div><span class="eyebrow">DASHBOARD</span>
            <h1>Good morning, {{ explode(' ', auth()->user()->name)[0] }}.</h1>
            <p>Here is what is happening in your studio today.</p>
        </div><a class="button button-dark" href="{{ route('admin.products.create') }}">Add product +</a>
    </div>
    <div class="stats-grid">
        @include('admin.components.stat-card', [
            'label' => 'Total products',
            'value' => $productCount,
            'hint' => 'Across your catalogue',
        ])
        @include('admin.components.stat-card', [
            'label' => 'Customers',
            'value' => $customerCount,
            'hint' => 'Registered shoppers',
        ])
        @include('admin.components.stat-card', [
            'label' => 'Orders',
            'value' => $orderCount,
            'hint' => 'All time',
        ])
    </div>
    <div class="admin-panel">
        <div class="panel-heading">
            <div><span class="eyebrow">ACTIVITY</span>
                <h2>Recent orders</h2>
            </div><a href="{{ route('admin.orders.index') }}">View all ↗</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td><a
                                    href="{{ route('admin.orders.show', $order) }}">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</a>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->created_at->format('M j, Y') }}</td>
                            <td><span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></td>
                            <td>${{ number_format($order->total_cents / 100, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
