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
                <section class="admin-panel hero-slide-panel">
                    <div class="hero-slide-preview">
                        <img src="{{ $heroSlide->imageUrl() }}" alt="{{ $heroSlide->image_alt }}">
                        <span>{{ str_pad((string) $heroSlide->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <form method="post" action="{{ route('admin.hero-slides.update', $heroSlide) }}"
                        enctype="multipart/form-data" class="hero-slide-form">
                        @csrf
                        @method('PATCH')
                        <div class="form-grid">
                            <div class="field">
                                <label for="kicker-{{ $heroSlide->id }}">Small heading</label>
                                <input id="kicker-{{ $heroSlide->id }}" name="kicker" value="{{ $heroSlide->kicker }}"
                                    required>
                            </div>
                            <div class="field">
                                <label for="title-{{ $heroSlide->id }}">Main caption</label>
                                <input id="title-{{ $heroSlide->id }}" name="title" value="{{ $heroSlide->title }}"
                                    required>
                            </div>
                        </div>
                        <div class="field">
                            <label for="image-alt-{{ $heroSlide->id }}">Image description</label>
                            <input id="image-alt-{{ $heroSlide->id }}" name="image_alt"
                                value="{{ $heroSlide->image_alt }}" required>
                            <small>Describe the image for customers using screen readers.</small>
                        </div>
                        <div class="form-grid">
                            <div class="field">
                                <label for="image-url-{{ $heroSlide->id }}">Image URL</label>
                                <input id="image-url-{{ $heroSlide->id }}" name="image_url" type="url"
                                    value="{{ $heroSlide->image_url }}" placeholder="https://example.com/image.webp">
                                <small>Paste a new URL, or leave blank to keep the current image.</small>
                            </div>
                            <div class="field">
                                <label for="image-{{ $heroSlide->id }}">Replace image</label>
                                <input id="image-{{ $heroSlide->id }}" name="image" type="file"
                                    accept="image/jpeg,image/png,image/webp">
                                <small>JPG, PNG, or WebP, up to 5 MB. An uploaded file takes priority over a URL.</small>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="field">
                                <label for="position-{{ $heroSlide->id }}">Image focus</label>
                                <select id="position-{{ $heroSlide->id }}" name="image_position" required>
                                    @foreach (['center' => 'Center', 'top' => 'Top', 'bottom' => 'Bottom', 'left' => 'Left', 'right' => 'Right'] as $value => $label)
                                        <option value="{{ $value }}" @selected($heroSlide->image_position === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="hero-slide-actions">
                            <div class="field hero-slide-order">
                                <label for="sort-order-{{ $heroSlide->id }}">Order</label>
                                <input id="sort-order-{{ $heroSlide->id }}" name="sort_order" type="number" min="1"
                                    max="999" value="{{ $heroSlide->sort_order }}" required>
                            </div>
                            <label class="checkbox-row">
                                <input type="checkbox" name="is_active" value="1" @checked($heroSlide->is_active)>
                                Visible on homepage
                            </label>
                            <button class="button button-small button-dark" type="submit">Save slide</button>
                        </div>
                    </form>
                    <form method="post" action="{{ route('admin.hero-slides.destroy', $heroSlide) }}"
                        onsubmit="return confirm('Delete this hero slide?')" class="hero-slide-delete">
                        @csrf
                        @method('DELETE')
                        <button class="text-button text-danger" type="submit">Delete slide</button>
                    </form>
                </section>
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
                <div class="field">
                    <label for="image_url">Image URL</label>
                    <input id="image_url" name="image_url" type="url" value="{{ old('image_url') }}"
                        placeholder="https://example.com/image.webp">
                    <small>Paste a direct image URL, or upload a file below.</small>
                </div>
                <div class="field">
                    <label for="image">Hero image</label>
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                    <small>JPG, PNG, or WebP, up to 5 MB. An uploaded file takes priority over a URL.</small>
                </div>
                <div class="form-grid">
                    <div class="field">
                        <label for="image_position">Image focus</label>
                        <select id="image_position" name="image_position" required>
                            @foreach (['center' => 'Center', 'top' => 'Top', 'bottom' => 'Bottom', 'left' => 'Left', 'right' => 'Right'] as $value => $label)
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
