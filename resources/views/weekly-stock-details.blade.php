<x-app-layout>
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                {{ __('Detail Stok Mingguan') }}
            </h2>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('stock.details') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    {{ __('Harian') }}
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

        @forelse ($stockDetails as $detail)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">
                    {{ $detail['date']->format('l, d M Y') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Stok Keluar --}}
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-2">
                            Stok Keluar
                        </h4>
                        
                        <!-- Search Box for Outgoing Stock -->
                        <div class="mb-3">
                            <input type="text" 
                                   class="outgoing-search w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="Cari produk, pelanggan..."
                                   data-date="{{ $detail['date']->format('Y-m-d') }}">
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm outgoing-table" data-date="{{ $detail['date']->format('Y-m-d') }}">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="p-2 text-left">Produk</th>
                                        <th class="p-2 text-left">Jumlah</th>
                                        <th class="p-2 text-left">Pelanggan</th>
                                        <th class="p-2 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detail['outgoing_stock'] as $movement)
                                        <tr class="border-b dark:border-gray-700 searchable-row">
                                            <td class="p-2 product-name">{{ $movement->product->name }}</td>
                                            <td class="p-2">{{ $movement->quantity }}</td>
                                            <td class="p-2 customer-name">
                                                @if($movement->reference_type === 'initial')
                                                    Stok Awal
                                                @elseif($movement->reference && ($movement->reference_type === 'App\Models\Sale' || $movement->reference_type === 'sale') && $movement->reference->customer)
                                                    {{ $movement->reference->customer->name ?? 'Tidak ada' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="p-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr class="no-data-row">
                                            <td colspan="4" class="p-2 text-center text-gray-500">
                                                Tidak ada stok keluar
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Stok Masuk --}}
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-2">
                            Stok Masuk
                        </h4>
                        
                        <!-- Search Box for Incoming Stock -->
                        <div class="mb-3">
                            <input type="text" 
                                   class="incoming-search w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="Cari produk, pengguna..."
                                   data-date="{{ $detail['date']->format('Y-m-d') }}">
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm incoming-table" data-date="{{ $detail['date']->format('Y-m-d') }}">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="p-2 text-left">Produk</th>
                                        <th class="p-2 text-left">Jumlah</th>
                                        <th class="p-2 text-left">Pengguna</th>
                                        <th class="p-2 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detail['incoming_stock'] as $movement)
                                        <tr class="border-b dark:border-gray-700 searchable-row">
                                            <td class="p-2 product-name">{{ $movement->product->name }}</td>
                                            <td class="p-2">{{ $movement->quantity }}</td>
                                            <td class="p-2 user-name">
                                                @if($movement->reference_type === 'initial')
                                                    Stok Awal
                                                @elseif($movement->reference && $movement->reference_type === 'App\Models\Purchase' && $movement->reference->user)
                                                    {{ $movement->reference->user->name ?? 'Tidak ada' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="p-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr class="no-data-row">
                                            <td colspan="4" class="p-2 text-center text-gray-500">
                                                Tidak ada stok masuk
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-4">
                Tidak ada data stok mingguan
            </div>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality for outgoing stock
            document.querySelectorAll('.outgoing-search').forEach(function(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const date = this.getAttribute('data-date');
                    const table = document.querySelector(`.outgoing-table[data-date="${date}"]`);
                    const rows = table.querySelectorAll('.searchable-row');
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
                    const noDataRow = table.querySelector('.no-data-row');
                    if (noDataRow) {
                        noDataRow.style.display = visibleRows === 0 && searchTerm !== '' ? '' : 'none';
                    }
                });
            });

            // Search functionality for incoming stock
            document.querySelectorAll('.incoming-search').forEach(function(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const date = this.getAttribute('data-date');
                    const table = document.querySelector(`.incoming-table[data-date="${date}"]`);
                    const rows = table.querySelectorAll('.searchable-row');
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
                    const noDataRow = table.querySelector('.no-data-row');
                    if (noDataRow) {
                        noDataRow.style.display = visibleRows === 0 && searchTerm !== '' ? '' : 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>
