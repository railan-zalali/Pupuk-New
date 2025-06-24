    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header with Draft Status -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2
                        class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-yellow-600 dark:text-yellow-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit Draft') }} #{{ $sale->invoice_number }}

                        <!-- Draft Status Badge -->
                        <span
                            class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Draft
                        </span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Dibuat {{ \Carbon\Carbon::parse($sale->created_at)->format('d F Y, H:i') }}
                        <span class="mx-2">•</span>
                        <span
                            class="text-yellow-600 dark:text-yellow-400">{{ \Carbon\Carbon::parse($sale->created_at)->diffForHumans() }}</span>
                        <span id="auto-save-status" class="ml-2 text-xs text-gray-400"></span>
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <!-- Draft Actions Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                            Aksi Draft
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1">
                                <button type="button" onclick="duplicateDraft({{ $sale->id }})"
                                    class="text-gray-700 dark:text-gray-300 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Duplikasi Draft
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-6">Buat salinan draft ini
                                    </p>
                                </button>
                                <button type="button" onclick="showDraftHistory({{ $sale->id }})"
                                    class="text-gray-700 dark:text-gray-300 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Riwayat Perubahan
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-6">Lihat log perubahan
                                    </p>
                                </button>
                                <button type="button" onclick="previewInvoice({{ $sale->id }})"
                                    class="text-gray-700 dark:text-gray-300 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Preview Invoice
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-6">Lihat tampilan faktur
                                    </p>
                                </button>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('sales.drafts') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Draft
                    </a>
                </div>
            </div>
        </div>

        <!-- Unsaved Changes Warning -->
        <div id="unsaved-warning"
            class="hidden rounded-lg bg-amber-50 dark:bg-amber-900/30 p-4 mb-6 border border-amber-200 dark:border-amber-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400 dark:text-amber-300" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">Perubahan Belum Disimpan</h3>
                    <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                        <p>Anda memiliki perubahan yang belum disimpan. Auto-save akan menyimpan perubahan setiap 30
                            detik.</p>
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

        <form action="{{ route('sales.update', $sale) }}" method="POST" id="saleForm" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Card: Informasi Penjualan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informasi Penjualan
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Edit detail transaksi dan
                        informasi pelanggan</p>
                </div>

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
                                    class="pl-10 mt-1 block w-full bg-gray-50 dark:bg-gray-700/50 border-gray-300 dark:border-gray-600 auto-save-trigger"
                                    :value="$sale->invoice_number" readonly />
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nomor faktur tidak dapat diubah
                            </p>
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
                                    class="pl-10 mt-1 block w-full border-gray-300 dark:border-gray-600 auto-save-trigger"
                                    :value="old(
                                        'date',
                                        $sale->date
                                            ? \Carbon\Carbon::parse($sale->date)->format('Y-m-d')
                                            : date('Y-m-d'),
                                    )" />
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2 lg:col-span-1">
                            <x-input-label for="customer_select" value="Pelanggan"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1">
                                <select id="customer_select" name="customer_id"
                                    class="select2 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 auto-save-trigger">
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
                                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 auto-save-trigger">
                                    <option value="cash" {{ $sale->payment_method === 'cash' ? 'selected' : '' }}>
                                        Tunai</option>
                                    <option value="transfer"
                                        {{ $sale->payment_method === 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="credit" {{ $sale->payment_method === 'credit' ? 'selected' : '' }}>
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
                                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 auto-save-trigger">
                                    <option value="Truk" {{ $sale->vehicle_type === 'Truk' ? 'selected' : '' }}>Truk
                                    </option>
                                    <option value="Pickup" {{ $sale->vehicle_type === 'Pickup' ? 'selected' : '' }}>
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
                                    class="pl-10 mt-1 block w-full border-gray-300 dark:border-gray-600 auto-save-trigger"
                                    :value="old('vehicle_number', $sale->vehicle_number)" placeholder="Contoh: B 1234 XYZ" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Item Penjualan (similar structure but pre-filled with existing data) -->
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
                            <span
                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200">
                                {{ $sale->saleDetails->count() }} Item
                            </span>
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Edit atau tambahkan produk
                        </p>
                    </div>

                    <div class="flex items-center space-x-2">
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
                                @foreach ($sale->saleDetails as $index => $detail)
                                    <tr
                                        class="sale-item hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                        <td class="px-4 py-3">
                                            <select name="product_id[]" required
                                                class="product-select w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 auto-save-trigger">
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
                                                class="unit-select w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 auto-save-trigger">
                                                <option value="{{ $detail->product_unit_id }}" selected>
                                                    {{ $detail->productUnit->unit->name }}
                                                    ({{ $detail->productUnit->unit->abbreviation }})
                                                </option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-3">
                                            <input type="number" name="quantity[]" required
                                                class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 auto-save-trigger"
                                                value="{{ $detail->quantity }}" min="0.01" step="0.01">
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="selling_price[]" required
                                                    class="pl-10 w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 auto-save-trigger"
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

            <!-- Card: Detail Pembayaran (similar to create view but with existing values) -->
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
                                                class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-right focus:border-indigo-500 focus:ring-indigo-500 auto-save-trigger"
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
                        <!-- Payment sections similar to create view -->
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
                        class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md auto-save-trigger"
                        placeholder="Tambahkan catatan transaksi jika diperlukan...">{{ old('notes', $sale->notes) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between py-4">
                <button type="button" onclick="deleteDraft()"
                    class="inline-flex justify-center py-2 px-4 border border-red-300 dark:border-red-600 shadow-sm text-sm font-medium rounded-md text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Draft
                </button>

                <div class="flex space-x-3">
                    <a href="{{ route('sales.drafts') }}"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Perbarui Draft
                    </button>
                    <button type="submit" name="complete_transaction" value="1"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Selesaikan Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13"></script>

        <script>
            // Auto-save functionality for edit mode
            let autoSaveTimer;
            let hasUnsavedChanges = false;
            let isAutoSaving = false;

            $(document).ready(function() {
                // Initialize existing functionality
                initializeCustomerSelect();

                // Initialize existing items
                $('#saleItems tr').each(function() {
                    initializeProductSelect($(this));
                });

                updatePaymentSections();
                addInputValidations();

                // Auto-save event listeners
                $('.auto-save-trigger').on('input change', function() {
                    markAsChanged();
                    scheduleAutoSave();
                });

                // Event delegation for dynamic content
                $('#saleItems').on('input change', 'input, select', function() {
                    markAsChanged();
                    scheduleAutoSave();
                });

                // Calculate initial totals
                calculateTotal();

                // Before unload warning
                window.addEventListener('beforeunload', function(e) {
                    if (hasUnsavedChanges && !isAutoSaving) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });

                // Form submission
                $('#saleForm').on('submit', function(e) {
                    hasUnsavedChanges = false; // Clear flag on submit
                });
            });

            function markAsChanged() {
                if (!hasUnsavedChanges) {
                    hasUnsavedChanges = true;
                    $('#unsaved-warning').removeClass('hidden');
                }
            }

            function scheduleAutoSave() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(autoSaveDraft, 30000); // 30 seconds
            }

            function autoSaveDraft() {
                if (!hasUnsavedChanges || isAutoSaving) return;

                const items = collectFormItems();
                if (items.length === 0) return;

                isAutoSaving = true;
                updateAutoSaveStatus('Menyimpan...');

                const formData = {
                    sale_id: {{ $sale->id }},
                    customer_id: $('#customer_select').val(),
                    discount: $('#discount').val() || 0,
                    notes: $('#notes').val(),
                    vehicle_type: $('#vehicle_type').val(),
                    vehicle_number: $('#vehicle_number').val(),
                    payment_method: $('#payment_method').val(),
                    items: items
                };

                $.ajax({
                    url: '/sales/auto-save-draft',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            hasUnsavedChanges = false;
                            $('#unsaved-warning').addClass('hidden');
                            updateAutoSaveStatus('Tersimpan pada ' + response.saved_at);
                        }
                    },
                    error: function(xhr) {
                        console.error('Auto-save failed:', xhr.responseJSON);
                        updateAutoSaveStatus('Gagal menyimpan');
                    },
                    complete: function() {
                        isAutoSaving = false;
                    }
                });
            }

            function collectFormItems() {
                const items = [];
                $('#saleItems tr').each(function() {
                    const row = $(this);
                    const productId = row.find('select[name="product_id[]"]').val();
                    const unitId = row.find('select[name="unit_id[]"]').val();
                    const quantity = row.find('input[name="quantity[]"]').val();
                    const price = row.find('input[name="selling_price[]"]').val();

                    if (productId && unitId && quantity && price) {
                        items.push({
                            product_id: productId,
                            unit_id: unitId,
                            quantity: parseFloat(quantity),
                            selling_price: parseFloat(price)
                        });
                    }
                });
                return items;
            }

            function updateAutoSaveStatus(message) {
                $('#auto-save-status').text(message);
                setTimeout(() => {
                    $('#auto-save-status').text('');
                }, 3000);
            }

            function duplicateDraft(draftId) {
                if (!confirm('Apakah Anda yakin ingin menduplikasi draft ini?')) {
                    return;
                }

                $.ajax({
                    url: `/sales/${draftId}/duplicate`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(`Draft berhasil diduplikasi dengan nomor faktur: ${response.new_invoice_number}`);
                            window.location.href = `/sales/${response.new_draft_id}/edit`;
                        } else {
                            alert('Gagal menduplikasi draft: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menduplikasi draft');
                    }
                });
            }

            function deleteDraft() {
                if (!confirm('Apakah Anda yakin ingin menghapus draft ini? Tindakan ini tidak dapat dibatalkan.')) {
                    return;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/{{ $sale->id }}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }

            function previewInvoice(draftId) {
                window.open(`/sales/${draftId}/preview-invoice`, '_blank');
            }

            function showDraftHistory(draftId) {
                // Implementation for showing draft history
                alert('Fitur riwayat perubahan akan segera tersedia');
            }

            const saleDetails = @json($sale->saleDetails);

            function createItemRow(detail = null) {
                return `
        <tr>
            <td class="px-6 py-4">
                <select name="product_id[]" required class="product-select w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300" onchange="updateProductInfo(this)">
                    <option value="">Pilih Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            data-price="{{ $product->selling_price }}"
                            data-stock="{{ $product->stock }}"
                            ${detail && detail.product_id == {{ $product->id }} ? 'selected' : ''}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="px-6 py-4 stock-display text-gray-900 dark:text-gray-300">
                ${detail ? detail.product.stock : '-'}
            </td>
            <td class="px-6 py-4">
                <select name="unit_id[]" required class="unit-select w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300" onchange="updatePrice(this)">
                    <option value="">Pilih Satuan</option>
                    ${detail ? generateUnitOptions(detail) : ''}
                </select>
            </td>
            <td class="px-6 py-4">
                <input type="number" name="quantity[]" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300"
                    value="${detail ? detail.quantity : 1}" min="1"
                    onchange="calculateSubtotal(this)">
            </td>
            <td class="px-6 py-4">
                <input type="number" name="selling_price[]" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300"
                    value="${detail ? detail.price : 0}" min="0"
                    onchange="calculateSubtotal(this)">
            </td>
            <td class="px-6 py-4 subtotal text-gray-900 dark:text-gray-300">
                ${detail ? formatRupiah(detail.subtotal) : 'Rp 0'}
            </td>
            <td class="px-6 py-4">
                <button type="button" onclick="removeItem(this)"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                    Hapus
                </button>
            </td>
        </tr>
    `;
            }

            function generateUnitOptions(detail) {
                if (detail && detail.product_unit_id) {
                    return `<option value="${detail.product_unit_id}" selected>${detail.product_unit.unit.name}</option>`;
                }
                return '';
            }

            function updateProductInfo(select) {
                const productId = select.value;
                const row = select.closest('tr');
                const unitSelect = row.querySelector('select[name="unit_id[]"]');
                const priceInput = row.querySelector('input[name="selling_price[]"]');
                const stockDisplay = row.querySelector('.stock-display');

                // Clear unit options
                unitSelect.innerHTML = '<option value="">-- Pilih Satuan --</option>';

                if (productId) {
                    // Fetch product units via AJAX
                    fetch(`/api/products/${productId}/units`)
                        .then(response => response.json())
                        .then(data => {
                            // Update stock display
                            stockDisplay.textContent = data.stock;

                            // Populate unit options
                            data.units.forEach(unit => {
                                const option = document.createElement('option');
                                option.value = unit.id;
                                option.textContent = `${unit.unit_name} (${unit.unit_abbreviation})`;
                                option.dataset.price = unit.selling_price;
                                option.dataset.conversionFactor = unit.conversion_factor;
                                unitSelect.appendChild(option);
                            });

                            // Select default unit if available
                            const defaultUnit = Array.from(unitSelect.options).find(option =>
                                option.dataset.isDefault === "1");
                            if (defaultUnit) {
                                defaultUnit.selected = true;
                                unitSelect.dispatchEvent(new Event('change'));
                            }
                        });
                } else {
                    stockDisplay.textContent = '-';
                    priceInput.value = 0;
                    calculateSubtotal(priceInput);
                }
            }



            // All calculation functions remain the same as in create.blade.php
            function updatePrice(select) {
                const tr = select.closest('tr');
                const priceInput = tr.querySelector('input[name="selling_price[]"]');
                const stockDisplay = tr.querySelector('.available-stock');
                const quantityInput = tr.querySelector('input[name="quantity[]"]');
                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption.value) {
                    const price = selectedOption.dataset.price;
                    const stock = selectedOption.dataset.stock;
                    priceInput.value = price || 0;
                    stockDisplay.textContent = stock;
                    quantityInput.max = stock;
                } else {
                    priceInput.value = 0;
                    stockDisplay.textContent = 0;
                    quantityInput.max = 0;
                }

                calculateSubtotal(priceInput);
            }

            function calculateSubtotal(input) {
                const tr = input.closest('tr');
                const quantity = tr.querySelector('input[name="quantity[]"]').value || 0;
                const price = tr.querySelector('input[name="selling_price[]"]').value || 0;
                const subtotal = quantity * price;
                tr.querySelector('.subtotal').textContent = formatRupiah(subtotal);
                calculateTotal();
            }

            // Add all other calculation functions from create.blade.php...

            function addItem() {
                const tbody = document.getElementById('saleItems');
                const newRow = $(createItemRow());
                $(tbody).append(newRow);

                newRow.find('.product-select').on('change', function() {
                    updatePrice(this);
                });
                initializeSelect2ForRow(newRow);
            }

            function loadSaleDetails() {
                const tbody = document.getElementById('saleItems');
                tbody.innerHTML = ''; // Clear existing rows

                if (saleDetails && saleDetails.length) {
                    saleDetails.forEach(detail => {
                        tbody.insertAdjacentHTML('beforeend', createItemRow(detail));
                    });
                } else {
                    addItem(); // Add one empty row if no details
                }

                // Initialize Select2 for all product selects
                $('.product-select').each(function() {
                    initializeSelect2ForRow($(this).closest('tr'));
                });

                // Calculate initial totals
                calculateTotal();

                // Update payment method display
                document.getElementById('payment_method').dispatchEvent(new Event('change'));
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadSaleDetails();

                // Initialize Select2 for customer select
                $('#customer_select').select2({
                    theme: 'tailwind',
                    placeholder: 'Pilih Pelanggan',
                    allowClear: true
                });

                // Initialize payment method handling
                document.getElementById('payment_method').addEventListener('change', function() {
                    const dpContainer = document.getElementById('dp_container');
                    const cashSection = document.getElementById('cash-payment-section');

                    if (this.value === 'credit') {
                        dpContainer.classList.remove('hidden');
                        cashSection.classList.add('hidden');
                        calculateRemainingAmount();
                    } else {
                        dpContainer.classList.add('hidden');
                        cashSection.classList.remove('hidden');
                        calculateChange();
                    }
                });
            });

            // Add form validation
            document.getElementById('saleForm').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
            });

            function validateForm() {
                const paymentMethod = document.getElementById('payment_method').value;

                if (paymentMethod === 'credit' && !document.getElementById('customer_select').value) {
                    alert('Untuk pembayaran kredit, pilih pelanggan terlebih dahulu');
                    return false;
                }

                if (!validateProductStock()) {
                    return false;
                }

                return true;
            }

            function formatRupiah(number) {
                return 'Rp ' + Math.round(number).toLocaleString('id-ID');
            }
        </script>
    @endpush
    </x-app-layout>
