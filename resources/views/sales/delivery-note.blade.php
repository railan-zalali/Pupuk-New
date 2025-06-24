<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice - Toko Tani Makmur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white p-10">
    <div class="max-w-3xl mx-auto border border-gray-300 p-8 shadow-md bg-white">
        <div class="flex justify-between mt-2 text-sm">
            <div>
                <h1 class="text-center text-xl font-bold uppercase">Toko "Tani Makmur"</h1>
                <p class="text-center text-sm">
                    Jl. KOPO No. 316 Telp. 6043233-6012850 <br />
                    <span class="font-semibold">Bandung</span>
                </p>
            </div>
            <div>
                <p>Bandung, 7-4-2025</p>
                <p class="mt-2">Kepada Yth,</p>
                <p>Oha Nudin</p>
                <p>Babakan Ciparay</p>
                <p>Bandung</p>
            </div>
        </div>
        <div class="text-center mt-6 mb-2">
            <p class="text-lg font-semibold uppercase">Surat Jalan</p>
            <p class="text-lg font-semibold uppercase -mt-2">Faktur</p>
        </div>
        <div>
            <span class="font-semibold">Truck Pickup No:</span> ___________________
        </div>
        <!-- Tabel Barang -->
        <table class="w-full mt-6 border border-black text-sm">
            <thead class="bg-gray-200">
                <tr class="text-left">
                    <th class="border border-black px-2 py-1">Banyaknya</th>
                    <th class="border border-black px-2 py-1">Nama Barang</th>
                    <th class="border border-black px-2 py-1">Satuan</th>
                    <th class="border border-black px-2 py-1">Harga</th>
                    <th class="border border-black px-2 py-1">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-black px-2 py-1">1</td>
                    <td class="border border-black px-2 py-1">Kangkung Bangkok</td>
                    <td class="border border-black px-2 py-1">1 bh</td>
                    <td class="border border-black px-2 py-1">50.000</td>
                    <td class="border border-black px-2 py-1">50.000</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="flex justify-between mt-6">
            <div></div>
            <div class="mt-10 text-center">
                <p class="text-xs font-bold border border-purple-700 text-purple-700 px-4 py-1 inline-block">
                    HARGA BELUM TERMASUK PPN <br /> PPN DIBEBASKAN
                </p>
            </div>
            <div class="text-right text-sm">
                <p>Rp.</p>
                <p class="text-lg font-semibold">50.000</p>
            </div>
        </div>

        <!-- Cap PPN (Tengah) -->
        <div class="flex justify-between mt-6">
            <div>
                <p class="mt-8">Tanda terima,</p>
            </div>
            <!-- Hormat Kami -->
            <div class="mt-12 text-right">
                <p>Hormat kami,</p>
                <p class="mt-8">____________________</p>
            </div>
        </div>
    </div>
</body>

</html>
