<section class="admin-panel hero-slide-panel">
    <div class="hero-slide-preview">
        <img src="{{ $heroSlide->imageUrl() }}" alt="{{ $heroSlide->image_alt }}">
        <span>{{ str_pad((string) $heroSlide->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
    </div>

    <form method="post" action="{{ route('admin.hero-slides.update', $heroSlide) }}" enctype="multipart/form-data"
        class="hero-slide-form">
        @csrf
        @method('PATCH')

        <div class="form-grid">
            <div class="field">
                <label for="kicker-{{ $heroSlide->id }}">Small heading</label>
                <input id="kicker-{{ $heroSlide->id }}" name="kicker" value="{{ $heroSlide->kicker }}" required>
            </div>
            <div class="field">
                <label for="title-{{ $heroSlide->id }}">Main caption</label>
                <input id="title-{{ $heroSlide->id }}" name="title" value="{{ $heroSlide->title }}" required>
            </div>
        </div>

        <div class="field">
            <label for="image-alt-{{ $heroSlide->id }}">Image description</label>
            <input id="image-alt-{{ $heroSlide->id }}" name="image_alt" value="{{ $heroSlide->image_alt }}" required>
            <small>Describe the image for customers using screen readers.</small>
        </div>

        @include('admin.components.image-source-fields', [
            'idPrefix' => 'hero-'.$heroSlide->id,
            'urlLabel' => 'Image URL',
            'urlField' => 'image_url',
            'urlValue' => $heroSlide->image_url,
            'urlHelp' => 'Paste a new URL, or leave blank to keep the current image.',
            'uploadLabel' => 'Replace image',
            'uploadField' => 'image',
            'uploadHelp' => 'JPG, PNG, or WebP, up to 5 MB. An uploaded file takes priority over a URL.',
        ])

        <div class="form-grid">
            <div class="field">
                <label for="position-{{ $heroSlide->id }}">Image focus</label>
                <select id="position-{{ $heroSlide->id }}" name="image_position" required>
                    @foreach ($imagePositions as $value => $label)
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
