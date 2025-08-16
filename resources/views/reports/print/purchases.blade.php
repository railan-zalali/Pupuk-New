<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
        }
        .report-title {
            font-size: 16px;
            margin: 5px 0;
        }
        .report-date {
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-bold {
            font-weight: bold;
        }
        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #333;
            page-break-inside: avoid;
        }
        .summary-item {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #333;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .footer {
                position: fixed;
                bottom: 0;
                right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="report-title">Laporan Pembelian</div>
        <div class="report-date">Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
        <div class="report-date">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Pembelian:</strong> {{ $summary['total_purchases'] }} transaksi
        </div>
        <div class="summary-item">
            <strong>Total Nominal:</strong> Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Rata-rata Pembelian:</strong> Rp {{ number_format($summary['average_purchase'], 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Faktur</th>
                <th>Supplier</th>
                <th class="text-right">Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $purchase->invoice_number }}</td>
                    <td>{{ $purchase->supplier->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($purchase->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }} - {{ now()->format('d/m/Y H:i:s') }}
    </div>

    <script>
        window.print();
    </script>
</body>
</html>