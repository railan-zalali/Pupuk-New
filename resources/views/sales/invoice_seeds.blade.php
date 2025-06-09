<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Jalan Faktur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-8 font-mono">
    <div class="max-w-3xl mx-auto border p-6 space-y-4">
        <div class="text-center">
            <h1 class="text-xl font-bold uppercase">Toko "Tani Makmur"</h1>
            <p class="text-sm">Jl. Kopo No. 316 Telp. 6043233-6012850 <br><span class="uppercase">Bandung</span></p>
        </div>

        <div class="flex justify-between items-start">
            <div class="flex-1"></div>
            <div class="text-center flex-1">
                <div class="text-xl font-bold underline leading-tight">Surat Jalan<br>Faktur</div>
            </div>
            <div class="flex-1 text-sm text-right space-y-1">
                <p>Bandung, <span class="underline">{{ \Carbon\Carbon::parse($sale->date)->format('d-m-Y') }}</span></p>
                <p>Kepada Yth,</p>
                <p>
                    @if ($sale->customer)
                        <span class="underline">{{ $sale->customer->nama }}</span><br>
                        <span class="underline">{{ $sale->customer->alamat }}</span><br>
                        <span class="underline">{{ $sale->customer->kecamatan_nama }}</span>
                    @else
                        <span class="underline">-</span><br>
                        <span class="underline">-</span><br>
                        <span class="underline">-</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex gap-4 text-sm mt-2">
            <div class="w-1/2">TRUCK / PICKUP No.: <span class="underline">{{ $sale->vehicle_number ?? '' }}</span>
            </div>
        </div>

        <table class="w-full text-sm border mt-4 border-black border-collapse">
            <thead class="bg-gray-200">
                <tr class="border border-black">
                    <th class="border border-black p-1">Banyaknya</th>
                    <th class="border border-black p-1">Nama Barang</th>
                    <th class="border border-black p-1">Satuan</th>
                    <th class="border border-black p-1">Harga</th>
                    <th class="border border-black p-1">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $seedItems = $sale->saleDetails->filter(function ($detail) {
                        return strtolower($detail->product->category->name) === 'bibit';
                    });
                @endphp
                @foreach ($seedItems as $detail)
                    <script>
                        console.log('{{ $detail->quantity }}');
                        console.log('{{ $detail->productUnit }}');
                    </script>
                    <tr class="border border-black">
                        <td class="border border-black p-2 text-center">{{ $detail->quantity }}</td>
                        <td class="border border-black p-2">{{ $detail->product->name }}</td>
                        <td class="border border-black p-2 text-center">{{ $detail->productUnit->unit->abbreviation }}
                        </td>
                        <td class="border border-black p-2 text-right">{{ number_format($detail->price, 0, ',', '.') }}
                        </td>
                        <td class="border border-black p-2 text-right">
                            {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @for ($i = $seedItems->count(); $i < 8; $i++)
                    <tr class="border border-black">
                        <td class="border border-black p-2">&nbsp;</td>
                        <td class="border border-black p-2"></td>
                        <td class="border border-black p-2"></td>
                        <td class="border border-black p-2"></td>
                        <td class="border border-black p-2"></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="flex justify-between items-end mt-12">
            <div class="text-left">
                <p>Tanda terima,</p>
                <div class="mt-12">
                    <p>(____________)</p>
                </div>
            </div>
            <div class="flex flex-col items-center">
                <div
                    class="text-xs border border-purple-600 text-purple-800 p-2 w-52 text-center uppercase font-bold rotate-[-6deg] bg-white mt-8">
                    Harga belum termasuk PPN<br>PPN DIBEBASKAN
                </div>
            </div>
            <div class="text-right">
                <p>Hormat kami,</p>
                <div class="mt-12">
                    <p>(____________)</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <div class="text-right">
                <p>Rp.</p>
                <p class="text-xl font-bold mt-1">{{ number_format($seedItems->sum('subtotal'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="no-print mt-4 text-center">
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded">Cetak Invoice</button>
    </div>
</body>

</html>
