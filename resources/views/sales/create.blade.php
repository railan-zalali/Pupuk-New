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
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __('Buat Penjualan Baru') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Isi informasi detail untuk transaksi penjualan baru
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('sales.index') }}"
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

        <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="space-y-6">
            @csrf

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

                <input type="hidden" name="new_customer_name" id="new_customer_name">

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
                                    :value="$invoiceNumber" readonly />
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nomor faktur dibuat otomatis oleh
                                sistem</p>
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
                                    :value="old('date', date('Y-m-d'))" required />
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
                                        <option value="{{ $customer->id }}">{{ $customer->nama }} -
                                            {{ $customer->kecamatan_nama }}, {{ $customer->kabupaten_nama }}</option>
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
                                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                    required>
                                    <option value="cash">Tunai</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="credit">Kredit</option>
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
                                    <option value="Truk">Truk</option>
                                    <option value="Pickup">Pickup</option>
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
                                    :value="old('vehicle_number')" placeholder="Contoh: B 1234 XYZ" />
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
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Tambahkan produk yang dibeli
                        </p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <div class="relative mr-2">
                            <input type="text" id="quick-search" placeholder="Cari produk..."
                                class="w-40 md:w-60 pl-8 pr-3 py-1.5 text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
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
                                <!-- Dynamic item rows will be added here -->
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-right">
                                        <button type="button" onclick="addItem()"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Tambah Baris Lain
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
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
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Informasi pembayaran dan diskon
                    </p>
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
                                            class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp 0</span>
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
                                                value="0" min="0" onchange="calculateFinalTotal()"
                                                placeholder="0">
                                        </div>
                                    </div>

                                    <div
                                        class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-base font-medium text-gray-900 dark:text-gray-100">Total
                                            Setelah Diskon:</span>
                                        <span id="finalAmount"
                                            class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Detail Pembayaran -->
                        <div class="space-y-4">
                            <!-- Pembayaran Kredit -->
                            <div id="dp_container"
                                class="bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Pembayaran Kredit
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uang Muka
                                            (DP):</span>
                                        <div class="flex space-x-2">
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="down_payment" id="down_payment"
                                                    class="pl-10 w-32 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                                    value="0" min="0"
                                                    onchange="calculateRemainingAmount()">
                                            </div>
                                            <div class="flex space-x-1">
                                                <button type="button" onclick="setDownPayment(50)"
                                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    50%
                                                </button>
                                                <button type="button" onclick="setDownPayment(75)"
                                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    75%
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sisa
                                            Hutang:</span>
                                        <span id="remainingAmount"
                                            class="text-lg font-bold text-red-600 dark:text-red-400">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pembayaran Tunai -->
                            <div id="cash-payment-section"
                                class="bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 p-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Pembayaran
                                    Tunai/Transfer</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah
                                            Dibayar:</span>
                                        <div class="flex space-x-2">
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="paid_amount" id="paid_amount"
                                                    class="pl-10 w-32 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                                    value="0" min="0" onchange="calculateChange()">
                                            </div>
                                            <button type="button" onclick="setExactAmount()"
                                                class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                                Uang Pas
                                            </button>
                                        </div>
                                    </div>

                                    <div
                                        class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">Kembalian:</span>
                                        <span id="changeAmount"
                                            class="text-lg font-bold text-green-600 dark:text-green-400">Rp 0</span>
                                    </div>
                                </div>
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
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Informasi tambahan untuk
                        transaksi ini</p>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <textarea id="notes" name="notes" rows="3"
                        class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                        placeholder="Tambahkan catatan transaksi jika diperlukan...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 py-4">
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
                <button type="submit" name="save_as_draft" value="1"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Draft
                </button>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Proses Transaksi
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <!-- Modal Histori Pembelian Pelanggan -->
        <div id="shortcuts-modal" class="fixed inset-0 overflow-y-auto hidden z-50" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <!-- Modal Header -->
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-indigo-800 dark:from-indigo-900 dark:to-indigo-700 px-4 py-4 sm:px-6 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white dark:bg-indigo-800 rounded-full p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-white"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                Histori Pembelian Pelanggan
                            </h3>
                        </div>
                        <button type="button" id="close-shortcuts-modal"
                            class="bg-indigo-700 hover:bg-indigo-800 dark:bg-indigo-800 dark:hover:bg-indigo-900 rounded-full p-1 text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white dark:focus:ring-offset-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="bg-white dark:bg-gray-800 px-4 pt-4 pb-4 sm:p-6 sm:pb-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 p-3 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 rounded-lg"
                            id="customer-history-info">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Silakan pilih pelanggan terlebih dahulu untuk melihat histori pembelian</span>
                            </div>
                        </div>

                        <!-- Loading Indicator -->
                        <div id="customer-history-loading" class="hidden flex justify-center p-8">
                            <div class="flex flex-col items-center">
                                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="mt-3 text-indigo-600 dark:text-indigo-400 font-medium">Memuat histori
                                    pembelian...</span>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div id="customer-history-empty" class="hidden">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg px-4 py-5 sm:p-6 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada histori
                                    pembelian</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Pelanggan ini belum pernah melakukan transaksi sebelumnya. Ini akan menjadi transaksi
                                    pertama mereka!
                                </p>
                                <div class="mt-5">
                                    <button type="button" onclick="$('#shortcuts-modal').addClass('hidden')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Lanjutkan Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- History Content -->
                        <div id="customer-history-content" class="hidden">
                            <!-- Transaksi Terakhir -->
                            <div class="mb-5">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Transaksi Terakhir
                                </h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Tanggal
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    No. Faktur
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Total
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="customer-history-table"
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                            <!-- Konten akan diisi secara dinamis -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Produk Favorit -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Produk Favorit
                                </h4>
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                                    <div id="customer-favorite-products" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <!-- Konten akan diisi secara dinamis -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keyboard Shortcuts Section -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7H6a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-3M9 7V5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9" />
                                </svg>
                                Pintasan Keyboard
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                                <div
                                    class="bg-gray-100 dark:bg-gray-700 p-2 rounded-md border border-gray-200 dark:border-gray-600">
                                    <span class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Tambah
                                        item</span>
                                    <span
                                        class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/40 rounded text-indigo-700 dark:text-indigo-300 font-mono">Alt+A</span>
                                </div>
                                <div
                                    class="bg-gray-100 dark:bg-gray-700 p-2 rounded-md border border-gray-200 dark:border-gray-600">
                                    <span class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Scan
                                        barcode</span>
                                    <span
                                        class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/40 rounded text-indigo-700 dark:text-indigo-300 font-mono">Alt+B</span>
                                </div>
                                <div
                                    class="bg-gray-100 dark:bg-gray-700 p-2 rounded-md border border-gray-200 dark:border-gray-600">
                                    <span class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Proses
                                        transaksi</span>
                                    <span
                                        class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/40 rounded text-indigo-700 dark:text-indigo-300 font-mono">Alt+S</span>
                                </div>
                                <div
                                    class="bg-gray-100 dark:bg-gray-700 p-2 rounded-md border border-gray-200 dark:border-gray-600">
                                    <span class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Simpan
                                        draft</span>
                                    <span
                                        class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/40 rounded text-indigo-700 dark:text-indigo-300 font-mono">Alt+D</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Buttons -->
        <div class="fixed bottom-6 right-6 flex flex-col items-end space-y-2 z-40">
            <div id="history-button" class="tooltip-container">
                <button type="button"
                    class="inline-flex items-center justify-center p-3 rounded-full bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    title="Histori Pelanggan (Alt+H)" onclick="showCustomerHistory()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
                <div class="tooltip">Histori Pelanggan (Alt+H)</div>
            </div>

            <div id="barcode-scan-button" class="tooltip-container">
                <button type="button" onclick="showBarcodeScanner()"
                    class="inline-flex items-center justify-center p-3 rounded-full bg-green-600 text-white shadow-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                    title="Scan Barcode (Alt+B)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </button>
                <div class="tooltip">Scan Barcode (Alt+B)</div>
            </div>

            <div id="help-button" class="tooltip-container">
                <button type="button"
                    class="inline-flex items-center justify-center p-3 rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    title="Pintasan Keyboard">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
                <div class="tooltip">Pintasan Keyboard</div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize Select2 for customer select
                initializeCustomerSelect();

                // Initialize the first item row on page load if no items exist
                if ($('#saleItems tr').length === 0) {
                    addItem();
                } else {
                    // Initialize Select2 for existing item rows
                    $('#saleItems tr').each(function() {
                        initializeProductSelect($(this));
                    });
                }

                // Update payment sections based on initial payment method
                updatePaymentSections();

                // Add input validations
                addInputValidations();

                // Event listeners
                $('#payment_method').on('change', updatePaymentSections);
                $('#discount').on('input', calculateFinalTotal);
                $('#paid_amount').on('input', calculateChange);
                $('#down_payment').on('input', calculateRemainingAmount);
                $('#set_exact_amount').on('click', setExactAmount);
                $('#set_dp_percentage_10').on('click', function() {
                    setDownPayment(10);
                });
                $('#set_dp_percentage_25').on('click', function() {
                    setDownPayment(25);
                });
                $('#set_dp_percentage_50').on('click', function() {
                    setDownPayment(50);
                });
                $('#add_item').on('click', addItem);

                // Event delegation for dynamically added rows
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

                // Help button
                $('#help-button').on('click', function() {
                    $('#shortcuts-modal').removeClass('hidden');
                });
                $('#history-button').on('click', function() {
                    showCustomerHistory();
                });
                $('#close-shortcuts-modal').on('click', function() {
                    $('#shortcuts-modal').addClass('hidden');
                });

                // Additional keyboard shortcuts
                $(document).on('keydown', function(e) {
                    // Alt+H: Show customer history
                    if (e.altKey && e.which === 72) { // 'H' key
                        e.preventDefault();
                        showCustomerHistory();
                    }

                    // Alt+A: Add item
                    if (e.altKey && e.which === 65) { // 'A' key
                        e.preventDefault();
                        addItem();
                    }

                    // Alt+B: Show barcode scanner
                    if (e.altKey && e.which === 66) { // 'B' key
                        e.preventDefault();
                        showBarcodeScanner();
                    }

                    // Alt+S: Submit form (process transaction)
                    if (e.altKey && e.which === 83) { // 'S' key
                        e.preventDefault();
                        if (validateForm()) {
                            $('#saleForm').submit();
                        }
                    }

                    // Alt+D: Save as draft
                    if (e.altKey && e.which === 68) { // 'D' key
                        e.preventDefault();
                        if (validateForm()) {
                            $('button[name="save_as_draft"]').click();
                        }
                    }
                });

                // Form submission validation
                $('#saleForm').on('submit', function(e) {
                    if (!validateForm()) {
                        e.preventDefault();
                    } else {
                        // Show loading state
                        const submitBtn = $(document.activeElement);
                        const originalText = submitBtn.html();

                        submitBtn.prop('disabled', true)
                            .html(
                                '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...'
                            );
                    }
                });

                // Show keyboard shortcuts notification on page load
                setTimeout(() => {
                    showNotification('Gunakan Alt+H untuk melihat histori pembelian pelanggan', 'info');
                }, 1000);
            });

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
                        <input type="number" name="quantity[]" required class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300" value="1" min="1">
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
                    // Destroy Select2 before removing the row
                    row.find('.product-select').select2('destroy');
                    row.remove();
                    calculateTotal();
                } else {
                    alert('Minimal satu item harus ada');
                }
            }

            function initializeProductSelect(row) {
                try {
                    row.find('.product-select').select2({
                        theme: 'tailwind',
                        placeholder: 'Pilih Produk',
                        width: '100%',
                        dropdownParent: $('#saleItems').closest('.overflow-x-auto'),
                        templateResult: formatProductOption,
                        templateSelection: formatProductSelection,
                        escapeMarkup: function(markup) {
                            return markup;
                        }
                    }).on('select2:open', function() {
                        $('.select2-dropdown').addClass('dark:bg-gray-800 dark:border-gray-700');
                        $('.select2-search__field').addClass('dark:bg-gray-800 dark:text-gray-300');
                    });
                } catch (e) {
                    console.error('Error initializing Select2:', e);
                    // Fallback to native select if Select2 fails
                    row.find('.product-select').removeClass('select2-hidden-accessible').css('display', 'block')
                        .next('.select2-container').remove();
                    row.find('.product-select').addClass(
                        'w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300');
                }
            }

            function updateProductInfo(selectElement) {
                const productId = selectElement.val();
                const row = selectElement.closest('tr');
                const unitSelect = row.find('select[name="unit_id[]"]');
                const priceInput = row.find('input[name="selling_price[]"]');
                const stockDisplay = row.find('.stock-display');

                // Clear unit options
                unitSelect.html('<option value="">-- Pilih Satuan --</option>');

                if (productId) {
                    // Show loading indicator
                    stockDisplay.html(
                        '<span class="inline-flex items-center"><svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Loading...</span></span>'
                    );
                    stockDisplay.removeClass(
                            'bg-green-100 bg-yellow-100 bg-red-100 dark:bg-green-900/30 dark:bg-yellow-900/30 dark:bg-red-900/30'
                        )
                        .addClass('bg-gray-100 dark:bg-gray-700');

                    // Fetch product units via Fetch API
                    fetch(`/products/${productId}/units`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Calculate already selected quantity for this product
                            const alreadySelectedQuantity = calculateAlreadySelectedQuantity(productId, row);

                            // Update stock display with adjusted stock
                            const adjustedStock = Math.max(0, (data.stock || 0) - alreadySelectedQuantity);
                            stockDisplay.text(adjustedStock);

                            // Add color coding based on stock level
                            stockDisplay.removeClass('bg-gray-100 dark:bg-gray-700');
                            if (adjustedStock > 10) {
                                stockDisplay.addClass(
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400');
                            } else if (adjustedStock > 0) {
                                stockDisplay.addClass(
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400');
                            } else {
                                stockDisplay.addClass('bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400');
                            }

                            // Populate unit options
                            if (data.units && data.units.length > 0) {
                                data.units.forEach(unit => {
                                    const option = $('<option></option>');
                                    option.val(unit.id);
                                    option.text(`${unit.unit_name} (${unit.unit_abbreviation})`);
                                    option.data('price', unit.selling_price);
                                    option.data('conversionFactor', unit.conversion_factor);
                                    option.data('isDefault', unit.is_default ? "1" : "0");
                                    unitSelect.append(option);
                                });

                                // Select default unit if available
                                const defaultUnit = unitSelect.find('option[data-is-default="1"]');
                                if (defaultUnit.length) {
                                    defaultUnit.prop('selected', true);
                                    updatePrice(unitSelect);
                                } else if (data.units.length > 0) {
                                    // Select the first unit if no default is set
                                    unitSelect.find('option:eq(1)').prop('selected', true);
                                    updatePrice(unitSelect);
                                }
                            } else {
                                console.warn('No units found for product:', productId);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching product units:', error);
                            stockDisplay.text('Error');
                            stockDisplay.removeClass(
                                    'bg-green-100 bg-yellow-100 bg-gray-100 dark:bg-green-900/30 dark:bg-yellow-900/30 dark:bg-gray-700'
                                )
                                .addClass('bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400');
                        });
                } else {
                    stockDisplay.text('-');
                    stockDisplay.removeClass(
                            'bg-green-100 bg-yellow-100 bg-red-100 text-green-800 text-yellow-800 text-red-800 dark:bg-green-900/30 dark:bg-yellow-900/30 dark:bg-red-900/30 dark:text-green-400 dark:text-yellow-400 dark:text-red-400'
                        )
                        .addClass('bg-gray-100 dark:bg-gray-700');
                    priceInput.val(0);
                    calculateSubtotal(row);
                }
            }

            // Function to calculate already selected quantity for a product
            function calculateAlreadySelectedQuantity(productId, currentRow) {
                let totalSelectedQuantity = 0;

                $('#saleItems tr').each(function() {
                    const row = $(this);
                    // Skip the current row to avoid counting it twice
                    if (row.is(currentRow)) {
                        return true; // continue to next iteration
                    }

                    const rowProductId = row.find('select[name="product_id[]"]').val();
                    if (rowProductId === productId) {
                        const quantity = parseInt(row.find('input[name="quantity[]"]').val()) || 0;
                        const unitSelect = row.find('select[name="unit_id[]"]');
                        const selectedOption = unitSelect.find('option:selected');

                        // If a unit is selected, consider its conversion factor
                        if (selectedOption.length && selectedOption.data('conversionFactor')) {
                            const conversionFactor = parseFloat(selectedOption.data('conversionFactor')) || 1;
                            totalSelectedQuantity += quantity * conversionFactor;
                        } else {
                            totalSelectedQuantity += quantity;
                        }
                    }
                });

                return totalSelectedQuantity;
            }

            function calculateSubtotal(row) {
                const quantity = parseInt(row.find('input[name="quantity[]"]').val()) || 0;
                const price = parseInt(row.find('input[name="selling_price[]"]').val()) || 0;
                const subtotal = quantity * price;
                row.find('.subtotal').text(formatRupiah(subtotal));
                calculateTotal();

                // Update stock displays for all rows with the same product
                updateStockDisplaysForProduct(row);
            }

            // Function to update stock displays for all rows with the same product
            function updateStockDisplaysForProduct(changedRow) {
                const changedProductId = changedRow.find('select[name="product_id[]"]').val();
                if (!changedProductId) return;

                $('#saleItems tr').each(function() {
                    const row = $(this);
                    const productSelect = row.find('select[name="product_id[]"]');
                    const productId = productSelect.val();

                    // Only update rows with the same product
                    if (productId === changedProductId && !row.is(changedRow)) {
                        // Recalculate the adjusted stock
                        const alreadySelectedQuantity = calculateAlreadySelectedQuantity(productId, row);
                        const originalStock = productSelect.find('option:selected').data('stock') || 0;
                        const adjustedStock = Math.max(0, originalStock - alreadySelectedQuantity);

                        // Update the stock display with color coding
                        const stockDisplay = row.find('.stock-display');
                        stockDisplay.text(adjustedStock);

                        // Add color coding based on stock level
                        stockDisplay.removeClass(
                            'bg-green-100 bg-yellow-100 bg-red-100 text-green-800 text-yellow-800 text-red-800 dark:bg-green-900/30 dark:bg-yellow-900/30 dark:bg-red-900/30 dark:text-green-400 dark:text-yellow-400 dark:text-red-400'
                        );

                        if (adjustedStock > 10) {
                            stockDisplay.addClass(
                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400');
                        } else if (adjustedStock > 0) {
                            stockDisplay.addClass(
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400');
                        } else {
                            stockDisplay.addClass('bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400');
                        }
                    }
                });
            }

            function updatePrice(selectElement) {
                const selectedOption = selectElement.find('option:selected');
                const row = selectElement.closest('tr');
                const priceInput = row.find('input[name="selling_price[]"]');

                if (selectedOption.length && selectedOption.data('price') !== undefined) {
                    priceInput.val(selectedOption.data('price'));

                    // Highlight the price change with animation
                    priceInput.addClass('bg-yellow-50 dark:bg-yellow-900/20');
                    setTimeout(() => {
                        priceInput.removeClass('bg-yellow-50 dark:bg-yellow-900/20');
                    }, 500);
                } else {
                    priceInput.val(0);
                }

                calculateSubtotal(row);
            }

            function calculateTotal() {
                let total = 0;
                $('#saleItems .subtotal').each(function() {
                    const value = parseFloat($(this).text().replace('Rp ', '').replace(/\./g, '') || 0);
                    total += value;
                });
                $('#totalAmount').text(formatRupiah(total));
                calculateFinalTotal(); // Recalculate final total after total changes
            }

            function calculateFinalTotal() {
                const totalText = $('#totalAmount').text();
                const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '') || 0);
                const discount = parseFloat($('#discount').val()) || 0;

                // Calculate final total after discount
                const finalTotal = Math.max(0, total - discount);
                $('#finalAmount').text(formatRupiah(finalTotal));

                // Animate the final amount to highlight change
                $('#finalAmount').addClass('animate-pulse text-indigo-700 dark:text-indigo-300');
                setTimeout(() => {
                    $('#finalAmount').removeClass('animate-pulse text-indigo-700 dark:text-indigo-300');
                }, 700);

                // Update payment calculations based on payment method
                const paymentMethod = $('#payment_method').val();
                if (paymentMethod === 'credit') {
                    calculateRemainingAmount();
                } else {
                    calculateChange();
                }
            }

            function setExactAmount() {
                const finalText = $('#finalAmount').text();
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '') || 0);
                $('#paid_amount').val(finalTotal);
                calculateChange();
            }

            function calculateChange() {
                const finalText = $('#finalAmount').text(); // Use finalAmount
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '') || 0);
                const paid = parseFloat($('#paid_amount').val()) || 0;
                const change = paid - finalTotal;
                $('#changeAmount').text(formatRupiah(Math.max(0, change)));

                // Validation for minimum paid amount
                const submitButton = $('button[type="submit"]');
                const paymentMethod = $('#payment_method').val();

                if (paymentMethod !== 'credit' && paid < finalTotal) {
                    // Disable submit button only for non-credit transactions
                    submitButton.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                } else {
                    submitButton.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                }
            }

            function calculateRemainingAmount() {
                const finalTotalText = $('#finalAmount').text();
                const finalTotal = parseFloat(finalTotalText.replace('Rp ', '').replace(/\./g, '') || 0);
                const dp = parseFloat($('#down_payment').val()) || 0;
                const remaining = finalTotal - dp;

                $('#remainingAmount').text(formatRupiah(Math.max(0, remaining)));

                // Update hidden paid_amount field for backend processing (DP is considered paid amount for credit)
                $('#paid_amount').val(dp);

                // Validation: DP cannot exceed total
                if (dp > finalTotal) {
                    alert('Uang muka tidak boleh melebihi total belanja');
                    $('#down_payment').val(finalTotal);
                    calculateRemainingAmount();
                }
            }

            function setDownPayment(percentage) {
                const finalText = $('#finalAmount').text();
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '') || 0);
                const downPaymentAmount = Math.round(finalTotal * percentage / 100);
                $('#down_payment').val(downPaymentAmount);
                calculateRemainingAmount();
            }

            function formatRupiah(number) {
                // Remove decimals & format with thousand separator
                return 'Rp ' + Math.round(number).toLocaleString('id-ID', {
                    maximumFractionDigits: 0,
                    currencyDisplay: 'symbol'
                });
            }

            function addInputValidations() {
                // Prevent negative values in all number inputs
                $('input[type="number"]').each(function() {
                    $(this).on('input', function() {
                        if (parseFloat($(this).val()) < 0) {
                            $(this).val(0);
                        }
                    });
                });
            }

            function validateProductStock() {
                let isValid = true;
                $('#saleItems tr').each(function() {
                    const row = $(this);
                    const productSelect = row.find('select[name="product_id[]"]');
                    const quantityInput = row.find('input[name="quantity[]"]');
                    const selectedOption = productSelect.find('option:selected');

                    if (selectedOption.length && selectedOption.val()) {
                        const availableStock = parseInt(selectedOption.data('stock')) || 0;
                        const requestedQuantity = parseInt(quantityInput.val()) || 0;

                        if (requestedQuantity > availableStock) {
                            alert(
                                `Stok tidak mencukupi untuk produk ${selectedOption.text()}. Tersedia: ${availableStock}`
                            );
                            isValid = false;
                            return false; // Break .each loop
                        }
                    }
                });

                return isValid;
            }

            function validateForm() {
                const tbody = $('#saleItems');
                if (tbody.children('tr').length === 0) {
                    alert('Tambahkan setidaknya satu item');
                    return false;
                }

                if (!validateProductStock()) {
                    return false;
                }

                const paymentMethod = $('#payment_method').val();
                const finalText = $('#finalAmount').text();
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '') || 0);
                const paid = parseFloat($('#paid_amount').val()) || 0;

                // Validation for paid amount for non-credit methods
                if (paymentMethod !== 'credit' && paid < finalTotal) {
                    alert('Jumlah yang dibayar harus lebih besar atau sama dengan total belanja');
                    return false;
                }

                // Validation for customer selection for credit payments
                if (paymentMethod === 'credit') {
                    if (!$('#customer_select').val()) {
                        alert('Transaksi kredit harus memilih pelanggan');
                        return false;
                    }
                }

                return true;
            }

            function updatePaymentSections() {
                const paymentMethod = $('#payment_method').val();
                const dpSection = $('#dp_container');
                const cashSection = $('#cash-payment-section');
                const submitButton = $('button[type="submit"]');
                const paidAmountInput = $('#paid_amount');
                const dpInput = $('#down_payment');

                if (paymentMethod === 'credit') {
                    dpSection.removeClass('hidden');
                    cashSection.addClass('hidden');

                    // Reset values
                    dpInput.val('0');
                    paidAmountInput.val('0');

                    // Enable submit button for credit
                    submitButton.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

                    // Check for customer selection
                    if (!$('#customer_select').val()) {
                        // This validation is also in validateForm, but an early alert might be helpful
                    }

                    // Calculate remaining amount
                    calculateRemainingAmount();
                } else {
                    dpSection.addClass('hidden');
                    cashSection.removeClass('hidden');

                    // Reset DP value
                    dpInput.val('0');

                    // Calculate change
                    calculateChange();
                }
            }

            function initializeCustomerSelect() {
                try {
                    $('#customer_select').select2({
                        theme: 'tailwind',
                        tags: true, // Allow creating new tags
                        placeholder: 'Pilih atau ketik nama pelanggan baru',
                        allowClear: true,
                        createTag: function(params) {
                            // Don't create a tag if input is empty
                            if ($.trim(params.term) === '') {
                                return null;
                            }
                            return {
                                id: 'new:' + params.term,
                                text: params.term,
                                newTag: true
                            }
                        },
                        templateResult: function(data) {

                            if (data.loading) return data.text;
                            const $container = $("<div class='select2-result-customer'></div>");
                            if (data.newTag) {
                                $container.append(
                                    $("<div class='text-blue-600'><i class='fas fa-plus-circle mr-1'></i> Tambah pelanggan baru: " +
                                        data.text + "</div>")
                                );
                            } else {
                                $container.append($("<div>" + data.text + "</div>"));
                            }
                            return $container;
                        },
                        templateSelection: function(data) {

                            if (data.newTag) {
                                return "Pelanggan Baru: " + data.text;
                            }
                            return data.text;
                        }
                    }).on('change', function(e) {

                        const selectedValue = $(this).val();
                        if (selectedValue && selectedValue.startsWith('new:')) {
                            const newCustomerName = selectedValue.substring(4);
                            $('#new_customer_name').val(newCustomerName);
                            $(this).next('.select2-container').find('.select2-selection').addClass('border-blue-500');
                        } else {
                            $('#new_customer_name').val('');
                            $(this).next('.select2-container').find('.select2-selection').removeClass(
                                'border-blue-500');
                        }
                    });
                } catch (e) {
                    console.error('Error initializing customer Select2:', e);
                    // Fallback to native select
                    $('#customer_select').removeClass('select2-hidden-accessible').css('display', 'block')
                        .next('.select2-container').remove();
                    $('#customer_select').addClass(
                        'w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300');
                }
            }

            function formatProductOption(product) {
                if (!product.id) return product.text;
                const $option = $(product.element); // Get the original option element
                const stock = $option.data('stock') || 0;

                return `
                <div class="flex items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            ${product.text}
                        </div>
                        <div class="flex items-center mt-1 text-sm">
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span class="${stock > 10 ? 'text-green-600 dark:text-green-400' : (stock > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400')}">
                                    Stok: ${stock}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            }

            function formatProductSelection(product) {
                if (!product.id) return product.text;
                return `
                <div class="flex items-center">
                    <div class="font-medium text-gray-900 dark:text-gray-100">${product.text}</div>
                </div>
                `;
            }

            // Floating tooltips for keyboard shortcuts
            function showBarcodeScanner() {
                // Create a modal dialog for barcode input
                const modalHtml = `
                <div id="barcode-modal" class="fixed inset-0 overflow-y-auto z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div>
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                        Scan Barcode Produk
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Masukkan atau scan barcode produk untuk menambahkannya ke transaksi
                                        </p>
                                        <div class="mt-4">
                                            <input type="text" id="barcode-input" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan barcode..." autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="button" id="scan-barcode-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                    Cari Produk
                                </button>
                                <button type="button" id="close-barcode-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                $('body').append(modalHtml);

                // Focus on the input field
                setTimeout(() => {
                    $('#barcode-input').focus();
                }, 100);

                // Handle the scan button click
                $('#scan-barcode-btn').on('click', function() {
                    const barcode = $('#barcode-input').val();
                    if (barcode) {
                        searchProductByBarcode(barcode);
                    }
                });

                // Handle Enter key press in the input field
                $('#barcode-input').on('keydown', function(e) {
                    if (e.which === 13) { // Enter key
                        e.preventDefault();
                        const barcode = $(this).val();
                        if (barcode) {
                            searchProductByBarcode(barcode);
                        }
                    }
                });

                // Close modal when close button is clicked
                $('#close-barcode-btn').on('click', function() {
                    $('#barcode-modal').remove();
                });

                // Close modal when clicking outside
                $(document).on('click', '#barcode-modal', function(e) {
                    if ($(e.target).closest('.sm\\:max-w-lg').length === 0) {
                        $('#barcode-modal').remove();
                    }
                });
            }

            function searchProductByBarcode(barcode) {
                // Show loading indicator
                $('#barcode-input').prop('disabled', true);
                $('#scan-barcode-btn').html(
                    '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mencari...'
                );

                // Simulate a search (replace with actual API call)
                // Here you would typically make an AJAX call to search for the product by barcode
                fetch(`/products/find-by-barcode?barcode=${barcode}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Product not found');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.id) {
                            // Close the modal
                            $('#barcode-modal').remove();

                            // Add the product to the sale
                            addProductToSale(data);

                            // Show success notification
                            showNotification(`Produk ${data.name} berhasil ditambahkan`, 'success');
                        } else {
                            throw new Error('Product not found');
                        }
                    })
                    .catch(error => {
                        console.error('Error finding product by barcode:', error);
                        $('#barcode-input').prop('disabled', false);
                        $('#scan-barcode-btn').text('Cari Produk');
                        showNotification('Produk tidak ditemukan', 'error');
                    });
            }

            function addProductToSale(product) {
                // Add a new row if needed
                if ($('#saleItems tr').length === 0) {
                    addItem();
                }

                // Find the last empty row or add a new one
                let emptyRow = $('#saleItems tr').filter(function() {
                    return $(this).find('select[name="product_id[]"]').val() === '';
                }).first();

                if (!emptyRow.length) {
                    addItem();
                    emptyRow = $('#saleItems tr:last');
                }

                // Select the product in the dropdown
                const productSelect = emptyRow.find('select[name="product_id[]"]');
                productSelect.val(product.id).trigger('change');
            }

            function showNotification(message, type = 'info') {
                // Remove any existing notifications
                $('.notification-toast').remove();

                // Create notification element
                const colorClass = type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' :
                    type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';

                const notificationHtml = `
                <div class="notification-toast fixed top-4 right-4 z-50 flex items-center p-4 mb-4 w-full max-w-xs rounded-lg shadow text-white ${colorClass}" role="alert">
                    <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 rounded-lg bg-white/20">
                        ${type === 'success' ?
                            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 1.414L8 12.586l7.293-7.293a1 1 0 011.414 1.414L10 11.414l7.293 7.293a1 1 0 01-1.414 1.414L10 11.414l-7.293 7.293a1 1 0 01-1.414-1.414L8.586 10 15.879 2.707a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' :
                            type === 'error' ?
                            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>' :
                            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
                        }
                    </div>
                    <div class="ml-3 text-sm font-normal">${message}</div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white/20 text-white hover:bg-white/30 rounded-lg p-1.5 inline-flex h-8 w-8" data-dismiss-target=".notification-toast" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                `;

                $('body').append(notificationHtml);

                // Auto-dismiss after 3 seconds
                setTimeout(() => {
                    $('.notification-toast').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);

                // Dismiss on click
                $('.notification-toast button').on('click', function() {
                    $(this).closest('.notification-toast').fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            }

            // Fungsi untuk menampilkan histori pelanggan
            function showCustomerHistory() {
                const customerId = $('#customer_select').val();

                // Tampilkan modal terlebih dahulu
                $('#shortcuts-modal').removeClass('hidden');

                // Tampilkan section yang sesuai
                $('#customer-history-loading').addClass('hidden');
                $('#customer-history-empty').addClass('hidden');
                $('#customer-history-content').addClass('hidden');

                // Jika pelanggan belum dipilih
                if (!customerId || customerId.startsWith('new:')) {
                    $('#customer-history-info').html(`
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Silakan pilih pelanggan terlebih dahulu untuk melihat histori pembelian</span>
                        </div>
                    `);
                    return;
                }

                // Jika pelanggan dipilih, ambil datanya
                $('#customer-history-info').text('');
                $('#customer-history-loading').removeClass('hidden');

                // Ambil data histori pelanggan
                fetchCustomerHistory(customerId);
            }

            // Fungsi untuk mengambil histori pelanggan
            function fetchCustomerHistory(customerId) {
                // Ajax request untuk mendapatkan histori
                fetch(`/customers/${customerId}/history`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        $('#customer-history-loading').addClass('hidden');

                        // Jika tidak ada histori
                        if (!data.history || data.history.length === 0) {
                            $('#customer-history-empty').removeClass('hidden');
                            return;
                        }

                        // Tampilkan histori
                        displayCustomerHistory(data);
                    })
                    .catch(error => {
                        console.error('Error fetching customer history:', error);
                        $('#customer-history-loading').addClass('hidden');
                        $('#customer-history-info').text('Terjadi kesalahan saat mengambil histori. Silakan coba lagi.');
                    });
            }

            // Fungsi untuk menampilkan data histori
            function displayCustomerHistory(data) {
                // Tampilkan nama pelanggan
                const customerName = $('#customer_select option:selected').text().split('-')[0].trim();
                $('#customer-history-info').html(`Histori untuk <strong>${customerName}</strong>`);

                // Tampilkan tabel histori
                const historyTable = $('#customer-history-table');
                historyTable.empty();

                data.history.forEach(sale => {
                    const date = new Date(sale.date);
                    const formattedDate =
                        `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;

                    let statusBadge = '';
                    if (sale.payment_status === 'paid') {
                        statusBadge =
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Lunas</span>`;
                    } else if (sale.payment_status === 'partial') {
                        statusBadge =
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Sebagian</span>`;
                    } else {
                        statusBadge =
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Belum Bayar</span>`;
                    }

                    const row = `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600/50">
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${formattedDate}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${sale.invoice_number}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Rp ${Number(sale.total_amount).toLocaleString('id-ID')}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm">${statusBadge}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-right">
                            <button type="button" class="history-copy-items inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/40 dark:text-indigo-300 dark:hover:bg-indigo-900/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-sale-id="${sale.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                Salin Item
                            </button>
                        </td>
                    </tr>
                    `;

                    historyTable.append(row);
                });

                // Tampilkan produk favorit
                const favoriteProducts = $('#customer-favorite-products');
                favoriteProducts.empty();

                if (data.favorite_products && data.favorite_products.length > 0) {
                    data.favorite_products.forEach(product => {
                        const productCard = `
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors duration-150 cursor-pointer favorite-product shadow-sm" data-product-id="${product.id}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500 mr-1.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm truncate">${product.name}</div>
                                    </div>
                                    <div class="mt-1 grid grid-cols-2 gap-1">
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Rp ${Number(product.price).toLocaleString('id-ID')}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            ${product.purchase_count}x dibeli
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="flex-shrink-0 ml-2 inline-flex items-center p-1.5 border border-transparent rounded-full text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        `;

                        favoriteProducts.append(productCard);
                    });
                } else {
                    favoriteProducts.append(`
                        <div class="col-span-full p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 dark:text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada produk yang sering dibeli oleh pelanggan ini</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Produk favorit akan muncul setelah beberapa transaksi</p>
                        </div>
                    `);
                }

                // Tampilkan konten
                $('#customer-history-content').removeClass('hidden');

                // Event listener untuk tombol salin item
                $('.history-copy-items').on('click', function() {
                    const saleId = $(this).data('sale-id');
                    copySaleItems(saleId);
                });

                // Event listener untuk tambah produk favorit
                $('.favorite-product').on('click', function() {
                    const productId = $(this).data('product-id');
                    addProductById(productId);
                    $('#shortcuts-modal').addClass('hidden');
                });
            }

            // Fungsi untuk menyalin item dari penjualan sebelumnya
            function copySaleItems(saleId) {
                // Tampilkan loading
                showNotification('Menyalin item...', 'info');

                // Ajax request untuk mendapatkan detail penjualan
                fetch(`/sales/${saleId}/details`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Hapus semua item yang ada
                        $('#saleItems tr').each(function() {
                            $(this).remove();
                        });

                        // Tambahkan item dari penjualan sebelumnya
                        data.details.forEach(detail => {
                            addItem();
                            const row = $('#saleItems tr:last');

                            // Pilih produk
                            row.find('select[name="product_id[]"]').val(detail.product_id).trigger('change');

                            // Tambahkan handler untuk memilih unit dan jumlah setelah produk dimuat
                            const checkUnitLoaded = setInterval(() => {
                                const unitSelect = row.find('select[name="unit_id[]"]');
                                if (unitSelect.find('option').length > 1) {
                                    clearInterval(checkUnitLoaded);

                                    // Pilih unit jika ada
                                    unitSelect.val(detail.product_unit_id).trigger('change');

                                    // Set jumlah
                                    row.find('input[name="quantity[]"]').val(detail.quantity).trigger(
                                        'input');
                                }
                            }, 100);
                        });

                        // Tutup modal
                        $('#shortcuts-modal').addClass('hidden');

                        // Tampilkan notifikasi
                        showNotification('Item berhasil disalin dari transaksi sebelumnya', 'success');
                    })
                    .catch(error => {
                        console.error('Error copying sale items:', error);
                        showNotification('Terjadi kesalahan saat menyalin item', 'error');
                    });
            }

            // Tambahkan event listener saat pelanggan dipilih
            $('#customer_select').on('change', function() {
                // Jika modal histori sedang terbuka, update isinya
                if (!$('#shortcuts-modal').hasClass('hidden')) {
                    showCustomerHistory();
                }
            });

            // Event listener untuk tambah produk favorit
            $('.favorite-product').on('click', function() {
                const productId = $(this).data('product-id');
                addProductById(productId);
                $('#shortcuts-modal').addClass('hidden');
            });

            // Fungsi untuk menambahkan produk berdasarkan ID
            function addProductById(productId) {
                // Cari apakah produk sudah ada di keranjang
                let existingRow = null;
                $('#saleItems tr').each(function() {
                    const row = $(this);
                    if (row.find('select[name="product_id[]"]').val() == productId) {
                        existingRow = row;
                        return false; // break each loop
                    }
                });

                if (existingRow) {
                    // Jika produk sudah ada, tambahkan kuantitasnya
                    const quantityInput = existingRow.find('input[name="quantity[]"]');
                    let currentQty = parseInt(quantityInput.val()) || 0;
                    quantityInput.val(currentQty + 1).trigger('input');

                    // Highlight row untuk menunjukkan perubahan
                    existingRow.addClass('bg-yellow-50 dark:bg-yellow-900/20')
                        .find('.subtotal').addClass('animate-pulse text-indigo-700 dark:text-indigo-400');

                    // Scroll ke baris yang diubah
                    $('html, body').animate({
                        scrollTop: existingRow.offset().top - 100
                    }, 500);

                    // Hapus kelas setelah animasi
                    setTimeout(() => {
                        existingRow.removeClass('bg-yellow-50 dark:bg-yellow-900/20')
                            .find('.subtotal').removeClass('animate-pulse text-indigo-700 dark:text-indigo-400');
                    }, 1000);
                } else {
                    // Jika produk belum ada, tambahkan baris baru
                    addItem();
                    const newRow = $('#saleItems tr:last');

                    // Pilih produk
                    newRow.find('select[name="product_id[]"]').val(productId).trigger('change');

                    // Scroll ke baris baru dengan animasi
                    $('html, body').animate({
                        scrollTop: newRow.offset().top - 100
                    }, 500);

                    // Highlight row baru dengan animasi
                    newRow.addClass('bg-green-50 dark:bg-green-900/20');
                    setTimeout(() => {
                        newRow.removeClass('bg-green-50 dark:bg-green-900/20');
                    }, 1500);
                }

                // Tampilkan notifikasi
                showNotification('Produk berhasil ditambahkan', 'success');
            }
        </script>
    @endpush

</x-app-layout>
