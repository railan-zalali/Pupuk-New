<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Transaksi Penjualan') }}</h2>

            <!-- Action buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex space-x-2">
                    <div class="relative text-gray-700 focus-within:text-gray-800 flex-grow">
                        <input type="text" id="searchInput" placeholder="Cari faktur, pelanggan..."
                            class="input-primary w-full pl-10 pr-4 py-2 rounded-md border dark:bg-gray-800 dark:text-gray-300 border-gray-300 focus:ring focus:ring-blue-200 focus:outline-none"
                            autofocus>
                        <i class="ti ti-search absolute left-3 top-2.5 text-gray-400 dark:text-gray-300"></i>
                    </div>
                    <a href="{{ route('sales.create') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Transaksi
                    </a>
                </div>

                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-2">
                    <select id="statusFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="completed">Selesai</option>
                        <option value="processing">Diproses</option>
                        <option value="draft">Draft</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <select id="paymentFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring focus:ring-blue-200 focus:border-blue-500">
                        <option value="">Semua Pembayaran</option>
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="credit">Kredit</option>
                    </select>
                    <button id="dateFilterBtn" class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 text-gray-900">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div id="sales-table-container" class="overflow-x-auto relative bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-md">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Faktur</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Pelanggan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Pembayaran</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-gray-50 text-xs dark:hover:bg-gray-700 {{ $sale->trashed() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                <td class="whitespace-nowrap px-6 py-4 text-gray-900 dark:text-gray-100">
                                    <div class="font-medium">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</div>
                                    <div class="text-gray-500 dark:text-gray-400 text-xs font-normal">
                                        {{ \Carbon\Carbon::parse($sale->date)->setTimezone('Asia/Jakarta')->format('H:i') }}
                                        <span class="text-gray-400 dark:text-gray-500">({{ \Carbon\Carbon::parse($sale->date)->diffForHumans() }})</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $sale->invoice_number }}</div>
                                    @if($sale->status === 'draft')
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($sale->customer)
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $sale->customer->nama }}</div>
                                        @if($sale->customer->phone)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->customer->phone }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                    <div class="font-medium">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
                                    @if($sale->discount > 0)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Diskon: Rp {{ number_format($sale->discount, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $paymentClasses = [
                                            'cash' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
                                            'transfer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                                            'credit' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200'
                                        ];
                                        $paymentIcons = [
                                            'cash' => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>',
                                            'transfer' => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"></path></svg>',
                                            'credit' => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>'
                                        ];
                                        $paymentClass = $paymentClasses[$sale->payment_method] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                        $paymentIcon = $paymentIcons[$sale->payment_method] ?? '';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $paymentClass }}">
                                        {!! $paymentIcon !!}
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>

                                    @if($sale->payment_method === 'credit' && $sale->remaining_amount > 0)
                                        <div class="mt-1 text-xs text-red-600 dark:text-red-400">Sisa: Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($sale->trashed())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Dibatalkan
                                        </span>
                                    @else
                                        @if ($sale->status === 'draft')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Draft
                                            </span>
                                        @elseif ($sale->status === 'processing')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Diproses
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Selesai
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                     <div class="flex items-center space-x-3">
                                         <a href="{{ route('sales.show', $sale) }}"
                                             class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                             </svg>
                                         </a>

                                         @unless ($sale->trashed())
                                             @if ($sale->status === 'draft')
                                                 <a href="{{ route('sales.edit', $sale) }}"
                                                     class="p-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-200 rounded-full hover:bg-yellow-50 dark:hover:bg-yellow-900/50">
                                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                             d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                     </svg>
                                                 </a>
                                             @endif
                                             <button type="button"
                                                 class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 rounded-full hover:bg-red-50 dark:hover:bg-red-900/50 cancel-sale-btn"
                                                 data-sale-id="{{ $sale->id }}" 
                                                 data-invoice="{{ $sale->invoice_number }}">
                                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                     <path stroke-linecap="round" stroke-linejoin="round"
                                                         stroke-width="2"
                                                         d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                 </svg>
                                             </button>
                                         @endunless
                                     </div>
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada
                                            transaksi
                                            ditemukan</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tambahkan transaksi
                                            baru
                                            untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembatalan -->
     <div id="cancelSaleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
         <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
             <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                 <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
             </div>

             <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

             <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                 <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                     <div class="sm:flex sm:items-start">
                         <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                             <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                             </svg>
                         </div>
                         <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                             <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">Konfirmasi Pembatalan</h3>
                             <div class="mt-2">
                                 <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin membatalkan transaksi dengan nomor faktur <span id="invoiceNumber" class="font-medium text-gray-900 dark:text-gray-100"></span>?</p>
                                 <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Tindakan ini akan mengembalikan stok produk dan tidak dapat dibatalkan.</p>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                     <form id="cancelSaleForm" method="POST">
                         @csrf
                         @method('DELETE')
                         <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                             Batalkan Transaksi
                         </button>
                     </form>
                     <button type="button" id="cancelModalClose" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                         Batal
                     </button>
                 </div>
             </div>
         </div>
     </div>

     @push('scripts')
         <script>
             document.addEventListener('DOMContentLoaded', function() {
                // Filter dan pencarian AJAX
                const searchInput = document.getElementById('searchInput');
                const statusFilter = document.getElementById('statusFilter');
                const paymentFilter = document.getElementById('paymentFilter');
                const dateFilterBtn = document.getElementById('dateFilterBtn');
                const dateFilterModal = document.getElementById('dateFilterModal');
                const dateModalClose = document.getElementById('dateModalClose');
                const applyDateFilter = document.getElementById('applyDateFilter');
                const clearDateFilter = document.getElementById('clearDateFilter');
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                
                let debounceTimer;
                let currentFilters = {
                    search: '',
                    status: '',
                    payment_method: '',
                    start_date: '',
                    end_date: ''
                };
                
                // Fungsi untuk memuat data dengan filter
                function loadSalesData() {
                    const tableContainer = document.getElementById('sales-table-container');
                    
                    // Tambahkan indikator loading
                    tableContainer.innerHTML = '<div class="flex justify-center items-center p-8"><svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
                    
                    // Buat URL dengan parameter filter
                    const params = new URLSearchParams();
                    if (currentFilters.search) params.append('search', currentFilters.search);
                    if (currentFilters.status) params.append('status', currentFilters.status);
                    if (currentFilters.payment_method) params.append('payment_method', currentFilters.payment_method);
                    if (currentFilters.start_date) params.append('start_date', currentFilters.start_date);
                    if (currentFilters.end_date) params.append('end_date', currentFilters.end_date);
                    
                    // Tambahkan parameter AJAX
                    params.append('ajax', 1);
                    
                    // Kirim request AJAX
                    fetch(`${window.location.pathname}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        
                        // Reinisialisasi tombol pembatalan setelah tabel diperbarui
                        initCancelButtons();
                    })
                    .catch(error => {
                        console.error('Error loading sales data:', error);
                        tableContainer.innerHTML = '<div class="p-4 text-center text-red-500">Terjadi kesalahan saat memuat data. Silakan coba lagi.</div>';
                    });
                }
                
                // Event listener untuk pencarian
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        currentFilters.search = this.value;
                        loadSalesData();
                    }, 300);
                });
                
                // Event listener untuk filter status
                statusFilter.addEventListener('change', function() {
                    currentFilters.status = this.value;
                    loadSalesData();
                });
                
                // Event listener untuk filter metode pembayaran
                paymentFilter.addEventListener('change', function() {
                    currentFilters.payment_method = this.value;
                    loadSalesData();
                });
                
                // Event listener untuk tombol filter tanggal
                dateFilterBtn.addEventListener('click', function() {
                    dateFilterModal.classList.remove('hidden');
                });
                
                // Event listener untuk tombol tutup modal tanggal
                dateModalClose.addEventListener('click', function() {
                    dateFilterModal.classList.add('hidden');
                });
                
                // Event listener untuk tombol terapkan filter tanggal
                applyDateFilter.addEventListener('click', function() {
                    currentFilters.start_date = startDateInput.value;
                    currentFilters.end_date = endDateInput.value;
                    loadSalesData();
                    dateFilterModal.classList.add('hidden');
                });
                
                // Event listener untuk tombol hapus filter tanggal
                clearDateFilter.addEventListener('click', function() {
                    startDateInput.value = '';
                    endDateInput.value = '';
                    currentFilters.start_date = '';
                    currentFilters.end_date = '';
                    loadSalesData();
                    dateFilterModal.classList.add('hidden');
                });
                
                // Tutup modal filter tanggal saat klik di luar modal
                window.addEventListener('click', function(event) {
                    if (event.target === dateFilterModal) {
                        dateFilterModal.classList.add('hidden');
                    }
                });
                
                // Modal Konfirmasi Pembatalan
                const cancelModal = document.getElementById('cancelSaleModal');
                const cancelModalClose = document.getElementById('cancelModalClose');
                const invoiceSpan = document.getElementById('invoiceNumber');
                const cancelForm = document.getElementById('cancelSaleForm');
                
                // Fungsi untuk menginisialisasi tombol pembatalan
                function initCancelButtons() {
                    const cancelButtons = document.querySelectorAll('.cancel-sale-btn');
                    
                    cancelButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const saleId = this.getAttribute('data-sale-id');
                            const invoice = this.getAttribute('data-invoice');
                            
                            // Set nomor faktur di modal
                            invoiceSpan.textContent = invoice;
                            
                            // Set action form
                            cancelForm.action = `/sales/${saleId}`;
                            
                            // Tampilkan modal
                            cancelModal.classList.remove('hidden');
                        });
                    });
                }
                
                // Inisialisasi tombol pembatalan
                initCancelButtons();
                
                // Tutup modal pembatalan saat tombol batal diklik
                cancelModalClose.addEventListener('click', function() {
                    cancelModal.classList.add('hidden');
                });
                
                // Tutup modal pembatalan saat klik di luar modal
                window.addEventListener('click', function(event) {
                    if (event.target === cancelModal) {
                        cancelModal.classList.add('hidden');
                    }
                });
             });
         </script>
     @endpush
    @include('sales.partials.date_filter_modal')
</x-app-layout>
