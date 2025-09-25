<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order #{{ $purchase->invoice_number }}</title>
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

        /* Purchase Order Info */
        .purchase-info {
            margin: 14px 0 6px 0;
        }

        .purchase-title {
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
            width: 38%;
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

        .item-status {
            width: 5%;
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
            width: 180px;
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

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-partial {
            background-color: #d1ecf1;
            color: #0c5460;
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

            .no-print {
                display: none !important;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background: #0056b3;
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
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>

    <div class="container">
        <!-- Header -->
        <div class="header">
            @php
                $storeSetting = \App\Models\StoreSetting::first();
            @endphp
            <div class="company-name">{{ $storeSetting->store_name ?? 'TOKO "TANI MAKMUR"' }}</div>
            <div class="company-info">
                {{ $storeSetting->store_address ?? 'Jl. KOPO No. 316' }}
                {{ $storeSetting->store_phone ? 'Telp. ' . $storeSetting->store_phone : 'Telp. 6043233-6012850' }}<br>
                {{ $storeSetting->store_email ?? 'BANDUNG' }}
            </div>
        </div>

        <!-- Purchase Order Title -->
        <div class="purchase-title">PURCHASE ORDER</div>

        <!-- Purchase Order Info -->
        <div class="purchase-info">
            <div class="info-row">
                <div class="info-left">
                    <div class="info-item">
                        <span class="info-label">No. PO</span>
                        <span class="info-value">{{ $purchase->invoice_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</span>
                    </div>
                    @if($purchase->due_date)
                    <div class="info-item">
                        <span class="info-label">Jatuh Tempo</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($purchase->due_date)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Dibuat Oleh</span>
                        <span class="info-value">{{ $purchase->user->name }}</span>
                    </div>
                </div>
                <div class="info-right">
                    <div class="info-item">
                        <span class="info-label">Supplier</span>
                        <span class="info-value">{{ $purchase->supplier->name }}</span>
                    </div>
                    @if($purchase->supplier->phone)
                    <div class="info-item">
                        <span class="info-label">Telepon</span>
                        <span class="info-value">{{ $purchase->supplier->phone }}</span>
                    </div>
                    @endif
                    @if($purchase->supplier->email)
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $purchase->supplier->email }}</span>
                    </div>
                    @endif
                    @if($purchase->supplier->address)
                    <div class="info-item">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">{{ $purchase->supplier->address }}</span>
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
                    <th class="item-status">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $subtotal = 0;
                @endphp
                @foreach ($purchase->purchaseDetails as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>
                            {{ $detail->product->name }}
                            @if($detail->product->code)
                                <br><small style="color: #666;">{{ $detail->product->code }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $detail->unit->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ number_format($detail->quantity, 0) }}</td>
                        <td class="text-right">{{ number_format($detail->purchase_price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($detail->received_quantity >= $detail->quantity)
                                <span class="status-badge status-completed">OK</span>
                            @elseif($detail->received_quantity > 0)
                                <span class="status-badge status-partial">P</span>
                            @else
                                <span class="status-badge status-pending">-</span>
                            @endif
                        </td>
                    </tr>
                    @php $subtotal += $detail->subtotal; @endphp
                @endforeach

                <!-- Empty rows to keep the table height consistent -->
                @for ($i = count($purchase->purchaseDetails); $i < 8; $i++)
                    <tr>
                        <td>&nbsp;</td>
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
            <div class="summary-row total-row">
                <div class="summary-label">Total:</div>
                <div class="summary-value">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Notes -->
        @if ($purchase->notes)
            <div class="notes">
                <div class="notes-title">Catatan:</div>
                <div>{{ $purchase->notes }}</div>
            </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div>Dibuat oleh,</div>
                <div class="signature-line">{{ $purchase->user->name }}</div>
            </div>
            <div class="signature-box">
                <div>Disetujui oleh,</div>
                <div class="signature-line">
                    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                </div>
            </div>
            <div class="signature-box">
                <div>Diterima oleh,</div>
                <div class="signature-line">
                    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                </div>
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