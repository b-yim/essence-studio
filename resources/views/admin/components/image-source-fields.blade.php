<div class="form-grid">
    <div class="field">
        <label for="{{ $idPrefix }}-url">{{ $urlLabel }}</label>
        <input id="{{ $idPrefix }}-url" name="{{ $urlField }}" type="url" value="{{ $urlValue }}"
            placeholder="https://example.com/image.webp">
        <small>{{ $urlHelp }}</small>
    </div>
    <div class="field">
        <label for="{{ $idPrefix }}-upload">{{ $uploadLabel }}</label>
        <input id="{{ $idPrefix }}-upload" name="{{ $uploadField }}" type="file"
            accept="image/jpeg,image/png,image/webp">
        <small>{{ $uploadHelp }}</small>
    </div>
</div>
