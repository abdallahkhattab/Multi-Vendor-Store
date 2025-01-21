
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->

<input 
    type="{{ $type }}"
    name="{{ $name }}"
    placeholder="{{ $placeholder }}"
    value="{{ old($name, $value) }}"
    {{ $attributes->class([
        'form-control',
        'is-invalid' => $errors->has($name)
    ]) }}
>

@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

    
    
