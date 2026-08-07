@props([
    'title',
    'value',
    'unit' => null,
    'icon',
    'color' => 'primary',
])

<div class="card custom-card border-0 shadow-sm h-100">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="custom-card-title">{{ $title }}</span>
            <div class="icon-circle bg-light-{{ $color }} text-{{ $color }}">
                <i class="fas {{ $icon }}"></i>
            </div>
        </div>
        <h3 class="custom-card-value">
            {{ $value }}
            @if ($unit)
                <span class="text-muted font-weight-normal" style="font-size: 14px;">{{ $unit }}</span>
            @endif
        </h3>
    </div>
</div>