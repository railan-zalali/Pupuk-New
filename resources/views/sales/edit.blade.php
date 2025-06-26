<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2
                        class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-600 dark:text-indigo-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit Draft Transaksi') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $sale->invoice_number }} - Dibuat {{ $sale->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('sales.drafts') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Draft Info Banner -->
        <div
            class="rounded-lg bg-yellow-50 dark:bg-yellow-900/20 p-4 mb-6 border border-yellow-200 dark:border-yellow-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Anda sedang mengedit draft
                        transaksi</h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <p>Perubahan tidak akan mempengaruhi stok produk hingga transaksi diselesaikan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="rounded-lg bg-red-50 dark:bg-red-900/30 p-4 mb-6 border border-red-200 dark:border-red-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Ada kesalahan dalam pengisian
                            form:</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('sales.update', $sale) }}" method="POST" id="editDraftForm" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Card: Informasi Penjualan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informasi Penjualan
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Detail transaksi dan informasi
                        pelanggan</p>
                </div>

                <input type="hidden" name="customer_name" id="customer_name">

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="col-span-1">
                            <x-input-label for="invoice_number" value="Nomor Faktur"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </div>
                                <x-text-input id="invoice_number" name="invoice_number" type="text"
                                    class="pl-10 mt-1 block w-full bg-gray-50 dark:bg-gray-700/50 border-gray-300 dark:border-gray-600"
                                    :value="$sale->invoice_number" readonly />
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nomor faktur tidak dapat diubah</p>
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="date" value="Tanggal Transaksi"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-text-input id="date" name="date" type="date"
                                    class="pl-10 mt-1 block w-full border-gray-300 dark:border-gray-600"
                                    :value="old('date', \Carbon\Carbon::parse($sale->date)->format('Y-m-d'))" />
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2 lg:col-span-1">
                            <x-input-label for="customer_select" value="Pelanggan"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1">
                                <select id="customer_select" name="customer_id"
                                    class="select2 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">-- Pilih Pelanggan atau Ketik Nama Baru --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->nama }} - {{ $customer->kecamatan_nama }},
                                            {{ $customer->kabupaten_nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih pelanggan yang ada atau
                                    ketik nama baru</p>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="payment_method" value="Metode Pembayaran"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <select id="payment_method" name="payment_method"
                                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>
                                        Tunai</option>
                                    <option value="transfer"
                                        {{ $sale->payment_method == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="credit" {{ $sale->payment_method == 'credit' ? 'selected' : '' }}>
                                        Kredit</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="vehicle_type" value="Jenis Kendaraan"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <select id="vehicle_type" name="vehicle_type"
                                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="Truk" {{ $sale->vehicle_type == 'Truk' ? 'selected' : '' }}>Truk
                                    </option>
                                    <option value="Pickup" {{ $sale->vehicle_type == 'Pickup' ? 'selected' : '' }}>
                                        Pickup</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="vehicle_number" value="Nomor Kendaraan"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                    </svg>
                                </div>
                                <x-text-input id="vehicle_number" name="vehicle_number" type="text"
                                    class="pl-10 mt-1 block w-full border-gray-300 dark:border-gray-600"
                                    :value="old('vehicle_number', $sale->vehicle_number)" placeholder="Contoh: B 1234 XYZ" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Item Penjualan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Item Penjualan
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Ubah produk dan jumlah yang
                            dibeli</p>
                    </div>

                    <button type="button" onclick="addItem()"
                        class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Item
                    </button>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-md">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 w-20 text-center">
                                        Stok
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                        Satuan
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                        Harga
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                        Subtotal
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="saleItems"
                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($sale->saleDetails as $index => $detail)
                                    <tr
                                        class="sale-item hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                        <td class="px-4 py-3">
                                            <select name="product_id[]" required
                                                class="product-select w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-stock="{{ $product->stock }}"
                                                        {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div
                                                class="stock-display text-sm text-gray-900 dark:text-gray-300 font-medium bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-md inline-block min-w-[60px] text-center">
                                                {{ $detail->product->stock }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <select name="unit_id[]" required
                                                class="unit-select w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                                data-selected="{{ $detail->product_unit_id }}">
                                                <option value="">-- Pilih Satuan --</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-3">
                                            <input type="number" name="quantity[]" required
                                                class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                                value="{{ $detail->quantity }}" min="0.01" step="0.01">
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="selling_price[]" required
                                                    class="pl-10 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                                    value="{{ $detail->price }}" min="0">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div
                                                class="subtotal text-base font-semibold text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button type="button"
                                                class="remove-item inline-flex items-center p-1.5 border border-transparent rounded-full text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card: Detail Pembayaran -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Detail Pembayaran
                    </h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Kolom Kiri: Total dan Diskon -->
                        <div class="space-y-4">
                            <div
                                class="bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                                <div class="grid gap-4">
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">Total:</span>
                                        <span id="totalAmount"
                                            class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">Diskon:</span>
                                        <div class="relative w-1/2">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" name="discount" id="discount"
                                                class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-right focus:border-indigo-500 focus:ring-indigo-500"
                                                value="{{ $sale->discount }}" min="0"
                                                onchange="calculateFinalTotal()" placeholder="0">
                                        </div>
                                    </div>

                                    <div
                                        class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-base font-medium text-gray-900 dark:text-gray-100">Total
                                            Setelah Diskon:</span>
                                        <span id="finalAmount"
                                            class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Payment Info (Hidden for draft) -->
                        <div class="space-y-4">
                            <div
                                class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Detail pembayaran akan diisi saat menyelesaikan transaksi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Catatan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Catatan
                    </h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <textarea id="notes" name="notes" rows="3"
                        class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                        placeholder="Tambahkan catatan transaksi jika diperlukan...">{{ old('notes', $sale->notes) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center py-4">
                <button type="button" onclick="deleteDraft()"
                    class="inline-flex justify-center items-center px-4 py-2 border border-red-300 dark:border-red-600 shadow-sm text-sm font-medium rounded-md text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Draft
                </button>

                <div class="flex space-x-3">
                    <button type="button" onclick="window.history.back()"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </button>

                    <button type="submit" id="save_draft_btn"
                        class="inline-flex justify-center py-2 px-4 border border-yellow-500 dark:border-yellow-600 shadow-sm text-sm font-medium rounded-md text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="-ml-1 mr-2 h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Perubahan Draft
                    </button>

                    <button type="submit" name="complete_transaction" value="1"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Selesaikan Transaksi
                    </button>
                </div>
            </div>
        </form>

        <!-- Hidden form for delete -->
        <form id="delete-form" action="{{ route('sales.destroy', $sale) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <script>
            // Global variables
            let isDraftSubmission = false;
            const existingProducts = @json($products);
            const existingDetails = @json($sale->saleDetails);

            $(document).ready(function() {
                // Initialize Select2 for customer
                initializeCustomerSelect();

                // Initialize existing rows
                $('#saleItems tr').each(function() {
                    initializeProductSelect($(this));
                    // Load units for existing products
                    const productSelect = $(this).find('select[name="product_id[]"]');
                    if (productSelect.val()) {
                        updateProductInfo(productSelect);
                    }
                });

                // Calculate initial total
                calculateTotal();

                // Event handlers
                $('#save_draft_btn').on('click', function(e) {
                    isDraftSubmission = true;
                });

                $('button[name="complete_transaction"]').on('click', function(e) {
                    isDraftSubmission = false;
                });

                $('#editDraftForm').on('submit', function(e) {
                    if (isDraftSubmission) {
                        if (!validateDraftForm()) {
                            e.preventDefault();
                            return false;
                        }
                    } else {
                        if (!validateCompleteForm()) {
                            e.preventDefault();
                            return false;
                        }
                    }

                    // Show loading state
                    const submitBtn = $(document.activeElement);
                    submitBtn.prop('disabled', true).html(
                        '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...'
                    );
                });

                // Event delegation
                $('#saleItems').on('change', 'select[name="product_id[]"]', function() {
                    updateProductInfo($(this));
                });

                $('#saleItems').on('change', 'select[name="unit_id[]"]', function() {
                    updatePrice($(this));
                });

                $('#saleItems').on('input', 'input[name="quantity[]"], input[name="selling_price[]"]', function() {
                    calculateSubtotal($(this).closest('tr'));
                });

                $('#saleItems').on('click', '.remove-item', function() {
                    removeItem($(this).closest('tr'));
                });

                $('#discount').on('input', calculateFinalTotal);
            });

            function initializeCustomerSelect() {
                $('#customer_select').select2({
                    theme: 'tailwind',
                    tags: true,
                    placeholder: 'Pilih atau ketik nama pelanggan baru',
                    allowClear: true,
                    createTag: function(params) {
                        if ($.trim(params.term) === '') {
                            return null;
                        }
                        return {
                            id: 'new:' + params.term,
                            text: params.term,
                            newTag: true
                        }
                    }
                }).on('change', function(e) {
                    const selectedValue = $(this).val();
                    if (selectedValue && selectedValue.startsWith('new:')) {
                        const newCustomerName = selectedValue.substring(4);
                        $('#customer_name').val(newCustomerName);
                    } else {
                        $('#customer_name').val('');
                    }
                });
            }

            function initializeProductSelect(row) {
                row.find('.product-select').select2({
                    theme: 'tailwind',
                    placeholder: 'Pilih Produk',
                    width: '100%'
                });
            }

            function createItemRow() {
                return `
            <tr class="sale-item hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                <td class="px-4 py-3">
                    <select name="product_id[]" required class="product-select w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-3 py-3">
                    <div class="stock-display text-sm text-gray-900 dark:text-gray-300 font-medium bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-md inline-block min-w-[60px] text-center">-</div>
                </td>
                <td class="px-3 py-3">
                    <select name="unit_id[]" required class="unit-select w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">-- Pilih Satuan --</option>
                    </select>
                </td>
                <td class="px-3 py-3">
                    <input type="number" name="quantity[]" required class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300" value="1" min="0.01" step="0.01">
                </td>
                <td class="px-4 py-3">
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="selling_price[]" required class="pl-10 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300" value="0" min="0">
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="subtotal text-base font-semibold text-gray-900 dark:text-gray-100">Rp 0</div>
                </td>
                <td class="px-4 py-3 text-right">
                    <button type="button" class="remove-item inline-flex items-center p-1.5 border border-transparent rounded-full text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            </tr>
            `;
            }

            function addItem() {
                const tbody = $('#saleItems');
                const newRow = $(createItemRow());
                tbody.append(newRow);
                initializeProductSelect(newRow);
            }

            function removeItem(row) {
                const tbody = $('#saleItems');
                if (tbody.children('tr').length > 1) {
                    row.find('.product-select').select2('destroy');
                    row.remove();
                    calculateTotal();
                } else {
                    alert('Minimal satu item harus ada');
                }
            }

            function updateProductInfo(selectElement) {
                const productId = selectElement.val();
                const row = selectElement.closest('tr');
                const unitSelect = row.find('select[name="unit_id[]"]');
                const stockDisplay = row.find('.stock-display');
                const selectedUnit = unitSelect.data('selected');

                unitSelect.html('<option value="">-- Pilih Satuan --</option>');

                if (productId) {
                    stockDisplay.html('<span class="text-gray-500">Loading...</span>');

                    fetch(`/products/${productId}/units`)
                        .then(response => response.json())
                        .then(data => {
                            stockDisplay.text(data.stock || 0);

                            if (data.units && data.units.length > 0) {
                                data.units.forEach(unit => {
                                    const option = $('<option></option>');
                                    option.val(unit.id);
                                    option.text(`${unit.name} (${unit.abbreviation})`);
                                    option.data('price', unit.selling_price);

                                    if (selectedUnit && selectedUnit == unit.id) {
                                        option.prop('selected', true);
                                    }

                                    unitSelect.append(option);
                                });

                                if (selectedUnit) {
                                    unitSelect.removeData('selected');
                                    updatePrice(unitSelect);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            stockDisplay.text('Error');
                        });
                } else {
                    stockDisplay.text('-');
                }
            }

            function updatePrice(selectElement) {
                const selectedOption = selectElement.find('option:selected');
                const row = selectElement.closest('tr');
                const priceInput = row.find('input[name="selling_price[]"]');

                if (selectedOption.length && selectedOption.data('price') !== undefined) {
                    priceInput.val(selectedOption.data('price'));
                }

                calculateSubtotal(row);
            }

            function calculateSubtotal(row) {
                const quantity = parseFloat(row.find('input[name="quantity[]"]').val()) || 0;
                const price = parseFloat(row.find('input[name="selling_price[]"]').val()) || 0;
                const subtotal = quantity * price;
                row.find('.subtotal').text(formatRupiah(subtotal));
                calculateTotal();
            }

            function calculateTotal() {
                let total = 0;
                $('#saleItems .subtotal').each(function() {
                    const value = parseFloat($(this).text().replace('Rp ', '').replace(/\./g, '') || 0);
                    total += value;
                });
                $('#totalAmount').text(formatRupiah(total));
                calculateFinalTotal();
            }

            function calculateFinalTotal() {
                const totalText = $('#totalAmount').text();
                const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '') || 0);
                const discount = parseFloat($('#discount').val()) || 0;
                const finalTotal = Math.max(0, total - discount);
                $('#finalAmount').text(formatRupiah(finalTotal));
            }

            function formatRupiah(number) {
                return 'Rp ' + Math.round(number).toLocaleString('id-ID', {
                    maximumFractionDigits: 0
                });
            }

            function validateDraftForm() {
                const tbody = $('#saleItems');
                if (tbody.children('tr').length === 0) {
                    alert('Tambahkan setidaknya satu item untuk menyimpan draft');
                    return false;
                }

                let hasValidProduct = false;
                $('#saleItems select[name="product_id[]"]').each(function() {
                    if ($(this).val()) {
                        hasValidProduct = true;
                        return false;
                    }
                });

                if (!hasValidProduct) {
                    alert('Pilih setidaknya satu produk untuk menyimpan draft');
                    return false;
                }

                return true;
            }

            function validateCompleteForm() {
                const tbody = $('#saleItems');
                if (tbody.children('tr').length === 0) {
                    alert('Tambahkan setidaknya satu item');
                    return false;
                }

                // More validation can be added here
                return true;
            }

            function deleteDraft() {
                if (confirm('Apakah Anda yakin ingin menghapus draft ini?')) {
                    document.getElementById('delete-form').submit();
                }
            }
        </script>
    @endpush
</x-app-layout>
