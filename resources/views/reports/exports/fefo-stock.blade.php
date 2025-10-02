<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FEFO Stock Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .report-title { font-size: 16px; margin: 5px 0; }
        .report-date { font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .summary { margin: 20px 0; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; }
        .summary-item { margin-bottom: 5px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; padding-top: 10px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="report-title">FEFO Stock Report</div>
        <div class="report-date">Generated on: {{ now()->format('d/m/Y H:i') }}</div>
        <div class="report-date">Period: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
        <div class="report-date">Category: {{ $categoryId ? ($categories->firstWhere('id', $categoryId)->name ?? 'Unknown') : 'All' }} | Supplier: {{ $supplierId ? ($suppliers->firstWhere('id', $supplierId)->name ?? 'Unknown') : 'All' }}</div>
    </div>

    <div class="summary">
        <div class="summary-item"><strong>Total Products:</strong> {{ number_format($summary['total_products']) }}</div>
        <div class="summary-item"><strong>Total FEFO Value:</strong> Rp {{ number_format($summary['total_fefo_value'], 0, ',', '.') }}</div>
        <div class="summary-item"><strong>Total Active Batches:</strong> {{ number_format($summary['total_active_batches']) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Current Stock</th>
                <th>FEFO Value</th>
                <th>Batch Count</th>
                <th>Nearest Expiry</th>
                <th>Farthest Expiry</th>
                <th>Expiry Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ $product->stock }}</td>
                <td>Rp {{ number_format($product->fefo_value, 0, ',', '.') }}</td>
                <td>{{ $product->batch_count }}</td>
                <td>{{ $product->nearest_expiry }}</td>
                <td>{{ $product->farthest_expiry }}</td>
                <td>{{ $product->expiry_status ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer generated report and does not require a signature.</p>
    </div>
</body>
</html>