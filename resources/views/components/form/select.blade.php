@props([
    'name',
    'placeholder' => null,
    'options' => []
])

<select name="{{ $name }}" {{ $attributes->merge(['class' => 'form-select']) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($options as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
