<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Contoh data untuk template
        return [
            // Baris contoh 1 (NIK tanpa tanda hubung)
            [
                '1234567890123456', // NIK tanpa tanda hubung
                'Nama Pelanggan', // Nama
                'Jl. Contoh Alamat No. 123', // Alamat
                'Nama Desa', // Desa
                'Nama Kecamatan', // Kecamatan
                'Nama Kabupaten', // Kabupaten
                'JAWA BARAT', // Provinsi
            ],
            // Baris contoh 2 (NIK dengan tanda hubung)
            [
                '9876-5432-1098-7654', // NIK dengan tanda hubung
                'Nama Pelanggan Lain', // Nama
                'Jl. Contoh Alamat Lain No. 456', // Alamat
                'Nama Desa Lain', // Desa
                'Nama Kecamatan Lain', // Kecamatan
                'Nama Kabupaten Lain', // Kabupaten
                'JAWA BARAT', // Provinsi
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Alamat',
            'Desa',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo color
            ],
        ]);

        // Style untuk contoh data
        $sheet->getStyle('A2:G3')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EEF2FF'], // Light indigo
            ],
        ]);

        // Tambahkan border ke semua sel
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Tambahkan komentar pada header untuk petunjuk
        $sheet->getComment('A1')->getText()->createTextRun('Nomor Induk Kependudukan (16 digit). Format dengan atau tanpa tanda hubung diterima (contoh: 1234567890123456 atau 1234-5678-9012-3456)');
        $sheet->getComment('B1')->getText()->createTextRun('Nama lengkap pelanggan');
        $sheet->getComment('C1')->getText()->createTextRun('Alamat lengkap pelanggan');
        $sheet->getComment('D1')->getText()->createTextRun('Nama desa/kelurahan');
        $sheet->getComment('E1')->getText()->createTextRun('Nama kecamatan');
        $sheet->getComment('F1')->getText()->createTextRun('Nama kabupaten/kota');
        $sheet->getComment('G1')->getText()->createTextRun('Nama provinsi (default: JAWA BARAT)');
    }
}