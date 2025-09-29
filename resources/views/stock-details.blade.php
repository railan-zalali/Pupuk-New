<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Stok Harian') }}</h2>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('weekly.stock.details') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    {{ __('Mingguan') }}
                </a>
                <a href="{{ route('monthly.stock.details') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    {{ __('Bulanan') }}
                </a>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Outgoing Stock Section -->
        <div>
            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Stok Keluar Harian</h3>
            
            <!-- Search Box for Outgoing Stock -->
            <div class="mt-3 mb-4">
                <input type="text" id="outgoing-search"
                    class="w-full max-w-md px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    placeholder="Cari produk atau pelanggan...">
            </div>
            
            <div class="mt-2">
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="outgoing-table">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Pelanggan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse ($outgoingStockDetails as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors searchable-row">
                                    <td class="whitespace-nowrap px-6 py-3 product-name">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $movement->product->name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400 customer-name">
                                        @if($movement->reference_type === 'initial')
                                            Stok Awal
                                        @elseif($movement->reference && $movement->reference_type === 'App\Models\Sale' && $movement->reference->customer)
                                            {{ $movement->reference->customer->name ?? 'Tidak ada' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr id="outgoing-no-data" style="display: none;">
                                    <td colspan="4" class="px-6 py-3 text-center text-gray-500">
                                        Tidak ada data yang ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Incoming Stock Section -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Stok Masuk Harian</h3>
            
            <!-- Search Box for Incoming Stock -->
            <div class="mt-3 mb-4">
                <input type="text" id="incoming-search"
                    class="w-full max-w-md px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    placeholder="Cari produk atau pengguna...">
            </div>
            
            <div class="mt-2">
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="incoming-table">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Pengguna</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse ($incomingStockDetails as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors searchable-row">
                                    <td class="whitespace-nowrap px-6 py-3 product-name">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $movement->product->name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400 user-name">
                                        @if($movement->reference_type === 'initial')
                                            Stok Awal
                                        @elseif($movement->reference && $movement->reference_type === 'App\Models\Purchase' && $movement->reference->user)
                                            {{ $movement->reference->user->name ?? 'Tidak ada' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr id="incoming-no-data" style="display: none;">
                                    <td colspan="4" class="px-6 py-3 text-center text-gray-500">
                                        Tidak ada data yang ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality for outgoing stock
            const outgoingSearch = document.getElementById('outgoing-search');
            const outgoingTable = document.getElementById('outgoing-table');
            const outgoingNoData = document.getElementById('outgoing-no-data');

            if (outgoingSearch && outgoingTable) {
                outgoingSearch.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = outgoingTable.querySelectorAll('.searchable-row');
                    let visibleRows = 0;

                    rows.forEach(function(row) {
                        const productName = row.querySelector('.product-name').textContent.toLowerCase();
                        const customerName = row.querySelector('.customer-name').textContent.toLowerCase();
                        
                        if (productName.includes(searchTerm) || customerName.includes(searchTerm)) {
                            row.style.display = '';
                            visibleRows++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show/hide no data message
                    if (outgoingNoData) {
                        outgoingNoData.style.display = visibleRows === 0 && searchTerm !== '' ? '' : 'none';
                    }
                });
            }

            // Search functionality for incoming stock
            const incomingSearch = document.getElementById('incoming-search');
            const incomingTable = document.getElementById('incoming-table');
            const incomingNoData = document.getElementById('incoming-no-data');

            if (incomingSearch && incomingTable) {
                incomingSearch.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = incomingTable.querySelectorAll('.searchable-row');
                    let visibleRows = 0;

                    rows.forEach(function(row) {
                        const productName = row.querySelector('.product-name').textContent.toLowerCase();
                        const userName = row.querySelector('.user-name').textContent.toLowerCase();
                        
                        if (productName.includes(searchTerm) || userName.includes(searchTerm)) {
                            row.style.display = '';
                            visibleRows++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show/hide no data message
                    if (incomingNoData) {
                        incomingNoData.style.display = visibleRows === 0 && searchTerm !== '' ? '' : 'none';
                    }
                });
            }
        });
    </script>
</x-app-layout>
