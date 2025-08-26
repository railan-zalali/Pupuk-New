<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Benih #{{ $invoiceNumber }}</title>
    <style>
        @page {
            size: A4 landscape;
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
            width: 277mm;
            /* 297 - 2*10mm */
            min-height: 190mm;
            /* 210 - 2*10mm */
            margin: 0 auto;
            background: #fff;
            position: relative;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
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

        /* Title & Info */
        .invoice-info {
            margin: 12px 0 6px 0;
        }

        .invoice-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 10px;
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
            display: flex;
            margin-bottom: 4px;
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

        /* Table */
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
            width: 6%;
        }

        .item-name {
            width: 42%;
        }

        .item-unit {
            width: 10%;
        }

        .item-qty {
            width: 10%;
        }

        .item-price {
            width: 16%;
        }

        .item-total {
            width: 16%;
        }

        /* Stamp */
        .ppn-stamp {
            position: absolute;
            bottom: 120px;
            left: 50%;
            transform: translateX(-50%) rotate(-3deg);
            border: 2px solid #4c51bf;
            padding: 8px 16px;
            text-align: center;
            background-color: rgba(255, 255, 255, .95);
        }

        .ppn-stamp-title {
            font-size: 14px;
            font-weight: bold;
            color: #4c51bf;
            margin-bottom: 2px;
        }

        .ppn-stamp-subtitle {
            font-size: 12px;
            font-weight: bold;
            color: #4c51bf;
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

        /* Signature */
        .signature-section {
            margin-top: 26px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            margin-top: 42px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        /* Notes & Badge */
        .notes {
            margin-top: 12px;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #fafafa;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .seed-badge {
            display: inline-block;
            background-color: #10b981;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            margin-left: 8px;
        }

        @media print {
            body {
                margin: 0;
            }

            .container {
                width: 277mm;
                min-height: auto;
                background: initial;
                page-break-after: always;
            }
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
        <div class="invoice-title">
            FAKTUR PENJUALAN BENIH
            <span class="seed-badge">BENIH</span>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="info-row">
                <div class="info-left">
                    <div class="info-item"><span class="info-label">No. Faktur</span><span
                            class="info-value">{{ $invoiceNumber }}</span></div>
                    <div class="info-item"><span class="info-label">Tanggal</span><span
                            class="info-value">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</span></div>
                    @if ($sale->customer)
                        <div class="info-item"><span class="info-label">Pelanggan</span><span
                                class="info-value">{{ $sale->customer->nama }}</span></div>
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
                        <div class="info-item"><span class="info-label">Alamat</span><span
                                class="info-value">{{ $sale->customer->alamat }},
                                {{ $sale->customer->kecamatan_nama }}</span></div>
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
                @foreach ($seedItems as $item)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $item->product->name }} <span class="seed-badge">BENIH</span></td>
                        <td class="text-center">
                            {{ $item->unit_name ?? ($item->productUnit->unit->abbreviation ?? 'N/A') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @php $subtotal += $item->subtotal; @endphp
                @endforeach

                <!-- Empty rows for dot-matrix consistency -->
                @for ($i = count($seedItems); $i < 10; $i++)
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

        <!-- PPN Stamp -->
        <div class="ppn-stamp">
            <div class="ppn-stamp-title">HARGA BELUM TERMASUK PPN</div>
            <div class="ppn-stamp-subtitle">PPN DIBEBASKAN</div>
        </div>

        <!-- Notes -->
        @if ($sale->notes)
            <div class="notes">
                <div class="notes-title">Catatan:</div>
                <div>{{ $sale->notes }}</div>
            </div>
        @endif

        <!-- Signature -->
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
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
