<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __('Analisis Pelanggan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Analisis perilaku dan pola belanja pelanggan
                </p>
            </div>
            <x-report-export-buttons route="reports.customer-analysis" :parameters="request()->all()" />
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
            <form action="{{ route('reports.customer-analysis') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <x-input-label for="start_date" value="Tanggal Mulai" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                        :value="request('start_date', $startDate->format('Y-m-d'))" required />
                </div>

                <div>
                    <x-input-label for="end_date" value="Tanggal Akhir" />
                    <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                        :value="request('end_date', $endDate->format('Y-m-d'))" required />
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
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pelanggan</h3>
                <p class="mt-2 text-md font-bold text-gray-900 dark:text-gray-100">
                    {{ number_format($summary['total_customers']) }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transaksi</h3>
                <p class="mt-2 text-md font-bold text-gray-900 dark:text-gray-100">
                    {{ number_format($summary['total_transactions']) }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</h3>
                <p class="mt-2 text-md font-bold text-gray-900 dark:text-gray-100">Rp
                    {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Transaksi</h3>
                <p class="mt-2 text-md font-bold text-gray-900 dark:text-gray-100">Rp
                    {{ number_format($summary['average_transaction'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pelanggan Baru vs Returning
                </h3>
                <div class="h-80">
                    <canvas id="newVsReturningChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Frekuensi Pembelian
                </h3>
                <div class="h-80">
                    <canvas id="purchaseFrequencyChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3
                class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                Penjualan Berdasarkan Tipe Pelanggan
            </h3>
            <div class="h-80">
                <canvas id="salesByCustomerTypeChart"></canvas>
            </div>
        </div>

        {{-- Top Customers Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pelanggan Terbaik
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nama</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kontak</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jumlah Transaksi</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Belanja</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Rata-rata Belanja</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topCustomers as $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $customer->customer->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $customer->customer->phone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($customer->transaction_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($customer->average_transaction, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data pelanggan dalam periode ini.
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
                // New vs Returning Customers Chart
                const newVsReturningCtx = document.getElementById('newVsReturningChart').getContext('2d');
                new Chart(newVsReturningCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pelanggan Baru', 'Pelanggan Kembali'],
                        datasets: [{
                            data: [{{ $newVsReturning['new'] }}, {{ $newVsReturning['returning'] }}],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.7)',
                                'rgba(59, 130, 246, 0.7)'
                            ],
                            borderColor: [
                                'rgb(16, 185, 129)',
                                'rgb(59, 130, 246)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw;
                                        const total = context.dataset.data.reduce((acc, val) => acc + val,
                                            0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Purchase Frequency Chart
                const purchaseFrequencyCtx = document.getElementById('purchaseFrequencyChart').getContext('2d');
                const frequencyLabels = Object.keys({!! json_encode($purchaseFrequency) !!}).map(key =>
                    key == 1 ? '1 kali' : key + ' kali'
                );

                new Chart(purchaseFrequencyCtx, {
                    type: 'bar',
                    data: {
                        labels: frequencyLabels,
                        datasets: [{
                            label: 'Jumlah Pelanggan',
                            data: Object.values({!! json_encode($purchaseFrequency) !!}),
                            backgroundColor: 'rgba(124, 58, 237, 0.5)',
                            borderColor: 'rgb(124, 58, 237)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Sales by Customer Type Chart
                const salesByCustomerTypeCtx = document.getElementById('salesByCustomerTypeChart').getContext('2d');
                new Chart(salesByCustomerTypeCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Dengan Pelanggan Terdaftar', 'Tanpa Pelanggan Terdaftar'],
                        datasets: [{
                            data: [
                                {{ $salesByCustomerType['with_customer'] }},
                                {{ $salesByCustomerType['without_customer'] }}
                            ],
                            backgroundColor: [
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(107, 114, 128, 0.7)'
                            ],
                            borderColor: [
                                'rgb(245, 158, 11)',
                                'rgb(107, 114, 128)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw;
                                        const total = context.dataset.data.reduce((acc, val) => acc + val,
                                            0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percentage}%)`;
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
