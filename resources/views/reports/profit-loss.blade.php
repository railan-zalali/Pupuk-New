<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <i class="ti ti-report-money text-xl"></i>
                    </div>
                    {{ __('Laporan Laba Rugi') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Analisis pendapatan, pengeluaran, dan profitabilitas periode {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.profit-loss', array_merge(request()->query(), ['type' => 'excel'])) }}" class="btn-success flex items-center gap-2">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
                <a href="{{ route('reports.profit-loss', array_merge(request()->query(), ['type' => 'pdf'])) }}" class="btn-danger flex items-center gap-2">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </a>
                <a href="{{ route('reports.profit-loss', array_merge(request()->query(), ['type' => 'print'])) }}" class="btn-secondary flex items-center gap-2" target="_blank">
                    <i class="ti ti-printer"></i> Cetak
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card animate-fade-in-up delay-100">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between cursor-pointer" onclick="document.getElementById('filterContent').classList.toggle('hidden')">
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-filter text-lg text-emerald-500"></i> Filter Periode
                </h3>
                <i class="ti ti-chevron-down text-gray-500"></i>
            </div>
            <div id="filterContent" class="p-6">
                <form action="{{ route('reports.profit-loss') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full justify-center">
                            <i class="ti ti-search mr-2"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in-up delay-200">
            <!-- Total Sales -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-emerald-50 to-transparent dark:from-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Penjualan</p>
                    <h4 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">
                        + Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-emoji-600 dark:text-emerald-400">
                        <i class="ti ti-trending-up mr-1"></i> Pendapatan
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-cash text-xl"></i>
                </div>
            </div>

            <!-- Total Purchases -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-red-50 to-transparent dark:from-red-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembelian</p>
                    <h4 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                        - Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-red-600 dark:text-red-400">
                        <i class="ti ti-trending-down mr-1"></i> Pengeluaran
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-red-100 dark:bg-red-500/20 rounded-lg text-red-600 dark:text-red-400">
                    <i class="ti ti-shopping-cart text-xl"></i>
                </div>
            </div>

            <!-- Gross Profit -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-purple-50 to-transparent dark:from-purple-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Laba Kotor</p>
                    <h4 class="text-2xl font-bold {{ $summary['gross_profit'] >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400' }} mt-2">
                        Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-purple-600 dark:text-purple-400">
                        <i class="ti ti-chart-pie mr-1"></i> Profitabilitas
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-purple-100 dark:bg-purple-500/20 rounded-lg text-purple-600 dark:text-purple-400">
                    <i class="ti ti-coin text-xl"></i>
                </div>
            </div>

            <!-- Transaction Count -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transaksi</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        {{ number_format($summary['total_transactions']) }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                        <i class="ti ti-list mr-1"></i> Aktivitas
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-blue-100 dark:bg-blue-500/20 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-activity text-xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300">
            <!-- Details Table -->
            <div class="lg:col-span-2 card overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="ti ti-list-details text-emerald-500"></i> Rincian Transaksi
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Referensi</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($items as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item['type'] == 'Sale')
                                    <span class="badge badge-emerald">
                                        <i class="ti ti-arrow-up-right mr-1"></i> Penjualan
                                    </span>
                                    @else
                                    <span class="badge badge-red">
                                        <i class="ti ti-arrow-down-left mr-1"></i> Pembelian
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item['reference'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $item['amount'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $item['amount'] >= 0 ? '+' : '' }}Rp {{ number_format(abs($item['amount']), 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                            <i class="ti ti-file-off text-xl"></i>
                                        </div>
                                        <p>Tidak ada transaksi dalam periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chart -->
            <div class="lg:col-span-1 card">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="ti ti-chart-bar text-purple-500"></i> Visualisasi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="h-64 w-full">
                        <canvas id="profitLossChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('profitLossChart').getContext('2d');

            // Data untuk chart
            const salesAmount = {
                {
                    $summary['total_sales']
                }
            };
            const purchasesAmount = {
                {
                    $summary['total_purchases']
                }
            };
            const profitAmount = {
                {
                    $summary['gross_profit']
                }
            };

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Penjualan', 'Pembelian', 'Laba Kotor'],
                    datasets: [{
                        label: 'Jumlah (Rp)',
                        data: [salesAmount, purchasesAmount, profitAmount],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.2)', // emerald
                            'rgba(239, 68, 68, 0.2)', // red
                            profitAmount >= 0 ? 'rgba(124, 58, 237, 0.2)' : 'rgba(239, 68, 68, 0.2)' // purple or red
                        ],
                        borderColor: [
                            'rgb(16, 185, 129)',
                            'rgb(239, 68, 68)',
                            profitAmount >= 0 ? 'rgb(124, 58, 237)' : 'rgb(239, 68, 68)'
                        ],
                        borderWidth: 2,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID', {
                                        notation: "compact"
                                    }).format(value);
                                },
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif"
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif"
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>