@extends('layout.store')

@section('title', 'Essence Studio · A scent of your own')

@section('content')
    @include('user.components.home-hero', ['heroSlides' => $heroSlides])

    <div class="brand-note container">
        <span>Selected with intention.</span>
        <span>Worn with feeling.</span>
        <span>Remembered long after.</span>
    </div>

    @include('user.components.featured-products', ['featured' => $featured])
    @include('user.components.mood-collections')
    @include('user.components.scent-finder')
    @include('user.components.story-section')
@endsection
