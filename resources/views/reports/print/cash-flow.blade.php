<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arus Kas</title>
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
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
        .text-right {
            text-align: right;
        }
        .text-bold {
            font-weight: bold;
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
        <div class="report-title">Laporan Arus Kas</div>
        <div class="report-date">Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
        <div class="report-date">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Saldo Awal:</strong> Rp {{ number_format($summary['opening_balance'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Pemasukan:</strong> Rp {{ number_format($summary['total_debit'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Pengeluaran:</strong> Rp {{ number_format($summary['total_credit'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Saldo Akhir:</strong> Rp {{ number_format($summary['closing_balance'], 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Referensi</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Kredit</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentBalance = $summary['opening_balance'];
            @endphp
            @foreach($transactions as $transaction)
                @php
                    $currentBalance += $transaction->debit - $transaction->credit;
                @endphp
                <tr>
                    <td>{{ $transaction->date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->reference_number ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->debit, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->credit, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($currentBalance, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right text-bold"><strong>Saldo Akhir:</strong></td>
                <td class="text-right text-bold">Rp {{ number_format($summary['closing_balance'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }} - {{ now()->format('d/m/Y H:i:s') }}
    </div>

    <script>
        window.print();
    </script>
</body>
</html>