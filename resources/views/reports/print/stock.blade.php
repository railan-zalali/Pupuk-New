<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Stock Report - {{ now()->format('d/m/Y') }}</title>
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
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .summary-item {
            margin-bottom: 10px;
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
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .low-stock {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            text-align: center;
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
        <div class="report-title">Stock Report</div>
        <div class="report-date">Generated on: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Products:</strong> {{ number_format($summary['total_products']) }}
        </div>
        <div class="summary-item">
            <strong>Total Stock Value:</strong> Rp {{ number_format($summary['total_stock_value'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Low Stock Items:</strong> {{ number_format($summary['low_stock_count']) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Current Stock</th>
                <th>Min Stock</th>
                <th>Purchase Price</th>
                <th>Stock Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>
                        {{ $product->name }}
                        @if ($product->description)
                            <br><small style="color: #666;">{{ $product->description }}</small>
                        @endif
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td class="{{ $product->stock <= $product->min_stock ? 'low-stock' : '' }}">
                        {{ $product->stock }}
                    </td>
                    <td>{{ $product->min_stock }}</td>
                    <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($product->stock_value, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Total Stock Value:</strong></td>
                <td><strong>Rp {{ number_format($summary['total_stock_value'], 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>{{ config('app.name') }} - Stock Report</p>
        <p>Printed by: {{ auth()->user()->name }}</p>
    </div>
</body>

</html>
