<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .invoice-info {
            margin-bottom: 20px;
        }

        .customer-info {
            margin-bottom: 20px;
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

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .category-header {
            background-color: #f0f0f0;
            padding: 10px;
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-header">
        <h1>INVOICE</h1>
        <h2>#{{ $sale->invoice_number }}</h2>
    </div>

    <div class="invoice-info">
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i') }}</p>
        <p><strong>Kasir:</strong> {{ $sale->user->name }}</p>
    </div>

    <div class="customer-info">
        <h3>Informasi Pelanggan</h3>
        @if ($sale->customer)
            <p><strong>Nama:</strong> {{ $sale->customer->nama }}</p>
            <p><strong>NIK:</strong> {{ $sale->customer->nik }}</p>
            <p><strong>Alamat:</strong> {{ $sale->customer->alamat ?? '-' }}, {{ $sale->customer->desa_nama }},
                {{ $sale->customer->kecamatan_nama }}</p>
        @else
            <p>Pelanggan Umum</p>
        @endif
    </div>

    @php
        $groupedItems = $sale->saleDetails->groupBy(function ($detail) {
            return $detail->product->category->name;
        });
    @endphp

    @foreach ($groupedItems as $categoryName => $items)
        <div class="category-header">
            {{ $categoryName }}
        </div>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->productUnit->unit->name }} ({{ $detail->productUnit->unit->abbreviation }})
                        </td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="total-section">
        <p><strong>Total Belanja:</strong> Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
        <p><strong>Potongan:</strong> Rp {{ number_format($sale->discount, 0, ',', '.') }}</p>
        <p><strong>Total Setelah Potongan:</strong> Rp
            {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</p>

        @if ($sale->payment_method === 'credit')
            <p><strong>Uang Muka:</strong> Rp {{ number_format($sale->down_payment, 0, ',', '.') }}</p>
            <p><strong>Sisa Hutang:</strong> Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}</p>
            @if ($sale->due_date)
                <p><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }}</p>
            @endif
        @else
            <p><strong>Dibayar:</strong> Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</p>
            @if ($sale->change_amount > 0)
                <p><strong>Kembalian:</strong> Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</p>
            @endif
        @endif
    </div>

    @if ($sale->notes)
        <div class="notes">
            <h3>Catatan:</h3>
            <p>{{ $sale->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Invoice ini adalah bukti pembayaran yang sah</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Cetak Invoice</button>
    </div>
</body>

</html>
