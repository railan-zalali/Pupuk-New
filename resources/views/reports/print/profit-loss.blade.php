<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Profit & Loss Report</title>
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
            display: flex;
            justify-content: space-between;
        }

        .summary-card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            width: 30%;
        }

        .card-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .card-amount {
            font-size: 20px;
            font-weight: bold;
        }

        .amount-positive {
            color: #059669;
        }

        .amount-negative {
            color: #dc2626;
        }

        .transactions {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
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
            margin-top: 50px;
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

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="report-title">Profit & Loss Report</div>
        <div class="report-period">
            Period: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
        </div>
        <div>Generated on: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-card">
            <div class="card-title">Total Sales</div>
            <div class="card-amount amount-positive">
                Rp {{ number_format($totalSales, 0, ',', '.') }}
            </div>
        </div>

        <div class="summary-card">
            <div class="card-title">Total Purchases</div>
            <div class="card-amount amount-negative">
                Rp {{ number_format($totalPurchases, 0, ',', '.') }}
            </div>
        </div>

        <div class="summary-card">
            <div class="card-title">Gross Profit</div>
            <div class="card-amount {{ $grossProfit >= 0 ? 'amount-positive' : 'amount-negative' }}">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="transactions">
        <div class="section-title">Sales Transactions</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice Number</th>
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->customer ? $sale->customer->name : '-' }}</td>
                        <td>{{ ucfirst($sale->payment_method) }}</td>
                        <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No sales found in this period.</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="4" class="text-right"><strong>Total Sales:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($totalSales, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Purchase Transactions</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice Number</th>
                    <th>Supplier</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                        <td>{{ $purchase->invoice_number }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                        <td class="text-right">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No purchases found in this period.</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="3" class="text-right"><strong>Total Purchases:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($totalPurchases, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div>{{ config('app.name') }} - Profit & Loss Report</div>
        <div>Printed by: {{ auth()->user()->name }}</div>
        <div>{{ now()->format('d/m/Y H:i') }}</div>
    </div>
</body>

</html>
