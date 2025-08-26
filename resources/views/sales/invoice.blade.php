<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoiceNumber }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #111;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container {
            width: 190mm;
            /* 210mm - 2x10mm margins */
            min-height: 277mm;
            /* 297mm - 2x10mm margins */
            margin: 0 auto;
            background: white;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 14px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .company-info {
            font-size: 11px;
            line-height: 1.4;
        }

        /* Invoice Info */
        .invoice-info {
            margin: 14px 0 6px 0;
        }

        .invoice-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: .8px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 6px;
        }

        .info-left,
        .info-right {
            width: 50%;
        }

        .info-item {
            margin-bottom: 4px;
            display: flex;
        }

        .info-label {
            width: 90px;
            color: #000;
        }

        .info-value {
            flex: 1;
            color: #000;
        }

        .info-label::after {
            content: ":";
            margin: 0 8px 0 6px;
        }

        /* Table Styles */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 0;
            table-layout: fixed;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 6px 6px;
            text-align: left;
            word-wrap: break-word;
        }

        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .items-table td {
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .item-no {
            width: 7%;
        }

        .item-name {
            width: 43%;
        }

        .item-unit {
            width: 10%;
        }

        .item-qty {
            width: 10%;
        }

        .item-price {
            width: 15%;
        }

        .item-total {
            width: 15%;
        }

        /* Summary */
        .summary {
            margin-top: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 4px;
        }

        .summary-label {
            width: 140px;
            text-align: right;
            padding-right: 14px;
        }

        .summary-value {
            width: 140px;
            text-align: right;
            padding-right: 6px;
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #000;
            padding-top: 4px;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            margin-top: 46px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        /* Notes */
        .notes {
            margin-top: 16px;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #fafafa;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
            }

            .container {
                margin: 0;
                width: 190mm;
                min-height: auto;
                background: initial;
                page-break-after: always;
            }
        }

        /* Utility */
        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $storeSetting->store_name ?? 'TOKO "TANI MAKMUR"' }}</div>
            <div class="company-info">
                {{ $storeSetting->store_address ?? 'Jl. KOPO No. 316' }}
                {{ $storeSetting->store_phone ? 'Telp. ' . $storeSetting->store_phone : 'Telp. 6043233-6012850' }}<br>
                {{ $storeSetting->store_email ?? 'BANDUNG' }}
            </div>
        </div>

        <!-- Invoice Title -->
        <div class="invoice-title">FAKTUR PENJUALAN</div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="info-row">
                <div class="info-left">
                    <div class="info-item">
                        <span class="info-label">No. Faktur</span>
                        <span class="info-value">{{ $invoiceNumber }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</span>
                    </div>
                    @if ($sale->customer)
                        <div class="info-item">
                            <span class="info-label">Pelanggan</span>
                            <span class="info-value">{{ $sale->customer->nama }}</span>
                        </div>
                    @endif
                </div>
                <div class="info-right">
                    <div class="info-item">
                        <span class="info-label">Pembayaran</span>
                        <span class="info-value">
                            @if ($sale->payment_method === 'cash')
                                Tunai
                            @elseif($sale->payment_method === 'transfer')
                                Transfer
                            @else
                                Kredit
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if ($sale->payment_status === 'paid')
                                Lunas
                            @elseif($sale->payment_status === 'partial')
                                Sebagian
                            @elseif($sale->payment_status === 'pending')
                                Tertunda
                            @else
                                {{ ucfirst($sale->payment_status) }}
                            @endif
                        </span>
                    </div>
                    @if ($sale->customer && $sale->customer->alamat)
                        <div class="info-item">
                            <span class="info-label">Alamat</span>
                            <span class="info-value">
                                {{ $sale->customer->alamat }}, {{ $sale->customer->kecamatan_nama }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="item-no">No</th>
                    <th class="item-name">Nama Barang</th>
                    <th class="item-unit">Satuan</th>
                    <th class="item-qty">Jumlah</th>
                    <th class="item-price">Harga</th>
                    <th class="item-total">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $subtotal = 0;
                @endphp
                @foreach ($nonSeedItems as $item)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">
                            {{ $item->unit_name ?? ($item->productUnit->unit->abbreviation ?? 'N/A') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @php $subtotal += $item->subtotal; @endphp
                @endforeach

                <!-- Empty rows to keep the table height consistent for dot-matrix printing -->
                @for ($i = count($nonSeedItems); $i < 10; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <div class="summary-label">Subtotal:</div>
                <div class="summary-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
            </div>
            @if ($sale->discount > 0)
                <div class="summary-row">
                    <div class="summary-label">Potongan:</div>
                    <div class="summary-value">Rp {{ number_format($sale->discount, 0, ',', '.') }}</div>
                </div>
            @endif
            <div class="summary-row total-row">
                <div class="summary-label">Total:</div>
                <div class="summary-value">Rp {{ number_format($subtotal - $sale->discount, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Notes -->
        @if ($sale->notes)
            <div class="notes">
                <div class="notes-title">Catatan:</div>
                <div>{{ $sale->notes }}</div>
            </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div>Tanda terima,</div>
                <div class="signature-line">
                    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                </div>
            </div>
            <div class="signature-box">
                <div>Hormat kami,</div>
                <div class="signature-line">{{ $sale->user->name }}</div>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
