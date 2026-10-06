@extends('layout.account')
@section('title', 'My orders · Essence Studio')
@section('account-content')
    <header class="account-heading"><span class="eyebrow">Your collection, in the making</span><h1>My <em>orders.</em></h1><p>View your purchases and their latest status.</p></header>
    @include('user.partials.order-list', ['orders' => $orders])
    @include('user.partials.pagination', ['paginator' => $orders])
@endsection
