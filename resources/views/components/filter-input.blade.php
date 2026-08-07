@props([
    'name',
    'label' => null,
    'type' => 'text',
    'width' => 'col-md-3 col-sm-6',
])

<div class="{{ $width }} mb-3 mb-md-0">
    @if ($label)
        <label for="{{ $name }}" class="custom-label">{{ $label }}</label>
    @endif

    {{-- Penambahan $attributes->merge() di sini --}}
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        value="{{ request($name) }}"
        {{ $attributes->merge(['class' => 'form-control custom-input']) }}>
</div>