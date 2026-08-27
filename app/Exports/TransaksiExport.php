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

        return [
            $this->rowNumber,
            $row['kode_transaksi'],
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
        // Style Header
        $sheet->getStyle('A1:K1')->applyFromArray([
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
            $sheet->getStyle('A2:K' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFDEE2E6'],
                    ],
                ],
            ]);

            $sheet->getStyle('A2:A' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('C2:C' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('G2:G' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('I2:K' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('H2:H' . $highestRow)
                ->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');

            $sheet->getStyle('H2:H' . $highestRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle('A' . $row . ':K' . $row)
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