<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
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
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
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
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="report-title">Laporan Laba Rugi</div>
        <div class="report-date">Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
        <div class="report-date">Generated: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Penjualan:</strong> Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Pembelian:</strong> Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Laba Kotor:</strong> Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Transaksi:</strong> {{ number_format($summary['total_transactions']) }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Transaksi</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Referensi</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($item->type) }}</td>
                        <td>{{ $item->reference }}</td>
                        <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }} - {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>