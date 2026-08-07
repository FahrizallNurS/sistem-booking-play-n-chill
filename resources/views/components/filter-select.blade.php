@props([
    'name',
    'label' => null,
    'options' => [],
    'placeholder' => null,
    'width' => 'col-md-3 col-sm-6',
])

<div class="{{ $width }} mb-3 mb-md-0">
    @if ($label)
        <label for="{{ $name }}" class="custom-label">{{ $label }}</label>
    @endif

    {{-- Penambahan $attributes->merge() di sini --}}
    <select name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge(['class' => 'form-control custom-input']) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $text)
            <option value="{{ $value }}" {{ request($name) == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>