@props([
    'title',
    'value',
    'unit' => null,
    'icon',
    'color',
    'id' => null
])

<div class="card custom-card border-0 shadow-sm h-100">
    <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
            <p class="mb-1 text-muted font-weight-bold" style="font-size: 13px;">{{ $title }}</p>
            <div class="d-flex align-items-baseline">
                <h3 class="font-weight-bold mb-0 text-dark" id="{{ $id }}">{{ $value }}</h3>
                @if($unit)
                    <span class="ml-1 text-muted" style="font-size: 14px;">{{ $unit }}</span>
                @endif
            </div>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center text-{{ $color }} bg-{{ $color }}-light" style="width: 48px; height: 48px; background-color: rgba(var(--{{ $color }}-rgb, 0,123,255), 0.1);">
            <i class="fas {{ $icon }} fa-lg"></i>
        </div>
    </div>
</div>