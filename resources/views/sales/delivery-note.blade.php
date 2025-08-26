<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan #{{ $sale->invoice_number }}</title>
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
            color: #111;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container {
            width: 190mm;
            min-height: 277mm;
            margin: 0 auto;
            background: #fff;
        }

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
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 8px;
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
        }

        .col {
            width: 50%;
        }

        .row {
            display: flex;
            margin-bottom: 4px;
        }

        .label {
            width: 90px;
        }

        .value {
            flex: 1;
        }

        .label::after {
            content: ":";
            margin: 0 8px 0 6px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px;
            table-layout: fixed;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
            word-wrap: break-word;
        }

        .table th {
            background: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .col-qty {
            width: 10%;
        }

        .col-name {
            width: 50%;
        }

        .col-unit {
            width: 12%;
        }

        .col-price {
            width: 14%;
        }

        .col-total {
            width: 14%;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 14px;
        }

        .stamp {
            font-size: 11px;
            font-weight: bold;
            color: #4c51bf;
            border: 2px solid #4c51bf;
            padding: 6px 10px;
            text-align: center;
        }

        .signs {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
        }

        .sign-box {
            width: 200px;
            text-align: center;
        }

        .sign-line {
            margin-top: 42px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        @media print {
            body {
                margin: 0;
            }

            .container {
                width: 190mm;
                min-height: auto;
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="company-name">{{ $storeSetting->store_name ?? 'TOKO "TANI MAKMUR"' }}</div>
            <div class="company-info">{{ $storeSetting->store_address ?? 'Jl. KOPO No. 316' }}
                {{ $storeSetting->store_phone ? 'Telp. ' . $storeSetting->store_phone : 'Telp. 6043233-6012850' }}<br>{{ $storeSetting->store_email ?? 'BANDUNG' }}
            </div>
        </div>

        <div class="title">Surat Jalan</div>

        <div class="info-grid">
            <div class="col">
                <div class="row"><span class="label">No. Faktur</span><span
                        class="value">{{ $sale->invoice_number }}</span></div>
                <div class="row"><span class="label">Tanggal</span><span
                        class="value">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</span></div>
                @if ($sale->customer)
                    <div class="row"><span class="label">Pelanggan</span><span
                            class="value">{{ $sale->customer->nama }}</span></div>
                @endif
            </div>
            <div class="col">
                @if ($sale->customer && $sale->customer->alamat)
                    <div class="row"><span class="label">Alamat</span><span
                            class="value">{{ $sale->customer->alamat }}, {{ $sale->customer->kecamatan_nama }}</span>
                    </div>
                @endif
                <div class="row"><span class="label">Pembayaran</span><span class="value">
                        @if ($sale->payment_method === 'cash')
                            Tunai
                        @elseif($sale->payment_method === 'transfer')
                            Transfer
                        @else
                            Kredit
                        @endif
                    </span></div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th class="col-qty">Qty</th>
                    <th class="col-name">Nama Barang</th>
                    <th class="col-unit">Satuan</th>
                    <th class="col-price">Harga</th>
                    <th class="col-total">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach ($sale->saleDetails as $detail)
                    @php
                        // Skip benih products in delivery note
                        if ($detail->product->category && strtolower($detail->product->category->name) === 'benih') {
                            continue;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td class="text-center">
                            {{ $detail->unit_name ?? ($detail->productUnit->unit->abbreviation ?? 'N/A') }}</td>
                        <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @php $subtotal += $detail->subtotal; @endphp
                @endforeach
                @for ($i = count($sale->saleDetails); $i < 10; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="footer">
            <div>
                <div class="text-right" style="font-weight:bold;">Total</div>
                <div class="text-right" style="font-size:13px; font-weight:bold;">Rp
                    {{ number_format($subtotal - ($sale->discount ?? 0), 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="signs">
            <div class="sign-box">
                <div>Tanda terima,</div>
                <div class="sign-line">
                    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                </div>
            </div>
            <div class="sign-box">
                <div>Hormat kami,</div>
                <div class="sign-line">{{ $sale->user->name }}</div>
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
