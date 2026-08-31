<?php

namespace App\Services\Concerns;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;


trait GeneratesStrukPdf
{
    /**
     * Render view struk ke PDF 58mm dan simpan ke public/assets/struk/{namaFile}.pdf.
     * $data disusun sendiri-sendiri oleh service pemanggil (booking / F&B),
     * trait ini cuma tanggung jawab render + simpan filenya.
     *
     * @return string path relatif (relatif ke public/) untuk dipakai asset()
     */
    protected function simpanStrukPdf(string $view, array $data, string $namaFile): string
    {
        $pdf = Pdf::loadView($view, $data)->setPaper([0, 0, 132, 2000]);

        $path = public_path('assets/struk');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $pdf->save($path . '/' . $namaFile . '.pdf');

        return 'assets/struk/' . $namaFile . '.pdf';
        
    }

    protected function generateNomorNota(): string
    {
        $tanggal = now()->format('Y-m-d');

        DB::table('tr_nomor_urut_harian')->insertOrIgnore([
            'tanggal'         => $tanggal,
            'urutan_terakhir' => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $urutan = DB::table('tr_nomor_urut_harian')
            ->where('tanggal', $tanggal)
            ->lockForUpdate()
            ->value('urutan_terakhir') + 1;

        DB::table('tr_nomor_urut_harian')
            ->where('tanggal', $tanggal)
            ->update([
                'urutan_terakhir' => $urutan,
                'updated_at'      => now(),
            ]);

        return now()->format('ymd') . '/' . config('struk.kode_cabang') . '/' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }
    
}