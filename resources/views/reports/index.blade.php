<x-app-layout>
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-chart-pie text-xl"></i>
                    </div>
                    {{ __('Pusat Laporan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Akses semua laporan operasional dan keuangan dalam satu tempat
                </p>
            </div>
        </div>

        <!-- Transaksi Section -->
        <div class="space-y-4 animate-fade-in-up delay-100">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                <i class="ti ti-exchange text-emerald-500"></i> Transaksi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Laporan Penjualan -->
                <a href="{{ route('reports.sales') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-shopping-cart text-2xl"></i>
                            </div>
                            <div class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                Utama
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Laporan Penjualan</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                            Analisis detail transaksi penjualan, omset, dan performa produk terlaris.
                        </p>
                        <div class="mt-4 flex items-center text-sm font-medium text-blue-600 dark:text-blue-400">
                            Lihat Detail <i class="ti ti-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </a>

                <!-- Laporan Pembelian -->
                <a href="{{ route('reports.purchases') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-truck-delivery text-2xl"></i>
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Laporan Pembelian</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                            Rekapitulasi pembelian stok dari supplier dan pengeluaran belanja.
                        </p>
                        <div class="mt-4 flex items-center text-sm font-medium text-amber-600 dark:text-amber-400">
                            Lihat Detail <i class="ti ti-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Inventori Section -->
        <div class="space-y-4 animate-fade-in-up delay-200">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                <i class="ti ti-box text-emerald-500"></i> Inventori & Stok
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Laporan Stok -->
                <a href="{{ route('reports.stock') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform duration-300 mb-4">
                            <i class="ti ti-packages text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Stok Saat Ini</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Posisi stok terkini dan valuasi aset.
                        </p>
                    </div>
                </a>

                <!-- Stok FIFO -->
                <a href="{{ route('reports.fifo-stock') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center text-teal-600 dark:text-teal-400 group-hover:scale-110 transition-transform duration-300 mb-4">
                            <i class="ti ti-sort-ascending-2 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">Analisa FIFO</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            First In First Out movement report.
                        </p>
                    </div>
                </a>

                <!-- Stok FEFO -->
                <a href="{{ route('reports.fefo-stock') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform duration-300 mb-4">
                            <i class="ti ti-calendar-time text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Analisa FEFO</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            First Expired First Out & expired items.
                        </p>
                    </div>
                </a>

                <a href="#" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 opacity-60 cursor-not-allowed">
                    <div class="p-6 relative">
                        <div class="absolute top-4 right-4 text-xs font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                            Segera
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 mb-4">
                            <i class="ti ti-chart-bar text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1">Stock Movement</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Masuk & keluar barang per periode.
                        </p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Keuangan Section -->
        <div class="space-y-4 animate-fade-in-up delay-300">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                <i class="ti ti-wallet text-emerald-500"></i> Keuangan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Laba Rugi -->
                <a href="{{ route('reports.profit-loss') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-report-money text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Laba / Rugi</h4>
                                <span class="text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 px-2 py-0.5 rounded">Penting</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Ringkasan pendapatan, pengeluaran, dan profitabilitas bisnis.
                        </p>
                    </div>
                </a>

                <!-- Arus Kas -->
                <a href="{{ route('reports.cash-flow') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300 mb-4">
                            <i class="ti ti-cash text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Arus Kas (Cash Flow)</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tracking pergerakan uang masuk dan keluar secara real-time.
                        </p>
                    </div>
                </a>

                <!-- Hutang Piutang -->
                <a href="{{ route('reports.accounts') }}" class="group card hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-2xl bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center text-pink-600 dark:text-pink-400 group-hover:scale-110 transition-transform duration-300 mb-4">
                            <i class="ti ti-scale text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">Hutang & Piutang</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Monitoring kewajiban pembayaran dan tagihan pelanggan.
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>