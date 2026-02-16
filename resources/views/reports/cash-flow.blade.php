<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="ti ti-cash text-xl"></i>
                    </div>
                    {{ __('Laporan Arus Kas') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Analisis pergerakan kas masuk dan keluar periode {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.cash-flow', array_merge(request()->query(), ['type' => 'excel'])) }}" class="btn-success flex items-center gap-2">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
                <a href="{{ route('reports.cash-flow', array_merge(request()->query(), ['type' => 'pdf'])) }}" class="btn-danger flex items-center gap-2">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </a>
                <a href="{{ route('reports.cash-flow', array_merge(request()->query(), ['type' => 'print'])) }}" class="btn-secondary flex items-center gap-2" target="_blank">
                    <i class="ti ti-printer"></i> Cetak
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card animate-fade-in-up delay-100">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between cursor-pointer" onclick="document.getElementById('filterContent').classList.toggle('hidden')">
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-filter text-lg text-emerald-500"></i> Filter Transaksi
                </h3>
                <i class="ti ti-chevron-down text-gray-500"></i>
            </div>
            <div id="filterContent" class="p-6">
                <form action="{{ route('reports.cash-flow') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                        <x-input-label for="type" value="Jenis Transaksi" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-exchange text-gray-400"></i>
                            </div>
                            <select name="type" id="type" class="input-field w-full pl-10">
                                <option value="">Semua Transaksi</option>
                                <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
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
            <!-- Opening Balance -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-gray-50 to-transparent dark:from-gray-700/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Awal</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        Rp {{ number_format($summary['opening_balance'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <i class="ti ti-wallet mr-1"></i> Awal periode
                    </div>
                </div>
            </div>

            <!-- Total In (Debit) -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-emerald-50 to-transparent dark:from-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                    <h4 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">
                        + Rp {{ number_format($summary['total_debit'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-arrow-down-left mr-1"></i> Cash In
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-Trending-up text-xl"></i>
                </div>
            </div>

            <!-- Total Out (Credit) -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-red-50 to-transparent dark:from-red-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                    <h4 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                        - Rp {{ number_format($summary['total_credit'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-red-600 dark:text-red-400">
                        <i class="ti ti-arrow-up-right mr-1"></i> Cash Out
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-red-100 dark:bg-red-500/20 rounded-lg text-red-600 dark:text-red-400">
                    <i class="ti ti-trending-down text-xl"></i>
                </div>
            </div>

            <!-- Closing Balance -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-50 to-transparent dark:from-indigo-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Akhir</p>
                    <h4 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                        Rp {{ number_format($summary['closing_balance'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-indigo-600 dark:text-indigo-400">
                        <i class="ti ti-wallet mr-1"></i> Posisi kas kini
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-indigo-100 dark:bg-indigo-500/20 rounded-lg text-indigo-600 dark:text-indigo-400">
                    <i class="ti ti-scale text-xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300">
            <!-- Details Table -->
            <div class="lg:col-span-2 card overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="ti ti-list text-emerald-500"></i> Detail Transaksi
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ref</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Masuk</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Keluar</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">
                                    {{ $transaction->reference_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $transaction->debit > 0 ? 'Rp ' . number_format($transaction->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-red-600 dark:text-red-400">
                                    {{ $transaction->credit > 0 ? 'Rp ' . number_format($transaction->credit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($transaction->balance, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                            <i class="ti ti-file-off text-xl"></i>
                                        </div>
                                        <p>Tidak ada transaksi.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>

            <!-- Chart -->
            <div class="lg:col-span-1 card">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="ti ti-chart-line text-indigo-500"></i> Tren Arus Kas
                    </h3>
                </div>
                <div class="p-6">
                    <div class="h-64 w-full">
                        <canvas id="cashFlowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('cashFlowChart').getContext('2d');
        const labels = @json($chart_data['labels']);
        const debitData = @json($chart_data['debit']);
        const creditData = @json($chart_data['credit']);
        const balanceData = @json($chart_data['balance']);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Pemasukan',
                        data: debitData,
                        borderColor: 'rgb(16, 185, 129)', // Emerald
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: creditData,
                        borderColor: 'rgb(239, 68, 68)', // Red
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Saldo',
                        data: balanceData,
                        borderColor: 'rgb(99, 102, 241)', // Indigo
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
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
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>