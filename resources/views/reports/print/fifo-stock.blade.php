<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FIFO Stock Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            font-size: 12px;
            color: #666;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .no-print {
            text-align: center;
            margin: 20px 0;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
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
    <div class="no-print">
        <button onclick="window.print()" style="padding: 5px 10px; background: #4CAF50; color: white; border: none; cursor: pointer;">
            Print Report
        </button>
        <button onclick="window.close()" style="padding: 5px 10px; background: #f44336; color: white; border: none; cursor: pointer;">
            Close
        </button>
    </div>

    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="report-title">FIFO Stock Report</div>
        <div class="report-date">Generated on: {{ now()->format('d/m/Y H:i') }}</div>
        <div class="report-date">Period: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Products:</strong> {{ number_format($summary['total_products']) }}
        </div>
        <div class="summary-item">
            <strong>Total FIFO Value:</strong> Rp {{ number_format($summary['total_fifo_value'], 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Active Batches:</strong> {{ number_format($summary['total_active_batches']) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Current Stock</th>
                <th>FIFO Value</th>
                <th>Batch Count</th>
                <th>Oldest Batch</th>
                <th>Newest Batch</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>Rp {{ number_format($product->fifo_value, 0, ',', '.') }}</td>
                <td>{{ $product->batch_count }}</td>
                <td>{{ $product->oldest_batch }}</td>
                <td>{{ $product->newest_batch }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer generated report and does not require a signature.</p>
    </div>
</body>
</html>