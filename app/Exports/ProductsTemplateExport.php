<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Contoh data sebagai panduan
        return [
            ['Pupuk Urea', 'PRD2023001', 'Pupuk Urea kualitas terbaik', '1', '1', '', 'Karung', '120000', '150000', '100', '10', '2023-12-31'],
            ['Pupuk NPK', 'PRD2023002', 'Pupuk NPK untuk tanaman', '1', '1', '', 'Karung', '130000', '160000', '80', '5', '2023-12-31']
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nama',
            'kode',
            'deskripsi',
            'category_id',
            'supplier_id',
            'unit_id',
            'unit_nama',
            'purchase_price',
            'selling_price',
            'stock',
            'min_stock',
            'expire_date'
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25, // nama
            'B' => 15, // kode
            'C' => 30, // deskripsi
            'D' => 10, // category_id
            'E' => 10, // supplier_id
            'F' => 10, // unit_id
            'G' => 15, // unit_nama
            'H' => 15, // purchase_price
            'I' => 15, // selling_price
            'J' => 10, // stock
            'K' => 10, // min_stock
            'L' => 15, // expire_date
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Styling header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ]);

        // Menambahkan komentar pada header untuk panduan
        $sheet->getComment('A1')->getText()->createTextRun('Nama produk (wajib)');
        $sheet->getComment('B1')->getText()->createTextRun('Kode produk (otomatis jika kosong)');
        $sheet->getComment('C1')->getText()->createTextRun('Deskripsi produk');
        $sheet->getComment('D1')->getText()->createTextRun('ID kategori produk');
        $sheet->getComment('E1')->getText()->createTextRun('ID supplier produk');
        $sheet->getComment('F1')->getText()->createTextRun('ID satuan produk');
        $sheet->getComment('G1')->getText()->createTextRun('Nama satuan (alternatif untuk unit_id)');
        $sheet->getComment('H1')->getText()->createTextRun('Harga beli produk');
        $sheet->getComment('I1')->getText()->createTextRun('Harga jual produk');
        $sheet->getComment('J1')->getText()->createTextRun('Stok awal produk');
        $sheet->getComment('K1')->getText()->createTextRun('Stok minimal produk');
        $sheet->getComment('L1')->getText()->createTextRun('Tanggal kadaluarsa (format: YYYY-MM-DD)');

        return $sheet;
    }
}
