<div {{ $attributes->merge(['class' => 'card shadow-sm border-0']) }} style="border-radius: 8px;">
    
    @if(isset($title) || isset($header) || isset($subtitle))
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <div class="d-flex justify-content-between align-items-center w-100">
            
            {{-- Bagian Judul & Subjudul --}}
            <div class="card-title-group">
                @if(isset($title))
                    <h3 class="card-title font-weight-bold mb-0" style="color: #6f42c1; font-size: 1.1rem; float: none;">
                        {{ $title }}
                    </h3>
                @endif
                
                @if(isset($subtitle))
                    <p class="text-muted mb-0 mt-1" style="font-size: 13px; font-weight: 400;">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            {{-- Bagian Tools/Tombol di Kanan --}}
            @if(isset($header))
                <div class="card-tools m-0">
                    {{ $header }}
                </div>
            @endif

        </div>
    </div>
    @endif

    <div class="card-body {{ $bodyClass ?? '' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
    <div class="card-footer bg-white" style="border-radius: 0 0 8px 8px;">
        {{ $footer }}
    </div>
    @endif

</div>