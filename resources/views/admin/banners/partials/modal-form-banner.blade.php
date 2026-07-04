<div class="modal fade" id="modalFormBanner" tabindex="-1" role="dialog" aria-labelledby="modalFormBannerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 720px !important; width: 90vw !important;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2); border: none;">
            
            {{-- Tag <form> dipindah ke sini (di dalam modal-content) agar tidak merusak struktur flexbox Bootstrap --}}
            <form id="formBanner">

                {{-- Header Modal --}}
                <div class="modal-header bg-white" style="border-bottom: 1px solid #e5e7eb; padding: 1.1rem 1.5rem;">
                    <h5 class="modal-title font-weight-bold text-dark" id="modalFormBannerLabel" style="font-size: 1.1rem;">
                        Tambah Banner
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #6b7280; opacity: 1;">
                        <span aria-hidden="true" style="font-size: 28px; line-height: 20px;">&times;</span>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="modal-body" style="padding: 1.5rem;">

                    {{-- Foto Banner --}}
                    <div class="form-group">
                        <label class="font-weight-bold text-dark" style="font-size: 0.95rem; margin-bottom: 0.6rem;">Foto Banner</label>
                        <div id="uploadDropzone"
                             onclick="document.getElementById('input_gambar_banner').click();"
                             onmouseover="this.style.borderColor='#6f42c1'; this.style.backgroundColor='#faf9ff';"
                             onmouseout="this.style.borderColor='#d1d5db'; this.style.backgroundColor='#ffffff';"
                             style="position: relative; border: 2px dashed #d1d5db; border-radius: 10px; background-color: #fff; min-height: 280px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: border-color 0.2s, background-color 0.2s; margin-bottom: 1.25rem; overflow: hidden;">

                            <input type="file" name="gambar" id="input_gambar_banner" accept="image/jpg,image/jpeg,image/png,image/webp" hidden>

                            <div id="uploadPlaceholder" class="text-center" style="color: #6b7280;">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #9ca3af; margin-bottom: 0.75rem; display: block;"></i>
                                <p class="mb-0" style="font-size: 0.95rem; color: #374151;">Upload Foto Max 2MB</p>
                                <p class="mb-0" style="font-size: 0.8rem; color: #9ca3af; margin-top: 0.15rem;">Format: JPG, JPEG, PNG, WEBP</p>
                            </div>

                            <img id="uploadPreview" class="d-none" alt="Preview" style="width: 100%; height: 100%; max-height: 280px; object-fit: contain;">
                        </div>
                    </div>

                    {{-- Judul Banner --}}
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark" style="font-size: 0.95rem; margin-bottom: 0.6rem;">Judul Banner</label>
                        <input type="text" name="title" id="input_title_banner" class="form-control"
                            placeholder="Contoh: Promo weekend" required
                            style="border: 1px solid #d1d5db; border-radius: 8px; padding: 0.65rem 0.9rem; font-size: 0.9rem; color: #374151;">
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="modal-footer" style="border-top: none; padding: 0 1.5rem 1.5rem 1.5rem; gap: 12px;">
                    <button type="button" data-dismiss="modal"
                        onmouseover="this.style.backgroundColor='#dc2626';"
                        onmouseout="this.style.backgroundColor='#ef4444';"
                        style="flex: 1; padding: 0.7rem 1rem; border-radius: 8px; font-weight: 700; border: none; font-size: 0.95rem; background-color: #ef4444; color: #fff;">
                        Batal
                    </button>
                    <button type="submit"
                        onmouseover="this.style.backgroundColor='#2571d1';"
                        onmouseout="this.style.backgroundColor='#2f86eb';"
                        style="flex: 1; padding: 0.7rem 1rem; border-radius: 8px; font-weight: 700; border: none; font-size: 0.95rem; background-color: #2f86eb; color: #fff;">
                        Simpan
                    </button>
                </div>

            </form> {{-- Batas akhir penutup form --}}
            
        </div>
    </div>
</div>

{{-- LOGIC JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputGambar = document.getElementById('input_gambar_banner');
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('uploadPreview');

    if (inputGambar) {
        inputGambar.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>