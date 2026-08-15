/**
 * confirm-helper.js
 * Fungsi generic buat popup konfirmasi (SweetAlert2), dipakai ulang di semua halaman admin.
 * Butuh SweetAlert2 sudah aktif (config/adminlte.php -> plugins.Sweetalert2.active = true)
 */

/**
 * Tampilkan popup konfirmasi generic.
 * @param {Object} opsi
 * @param {string} opsi.title           - Judul popup
 * @param {string} opsi.text            - Isi pesan
 * @param {string} [opsi.icon]          - 'warning' | 'question' | 'info' | 'error' (default: 'warning')
 * @param {string} [opsi.confirmText]   - Teks tombol konfirmasi (default: 'Ya')
 * @returns {Promise} hasil dari Swal.fire(), pakai .then(result => { if (result.isConfirmed) {...} })
 */
function konfirmasiAksi(opsi) {
    return Swal.fire({
        icon: opsi.icon || 'warning',
        title: opsi.title,
        text: opsi.text,
        showCancelButton: true,
        confirmButtonText: opsi.confirmText || 'Ya',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#6f42c1',
    });
}

/**
 * Konfirmasi hapus lalu submit form beneran (untuk halaman yang backend-nya sudah jalan).
 * Dipakai untuk <button type="button" data-form-id="formHapusXxx" class="btn-konfirmasi-hapus-submit">
 * @param {string} formId    - ID <form> yang mau disubmit kalau user konfirmasi "Ya"
 * @param {string} namaItem  - Nama item yang mau dihapus, ditampilkan di pesan
 */
function konfirmasiHapusSubmit(formId, namaItem) {
    konfirmasiAksi({
        title: 'Hapus ' + (namaItem || 'data') + '?',
        text: 'Data yang dihapus tidak bisa dikembalikan.',
        icon: 'warning',
        confirmText: 'Ya, hapus',
    }).then(function (result) {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

/**
 * Konfirmasi toggle status (Aktifkan/Nonaktifkan) lalu submit form beneran.
 * @param {string} formId
 * @param {boolean} sedangAktif - true kalau status sekarang aktif (mau dinonaktifkan)
 * @param {string} namaItem
 */
function konfirmasiToggleStatusSubmit(formId, sedangAktif, namaItem) {
    var aksi = sedangAktif ? 'nonaktifkan' : 'aktifkan';
    konfirmasiAksi({
        title: (sedangAktif ? 'Nonaktifkan' : 'Aktifkan') + ' ' + (namaItem || 'data') + '?',
        text: 'Status akan diubah menjadi ' + (sedangAktif ? 'nonaktif' : 'aktif') + '.',
        icon: 'question',
        confirmText: 'Ya, ' + aksi,
    }).then(function (result) {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

/**
 * Konfirmasi aksi dummy (belum ada backend) — cukup tampilkan alert sukses, tanpa submit apa pun.
 * @param {string} title
 * @param {string} text
 * @param {string} pesanSukses
 */
function konfirmasiAksiDummy(title, text, pesanSukses) {
    konfirmasiAksi({
        title: title,
        text: text,
        icon: 'warning',
        confirmText: 'Ya, hapus',
    }).then(function (result) {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Berhasil!',
                text: pesanSukses,
                icon: 'success',
                confirmButtonColor: '#6f42c1',
            });
        }
    });
}