<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .report-period {
            color: #666;
            margin-bottom: 10px;
        }

        .summary {
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .summary-card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .card-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .card-value {
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        @media print {
            body {
                padding: 0;
            }

            @page {
                margin: 2cm;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="report-title">Sales Report</div>
        <div class="report-period">
            Period: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
        </div>
        <div>Generated on: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-card">
            <div class="card-title">Total Sales</div>
            <div class="card-value">{{ $summary['total_sales'] }}</div>
        </div>

        <div class="summary-card">
            <div class="card-title">Total Amount</div>
            <div class="card-value">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</div>
        </div>

        <div class="summary-card">
            <div class="card-title">Average Sale</div>
            <div class="card-value">Rp {{ number_format($summary['average_sale'], 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Payment Method</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->customer ? $sale->customer->name : '-' }}</td>
                    <td>{{ ucfirst($sale->payment_method) }}</td>
                    <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>{{ config('app.name') }} - Sales Report</div>
        <div>Printed by: {{ auth()->user()->name }}</div>
        <div>{{ now()->format('d/m/Y H:i') }}</div>
    </div>
</body>

</html>
