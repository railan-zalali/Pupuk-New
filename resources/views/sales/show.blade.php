<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header with Status Badge -->
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                            {{ __('Detail Transaksi') }} 
                            <span class="ml-2 text-indigo-600 dark:text-indigo-400">#{{ $sale->invoice_number }}</span>
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
                </div>
                
                <!-- Status Badge -->
                <div class="mt-3 flex items-center">
                    @if ($sale->trashed())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Transaksi Dibatalkan
                        </span>
                    @elseif ($sale->status === 'draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Draft
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Transaksi Selesai
                        </span>
                    @endif
                    
                    @if ($sale->payment_method === 'credit')
                        @if ($sale->remaining_amount > 0)
                            <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Kredit (Belum Lunas)
                            </span>
                        @else
                            <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Kredit (Lunas)
                            </span>
                        @endif
                    @endif
                </div>
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

        <!-- Transaction Timeline -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Status Transaksi
            </h3>
            
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-5 top-0 h-full w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-6">
                    <!-- Created -->
                    <div class="relative flex items-start">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 z-10">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Transaksi Dibuat</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($sale->created_at)->format('d F Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Transaksi dibuat oleh {{ $sale->user->name }}</p>
                        </div>
                    </div>
                    
                    @if ($sale->draft_id)
                    <!-- From Draft -->
                    <div class="relative flex items-start">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 z-10">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Dibuat dari Draft</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($sale->date)->format('d F Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Transaksi ini dibuat dari draft <a href="{{ route('drafts.show', $sale->draft_id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">#{{ $sale->draft->invoice_number }}</a>
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if ($sale->payment_method === 'credit')
                    <!-- Credit Payment -->
                    <div class="relative flex items-start">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full {{ $sale->remaining_amount > 0 ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-green-100 dark:bg-green-900/30' }} z-10">
                            <svg class="h-5 w-5 {{ $sale->remaining_amount > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Pembayaran Kredit</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($sale->date)->format('d F Y, H:i') }}</p>
                            @if ($sale->remaining_amount > 0)
                                <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                    Sisa pembayaran: Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                                    @if ($sale->due_date)
                                        <span class="ml-2">•</span>
                                        <span class="ml-2">Jatuh tempo: {{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }}</span>
                                    @endif
                                </p>
                            @else
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">Pembayaran kredit telah lunas</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    @if ($sale->trashed())
                    <!-- Cancelled -->
                    <div class="relative flex items-start">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 z-10">
                            <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Transaksi Dibatalkan</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $sale->deleted_at->format('d F Y, H:i') }}</p>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">Semua produk telah dikembalikan ke inventaris</p>
                        </div>
                    </div>
                    @else
                    <!-- Completed -->
                    <div class="relative flex items-start">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 z-10">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Transaksi Selesai</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($sale->date)->format('d F Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Transaksi telah selesai dengan metode pembayaran 
                                <span class="font-medium capitalize">
                                    @if ($sale->payment_method === 'cash')
                                        Tunai
                                    @elseif ($sale->payment_method === 'credit')
                                        Kredit
                                    @elseif ($sale->payment_method === 'transfer')
                                        Transfer Bank
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

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
                
                @if ($sale->draft_id)
                <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-md border-l-4 border-blue-400">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                Transaksi ini dibuat dari draft <a href="{{ route('drafts.show', $sale->draft_id) }}" class="underline hover:text-blue-600">#{{ $sale->draft->invoice_number }}</a>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Detail Pembayaran
                </h3>

                <!-- Payment Summary Card -->
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/30 dark:to-blue-900/30 rounded-lg p-6 border border-indigo-100 dark:border-indigo-800/30 shadow-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Belanja:</span>
                                <span class="ml-auto text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <div class="flex items-center mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Potongan:</span>
                                <span class="ml-auto text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($sale->discount, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <div class="flex items-center mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Setelah Potongan:</span>
                                <span class="ml-auto text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="{{ $sale->payment_method === 'credit' ? 'border-l border-indigo-200 dark:border-indigo-700 pl-4' : '' }}">
                            @if ($sale->payment_method === 'credit')
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uang Muka:</span>
                                    <span class="ml-auto text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($sale->down_payment, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Terbayar:</span>
                                    <span class="ml-auto text-sm font-semibold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 {{ $sale->remaining_amount > 0 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sisa Hutang:</span>
                                    <span class="ml-auto text-sm font-semibold {{ $sale->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                @if ($sale->due_date)
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Jatuh Tempo:</span>
                                    <span class="ml-auto text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($sale->due_date)->format('d F Y') }}
                                    </span>
                                </div>
                                @endif
                            @else
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dibayar:</span>
                                    <span class="ml-auto text-sm font-semibold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                @if ($sale->payment_method === 'cash')
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran:</span>
                                    <span class="ml-auto text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Tunai
                                    </span>
                                </div>
                                @elseif ($sale->payment_method === 'transfer')
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran:</span>
                                    <span class="ml-auto text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Transfer Bank
                                    </span>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Payment Summary Cards -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Total Belanja
                        </dt>
                        <dd class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Potongan
                        </dt>
                        <dd class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($sale->discount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                            Total Setelah Potongan
                        </dt>
                        <dd class="mt-2 text-2xl font-semibold text-indigo-600 dark:text-indigo-400">
                            Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                            @if ($sale->payment_method === 'credit')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 {{ $sale->remaining_amount > 0 ? 'text-yellow-500 dark:text-yellow-400' : 'text-green-500 dark:text-green-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Status Pembayaran
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Status Pembayaran
                            @endif
                        </dt>
                        <dd class="mt-2 text-xl font-semibold {{ $sale->payment_method === 'credit' && $sale->remaining_amount > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}">
                            @if ($sale->payment_method === 'credit')
                                @if ($sale->remaining_amount > 0)
                                    Kredit (Belum Lunas)
                                @else
                                    Kredit (Lunas)
                                @endif
                            @else
                                Lunas
                            @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sale Items -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Item Penjualan
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $sale->saleDetails->count() }} item
                        </span>
                        <span class="bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Total: Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
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
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($sale->saleDetails as $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $detail->product->name }}</div>
                                        @if($detail->product->code)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kode: {{ $detail->product->code }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ strtolower($detail->product->category->name ?? '') === 'benih' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200' }}">
                                            {{ $detail->product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div>{{ $detail->productUnit->unit->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">({{ $detail->productUnit->unit->abbreviation }})</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-medium">
                                        <span class="px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-md">{{ $detail->quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div>Rp {{ number_format($detail->price, 0, ',', '.') }}</div>
                                        @if($detail->productUnit->conversion > 1)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Per {{ $detail->productUnit->unit->abbreviation }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-medium">
                                        <span class="px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-md">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- Summary Row -->
                            <tr class="bg-gray-50 dark:bg-gray-700/50 border-t-2 border-gray-200 dark:border-gray-600">
                                <td colspan="5" class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                    Total
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-white bg-indigo-600 dark:bg-indigo-500 px-3 py-1 rounded-md">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if ($sale->notes)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Catatan
                </h3>
                <div class="mt-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 p-5 rounded-lg border border-yellow-100 dark:border-yellow-800/30 shadow-sm">
                    <div class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 dark:text-yellow-400 flex-shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-700 dark:text-gray-300 italic">{{ $sale->notes }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 print:hidden">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                Tindakan
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 hover:shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </button>

                <button type="button" onclick="window.print()"
                    class="inline-flex items-center justify-center px-4 py-2.5 border border-indigo-200 dark:border-indigo-800 rounded-lg shadow-sm text-sm font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-800/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 hover:shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak</span>
                </button>

                @if ($sale->payment_method === 'credit' && $sale->remaining_amount > 0 && !$sale->trashed())
                    <a href="{{ route('sales.credit', ['sale_id' => $sale->id]) }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Terima Pembayaran</span>
                    </a>
                @endif

                @unless ($sale->trashed())
                    <button type="button" 
                        data-sale-id="{{ $sale->id }}" 
                        data-invoice="{{ $sale->invoice_number }}"
                        class="cancel-sale-btn inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Batalkan Penjualan</span>
                    </button>
                @endunless
            </div>
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
    <!-- Cancel Sale Confirmation Modal -->
    <div id="cancelSaleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Konfirmasi Pembatalan
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Apakah Anda yakin ingin membatalkan penjualan dengan nomor invoice <span id="invoice-number" class="font-medium text-gray-900 dark:text-gray-100"></span>? 
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Tindakan ini akan mengembalikan semua produk ke inventaris menggunakan prinsip FIFO.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="cancelSaleForm" action="" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Batalkan Penjualan
                        </button>
                    </form>
                    <button type="button" id="cancelModalBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cancel Sale Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const cancelSaleModal = document.getElementById('cancelSaleModal');
            const cancelSaleButtons = document.querySelectorAll('.cancel-sale-btn');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const invoiceNumberSpan = document.getElementById('invoice-number');
            const cancelSaleForm = document.getElementById('cancelSaleForm');
            
            // Show modal when cancel button is clicked
            cancelSaleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const saleId = this.getAttribute('data-sale-id');
                    const invoiceNumber = this.getAttribute('data-invoice');
                    
                    // Set the invoice number in the modal
                    invoiceNumberSpan.textContent = invoiceNumber;
                    
                    // Set the form action
                    cancelSaleForm.action = `/sales/${saleId}`;
                    
                    // Show the modal
                    cancelSaleModal.classList.remove('hidden');
                });
            });
            
            // Hide modal when cancel button is clicked
            cancelModalBtn.addEventListener('click', function() {
                cancelSaleModal.classList.add('hidden');
            });
            
            // Close modal when clicking outside
            cancelSaleModal.addEventListener('click', function(event) {
                if (event.target === cancelSaleModal) {
                    cancelSaleModal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>