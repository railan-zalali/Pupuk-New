<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

trait ReportExport
{
    /**
     * Handle export based on request format
     *
     * @param array $data
     * @param string $view
     * @param string $filename
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    protected function handleExport($data, $view, $filename)
    {
        $type = request('type', 'view');

        switch ($type) {
            case 'excel':
                return $this->exportToExcel($data, $filename);

            case 'pdf':
                return $this->exportToPdf($data, $view, $filename);

            case 'print':
                return view('reports.print.' . $view, $data);

            default:
                return view('reports.' . $view, $data);
        }
    }

    protected function exportToExcel($data, $filename)
    {
        return Excel::download(
            new \App\Exports\ReportExport($data),
            $filename . '.xlsx'
        );
    }

    protected function exportToPdf($data, $view, $filename)
    {
        $pdf = Pdf::loadView("reports.exports.{$view}", $data);
        return $pdf->download("{$filename}.pdf");
    }

    protected function formatForCsv($item)
    {
        if ($item instanceof Sale) {
            return [
                $item->created_at->format('d/m/Y H:i'),
                $item->invoice_number,
                $item->customer ? $item->customer->name : '-',
                $item->total_amount,
                $item->payment_method
            ];
        }

        if ($item instanceof Product) {
            return [
                $item->code,
                $item->name,
                $item->category->name,
                $item->stock,
                $item->min_stock,
                $item->stock_value
            ];
        }

        // Default format jika tidak cocok dengan model yang ada
        return array_values((array) $item);
    }
}
