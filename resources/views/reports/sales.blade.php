<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="ti ti-chart-bar text-xl"></i>
                    </div>
                    {{ __('Laporan Penjualan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Analisa detail transaksi penjualan periode {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.sales', array_merge(request()->query(), ['type' => 'excel'])) }}" class="btn-success flex items-center gap-2">
                    <i class="ti ti-file-spreadsheet"></i> Excel
                </a>
                <a href="{{ route('reports.sales', array_merge(request()->query(), ['type' => 'pdf'])) }}" class="btn-danger flex items-center gap-2">
                    <i class="ti ti-file-type-pdf"></i> PDF
                </a>
                <a href="{{ route('reports.sales', array_merge(request()->query(), ['type' => 'print'])) }}" class="btn-secondary flex items-center gap-2" target="_blank">
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
                <form action="{{ route('reports.sales') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                        <x-input-label for="payment_method" value="Metode Pembayaran" class="mb-1.5" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-credit-card text-gray-400"></i>
                            </div>
                            <select name="payment_method" id="payment_method" class="input-field w-full pl-10">
                                <option value="">Semua Metode</option>
                                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="credit" {{ request('payment_method') === 'credit' ? 'selected' : '' }}>Kredit</option>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up delay-200">
            <!-- Total Sales -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-emerald-50 to-transparent dark:from-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transaksi</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $summary['total_sales'] }}</h4>
                    <div class="mt-2 flex items-center text-xs text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-trending-up mr-1"></i> Transaksi berhasil
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-shopping-cart text-xl"></i>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent dark:from-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Omset</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</h4>
                    <div class="mt-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                        <i class="ti ti-coins mr-1"></i> Pendapatan kotor
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-blue-100 dark:bg-blue-500/20 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-cash text-xl"></i>
                </div>
            </div>

            <!-- Average Sales -->
            <div class="card p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-purple-50 to-transparent dark:from-purple-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata / Transaksi</p>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($summary['average_sale'], 0, ',', '.') }}</h4>
                    <div class="mt-2 flex items-center text-xs text-purple-600 dark:text-purple-400">
                        <i class="ti ti-chart-bar mr-1"></i> Nilai keranjang
                    </div>
                </div>
                <div class="absolute top-6 right-6 p-2 bg-purple-100 dark:bg-purple-500/20 rounded-lg text-purple-600 dark:text-purple-400">
                    <i class="ti ti-calculator text-xl"></i>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card p-6 relative overflow-hidden group">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Metode Pembayaran</p>
                <div class="space-y-2 mt-1">
                    @foreach ($summary['payment_methods'] as $method => $count)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-300 flex items-center gap-2">
                            @if ($method == 'cash')
                            <i class="ti ti-cash text-emerald-500"></i> Tunai
                            @elseif($method == 'transfer')
                            <i class="ti ti-building-bank text-blue-500"></i> Transfer
                            @elseif($method == 'credit')
                            <i class="ti ti-credit-card text-amber-500"></i> Kredit
                            @else
                            <i class="ti ti-circle-filled text-gray-400"></i> {{ ucfirst($method) }}
                            @endif
                        </span>
                        <span class="font-bold text-gray-900 dark:text-gray-100">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-300">
            <!-- Sales Chart -->
            <div class="lg:col-span-2 card">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="ti ti-chart-line text-blue-500"></i> Tren Penjualan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="h-80 w-full">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stats & Payment Chart -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Payment Chart -->
                <div class="card">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <i class="ti ti-chart-pie text-emerald-500"></i> Distribusi Pembayaran
                        </h3>
                    </div>
                    <div class="p-6 flex justify-center">
                        <div class="h-48 w-full">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Key Stats -->
                <div class="card p-6">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">Statistik Kunci</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Penjualan Tertinggi</span>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($sales->max('total_amount') ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Penjualan Terendah</span>
                            <span class="text-sm font-bold text-red-600 dark:text-red-400">
                                Rp {{ number_format($sales->min('total_amount') ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Item Terjual</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                {{ $sales->sum(function ($sale) {return $sale->saleDetails->sum('quantity');}) ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="card overflow-hidden animate-fade-in-up delay-300">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-list text-emerald-500"></i> Riwayat Transaksi
                </h3>

                <div class="relative">
                    <input type="text" id="invoiceSearch" placeholder="Cari nomor faktur..."
                        class="pl-10 pr-4 py-2 rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:text-gray-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Faktur</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="salesTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" data-invoice="{{ $sale->invoice_number }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $sale->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100 font-mono">
                                {{ $sale->invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-xs text-emerald-600 dark:text-emerald-400 font-bold">
                                        {{ $sale->customer ? substr($sale->customer->nama, 0, 1) : 'U' }}
                                    </div>
                                    {{ $sale->customer ? $sale->customer->nama : 'Umum' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if ($sale->payment_method == 'cash')
                                <span class="inline-flex items-center gap-1"><i class="ti ti-cash"></i> Tunai</span>
                                @elseif($sale->payment_method == 'transfer')
                                <span class="inline-flex items-center gap-1"><i class="ti ti-building-bank"></i> Transfer</span>
                                @elseif($sale->payment_method == 'credit')
                                <span class="inline-flex items-center gap-1 text-amber-600"><i class="ti ti-credit-card"></i> Kredit</span>
                                @else
                                {{ ucfirst($sale->payment_method) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($sale->trashed())
                                <span class="badge badge-red">Dibatalkan</span>
                                @else
                                <span class="badge badge-emerald">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('sales.show', $sale) }}" class="btn-action btn-secondary inline-flex" title="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                        <i class="ti ti-file-off text-xl"></i>
                                    </div>
                                    <p>Tidak ada data penjualan dalam periode ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Invoice search functionality
            const invoiceSearch = document.getElementById('invoiceSearch');
            const salesTableBody = document.getElementById('salesTableBody');
            const allRows = salesTableBody.querySelectorAll('tr');

            if (invoiceSearch) {
                invoiceSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    let hasVisible = false;

                    // Remove existing empty message if any
                    const existingEmpty = salesTableBody.querySelector('.search-empty-state');
                    if (existingEmpty) existingEmpty.remove();

                    allRows.forEach(row => {
                        if (row.classList.contains('empty-state')) return; // Skip original empty state

                        const invoiceNumber = row.getAttribute('data-invoice');
                        if (invoiceNumber && invoiceNumber.toLowerCase().includes(searchTerm)) {
                            row.style.display = '';
                            hasVisible = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (!hasVisible && searchTerm !== '') {
                        const emptyStateRow = document.createElement('tr');
                        emptyStateRow.className = 'search-empty-state';
                        emptyStateRow.innerHTML = `
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="ti ti-search-off text-2xl mb-2"></i>
                                        <p>Tidak ada transaksi yang ditemukan dengan faktur "${searchTerm}".</p>
                                    </div>
                                </td>
                            `;
                        salesTableBody.appendChild(emptyStateRow);
                    }
                });
            }

            // Prepare data for sales chart
            try {
                const salesCtx = document.getElementById('salesChart');
                if (salesCtx) {
                    const salesData = @json($salesChartData ?? []);

                    if (Object.keys(salesData).length === 0) {
                        salesCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm"><p>Tidak ada data grafik</p></div>';
                    } else {
                        new Chart(salesCtx, {
                            type: 'line',
                            data: {
                                labels: Object.keys(salesData),
                                datasets: [{
                                    label: 'Total Penjualan',
                                    data: Object.values(salesData),
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)', // emerald-500 with opacity
                                    borderColor: '#10b981', // emerald-500
                                    borderWidth: 2,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: '#10b981',
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    tension: 0.3,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
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
                                                return 'Rp ' + new Intl.NumberFormat('id-ID', {
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
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                        titleColor: '#1f2937',
                                        bodyColor: '#4b5563',
                                        borderColor: '#e5e7eb',
                                        borderWidth: 1,
                                        padding: 10,
                                        boxPadding: 4,
                                        titleFont: {
                                            family: "'Plus Jakarta Sans', sans-serif",
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        bodyFont: {
                                            family: "'Plus Jakarta Sans', sans-serif",
                                        },
                                        callbacks: {
                                            label: function(context) {
                                                return 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error creating sales chart:', error);
            }

            // Prepare data for payment method chart
            try {
                const paymentCtx = document.getElementById('paymentMethodChart');
                if (paymentCtx) {
                    const paymentMethods = @json($summary['payment_methods'] ?? []);
                    const paymentLabels = [];
                    const paymentData = [];

                    // Custom colors for methods
                    const methodColors = {
                        'cash': '#10b981', // emerald-500
                        'transfer': '#3b82f6', // blue-500
                        'credit': '#f59e0b', // amber-500
                        'other': '#6b7280' // gray-500
                    };

                    const paymentColors = [];

                    if (Object.keys(paymentMethods).length === 0) {
                        paymentCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm"><p>Tidak ada data metode</p></div>';
                    } else {
                        Object.keys(paymentMethods).forEach((method) => {
                            let label;
                            if (method === 'cash') label = 'Tunai';
                            else if (method === 'transfer') label = 'Transfer';
                            else if (method === 'credit') label = 'Kredit';
                            else label = method;

                            paymentLabels.push(label);
                            paymentData.push(paymentMethods[method]);
                            paymentColors.push(methodColors[method] || methodColors['other']);
                        });

                        new Chart(paymentCtx, {
                            type: 'doughnut',
                            data: {
                                labels: paymentLabels,
                                datasets: [{
                                    data: paymentData,
                                    backgroundColor: paymentColors,
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 15,
                                            font: {
                                                family: "'Plus Jakarta Sans', sans-serif",
                                                size: 11
                                            }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                        titleColor: '#1f2937',
                                        bodyColor: '#4b5563',
                                        borderColor: '#e5e7eb',
                                        borderWidth: 1
                                    }
                                },
                                cutout: '65%'
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error creating payment method chart:', error);
            }
        });
    </script>
    @endpush
</x-app-layout>