<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected Collection $transaksis;
    protected int $rowNumber = 0;

    public function __construct(Collection|array $transaksis)
    {
        $this->transaksis = collect($transaksis);
    }

    public function collection(): Collection
    {
        return $this->transaksis;
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE TRANSAKSI',
            'TANGGAL TRANSAKSI',
            'JENIS TRANSAKSI',
            'PELANGGAN',
            'KASIR',
            'NAMA PRODUK',
            'JUMLAH',
            'NOMINAL TRANSAKSI',
            'METODE',
            'SUMBER',
            'STATUS TRANSAKSI'
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        $tanggal = $row['tanggal_transaksi'] ?? null;
        $tanggalFormatted = $tanggal ? Carbon::parse($tanggal)->format('d-m-Y H:i') : '-';

        return [
            $this->rowNumber,
            $row['kode_transaksi'],
            $tanggalFormatted,
            $row['jenis_laporan'],
            $row['pelanggan'],
            $row['kasir'],
            $row['nama_produk'],
            $row['jumlah'],
            $row['nominal_transaksi'],
            $row['metode_pembayaran'],
            $row['sumber_booking'],
            ucfirst($row['status_sewa']),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Style Header (A..L, sekarang 12 kolom)
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF343A40'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFDEE2E6'],
                ],
            ],
        ]);

        $highestRow = $sheet->getHighestRow();

        if ($highestRow > 1) {
            $sheet->getStyle('A2:L' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFDEE2E6'],
                    ],
                ],
            ]);

            // NO
            $sheet->getStyle('A2:A' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // TANGGAL TRANSAKSI
            $sheet->getStyle('C2:C' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // JENIS TRANSAKSI
            $sheet->getStyle('D2:D' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // JUMLAH
            $sheet->getStyle('H2:H' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // METODE, SUMBER, STATUS TRANSAKSI
            $sheet->getStyle('J2:L' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // NOMINAL TRANSAKSI
            $sheet->getStyle('I2:I' . $highestRow)
                ->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');

            $sheet->getStyle('I2:I' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle('A' . $row . ':L' . $row)
                        ->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFF8F9FA'],
                            ],
                        ]);
                }
            }
        }

        return [];
    }
}