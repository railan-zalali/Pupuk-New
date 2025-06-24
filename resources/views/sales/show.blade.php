<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Detail Transaksi') }} #{{ $sale->invoice_number }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ \Carbon\Carbon::parse($sale->date)->format('d F Y, H:i') }}
                </p>
            </div>

            <div class="flex space-x-3">
                @php
                    // Check if sale has seed products
                    $hasSeedProducts = $sale->saleDetails->filter(function ($detail) {
                        return $detail->product->category && 
                               (strtolower($detail->product->category->name) === 'benih');
                    })->isNotEmpty();
                    
                    // Check if sale has non-seed products
                    $hasNonSeedProducts = $sale->saleDetails->filter(function ($detail) {
                        return !$detail->product->category || 
                               (strtolower($detail->product->category->name) !== 'benih');
                    })->isNotEmpty();
                @endphp

                <button type="button" onclick="window.print()"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 border border-gray-300 shadow-sm hover:bg-gray-50 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Print
                </button>

                <!-- Dropdown Menu for Invoice Types -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Cetak Dokumen
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown panel -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="py-1">
                            @if ($hasNonSeedProducts)
                                <a href="{{ route('sales.invoice', $sale) }}" target="_blank"
                                    class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Invoice Biasa
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Untuk produk umum</p>
                                </a>
                            @endif

                            @if ($hasSeedProducts)
                                <a href="{{ route('sales.invoice-seeds', $sale) }}" target="_blank"
                                    class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Invoice Benih
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Dengan cap PPN dibebaskan</p>
                                </a>
                            @endif

                            <a href="{{ route('sales.delivery-note', $sale) }}" target="_blank"
                                class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Surat Jalan
                                </div>
                                <p class="text-xs text-gray-500 mt-1 ml-6">Dokumen pengiriman barang</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Banner -->
        @if ($sale->trashed() || $sale->payment_method === 'credit')
            <div
                class="rounded-lg {{ $sale->trashed() ? 'bg-red-50 dark:bg-red-900/50 border-l-4 border-red-400' : ($sale->remaining_amount > 0 ? 'bg-yellow-50 dark:bg-yellow-900/50 border-l-4 border-yellow-400' : 'bg-green-50 dark:bg-green-900/50 border-l-4 border-green-400') }} p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if ($sale->trashed())
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        @elseif($sale->remaining_amount > 0)
                            <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p
                            class="text-sm font-medium {{ $sale->trashed() ? 'text-red-800 dark:text-red-200' : ($sale->remaining_amount > 0 ? 'text-yellow-800 dark:text-yellow-200' : 'text-green-800 dark:text-green-200') }}">
                            @if ($sale->trashed())
                                Transaksi ini telah dibatalkan pada {{ $sale->deleted_at->format('d/m/Y H:i') }}
                            @elseif($sale->remaining_amount > 0)
                                Transaksi ini memiliki sisa pembayaran kredit sebesar Rp
                                {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                                @if ($sale->due_date)
                                    <span class="ml-2">•</span>
                                    <span class="ml-2">Jatuh tempo:
                                        {{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }}</span>
                                @endif
                            @else
                                Pembayaran kredit untuk transaksi ini telah lunas
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informasi Pelanggan
                </h3>

                @if ($sale->customer)
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Nama</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $sale->customer->nama }}</div>
                        </div>

                        <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">NIK</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $sale->customer->nik }}</div>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Alamat</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $sale->customer->alamat ?? '-' }},
                                {{ $sale->customer->desa_nama }},
                                {{ $sale->customer->kecamatan_nama }}
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('customers.show', $sale->customer) }}"
                                class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Detail Pelanggan
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-md text-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 mx-auto text-gray-400 dark:text-gray-500 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Pelanggan Umum</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tidak ada data pelanggan yang
                            terdaftar</p>
                    </div>
                @endif
            </div>

            <!-- Transaction Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Informasi Transaksi
                </h3>

                <div class="space-y-3">
                    <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">No. Invoice</div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $sale->invoice_number }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal</div>
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                        <div>
                            @if ($sale->trashed())
                                <span
                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/50 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-200">
                                    <svg class="mr-1 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Dibatalkan
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-200">
                                    <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Selesai
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Metode Pembayaran</div>
                        <div class="text-sm text-gray-900 dark:text-gray-100 capitalize">
                            @if ($sale->payment_method === 'cash')
                                <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tunai
                                </span>
                            @elseif ($sale->payment_method === 'credit')
                                <span class="inline-flex items-center text-yellow-600 dark:text-yellow-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Kredit
                                </span>
                            @elseif ($sale->payment_method === 'transfer')
                                <span class="inline-flex items-center text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                    Transfer Bank
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($sale->vehicle_type || $sale->vehicle_number)
                        <div class="grid grid-cols-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Kendaraan</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $sale->vehicle_type ?? '-' }}
                                @if ($sale->vehicle_number)
                                    ({{ $sale->vehicle_number }})
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Kasir</div>
                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $sale->user->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Detail Pembayaran
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Belanja</dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Potongan</dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($sale->discount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Setelah Potongan</dt>
                        <dd class="mt-1 text-xl font-semibold text-indigo-600 dark:text-indigo-400">
                            Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $sale->payment_method === 'credit' ? 'Uang Muka' : 'Dibayar' }}
                        </dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Rp
                            {{ number_format($sale->payment_method === 'credit' ? $sale->down_payment : $sale->paid_amount, 0, ',', '.') }}
                        </dd>
                    </div>
                </div>

                @if ($sale->payment_method === 'credit')
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Terbayar</dt>
                            <dd class="mt-1 text-xl font-semibold text-green-600 dark:text-green-400">
                                Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Hutang</dt>
                            <dd
                                class="mt-1 text-xl font-semibold {{ $sale->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                                Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jatuh Tempo</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $sale->due_date ? \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') : '-' }}
                            </dd>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sale Items -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Item Penjualan
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Satuan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($sale->saleDetails as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $detail->product->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ strtolower($detail->product->category->name ?? '') === 'benih' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200' }}">
                                            {{ $detail->product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $detail->productUnit->unit->name }}
                                        ({{ $detail->productUnit->unit->abbreviation }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if ($sale->notes)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Catatan
                </h3>
                <div
                    class="mt-4 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $sale->notes }}</p>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end space-x-3 print:hidden">
            <button type="button" onclick="window.history.back()"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </button>

            @if ($sale->payment_method === 'credit' && $sale->remaining_amount > 0 && !$sale->trashed())
                <a href="{{ route('sales.credit', ['sale_id' => $sale->id]) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Terima Pembayaran
                </a>
            @endif

            @unless ($sale->trashed())
                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan penjualan ini? Ini akan mengembalikan produk ke inventaris.')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Batalkan Penjualan
                    </button>
                </form>
            @endunless
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print:hidden {
                display: block !important;
            }

            .no-print,
            .print\\:hidden {
                display: none !important;
            }

            @page {
                margin: 2cm;
                size: auto;
            }
        }
    </style>
</x-app-layout>