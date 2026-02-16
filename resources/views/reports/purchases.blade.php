<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="ti ti-truck-delivery text-xl"></i>
                    </div>
                    {{ __('Laporan Pembelian') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Analisa detail transaksi pembelian periode {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.purchases', array_merge(request()->query(), ['type' => 'excel'])) }}" class="btn-success flex items-center gap-2">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
                <a href="{{ route('reports.purchases', array_merge(request()->query(), ['type' => 'pdf'])) }}" class="btn-danger flex items-center gap-2">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </a>
                <a href="{{ route('reports.purchases', array_merge(request()->query(), ['type' => 'print'])) }}" class="btn-secondary flex items-center gap-2" target="_blank">
                    <i class="ti ti-printer"></i> Cetak
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card animate-fade-in-up delay-100">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between cursor-pointer" onclick="document.getElementById('filterContent').classList.toggle('hidden')">
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-filter text-lg text-emerald-500"></i> Filter Laporan
                </h3>
                <i class="ti ti-chevron-down text-gray-500"></i>
            </div>
            <div id="filterContent" class="p-6">
                <form action="{{ route('reports.purchases') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <x-input-label for="start_date" value="Tanggal Mulai" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400"></i>
                            </div>
                            <x-text-input type="date" name="start_date" id="start_date"
                                value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="pl-10 w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="end_date" value="Tanggal Akhir" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400"></i>
                            </div>
                            <x-text-input type="date" name="end_date" id="end_date"
                                value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="pl-10 w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="supplier_id" value="Supplier" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-building-store text-gray-400"></i>
                            </div>
                            <select name="supplier_id" id="supplier_id" class="input-field w-full pl-10">
                                <option value="">Semua Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-activity text-gray-400"></i>
                            </div>
                            <select name="status" id="status" class="input-field w-full pl-10">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Sebagian</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <x-input-label for="search" value="Cari Invoice" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-search text-gray-400"></i>
                            </div>
                            <x-text-input type="text" name="search" id="search"
                                value="{{ request('search', '') }}" placeholder="Cari nomor invoice..."
                                class="pl-10 w-full" />
                        </div>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full justify-center">
                            <i class="ti ti-search mr-2"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up delay-200">
            <!-- Total Purchases -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-amber-50 to-transparent dark:from-amber-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembelian</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $summary['total_purchases'] }}</h4>
                    <div class="mt-2 flex items-center text-xs text-amber-600 dark:text-amber-400">
                        <i class="ti ti-shopping-cart-plus mr-1"></i> Transaksi masuk
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-amber-100 dark:bg-amber-500/20 rounded-lg text-amber-600 dark:text-amber-400">
                    <i class="ti ti-receipt text-xl"></i>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-red-50 to-transparent dark:from-red-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</h4>
                    <div class="mt-2 flex items-center text-xs text-red-600 dark:text-red-400">
                        <i class="ti ti-coins mr-1"></i> Belanja stok
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-red-100 dark:bg-red-500/20 rounded-lg text-red-600 dark:text-red-400">
                    <i class="ti ti-wallet-off text-xl"></i>
                </div>
            </div>

            <!-- Average Purchase -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata / Transaksi</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($summary['average_purchase'], 0, ',', '.') }}</h4>
                    <div class="mt-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                        <i class="ti ti-chart-bar mr-1"></i> Nilai belanja
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-blue-100 dark:bg-blue-500/20 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-calculator text-xl"></i>
                </div>
            </div>

            <!-- Status Distribution -->
            <div class="card p-6 relative overflow-hidden group">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status Pembelian</p>
                <div class="space-y-2 mt-1">
                    @foreach ($summary['status_counts'] as $status => $count)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
                            @if($status == 'pending')
                            <i class="ti ti-clock text-amber-500"></i> Pending
                            @elseif($status == 'partial')
                            <i class="ti ti-chart-pie-2 text-blue-500"></i> Sebagian
                            @elseif($status == 'completed')
                            <i class="ti ti-circle-check text-emerald-500"></i> Selesai
                            @else
                            <i class="ti ti-circle-filled text-gray-400"></i> {{ ucfirst($status) }}
                            @endif
                        </span>
                        <span class="font-bold text-gray-900 dark:text-gray-100">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Purchase Table -->
        <div class="card overflow-hidden animate-fade-in-up delay-300">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-list text-emerald-500"></i> Riwayat Transaksi Pembelian
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($purchases as $purchase)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $purchase->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100 font-mono">
                                {{ $purchase->invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-xs text-amber-600 dark:text-amber-400 font-bold">
                                        {{ substr($purchase->supplier->name, 0, 1) }}
                                    </div>
                                    {{ $purchase->supplier->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($purchase->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                                @elseif($purchase->status === 'partial')
                                <span class="badge badge-blue">Sebagian</span>
                                @else
                                <span class="badge badge-emerald">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('purchases.show', $purchase) }}" class="btn-action btn-secondary inline-flex" title="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                        <i class="ti ti-file-off text-xl"></i>
                                    </div>
                                    <p>Tidak ada data pembelian dengan filter ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50">
                {{ $purchases->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>