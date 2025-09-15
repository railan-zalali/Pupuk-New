<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hutang Piutang</title>
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
            margin-bottom: 30px;
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
        <div class="report-title">Laporan Hutang Piutang</div>
        <div class="report-date">Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
        <div class="report-date">Generated: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Hutang:</strong> Rp {{ number_format($summary['total_payables'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Piutang:</strong> Rp {{ number_format($summary['total_receivables'], 0, ',', '.') }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Hutang (Payables)</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Faktur</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Sisa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payables as $payable)
                    <tr>
                        <td>{{ $payable->created_at->format('d/m/Y') }}</td>
                        <td>{{ $payable->invoice_number }}</td>
                        <td>{{ $payable->supplier->name ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($payable->total_amount, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($payable->remaining_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($payable->payment_status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada hutang</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right text-bold"><strong>Total Hutang:</strong></td>
                    <td class="text-right text-bold">Rp {{ number_format($summary['total_payables'], 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Piutang (Receivables)</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Faktur</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Sisa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receivables as $receivable)
                    <tr>
                        <td>{{ $receivable->created_at->format('d/m/Y') }}</td>
                        <td>{{ $receivable->invoice_number }}</td>
                        <td>{{ $receivable->customer->nama ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($receivable->total_amount, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($receivable->payment_status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada piutang</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right text-bold"><strong>Total Piutang:</strong></td>
                    <td class="text-right text-bold">Rp {{ number_format($summary['total_receivables'], 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }} - {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>