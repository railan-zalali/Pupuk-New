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
                // Check if created_at is an object before calling format
                if (is_object($item) && isset($item->created_at) && is_object($item->created_at)) {
                    $row[] = $item->created_at->format('d/m/Y H:i');
                } elseif (is_array($item) && isset($item['created_at']) && is_object($item['created_at'])) {
                    $row[] = $item['created_at']->format('d/m/Y H:i');
                } else {
                    $row[] = '-';
                }
            } elseif ($field === 'total_amount' || $field === 'amount') {
                $row[] = number_format($this->getItemValue($item, $field), 0, ',', '.');
            } elseif ($field === 'payment_method') {
                $row[] = $this->formatPaymentMethod($this->getItemValue($item, $field));
            } elseif ($field === 'customer_name') {
                if (is_object($item) && isset($item->customer) && is_object($item->customer)) {
                    $row[] = $item->customer->nama ?? '-';
                } else {
                    $row[] = '-';
                }
            } elseif ($field === 'supplier_name') {
                if (is_object($item) && isset($item->supplier) && is_object($item->supplier)) {
                    $row[] = $item->supplier->name ?? '-';
                } else {
                    $row[] = '-';
                }
            } else {
                $row[] = $this->getItemValue($item, $field);
            }
        }
        return $row;
    }
    
    /**
     * Safely get a value from an item, handling both object properties and array keys
     *
     * @param mixed $item
     * @param string $field
     * @return mixed
     */
    protected function getItemValue($item, $field)
    {
        if (is_object($item)) {
            return $item->{$field} ?? null;
        } elseif (is_array($item)) {
            return $item[$field] ?? null;
        }
        return null;
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
