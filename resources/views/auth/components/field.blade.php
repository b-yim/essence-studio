<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="input-wrap"><input id="{{ $name }}" name="{{ $name }}" type="{{ $type ?? 'text' }}" value="{{ ($type ?? 'text') === 'password' ? '' : old($name, $value ?? '') }}" autocomplete="{{ $autocomplete ?? $name }}" @if ($required ?? true) required @endif @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>
        @if (($type ?? 'text') === 'password')<button class="password-toggle" type="button" data-password-toggle="{{ $name }}" aria-label="Show {{ strtolower($label) }}" hidden>Show</button>@endif
    </div>
    @error($name)<small id="{{ $name }}-error" class="field-error">{{ $message }}</small>@enderror
</div>
