@extends('layout.store')

@section('title', 'My orders · Essence Studio')

@section('content')
    <section class="page-heading container"><span class="eyebrow">YOUR ACCOUNT</span>
        <h1>My orders.</h1>
        <p>Every scent has a story. Here are yours.</p>
    </section>
    <div class="container section compact-section">
        @include('user.partials.order-list', ['orders' => $orders])
        @include('user.partials.pagination', ['paginator' => $orders])
    </div>
@endsection
