@props([
    'percent',
    'color' => 'primary',
    'height' => null, // contoh: '6px'. Kosongkan untuk pakai style custom-progress bawaan card.
])

<div
    class="progress mb-1 {{ $height ? '' : 'custom-progress' }}"
    @if ($height)
        style="height: {{ $height }}; border-radius: 10px; background-color: #edf2f7;"
    @endif
>
    <div class="progress-bar bg-{{ $color }}" style="width: {{ $percent }}%"></div>
</div>