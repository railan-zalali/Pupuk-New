<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Laporan Penjualan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Lihat dan analisa data penjualan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('reports.sales', array_merge(request()->query(), ['type' => 'excel'])) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Excel
                </a>
                <a href="{{ route('reports.sales', array_merge(request()->query(), ['type' => 'pdf'])) }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    PDF
                </a>
                <a href="{{ route('reports.sales', array_merge(request()->query(), ['type' => 'print'])) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3
                class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter Laporan
            </h3>
            <form action="{{ route('reports.sales') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <x-input-label for="start_date" value="Tanggal Mulai" />
                    <x-text-input type="date" name="start_date" id="start_date"
                        value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="end_date" value="Tanggal Akhir" />
                    <x-text-input type="date" name="end_date" id="end_date"
                        value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="payment_method" value="Metode Pembayaran" />
                    <select name="payment_method" id="payment_method"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">Semua Metode</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai
                        </option>
                        <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>
                            Transfer</option>
                        <option value="credit" {{ request('payment_method') === 'credit' ? 'selected' : '' }}>Kredit
                        </option>
                    </select>
                </div>

                <div class="flex items-end">
                    <x-primary-button type="submit" class="w-full justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Terapkan Filter
                    </x-primary-button>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Penjualan</h3>
                <p class="mt-2 text-md font-bold text-gray-900 dark:text-gray-100">{{ $summary['total_sales'] }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Nominal</h3>
                <p class="mt-2 text-md font-bold text-gray-900 dark:text-gray-100">Rp
                    {{ number_format($summary['total_amount'], 0, ',', '.') }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Penjualan</h3>
                <p class="mt-2 text-md font-bold text-gray-900 dark:text-gray-100">Rp
                    {{ number_format($summary['average_sale'], 0, ',', '.') }}</p>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</h3>
                <div class="mt-2 space-y-1">
                    @foreach ($summary['payment_methods'] as $method => $count)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                @if ($method == 'cash')
                                    Tunai
                                @elseif($method == 'transfer')
                                    Transfer
                                @elseif($method == 'credit')
                                    Kredit
                                @else
                                    {{ ucfirst($method) }}
                                @endif
                            </span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sales Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3
                class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                Grafik Penjualan
            </h3>
            <div class="h-80">
                <canvas id="salesChart"></canvas>
            </div>
        </div>


        {{-- Payment Method Distribution --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    Distribusi Metode Pembayaran
                </h3>
                <div class="h-64">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Statistik Penjualan
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Penjualan Tertinggi</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($sales->max('total_amount') ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Penjualan Terendah</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($sales->min('total_amount') ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Produk Terjual</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                            {{ $sales->sum(function ($sale) {return $sale->saleDetails->sum('quantity');}) ?? 0 }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Produk per
                            Transaksi</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                            {{ $sales->count() > 0? number_format($sales->sum(function ($sale) {return $sale->saleDetails->sum('quantity');}) / $sales->count(),1): 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Daftar Transaksi
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Faktur</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Pelanggan</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Pembayaran</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $sale->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $sale->invoice_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $sale->customer ? $sale->customer->nama : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if ($sale->payment_method == 'cash')
                                        Tunai
                                    @elseif($sale->payment_method == 'transfer')
                                        Transfer
                                    @elseif($sale->payment_method == 'credit')
                                        Kredit
                                    @else
                                        {{ ucfirst($sale->payment_method) }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $sale->trashed() ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' }}">
                                        {{ $sale->trashed() ? 'Dibatalkan' : 'Selesai' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('sales.show', $sale) }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data penjualan dalam periode ini.
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
            // Prepare data for sales chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesData = @json($salesChartData);

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(salesData),
                    datasets: [{
                        label: 'Total Penjualan',
                        data: Object.values(salesData),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(
                                        context.raw);
                                }
                            }
                        }
                    }
                }
            });

            // Prepare data for payment method chart
            const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
            const paymentMethods = @json($summary['payment_methods']);
            const paymentLabels = [];
            const paymentData = [];
            const paymentColors = [
                'rgba(59, 130, 246, 0.7)', // Blue
                'rgba(16, 185, 129, 0.7)', // Green
                'rgba(245, 158, 11, 0.7)', // Yellow
                'rgba(239, 68, 68, 0.7)' // Red
            ];

            Object.keys(paymentMethods).forEach((method, index) => {
                let label;
                if (method === 'cash') label = 'Tunai';
                else if (method === 'transfer') label = 'Transfer';
                else if (method === 'credit') label = 'Kredit';
                else label = method;

                paymentLabels.push(label);
                paymentData.push(paymentMethods[method]);
            });

            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        data: paymentData,
                        backgroundColor: paymentColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
