<?php

namespace App\Services\Concerns;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

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

    
}