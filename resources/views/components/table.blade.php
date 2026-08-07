<div class="table-responsive bg-white rounded-4 shadow-sm border-0 mt-4">
    <table class="table table-hover mb-0 align-middle">
        <thead class="text-muted" style="background-color: #f4f6f9; font-size: 12px; letter-spacing: 0.8px; text-transform: uppercase;">
            <tr>
                {{-- Slot khusus untuk kolom header (th) --}}
                {{ $head }}
            </tr>
        </thead>
        <tbody style="font-size: 14px; color: #2d3748;">
            {{-- Slot default untuk baris data (tr & td) --}}
            {{ $slot }}
        </tbody>
    </table>
    
    @if(isset($footer))
        <div class="card-footer bg-white border-top border-light d-flex justify-content-between align-items-center py-3">
            {{ $footer }}
        </div>
    @endif
</div>