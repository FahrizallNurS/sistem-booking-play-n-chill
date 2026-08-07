@props([
    'action' => null,
    'method' => 'GET',
])

<div class="card custom-card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ $action ?? url()->current() }}" method="{{ $method }}">
            <div class="row align-items-end">

                {{ $slot }}

                <div class="col-12 col-md-{{ isset($actions) ? '5' : 'auto' }} mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold" style="border-radius: 6px;">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ $action ?? url()->current() }}" class="btn btn-light px-4 ml-2 font-weight-bold text-muted" style="border-radius: 6px; border: 1px solid #e1e4e6;">
                        <i class="fas fa-sync-alt mr-2"></i> Reset
                    </a>

                    @isset($actions)
                        {{ $actions }}
                    @endisset
                </div>
            </div>
        </form>
    </div>
</div>

@once
    <style>
    .custom-label {
        font-size: 11px;
        font-weight: 700;
        color: #8a949f;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
    }
    .custom-input {
        border-radius: 6px !important;
        border: 1px solid #e1e4e6 !important;
        height: 42px !important;
        font-size: 14px;
        color: #495057;
    }
    .custom-input:focus {
        border-color: #6f42c1 !important;
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.15) !important;
    }
    </style>
@endonce