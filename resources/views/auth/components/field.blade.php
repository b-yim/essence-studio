<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type ?? 'text' }}"
        value="{{ ($type ?? 'text') === 'password' ? '' : old($name) }}" autocomplete="{{ $autocomplete ?? $name }}"
        @if ($required ?? true) required @endif>
    @error($name)
        <small class="field-error">{{ $message }}</small>
    @enderror
</div>
