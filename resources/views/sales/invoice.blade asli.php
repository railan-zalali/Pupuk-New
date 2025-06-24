<!DOCTYPE html>
<html lang="en">

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

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-info {
            margin-bottom: 5px;
        }

        .invoice-info {
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-section h3 {
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .total-section {
            margin-left: auto;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .total-label {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
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
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak Invoice
        </button>
        <button onclick="window.close()"
            style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <div class="invoice-header">
        <div class="company-name">{{ config('app.name', 'Toko Pupuk') }}</div>
        <div class="company-info">Jl. Contoh No. 123, Kota</div>
        <div class="company-info">Telp: (123) 456-7890</div>
    </div>

    <div class="invoice-info">
        <h2 style="text-align: center; margin-bottom: 20px;">INVOICE #{{ $sale->invoice_number }}</h2>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <h3>Informasi Penjualan</h3>
            <div class="info-item">
                <div class="info-label">Tanggal</div>
                <div>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div>{{ $sale->trashed() ? 'Dibatalkan' : 'Selesai' }}</div>
            </div>
        </div>

        <div class="info-section">
            <h3>Informasi Pelanggan</h3>
            <div class="info-item">
                <div class="info-label">Nama Pelanggan</div>
                <div>{{ $sale->customer->nama ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Metode Pembayaran</div>
                <div>{{ ucfirst($sale->payment_method) }}</div>
                @if ($sale->payment_method === 'credit')
                    <div class="total-row">
                        <div class="total-label">Uang Muka (DP):</div>
                        <div>Rp {{ number_format($sale->down_payment, 0, ',', '.') }}</div>
                    </div>

                    @if ($sale->payment_status !== 'paid')
                        <div class="total-row">
                            <div class="total-label">Sisa Hutang:</div>
                            <div>Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="total-row">
                            <div class="total-label">Jatuh Tempo:</div>
                            <div>{{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }}</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="info-section">
            <h3>Informasi Pembayaran</h3>
            <div class="info-item">
                <div class="info-label">Total</div>
                <div>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Total:</div>
                <div>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Potongan:</div>
                <div>Rp {{ number_format($sale->discount, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Total Setelah Potongan:</div>
                <div>Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Jumlah Dibayar</div>
                <div>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Kembalian</div>
                <div>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleDetails as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->product->name }}<br>
                        <small style="color: #666;">{{ $detail->product->code }}</small>
                    </td>
                    <td>Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <div class="total-label">Total:</div>
            <div>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Jumlah Dibayar:</div>
            <div>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Kembalian:</div>
            <div>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih atas pembelian Anda!</p>
        <p style="font-size: 12px; color: #666;">Harap simpan invoice ini sebagai bukti pembelian.</p>
        <p style="font-size: 12px; color: #666;">Kasir: {{ $sale->user->name }}</p>
    </div>
</body>

</html>
