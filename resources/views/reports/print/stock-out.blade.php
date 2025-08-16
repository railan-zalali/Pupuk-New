<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Stock Out Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
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

        .report-date {
            color: #666;
            margin-bottom: 20px;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
        }

        .summary-item {
            flex: 1;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="report-title">Stock Out Report</div>
        <div class="report-date">Period: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
        <div class="report-date">Generated on: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Products:</strong><br> {{ number_format($summary['total_products']) }}
        </div>
        <div class="summary-item">
            <strong>Total Quantity:</strong><br> {{ number_format($summary['total_quantity']) }}
        </div>
        <div class="summary-item">
            <strong>Total Value:</strong><br> Rp {{ number_format($summary['total_value'], 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->category_name }}</td>
                    <td>{{ number_format($product->total_quantity) }}</td>
                    <td>Rp {{ number_format($product->total_value, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>{{ number_format($summary['total_quantity']) }}</strong></td>
                <td><strong>Rp {{ number_format($summary['total_value'], 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>{{ config('app.name') }} - Stock Out Report</p>
        <p>Printed by: {{ auth()->user()->name }}</p>
    </div>
</body>

</html>