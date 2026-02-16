<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-packages text-xl"></i>
                    </div>
                    {{ __('Laporan Stok') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Lihat posisi stok terkini dan valuasi aset persediaan
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.stock', array_merge(request()->query(), ['type' => 'excel'])) }}" class="btn-success flex items-center gap-2">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
                <a href="{{ route('reports.stock', array_merge(request()->query(), ['type' => 'pdf'])) }}" class="btn-danger flex items-center gap-2">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </a>
                <a href="{{ route('reports.stock', array_merge(request()->query(), ['type' => 'print'])) }}" class="btn-secondary flex items-center gap-2" target="_blank">
                    <i class="ti ti-printer"></i> Cetak
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up delay-100">
            <!-- Total Products -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Item Produk</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ number_format($summary['total_products']) }}</h4>
                    <div class="mt-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                        <i class="ti ti-box mr-1"></i> SKU Aktif
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-blue-100 dark:bg-blue-500/20 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-layout-grid text-xl"></i>
                </div>
            </div>

            <!-- Stock Value -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-emerald-50 to-transparent dark:from-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Nilai Aset</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($summary['total_stock_value'], 0, ',', '.') }}</h4>
                    <div class="mt-2 flex items-center text-xs text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-coins mr-1"></i> Valuasi persediaan
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-wallet text-xl"></i>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-red-50 to-transparent dark:from-red-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Menipis</p>
                    <h4 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">{{ number_format($summary['low_stock_count']) }}</h4>
                    <div class="mt-2 flex items-center text-xs text-red-600 dark:text-red-400">
                        <i class="ti ti-alert-triangle mr-1"></i> Perlu restock
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-red-100 dark:bg-red-500/20 rounded-lg text-red-600 dark:text-red-400">
                    <i class="ti ti-alert-circle text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Stock Table -->
        <div class="card overflow-hidden animate-fade-in-up delay-200">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-list text-emerald-500"></i> Rincian Stok
                </h3>

                <form action="{{ route('reports.stock') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama produk / kode..."
                            class="pl-10 pr-4 py-2 w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:text-gray-200">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-search text-gray-400"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">HPP</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Aset</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">
                                {{ $product->code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900 dark:text-gray-100">
                                {{ number_format($product->actual_stock) }} {{ $product->unit ?? 'Unit' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($product->actual_stock * $product->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($product->actual_stock <= $product->min_stock)
                                    <span class="badge badge-red">Stok Rendah</span>
                                    @else
                                    <span class="badge badge-emerald">Aman</span>
                                    @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                        <i class="ti ti-package-off text-xl"></i>
                                    </div>
                                    <p>Tidak ada data produk yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>