@php
    use App\Helpers\ActivityLogHelper;
@endphp
<x-app-layout>
    <div class="py-12">
        <!-- Dashboard yang diperbarui -->
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header & Overview -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h1>
                <p class="text-gray-600">Ringkasan bisnis Anda untuk {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Daily Sales Card -->
                <div
                    class="overflow-hidden rounded-lg bg-gradient-to-br from-blue-50 to-white shadow-sm border border-blue-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-blue-100 rounded-full">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Penjualan Harian</dt>
                                    <dd class="text-xl font-semibold text-gray-900">Rp
                                        {{ number_format($totalSalesToday, 0, ',', '.') }}</dd>
                                    <dd class="mt-1 text-xs text-green-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ number_format($salesChangeToday, 2) }}% dari kemarin</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Sales Card -->
                <div
                    class="overflow-hidden rounded-lg bg-gradient-to-br from-green-50 to-white shadow-sm border border-green-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-green-100 rounded-full">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Penjualan Bulanan</dt>
                                    <dd class="text-xl font-semibold text-gray-900">Rp
                                        {{ number_format($totalSalesThisMonth, 0, ',', '.') }}</dd>
                                    <dd class="mt-1 text-xs text-green-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ number_format($salesChangeThisMonth, 2) }}% dari bulan lalu</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Products Card -->
                <div
                    class="overflow-hidden rounded-lg bg-gradient-to-br from-indigo-50 to-white shadow-sm border border-indigo-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-full">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Total Produk</dt>
                                    <dd class="text-xl font-semibold text-gray-900">{{ number_format($totalProducts) }}
                                    </dd>
                                    <dd class="mt-1 text-xs text-indigo-600">
                                        {{ $lowStockProducts }} produk stok menipis
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    @if ($lowStockProducts > 0)
                        <div class="bg-indigo-50 px-5 py-2">
                            <a href="{{ route('products.index') }}"
                                class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center justify-between">
                                <span>Lihat semua peringatan</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Credit Card -->
                <div
                    class="overflow-hidden rounded-lg bg-gradient-to-br from-orange-50 to-white shadow-sm border border-orange-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-orange-100 rounded-full">
                                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="truncate text-sm font-medium text-gray-500">Kredit Bulanan</dt>
                                    <dd class="text-xl font-semibold text-gray-900">Rp
                                        {{ number_format($totalCreditAmount, 0, ',', '.') }}</dd>
                                    <dd class="mt-1 text-xs text-orange-600">
                                        {{ $totalUpcomingCredits }} transaksi akan jatuh tempo
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    @if ($totalUpcomingCredits > 0)
                        <div class="bg-orange-50 px-5 py-2">
                            <a href="{{ route('sales.credit') }}"
                                class="text-sm text-orange-600 hover:text-orange-800 flex items-center justify-between">
                                <span>Lihat semua kredit</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Charts Section -->
            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Sales Chart -->
                <div class="overflow-hidden rounded-lg bg-white shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                                Penjualan Mingguan
                            </h3>
                            <div class="flex items-center space-x-2">
                                <button
                                    class="px-3 py-1 text-xs font-medium rounded-md bg-blue-50 text-blue-600">Mingguan</button>
                                <button
                                    class="px-3 py-1 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-50">Bulanan</button>
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
                <div class="overflow-hidden rounded-lg bg-white shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Produk Terlaris
                            </h3>
                            <div>
                                <select
                                    class="text-xs rounded-md border-gray-300 py-1 pl-2 pr-7 text-gray-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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

            <!-- Tables Section -->
            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Low Stock Table -->
                <div class="overflow-hidden rounded-lg bg-white shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Stok Produk Rendah
                            </h3>
                            @if ($lowStockProducts > 0)
                                <a href="{{ route('products.index') }}"
                                    class="text-sm text-blue-600 hover:text-blue-900 flex items-center">
                                    <span>Lihat semua</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <div class="mt-2">
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Produk
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Stok Saat Ini
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Stok Minimum
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @forelse($lowStockAlerts as $product)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="whitespace-nowrap px-6 py-3">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="h-8 w-8 flex-shrink-0 rounded-md bg-gray-100 flex items-center justify-center mr-3">
                                                            @if ($product->image_path)
                                                                <img src="{{ Storage::url($product->image_path) }}"
                                                                    alt="{{ $product->name }}"
                                                                    class="h-full w-full object-cover rounded-md">
                                                            @else
                                                                <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $product->name }}</div>
                                                            <div class="text-xs text-gray-500">{{ $product->code }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                    <span class="font-bold text-red-600">{{ $product->stock }}</span>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500">
                                                    {{ $product->min_stock }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                        <svg class="mr-1 h-2 w-2 text-red-400" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Stok Rendah
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-8 text-center">
                                                    <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <p class="text-gray-500 font-medium">Semua produk memiliki stok
                                                        yang cukup</p>
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
                <div class="overflow-hidden rounded-lg bg-white shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Transaksi Terbaru
                            </h3>
                            <a href="{{ route('sales.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-900 flex items-center">
                                <span>Lihat semua</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        <div class="mt-2">
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Faktur
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Tanggal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Jumlah
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @forelse($recentTransactions as $transaction)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="whitespace-nowrap px-6 py-3">
                                                    <a href="{{ route('sales.show', $transaction) }}"
                                                        class="text-blue-600 hover:text-blue-900 font-medium flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        {{ $transaction->invoice_number }}
                                                    </a>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500">
                                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-3 text-sm font-medium text-gray-900">
                                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-3 text-sm">
                                                    @if ($transaction->trashed())
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                            <svg class="mr-1 h-2 w-2 text-red-400" fill="currentColor"
                                                                viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3" />
                                                            </svg>
                                                            Batal
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                            <svg class="mr-1 h-2 w-2 text-green-400"
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
                                                    <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <p class="text-gray-500 font-medium">Belum ada transaksi terbaru
                                                    </p>
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

            @if (auth()->user()->hasRole('admin'))
                <!-- Activity Logs Section -->
                <div class="mt-8">
                    <div class="overflow-hidden rounded-lg bg-white shadow-sm border border-gray-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Aktivitas Terbaru
                                </h3>
                                <a href="{{ route('activity_logs.index') }}"
                                    class="text-sm text-blue-600 hover:text-blue-900 flex items-center">
                                    <span>Lihat semua</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                            <div class="mt-2">
                                <div class="divide-y divide-gray-200">
                                    @forelse($latestLogs as $log)
                                        <div class="py-3 flex items-start">
                                            <div class="flex-shrink-0">
                                                <span
                                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-{{ ActivityLogHelper::getActivityBadgeColor($log->action) }}-100 text-{{ ActivityLogHelper::getActivityBadgeColor($log->action) }}-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        @switch(ActivityLogHelper::getActivityIcon($log->action))
                                                            @case('plus')
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            @break

                                                            <!-- Case statements remain the same -->
                                                        @endswitch
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <a href="{{ route('activity_logs.show', $log) }}"
                                                    class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                                    {{ ucfirst($log->type) }} - {{ ucfirst($log->module) }}
                                                </a>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ Str::limit($log->description, 100) }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $log->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        @empty
                                            <div class="py-8 text-center">
                                                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-gray-500 font-medium">Belum ada aktivitas yang tercatat</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endif
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Format Rupiah
                function formatRupiah(number) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                }

                // Daily Sales Chart
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
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                tooltip: {
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
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value);
                                        }
                                    }
                                }
                            }
                        }
                    }
                );

                // Top Products Chart
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
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    }
                );
            </script>
        @endpush
    </x-app-layout>
