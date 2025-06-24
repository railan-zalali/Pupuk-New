<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .summary-item {
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Penjualan:</strong> {{ $summary['total_sales'] }} transaksi
        </div>
        <div class="summary-item">
            <strong>Total Nominal:</strong> Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Rata-rata Penjualan:</strong> Rp {{ number_format($summary['average_sale'], 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Faktur</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->customer ? $sale->customer->name : '-' }}</td>
                    <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    <td>
                        @if ($sale->payment_method == 'cash')
                            Tunai
                        @elseif ($sale->payment_method == 'transfer')
                            Transfer
                        @elseif ($sale->payment_method == 'credit')
                            Kredit
                        @else
                            {{ ucfirst($sale->payment_method) }}
                        @endif
                    </td>
                    <td>{{ $sale->trashed() ? 'Dibatalkan' : 'Selesai' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>
