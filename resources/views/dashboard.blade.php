{{-- <x-app-layout>
    <!-- Sticky Header with Quick Actions - Modern, clean design -->
    <div
        class="sticky top-0 z-10 bg-white dark:bg-gray-900 shadow-sm border-b border-gray-100 dark:border-gray-800 py-4 px-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="ti ti-dashboard text-indigo-500 mr-2"></i>
                {{ __('Dashboard') }}
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('sales.create') }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-sm transition-all duration-200">
                    <i class="ti ti-shopping-cart-plus"></i>
                    Transaksi Baru
                </a>
                <a href="{{ route('products.create') }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 shadow-sm transition-all duration-200">
                    <i class="ti ti-plus"></i>
                    Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-6 px-4 sm:px-6 lg:px-8">
        <!-- Date & Dashboard Settings Bar - Enhanced with better alignment and icons -->
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-lg">
                <i class="ti ti-calendar-event mr-2 text-indigo-500"></i>
                {{ now()->format('l, d F Y') }}
            </div>
            <div class="dashboard-controls flex items-center gap-3">
                <button id="refresh-dashboard"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all">
                    <i class="ti ti-refresh text-indigo-500"></i>
                    Refresh
                </button>
                <div class="relative">
                    <button id="notification-btn"
                        class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all relative">
                        <i class="ti ti-bell text-indigo-500"></i>
                        Notifikasi
                        @if ($lowStockProducts > 0 || $totalUpcomingCredits > 0)
                            <span
                                class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center text-xs text-white font-medium shadow-md">
                                {{ $lowStockProducts + $totalUpcomingCredits }}
                            </span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards Row -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Daily Sales Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-blue-100 opacity-90">Penjualan Harian</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                Rp {{ number_format($totalSalesToday, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4">
                            <div class="relative h-1 w-full bg-blue-400/30 rounded-full overflow-hidden">
                                <div class="absolute h-full bg-white rounded-full"
                                    style="width: {{ min(max($salesChangeToday, 0), 100) }}%"></div>
                            </div>
                            <div class="mt-2 flex items-center text-xs text-blue-100">
                                @if ($salesChangeToday >= 0)
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format($salesChangeToday, 2) }}% dari kemarin</span>
                                @else
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 13a1 1 0 110 2H7a1 1 0 01-1-1v-5a1 1 0 112 0v2.586l4.293-4.293a1 1 0 011.414 0L16 9.586l4.293-4.293a1 1 0 111.414 1.414L16.414 12l4.293 4.293a1 1 0 01-1.414 1.414L15 13.414l-2.293 2.293a1 1 0 01-1.414 0L8 12.414 5.414 15H8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format(abs($salesChangeToday), 2) }}% dari kemarin</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="#"
                        class="text-sm font-medium text-blue-100 hover:text-white flex items-center justify-between">
                        <span>Lihat detail</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Monthly Sales Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-green-100 opacity-90">Penjualan Bulanan</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                Rp {{ number_format($totalSalesThisMonth, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4">
                            <div class="relative h-1 w-full bg-green-400/30 rounded-full overflow-hidden">
                                <div class="absolute h-full bg-white rounded-full"
                                    style="width: {{ min(max($salesChangeThisMonth, 0), 100) }}%"></div>
                            </div>
                            <div class="mt-2 flex items-center text-xs text-green-100">
                                @if ($salesChangeThisMonth >= 0)
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format($salesChangeThisMonth, 2) }}% dari bulan lalu</span>
                                @else
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 13a1 1 0 110 2H7a1 1 0 01-1-1v-5a1 1 0 112 0v2.586l4.293-4.293a1 1 0 011.414 0L16 9.586l4.293-4.293a1 1 0 111.414 1.414L16.414 12l4.293 4.293a1 1 0 01-1.414 1.414L15 13.414l-2.293 2.293a1 1 0 01-1.414 0L8 12.414 5.414 15H8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format(abs($salesChangeThisMonth), 2) }}% dari bulan lalu</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="#"
                        class="text-sm font-medium text-green-100 hover:text-white flex items-center justify-between">
                        <span>Lihat detail</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Products Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-indigo-100 opacity-90">Total Produk</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                {{ number_format($totalProducts) }}
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            @if ($lowStockProducts > 0)
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $lowStockProducts }} stok menipis</span>
                                </div>
                            @else
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Stok tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="{{ route('products.index') }}"
                        class="text-sm font-medium text-indigo-100 hover:text-white flex items-center justify-between">
                        <span>Lihat semua produk</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Credit Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-orange-100 opacity-90">Kredit Bulanan</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                Rp {{ number_format($totalCreditAmount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            @if ($totalUpcomingCredits > 0)
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $totalUpcomingCredits }} akan jatuh tempo</span>
                                </div>
                            @else
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Semua kredit terkendali</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="{{ route('sales.credit') }}"
                        class="text-sm font-medium text-orange-100 hover:text-white flex items-center justify-between">
                        <span>Lihat semua kredit</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stock Movement Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- Daily Outgoing Stock Card -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl relative">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <span
                        class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-red-100 dark:bg-red-900/30">
                        <svg class="h-4 w-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 12H4M12 20V4" />
                        </svg>
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Keluar Harian</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($dailyOutgoingStock, 0, ',', '.') }} unit
                        </p>
                    </div>
                    <div class="mt-6">
                        <div class="h-10 w-full bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <div class="h-full bg-red-100 dark:bg-red-900/30 relative" style="width: 70%">
                                <div class="absolute inset-0 flex items-center px-3">
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 h-1 rounded-full">
                                        <div class="bg-red-500 dark:bg-red-400 h-1 rounded-full" style="width: 70%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('stock.details') }}"
                        class="flex items-center justify-between text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <span>Lihat selengkapnya</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Daily Incoming Stock Card -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl relative">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <span
                        class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-green-100 dark:bg-green-900/30">
                        <svg class="h-4 w-4 text-green-500 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Masuk Harian</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($dailyIncomingStock, 0, ',', '.') }} unit
                        </p>
                    </div>
                    <div class="mt-6">
                        <div class="h-10 w-full bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <div class="h-full bg-green-100 dark:bg-green-900/30 relative" style="width: 65%">
                                <div class="absolute inset-0 flex items-center px-3">
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 h-1 rounded-full">
                                        <div class="bg-green-500 dark:bg-green-400 h-1 rounded-full"
                                            style="width: 65%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('stock.details') }}"
                        class="flex items-center justify-between text-sm font-medium text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                        <span>Lihat selengkapnya</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Sales Chart -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            Penjualan Mingguan
                        </h3>
                        <div class="chart-controls flex items-center space-x-1">
                            <button
                                class="px-3 py-1 text-xs font-medium rounded-md bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">Mingguan</button>
                            <button
                                class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Bulanan</button>
                            <button
                                class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Tahunan</button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="h-80">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products Chart -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Produk Terlaris
                        </h3>
                        <div class="chart-duration">
                            <select
                                class="text-xs rounded-md border-gray-300 dark:border-gray-600 py-1 pl-2 pr-7 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option>Minggu Ini</option>
                                <option>Bulan Ini</option>
                                <option>3 Bulan Terakhir</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="h-80">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Trend Chart -->
        <div
            class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Tren Penjualan
                    </h3>
                    <div class="chart-range flex items-center space-x-2">
                        <button
                            class="px-3 py-1 text-xs font-medium rounded-md bg-purple-50 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300">6
                            Bulan</button>
                        <button
                            class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">12
                            Bulan</button>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="h-80">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Low Stock Table -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-red-500 dark:text-red-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Stok Produk Rendah
                        </h3>
                        @if ($lowStockProducts > 0)
                            <a href="{{ route('products.index') }}"
                                class="text-sm text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 flex items-center">
                                <span>Lihat semua</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif
                    </div>
                    <div class="mt-2 overflow-x-auto">
                        <div class="min-w-full rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Produk
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Stok Saat Ini
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Stok Minimum
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    @forelse($lowStockAlerts as $product)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="whitespace-nowrap px-6 py-3">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-8 w-8 flex-shrink-0 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                                        @if ($product->image_path)
                                                            <img src="{{ Storage::url($product->image_path) }}"
                                                                alt="{{ $product->name }}"
                                                                class="h-full w-full object-cover rounded-md">
                                                        @else
                                                            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $product->name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $product->code }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                <span
                                                    class="font-bold text-red-600 dark:text-red-400">{{ $product->stock }}</span>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product->min_stock }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                    <svg class="mr-1 h-2 w-2 text-red-400 dark:text-red-500"
                                                        fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Stok Rendah
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center">
                                                <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Semua produk
                                                    memiliki stok yang cukup</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-green-500 dark:text-green-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Transaksi Terbaru
                        </h3>
                        <a href="{{ route('sales.index') }}"
                            class="text-sm text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 flex items-center">
                            <span>Lihat semua</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-2 overflow-x-auto">
                        <div class="min-w-full rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Faktur
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Tanggal
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Jumlah
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    @forelse($recentTransactions as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="whitespace-nowrap px-6 py-3">
                                                <a href="{{ route('sales.show', $transaction) }}"
                                                    class="text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 font-medium flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    {{ $transaction->invoice_number }}
                                                </a>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                @if ($transaction->trashed())
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                        <svg class="mr-1 h-2 w-2 text-red-400 dark:text-red-500"
                                                            fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Batal
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400">
                                                        <svg class="mr-1 h-2 w-2 text-green-400 dark:text-green-500"
                                                            fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Selesai
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center">
                                                <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada
                                                    transaksi terbaru</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expiring Products Section -->
        <div
            class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Produk yang Akan Expired
                    </h3>
                    @if (count($expiringProducts) > 0)
                        <a href="#"
                            class="text-sm text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 flex items-center">
                            <span>Lihat semua</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="mt-2 overflow-x-auto">
                    <div class="min-w-full rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Kode
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Nama Produk
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Stok
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Tanggal Expired
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Sisa Hari
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Tindakan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($expiringProducts as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->code }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-8 w-8 flex-shrink-0 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                                    @if ($product->image_path)
                                                        <img src="{{ Storage::url($product->image_path) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-full w-full object-cover rounded-md">
                                                    @else
                                                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $product->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->stock }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->productUnits->first()->expire_date->format('d/m/Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @php
                                                $daysLeft = now()->diffInDays(
                                                    $product->productUnits->first()->expire_date,
                                                    false,
                                                );
                                            @endphp
                                            @if ($daysLeft < 0)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-3 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                    Expired
                                                </span>
                                            @elseif($daysLeft <= 7)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-3 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                    {{ $daysLeft }} hari
                                                </span>
                                            @elseif($daysLeft <= 30)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900 px-3 py-0.5 text-xs font-medium text-yellow-800 dark:text-yellow-400">
                                                    {{ $daysLeft }} hari
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-0.5 text-xs font-medium text-green-800 dark:text-green-400">
                                                    {{ $daysLeft }} hari
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center">
                                            <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 mb-2"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada produk
                                                yang akan segera expired</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Notification Panel -->
    <div id="notification-panel"
        class="fixed inset-0 z-30 transform translate-x-full transition-transform duration-300">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity opacity-0"
            id="notification-backdrop"></div>
        <div
            class="absolute inset-y-0 right-0 max-w-sm w-full bg-white dark:bg-gray-800 shadow-xl transform translate-x-0 transition-transform">
            <div class="h-full flex flex-col">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Notifikasi</h2>
                    <button id="close-notification"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-4">
                    @if ($lowStockProducts > 0 || $totalUpcomingCredits > 0)
                        <div class="space-y-4">
                            @if ($lowStockProducts > 0)
                                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400 dark:text-red-300" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Stok Produk
                                                Rendah</h3>
                                            <div class="mt-2 text-sm text-red-700 dark:text-red-200">
                                                <p>Terdapat {{ $lowStockProducts }} produk dengan stok rendah. Segera
                                                    lakukan pembelian untuk menghindari kehabisan stok.</p>
                                            </div>
                                            <div class="mt-4">
                                                <div class="-mx-2 -my-1.5 flex">
                                                    <a href="{{ route('products.index') }}"
                                                        class="px-2 py-1.5 rounded-md text-sm font-medium text-red-800 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                                                        Lihat Produk
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($totalUpcomingCredits > 0)
                                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-orange-400 dark:text-orange-300" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-orange-800 dark:text-orange-300">Kredit
                                                Akan Jatuh Tempo</h3>
                                            <div class="mt-2 text-sm text-orange-700 dark:text-orange-200">
                                                <p>Terdapat {{ $totalUpcomingCredits }} transaksi kredit yang akan
                                                    segera jatuh tempo. Segera lakukan penagihan.</p>
                                            </div>
                                            <div class="mt-4">
                                                <div class="-mx-2 -my-1.5 flex">
                                                    <a href="{{ route('sales.credit') }}"
                                                        class="px-2 py-1.5 rounded-md text-sm font-medium text-orange-800 dark:text-orange-300 hover:bg-orange-100 dark:hover:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 dark:focus:ring-offset-gray-800">
                                                        Lihat Kredit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (count($expiringProducts) > 0)
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Produk
                                                Akan Expired</h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-200">
                                                <p>Terdapat {{ count($expiringProducts) }} produk yang akan segera
                                                    expired. Pastikan untuk memprioritaskan penjualan produk tersebut.
                                                </p>
                                            </div>
                                            <div class="mt-4">
                                                <div class="-mx-2 -my-1.5 flex">
                                                    <a href="#expiring-products"
                                                        class="px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 dark:text-yellow-300 hover:bg-yellow-100 dark:hover:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800">
                                                        Lihat Produk
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada notifikasi
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Semua sistem berjalan dengan baik.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Format Rupiah
            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            // Daily Sales Chart with enhanced styling
            const dailySalesChart = new Chart(
                document.getElementById('dailySalesChart'), {
                    type: 'line',
                    data: {
                        labels: @json($dailySales->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m'))),
                        datasets: [{
                            label: 'Penjualan Harian',
                            data: @json($dailySales->pluck('total')),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                titleFont: {
                                    size: 13
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return formatRupiah(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(226, 232, 240, 0.6)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return formatRupiah(value);
                                    },
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                }
            );

            // Top Products Chart with enhanced styling
            const topProductsChart = new Chart(
                document.getElementById('topProductsChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($topProducts->pluck('name')),
                        datasets: [{
                            label: 'Unit Terjual',
                            data: @json($topProducts->pluck('total_sold')),
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(139, 92, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)',
                                'rgb(139, 92, 246)'
                            ],
                            borderWidth: 1,
                            borderRadius: 6,
                            maxBarThickness: 32
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                titleFont: {
                                    size: 13
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 12,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    padding: 8,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(226, 232, 240, 0.6)'
                                },
                                ticks: {
                                    stepSize: 1,
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                }
            );

            // Sales Trend Chart with enhanced styling
            const salesTrendChart = new Chart(
                document.getElementById('salesTrendChart'), {
                    type: 'line',
                    data: {
                        labels: @json($salesTrend->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M y'))),
                        datasets: [{
                            label: 'Tren Penjualan',
                            data: @json($salesTrend->pluck('total')),
                            borderColor: 'rgb(124, 58, 237)',
                            backgroundColor: 'rgba(124, 58, 237, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(124, 58, 237)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                titleFont: {
                                    size: 13
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return formatRupiah(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(226, 232, 240, 0.6)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return formatRupiah(value);
                                    },
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                }
            );

            // Handle Notification Panel
            document.addEventListener('DOMContentLoaded', function() {
                const notificationBtn = document.getElementById('notification-btn');
                const notificationPanel = document.getElementById('notification-panel');
                const notificationBackdrop = document.getElementById('notification-backdrop');
                const closeNotificationBtn = document.getElementById('close-notification');

                if (notificationBtn && notificationPanel && closeNotificationBtn && notificationBackdrop) {
                    // Open panel
                    notificationBtn.addEventListener('click', function() {
                        notificationPanel.classList.remove('translate-x-full');
                        notificationBackdrop.classList.remove('opacity-0');
                        document.body.classList.add('overflow-hidden');
                    });

                    // Close panel handlers
                    const closePanel = function() {
                        notificationPanel.classList.add('translate-x-full');
                        notificationBackdrop.classList.add('opacity-0');
                        document.body.classList.remove('overflow-hidden');
                    };

                    closeNotificationBtn.addEventListener('click', closePanel);
                    notificationBackdrop.addEventListener('click', closePanel);
                }

                // Handle refresh dashboard button
                const refreshBtn = document.getElementById('refresh-dashboard');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', function() {
                        // Add spinning animation
                        const icon = this.querySelector('svg');
                        icon.classList.add('animate-spin');

                        // Reload after a brief delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    });
                }
            });
        </script>
    @endpush
</x-app-layout> --}}

<x-app-layout>
    <!-- Sticky Header with Quick Actions -->
    <div
        class="sticky top-0 z-10 bg-white dark:bg-gray-900 shadow-sm border-b border-gray-100 dark:border-gray-800 py-4 px-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="ti ti-dashboard text-indigo-500 mr-2"></i>
                {{ __('Dashboard') }}
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('sales.create') }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-sm transition-all duration-200">
                    <i class="ti ti-shopping-cart-plus"></i>
                    Transaksi Baru
                </a>
                <a href="{{ route('products.create') }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 shadow-sm transition-all duration-200">
                    <i class="ti ti-plus"></i>
                    Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-6 px-4 sm:px-6 lg:px-8">
        <!-- Date & Dashboard Settings Bar -->
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-lg">
                <i class="ti ti-calendar-event mr-2 text-indigo-500"></i>
                {{ now()->format('l, d F Y') }}
            </div>
            <div class="dashboard-controls flex items-center gap-3">
                <button id="refresh-dashboard"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all">
                    <i class="ti ti-refresh text-indigo-500"></i>
                    Refresh
                </button>
                <div class="relative">
                    <button id="notification-btn"
                        class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all relative">
                        <i class="ti ti-bell text-indigo-500"></i>
                        Notifikasi
                        @if ($lowStockProducts > 0 || $totalUpcomingCredits > 0)
                            <span
                                class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center text-xs text-white font-medium shadow-md">
                                {{ $lowStockProducts + $totalUpcomingCredits }}
                            </span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards Row -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Daily Sales Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-blue-100 opacity-90">Penjualan Harian</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                Rp {{ number_format($totalSalesToday, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4">
                            <div class="relative h-1 w-full bg-blue-400/30 rounded-full overflow-hidden">
                                <div class="absolute h-full bg-white rounded-full"
                                    style="width: {{ min(max($salesChangeToday, 0), 100) }}%"></div>
                            </div>
                            <div class="mt-2 flex items-center text-xs text-blue-100">
                                @if ($salesChangeToday >= 0)
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format($salesChangeToday, 2) }}% dari kemarin</span>
                                @else
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 13a1 1 0 110 2H7a1 1 0 01-1-1v-5a1 1 0 112 0v2.586l4.293-4.293a1 1 0 011.414 0L16 9.586l4.293-4.293a1 1 0 111.414 1.414L16.414 12l4.293 4.293a1 1 0 01-1.414 1.414L15 13.414l-2.293 2.293a1 1 0 01-1.414 0L8 12.414 5.414 15H8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format(abs($salesChangeToday), 2) }}% dari kemarin</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="#"
                        class="text-sm font-medium text-blue-100 hover:text-white flex items-center justify-between">
                        <span>Lihat detail</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Monthly Sales Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-green-100 opacity-90">Penjualan Bulanan</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                Rp {{ number_format($totalSalesThisMonth, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4">
                            <div class="relative h-1 w-full bg-green-400/30 rounded-full overflow-hidden">
                                <div class="absolute h-full bg-white rounded-full"
                                    style="width: {{ min(max($salesChangeThisMonth, 0), 100) }}%"></div>
                            </div>
                            <div class="mt-2 flex items-center text-xs text-green-100">
                                @if ($salesChangeThisMonth >= 0)
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format($salesChangeThisMonth, 2) }}% dari bulan lalu</span>
                                @else
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 13a1 1 0 110 2H7a1 1 0 01-1-1v-5a1 1 0 112 0v2.586l4.293-4.293a1 1 0 011.414 0L16 9.586l4.293-4.293a1 1 0 111.414 1.414L16.414 12l4.293 4.293a1 1 0 01-1.414 1.414L15 13.414l-2.293 2.293a1 1 0 01-1.414 0L8 12.414 5.414 15H8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ number_format(abs($salesChangeThisMonth), 2) }}% dari bulan lalu</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="#"
                        class="text-sm font-medium text-green-100 hover:text-white flex items-center justify-between">
                        <span>Lihat detail</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Products Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-indigo-100 opacity-90">Total Produk</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                {{ number_format($totalProducts) }}
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            @if ($lowStockProducts > 0)
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $lowStockProducts }} stok menipis</span>
                                </div>
                            @else
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Stok tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="{{ route('products.index') }}"
                        class="text-sm font-medium text-indigo-100 hover:text-white flex items-center justify-between">
                        <span>Lihat semua produk</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Credit Card -->
            <div
                class="overflow-hidden rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg transition-all duration-200 hover:shadow-xl">
                <div class="px-6 py-5 relative">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-32 w-32 -mr-6 -mt-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-sm font-medium text-orange-100 opacity-90">Kredit Bulanan</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold">
                                Rp {{ number_format($totalCreditAmount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            @if ($totalUpcomingCredits > 0)
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $totalUpcomingCredits }} akan jatuh tempo</span>
                                </div>
                            @else
                                <div
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Semua kredit terkendali</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gradient-to-r from-black/5 to-black/10">
                    <a href="{{ route('sales.credit') }}"
                        class="text-sm font-medium text-orange-100 hover:text-white flex items-center justify-between">
                        <span>Lihat semua kredit</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stock Movement Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- Daily Outgoing Stock Card -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl relative">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <span
                        class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-red-100 dark:bg-red-900/30">
                        <svg class="h-4 w-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 12H4M12 20V4" />
                        </svg>
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Keluar Harian</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($dailyOutgoingStock, 0, ',', '.') }} unit
                        </p>
                    </div>
                    <div class="mt-6">
                        <div class="h-10 w-full bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <div class="h-full bg-red-100 dark:bg-red-900/30 relative" style="width: 70%">
                                <div class="absolute inset-0 flex items-center px-3">
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 h-1 rounded-full">
                                        <div class="bg-red-500 dark:bg-red-400 h-1 rounded-full" style="width: 70%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('stock.details') }}"
                        class="flex items-center justify-between text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <span>Lihat selengkapnya</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Daily Incoming Stock Card -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl relative">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <span
                        class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-green-100 dark:bg-green-900/30">
                        <svg class="h-4 w-4 text-green-500 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Masuk Harian</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($dailyIncomingStock, 0, ',', '.') }} unit
                        </p>
                    </div>
                    <div class="mt-6">
                        <div class="h-10 w-full bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <div class="h-full bg-green-100 dark:bg-green-900/30 relative" style="width: 65%">
                                <div class="absolute inset-0 flex items-center px-3">
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 h-1 rounded-full">
                                        <div class="bg-green-500 dark:bg-green-400 h-1 rounded-full"
                                            style="width: 65%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('stock.details') }}"
                        class="flex items-center justify-between text-sm font-medium text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                        <span>Lihat selengkapnya</span>
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Sales Chart -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            Penjualan Mingguan
                        </h3>
                        <div class="chart-controls flex items-center space-x-1">
                            <button
                                class="px-3 py-1 text-xs font-medium rounded-md bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">Mingguan</button>
                            <button
                                class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Bulanan</button>
                            <button
                                class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Tahunan</button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="h-80">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products Chart -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Produk Terlaris
                        </h3>
                        <div class="chart-duration">
                            <select
                                class="text-xs rounded-md border-gray-300 dark:border-gray-600 py-1 pl-2 pr-7 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option>Minggu Ini</option>
                                <option>Bulan Ini</option>
                                <option>3 Bulan Terakhir</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="h-80">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Trend Chart -->
        <div
            class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Tren Penjualan
                    </h3>
                    <div class="chart-range flex items-center space-x-2">
                        <button
                            class="px-3 py-1 text-xs font-medium rounded-md bg-purple-50 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300">6
                            Bulan</button>
                        <button
                            class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">12
                            Bulan</button>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="h-80">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Low Stock Table -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-red-500 dark:text-red-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Stok Produk Rendah
                        </h3>
                        @if ($lowStockProducts > 0)
                            <a href="{{ route('products.index') }}"
                                class="text-sm text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 flex items-center">
                                <span>Lihat semua</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif
                    </div>
                    <div class="mt-2 overflow-x-auto">
                        <div class="min-w-full rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Produk
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Stok Saat Ini
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Stok Minimum
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    @forelse($lowStockAlerts as $product)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="whitespace-nowrap px-6 py-3">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-8 w-8 flex-shrink-0 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                                        @if ($product->image_path)
                                                            <img src="{{ Storage::url($product->image_path) }}"
                                                                alt="{{ $product->name }}"
                                                                class="h-full w-full object-cover rounded-md">
                                                        @else
                                                            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $product->name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $product->code }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                <span
                                                    class="font-bold text-red-600 dark:text-red-400">{{ $product->stock }}</span>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product->min_stock }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                    <svg class="mr-1 h-2 w-2 text-red-400 dark:text-red-500"
                                                        fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Stok Rendah
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center">
                                                <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Semua produk
                                                    memiliki stok yang cukup</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div
                class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-green-500 dark:text-green-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Transaksi Terbaru
                        </h3>
                        <a href="{{ route('sales.index') }}"
                            class="text-sm text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 flex items-center">
                            <span>Lihat semua</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-2 overflow-x-auto">
                        <div class="min-w-full rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Faktur
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Tanggal
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Jumlah
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    @forelse($recentTransactions as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="whitespace-nowrap px-6 py-3">
                                                <a href="{{ route('sales.show', $transaction) }}"
                                                    class="text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 font-medium flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    {{ $transaction->invoice_number }}
                                                </a>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                @if ($transaction->trashed())
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                        <svg class="mr-1 h-2 w-2 text-red-400 dark:text-red-500"
                                                            fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Batal
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400">
                                                        <svg class="mr-1 h-2 w-2 text-green-400 dark:text-green-500"
                                                            fill="currentColor" viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Selesai
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center">
                                                <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada
                                                    transaksi terbaru</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expiring Products Section -->
        <div
            class="overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Produk yang Akan Expired
                    </h3>
                    @if (count($expiringProducts) > 0)
                        <a href="#"
                            class="text-sm text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-500 flex items-center">
                            <span>Lihat semua</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="mt-2 overflow-x-auto">
                    <div class="min-w-full rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Kode
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Nama Produk
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Stok
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Tanggal Expired
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Sisa Hari
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                        Tindakan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($expiringProducts as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->code }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-8 w-8 flex-shrink-0 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                                    @if ($product->image_path)
                                                        <img src="{{ Storage::url($product->image_path) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-full w-full object-cover rounded-md">
                                                    @else
                                                        <svg class="h-4 w-4 text-gray-400 dark:text-gray-500"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $product->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->stock }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->productUnits->first()->expire_date->format('d/m/Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @php
                                                $daysLeft = now()->diffInDays(
                                                    $product->productUnits->first()->expire_date,
                                                    false,
                                                );
                                            @endphp
                                            @if ($daysLeft < 0)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-3 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                    Expired
                                                </span>
                                            @elseif($daysLeft <= 7)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-3 py-0.5 text-xs font-medium text-red-800 dark:text-red-400">
                                                    {{ $daysLeft }} hari
                                                </span>
                                            @elseif($daysLeft <= 30)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900 px-3 py-0.5 text-xs font-medium text-yellow-800 dark:text-yellow-400">
                                                    {{ $daysLeft }} hari
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-0.5 text-xs font-medium text-green-800 dark:text-green-400">
                                                    {{ $daysLeft }} hari
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center">
                                            <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500 mb-2"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada produk
                                                yang akan segera expired</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Panel -->
    <div id="notification-panel"
        class="fixed inset-0 z-30 transform translate-x-full transition-transform duration-300">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity opacity-0"
            id="notification-backdrop"></div>
        <div
            class="absolute inset-y-0 right-0 max-w-sm w-full bg-white dark:bg-gray-800 shadow-xl transform translate-x-0 transition-transform">
            <div class="h-full flex flex-col">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Notifikasi</h2>
                    <button id="close-notification"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-4">
                    @if ($lowStockProducts > 0 || $totalUpcomingCredits > 0)
                        <div class="space-y-4">
                            @if ($lowStockProducts > 0)
                                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400 dark:text-red-300" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Stok Produk
                                                Rendah</h3>
                                            <div class="mt-2 text-sm text-red-700 dark:text-red-200">
                                                <p>Terdapat {{ $lowStockProducts }} produk dengan stok rendah. Segera
                                                    lakukan pembelian untuk menghindari kehabisan stok.</p>
                                            </div>
                                            <div class="mt-4">
                                                <div class="-mx-2 -my-1.5 flex">
                                                    <a href="{{ route('products.index') }}"
                                                        class="px-2 py-1.5 rounded-md text-sm font-medium text-red-800 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                                                        Lihat Produk
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($totalUpcomingCredits > 0)
                                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-orange-400 dark:text-orange-300" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-orange-800 dark:text-orange-300">Kredit
                                                Akan Jatuh Tempo</h3>
                                            <div class="mt-2 text-sm text-orange-700 dark:text-orange-200">
                                                <p>Terdapat {{ $totalUpcomingCredits }} transaksi kredit yang akan
                                                    segera jatuh tempo. Segera lakukan penagihan.</p>
                                            </div>
                                            <div class="mt-4">
                                                <div class="-mx-2 -my-1.5 flex">
                                                    <a href="{{ route('sales.credit') }}"
                                                        class="px-2 py-1.5 rounded-md text-sm font-medium text-orange-800 dark:text-orange-300 hover:bg-orange-100 dark:hover:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 dark:focus:ring-offset-gray-800">
                                                        Lihat Kredit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (count($expiringProducts) > 0)
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Produk
                                                Akan Expired</h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-200">
                                                <p>Terdapat {{ count($expiringProducts) }} produk yang akan segera
                                                    expired. Pastikan untuk memprioritaskan penjualan produk tersebut.
                                                </p>
                                            </div>
                                            <div class="mt-4">
                                                <div class="-mx-2 -my-1.5 flex">
                                                    <a href="#expiring-products"
                                                        class="px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 dark:text-yellow-300 hover:bg-yellow-100 dark:hover:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800">
                                                        Lihat Produk
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada notifikasi
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Semua sistem berjalan dengan baik.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Utility functions
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Format Rupiah
            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            // Load charts only when visible
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Intersecton Observer for lazy loading charts
                const chartObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const chartId = entry.target.id;
                            if (chartId === 'dailySalesChart') initDailySalesChart();
                            if (chartId === 'topProductsChart') initTopProductsChart();
                            if (chartId === 'salesTrendChart') initSalesTrendChart();
                            chartObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1
                });

                // Observe all chart canvases
                document.querySelectorAll('canvas').forEach(chart => chartObserver.observe(chart));

                // Handle Notification Panel
                const notificationBtn = document.getElementById('notification-btn');
                const notificationPanel = document.getElementById('notification-panel');
                const notificationBackdrop = document.getElementById('notification-backdrop');
                const closeNotificationBtn = document.getElementById('close-notification');

                if (notificationBtn && notificationPanel && closeNotificationBtn && notificationBackdrop) {
                    // Open panel
                    notificationBtn.addEventListener('click', function() {
                        notificationPanel.classList.remove('translate-x-full');
                        notificationBackdrop.classList.remove('opacity-0');
                        document.body.classList.add('overflow-hidden');
                    });

                    // Close panel handlers
                    const closePanel = function() {
                        notificationPanel.classList.add('translate-x-full');
                        notificationBackdrop.classList.add('opacity-0');
                        document.body.classList.remove('overflow-hidden');
                    };

                    closeNotificationBtn.addEventListener('click', closePanel);
                    notificationBackdrop.addEventListener('click', closePanel);
                }

                // Handle refresh dashboard button with debounce
                const refreshBtn = document.getElementById('refresh-dashboard');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', debounce(function() {
                        const icon = this.querySelector('svg');
                        icon.classList.add('animate-spin');
                        setTimeout(() => window.location.reload(), 500);
                    }, 300));
                }
            });

            // Chart Initialization Functions
            function initDailySalesChart() {
                const dailySalesChart = new Chart(
                    document.getElementById('dailySalesChart'), {
                        type: 'line',
                        data: {
                            labels: @json($dailySales->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m'))),
                            datasets: [{
                                label: 'Penjualan Harian',
                                data: @json($dailySales->pluck('total')),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: 'rgb(59, 130, 246)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                    titleFont: {
                                        size: 13
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            return formatRupiah(context.raw);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        drawBorder: false,
                                        color: 'rgba(226, 232, 240, 0.6)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value);
                                        },
                                        padding: 10,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        padding: 10,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            }
                        }
                    }
                );
            }

            function initTopProductsChart() {
                const topProductsChart = new Chart(
                    document.getElementById('topProductsChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($topProducts->pluck('name')),
                            datasets: [{
                                label: 'Unit Terjual',
                                data: @json($topProducts->pluck('total_sold')),
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(16, 185, 129, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(139, 92, 246, 0.8)'
                                ],
                                borderColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(245, 158, 11)',
                                    'rgb(239, 68, 68)',
                                    'rgb(139, 92, 246)'
                                ],
                                borderWidth: 1,
                                borderRadius: 6,
                                maxBarThickness: 32
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                    titleFont: {
                                        size: 13
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 12,
                                    cornerRadius: 8
                                }
                            },
                            scales: {
                                y: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        padding: 8,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        drawBorder: false,
                                        color: 'rgba(226, 232, 240, 0.6)'
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        padding: 10,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            }
                        }
                    }
                );
            }

            function initSalesTrendChart() {
                const salesTrendChart = new Chart(
                    document.getElementById('salesTrendChart'), {
                        type: 'line',
                        data: {
                            labels: @json($salesTrend->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M y'))),
                            datasets: [{
                                label: 'Tren Penjualan',
                                data: @json($salesTrend->pluck('total')),
                                borderColor: 'rgb(124, 58, 237)',
                                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: 'rgb(124, 58, 237)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                    titleFont: {
                                        size: 13
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            return formatRupiah(context.raw);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        drawBorder: false,
                                        color: 'rgba(226, 232, 240, 0.6)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value);
                                        },
                                        padding: 10,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        padding: 10,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            }
                        }
                    }
                );
            }
        </script>
    @endpush
</x-app-layout>
