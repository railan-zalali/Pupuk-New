<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 dark:bg-pink-500/20 flex items-center justify-center text-pink-600 dark:text-pink-400">
                        <i class="ti ti-scale text-xl"></i>
                    </div>
                    {{ __('Laporan Hutang Piutang') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Monitor status hutang ke supplier dan piutang pelanggan
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.accounts', array_merge(request()->query(), ['type' => 'excel'])) }}" class="btn-success flex items-center gap-2">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
                <a href="{{ route('reports.accounts', array_merge(request()->query(), ['type' => 'pdf'])) }}" class="btn-danger flex items-center gap-2">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </a>
                <a href="{{ route('reports.accounts', array_merge(request()->query(), ['type' => 'print'])) }}" class="btn-secondary flex items-center gap-2" target="_blank">
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
                <form action="{{ route('reports.accounts') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                        <x-input-label for="type" value="Jenis Laporan" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-file-analytics text-gray-400"></i>
                            </div>
                            <select name="type" id="type" class="input-field w-full pl-10">
                                <option value="receivable" {{ request('type') === 'receivable' ? 'selected' : '' }}>Piutang (Pelanggan)</option>
                                <option value="payable" {{ request('type') === 'payable' ? 'selected' : '' }}>Hutang (Supplier)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="payment_status" value="Status Pembayaran" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-check-circle text-gray-400"></i>
                            </div>
                            <select name="payment_status" id="payment_status" class="input-field w-full pl-10">
                                <option value="all" {{ request('payment_status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="outstanding" {{ request('payment_status') === 'outstanding' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Sebagian</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-end md:col-span-4">
                        <button type="submit" class="btn-primary w-full justify-center">
                            <i class="ti ti-search mr-2"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in-up delay-200">
            <!-- Total Amount -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total {{ request('type') === 'payable' ? 'Hutang' : 'Piutang' }}</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                        <i class="ti ti-sum mr-1"></i> Akumulasi nilai
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-blue-100 dark:bg-blue-500/20 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-wallet text-xl"></i>
                </div>
            </div>

            <!-- Transaction Count -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-purple-50 to-transparent dark:from-purple-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Transaksi</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        {{ $summary['total_transactions'] }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-purple-600 dark:text-purple-400">
                        <i class="ti ti-files mr-1"></i> Invoice tercatat
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-purple-100 dark:bg-purple-500/20 rounded-lg text-purple-600 dark:text-purple-400">
                    <i class="ti ti-file-invoice text-xl"></i>
                </div>
            </div>

            <!-- Average Amount -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-emerald-50 to-transparent dark:from-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata / Transaksi</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        Rp {{ number_format($summary['average_amount'], 0, ',', '.') }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-chart-bar mr-1"></i> Nilai rata-rata
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-calculator text-xl"></i>
                </div>
            </div>

            <!-- Entity Count -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-pink-50 to-transparent dark:from-pink-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah {{ request('type') === 'payable' ? 'Supplier' : 'Customer' }}</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        {{ $summary['total_entities'] }}
                    </h4>
                    <div class="mt-2 flex items-center text-xs text-pink-600 dark:text-pink-400">
                        <i class="ti ti-users mr-1"></i> Pihak terlibat
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-pink-100 dark:bg-pink-500/20 rounded-lg text-pink-600 dark:text-pink-400">
                    <i class="ti ti-user-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300">
            <!-- Details Table -->
            <div class="lg:col-span-2 card overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="ti ti-list text-emerald-500"></i> Detail {{ request('type') === 'payable' ? 'Hutang' : 'Piutang' }}
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ref</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ request('type') === 'payable' ? 'Supplier' : 'Customer' }}</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Sisa</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($accounts as $account)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $account->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">
                                    {{ $account->invoice_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                    @if(request('type') === 'payable')
                                    {{ $account->supplier->name ?? '-' }}
                                    @else
                                    {{ $account->customer->nama ?? '-' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($account->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $account->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Rp {{ number_format($account->remaining_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($account->remaining_amount <= 0)
                                        <span class="badge badge-emerald">Lunas</span>
                                        @elseif($account->remaining_amount < $account->total_amount)
                                            <span class="badge badge-blue">Sebagian</span>
                                            @else
                                            <span class="badge badge-red">Belum Lunas</span>
                                            @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                            <i class="ti ti-files-off text-xl"></i>
                                        </div>
                                        <p>Tidak ada data ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($accounts->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50">
                    {{ $accounts->links() }}
                </div>
                @endif
            </div>

            <!-- Chart -->
            <div class="lg:col-span-1 card">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="ti ti-chart-pie text-pink-500"></i> Komposisi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="h-64 w-full">
                        <canvas id="accountsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('accountsChart').getContext('2d');
            const labels = @json($chart_data['labels']);
            const totalData = @json($chart_data['total']);
            const paidData = @json($chart_data['paid']);
            const remainingData = @json($chart_data['remaining']);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Terbayar',
                            data: paidData,
                            backgroundColor: 'rgba(16, 185, 129, 0.5)', // Emerald
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Sisa Pembayaran',
                            data: remainingData,
                            backgroundColor: 'rgba(239, 68, 68, 0.5)', // Red
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1,
                            stack: 'Stack 0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID', {
                                        notation: "compact"
                                    }).format(value);
                                }
                            },
                            stacked: true
                        },
                        x: {
                            stacked: true,
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