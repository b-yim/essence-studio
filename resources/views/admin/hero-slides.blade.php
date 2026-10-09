@extends('layout.admin')

@section('title', 'Hero slides')

@section('content')
    <div class="admin-page-heading">
        <div>
            <span class="eyebrow">STOREFRONT</span>
            <h1>Hero slides</h1>
            <p>Change the homepage images, captions, order, and visibility.</p>
        </div>
    </div>

    <div class="admin-columns hero-slide-columns">
        <div class="admin-side-stack">
            @foreach ($heroSlides as $heroSlide)
                @include('admin.components.hero-slide-card', [
                    'heroSlide' => $heroSlide,
                    'imagePositions' => $imagePositions,
                ])
            @endforeach
        </div>

        <aside class="admin-panel hero-slide-create">
            <h2>Add a slide</h2>
            <form method="post" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data"
                class="stack-form">
                @csrf
                <div class="field">
                    <label for="kicker">Small heading</label>
                    <input id="kicker" name="kicker" value="{{ old('kicker') }}" placeholder="The evening edit"
                        required>
                </div>
                <div class="field">
                    <label for="title">Main caption</label>
                    <input id="title" name="title" value="{{ old('title') }}" placeholder="Warm. Bold. Memorable."
                        required>
                </div>
                <div class="field">
                    <label for="image_alt">Image description</label>
                    <input id="image_alt" name="image_alt" value="{{ old('image_alt') }}"
                        placeholder="Amber perfume bottle on dark silk" required>
                </div>
                @include('admin.components.image-source-fields', [
                    'idPrefix' => 'new-hero',
                    'urlLabel' => 'Image URL',
                    'urlField' => 'image_url',
                    'urlValue' => old('image_url'),
                    'urlHelp' => 'Paste a direct image URL, or upload a file.',
                    'uploadLabel' => 'Hero image',
                    'uploadField' => 'image',
                    'uploadHelp' => 'JPG, PNG, or WebP, up to 5 MB. An uploaded file takes priority over a URL.',
                ])
                <div class="form-grid">
                    <div class="field">
                        <label for="image_position">Image focus</label>
                        <select id="image_position" name="image_position" required>
                            @foreach ($imagePositions as $value => $label)
                                <option value="{{ $value }}" @selected(old('image_position', 'center') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="sort_order">Order</label>
                        <input id="sort_order" name="sort_order" type="number" min="1" max="999"
                            value="{{ old('sort_order', ($heroSlides->max('sort_order') ?? 0) + 10) }}" required>
                    </div>
                </div>
                <label class="checkbox-row">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                    Visible on homepage
                </label>
                <button class="button button-dark" type="submit">Add hero slide</button>
            </form>
        </aside>
    </div>
@endsection
