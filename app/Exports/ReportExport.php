<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['items']);
    }

    public function headings(): array
    {
        return array_keys($this->data['headers']);
    }

    public function map($item): array
    {
        $row = [];
        foreach ($this->data['headers'] as $header => $field) {
            if ($field === 'date') {
                $row[] = $item->created_at->format('d/m/Y H:i');
            } elseif ($field === 'total_amount' || $field === 'amount') {
                $row[] = number_format($item->$field, 0, ',', '.');
            } elseif ($field === 'payment_method') {
                $row[] = $this->formatPaymentMethod($item->$field);
            } else {
                $row[] = $item->$field;
            }
        }
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    protected function formatPaymentMethod($method)
    {
        return match ($method) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            'credit' => 'Kredit',
            default => ucfirst($method)
        };
    }
}
