<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsPengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Wajib dipanggil buat fitur hapus file lama

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = MsPengaturan::current();

        return view('admin.Pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        // 1. Validasi Inputan
            $validated = $request->validate([
            'wifi_ssid'     => 'required|string|max:50',
            'wifi_password' => 'required|string|max:50',
            'nama_toko'     => 'required|string|max:150',
            'alamat_toko'   => 'required|string|max:200',
            'slogan_header' => 'nullable|string|max:100', // Boleh kosong
            'logo_struk'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'ig'            => 'required|string|max:100',
            'wa'            => 'required|string|max:100',
            'tiktok'        => 'required|string|max:100',
        ]);

        $pengaturan = MsPengaturan::current();

        // 2. Logika Upload Logo
        if ($request->hasFile('logo_struk')) {
            $file = $request->file('logo_struk');
            // Bikin nama file unik pakai time() biar ga bentrok
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/img');

            // Cek dan hapus logo lama di public/assets/img kalau ada
            if ($pengaturan->logo_struk && File::exists($destinationPath . '/' . $pengaturan->logo_struk)) {
                File::delete($destinationPath . '/' . $pengaturan->logo_struk);
            }

            // Pindah file logo baru ke target folder
            $file->move($destinationPath, $filename);

            // Timpa array validated biar nama file masuk ke database
            $validated['logo_struk'] = $filename;
        }

        // 3. Simpan ke database
        $pengaturan->update($validated);

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan sistem dan struk berhasil diperbarui.');
    }
}