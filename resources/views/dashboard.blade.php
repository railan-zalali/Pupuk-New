<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Greeting & Quick Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in-up">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50">
                    Selamat {{ now()->hour < 12 ? 'Pagi' : (now()->hour < 17 ? 'Siang' : 'Malam') }}, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} · <span id="current-time" class="font-medium tabular-nums">--:--:--</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('sales.create') }}" class="btn-primary btn-sm">
                    <i class="ti ti-plus text-sm"></i> Penjualan Baru
                </a>
                <a href="{{ route('purchases.create') }}" class="btn-secondary btn-sm">
                    <i class="ti ti-package text-sm"></i> Pembelian
                </a>
                <button id="refresh-dashboard" class="btn-ghost btn-sm" title="Refresh">
                    <i class="ti ti-refresh text-base"></i>
                </button>
                <button id="notification-btn" class="btn-ghost btn-sm relative" title="Notifikasi">
                    <i class="ti ti-bell text-base"></i>
                    @php
                    $notifCount = ($data['lowStockProducts'] ?? 0) +
                    (isset($data['expiredProducts']) ? $data['expiredProducts']->count() : 0) +
                    (isset($data['expiringProducts']) ? $data['expiringProducts']->count() : 0) +
                    ($data['totalUpcomingCredits'] ?? 0);
                    @endphp
                    @if($notifCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center pulse-dot">
                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                    </span>
                    @endif
                </button>
            </div>
        </div>

        {{-- KPI Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Daily Sales --}}
            <div class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 animate-fade-in-up stagger-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Penjualan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 tabular-nums">
                            Rp {{ number_format($data['dailySalesTotal'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="stat-card-icon bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500">
                        <i class="ti ti-cash-register"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs">
                    @php
                    $dailyChange = $data['dailySalesChange'] ?? 0;
                    @endphp
                    <span class="inline-flex items-center gap-0.5 font-semibold {{ $dailyChange >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }}">
                        <i class="ti {{ $dailyChange >= 0 ? 'ti-trending-up' : 'ti-trending-down' }} text-sm"></i>
                        {{ abs($dailyChange) }}%
                    </span>
                    <span class="text-gray-400 dark:text-gray-500 ml-1.5">vs kemarin</span>
                </div>
            </div>

            {{-- Monthly Sales --}}
            <div class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 animate-fade-in-up stagger-2">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Penjualan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 tabular-nums">
                            Rp {{ number_format($data['monthlySalesTotal'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="stat-card-icon bg-sky-50 dark:bg-sky-500/10 text-sky-500">
                        <i class="ti ti-chart-line"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs">
                    @php
                    $monthlyChange = $data['monthlySalesChange'] ?? 0;
                    @endphp
                    <span class="inline-flex items-center gap-0.5 font-semibold {{ $monthlyChange >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }}">
                        <i class="ti {{ $monthlyChange >= 0 ? 'ti-trending-up' : 'ti-trending-down' }} text-sm"></i>
                        {{ abs($monthlyChange) }}%
                    </span>
                    <span class="text-gray-400 dark:text-gray-500 ml-1.5">vs bulan lalu</span>
                </div>
            </div>

            {{-- Total Products --}}
            <div class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 animate-fade-in-up stagger-3">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total Produk</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 tabular-nums">
                            {{ number_format($data['totalProducts'] ?? 0) }}
                        </p>
                    </div>
                    <div class="stat-card-icon bg-violet-50 dark:bg-violet-500/10 text-violet-500">
                        <i class="ti ti-packages"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs">
                    @if(($data['lowStockProducts'] ?? 0) > 0)
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-600 dark:text-amber-400">
                        <i class="ti ti-alert-triangle text-sm"></i>
                        {{ $data['lowStockProducts'] }} stok rendah
                    </span>
                    @else
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                        <i class="ti ti-check text-sm"></i> Stok aman
                    </span>
                    @endif
                </div>
            </div>

            {{-- Monthly Credit --}}
            <div class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 animate-fade-in-up stagger-4">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Kredit Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-50 tabular-nums">
                            Rp {{ number_format($data['monthlyCredit'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="stat-card-icon bg-amber-50 dark:bg-amber-500/10 text-amber-500">
                        <i class="ti ti-credit-card"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs">
                    @if(($data['totalUpcomingCredits'] ?? 0) > 0)
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-600 dark:text-amber-400">
                        <i class="ti ti-clock text-sm"></i>
                        {{ $data['totalUpcomingCredits'] }} jatuh tempo
                    </span>
                    @else
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                        <i class="ti ti-check text-sm"></i> Tidak ada jatuh tempo
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Daily Sales Chart --}}
            <div class="lg:col-span-2 card p-5 animate-fade-in-up stagger-3">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Penjualan Harian</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">7 hari terakhir</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>

            {{-- Top Products Chart --}}
            <div class="card p-5 animate-fade-in-up stagger-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Produk Terlaris</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Bulan ini</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Sales Trend Chart --}}
        <div class="card p-5 animate-fade-in-up stagger-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Tren Penjualan</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">6 bulan terakhir</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        {{-- Tables Section --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            {{-- Low Stock Products --}}
            <div class="card overflow-hidden animate-fade-in-up stagger-4">
                <div class="flex items-center justify-between p-5 pb-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                            <i class="ti ti-alert-triangle text-amber-500 text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Stok Rendah</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Produk perlu restok</p>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 flex items-center gap-1">
                        Lihat Semua <i class="ti ti-chevron-right text-xs"></i>
                    </a>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Min. Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['lowStockList'] ?? [] as $product)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                            @else
                                            <i class="ti ti-package text-gray-400 text-sm"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-400 truncate">{{ $product->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400 tabular-nums">{{ $product->stock }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-500 tabular-nums">{{ $product->minimum_stock }}</span>
                                </td>
                                <td>
                                    @if($product->stock <= 0)
                                        <span class="badge badge-danger">Habis</span>
                                        @else
                                        <span class="badge badge-warning">Rendah</span>
                                        @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-10">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center mb-3">
                                            <i class="ti ti-check text-emerald-500 text-xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Semua stok aman</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="card overflow-hidden animate-fade-in-up stagger-5">
                <div class="flex items-center justify-between p-5 pb-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center">
                            <i class="ti ti-receipt text-sky-500 text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Transaksi Terbaru</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Penjualan terakhir</p>
                        </div>
                    </div>
                    <a href="{{ route('sales.index') }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 flex items-center gap-1">
                        Lihat Semua <i class="ti ti-chevron-right text-xs"></i>
                    </a>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Faktur</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recentTransactions'] ?? [] as $transaction)
                            <tr>
                                <td>
                                    <div>
                                        <a href="{{ route('sales.show', $transaction) }}" class="text-sm font-medium text-gray-800 dark:text-gray-100 hover:text-emerald-600 dark:hover:text-emerald-400">
                                            {{ $transaction->invoice_number }}
                                        </a>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $transaction->created_at->diffForHumans() }}</p>
                                    </div>
                                </td>
                                <td class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $transaction->customer->name ?? 'Umum' }}
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 tabular-nums">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($transaction->status === 'completed')
                                    <span class="badge badge-success">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                    @elseif($transaction->status === 'cancelled')
                                    <span class="badge badge-danger">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Batal
                                    </span>
                                    @elseif($transaction->status === 'draft')
                                    <span class="badge badge-neutral">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Draft
                                    </span>
                                    @else
                                    <span class="badge badge-info">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        Pending
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-10">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-700 flex items-center justify-center mb-3">
                                            <i class="ti ti-receipt-off text-gray-400 text-xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada transaksi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(isset($data['recentTransactions']) && $data['recentTransactions']->hasPages())
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700/40 flex items-center justify-between text-xs">
                    <span class="text-gray-400">
                        {{ $data['recentTransactions']->firstItem() }}–{{ $data['recentTransactions']->lastItem() }} dari {{ $data['recentTransactions']->total() }}
                    </span>
                    <div class="flex gap-1">
                        @if(!$data['recentTransactions']->onFirstPage())
                        <a href="{{ $data['recentTransactions']->previousPageUrl() }}" class="px-2.5 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Prev</a>
                        @endif
                        @if($data['recentTransactions']->hasMorePages())
                        <a href="{{ $data['recentTransactions']->nextPageUrl() }}" class="px-2.5 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Next</a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Expired Products Section --}}
        @if(isset($data['expiredProducts']) && $data['expiredProducts']->count() > 0)
        <div id="expired-products" class="card overflow-hidden animate-fade-in-up">
            <div class="flex items-center justify-between p-5 pb-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
                        <i class="ti ti-alert-octagon text-red-500 text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Produk Expired</h3>
                        <p class="text-xs text-red-500">{{ $data['expiredProducts']->count() }} produk sudah expired</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Tgl Expired</th>
                            <th>Hari Terlambat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['expiredProducts'] as $product)
                        <tr class="bg-red-50/50 dark:bg-red-500/5">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                        @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                        <i class="ti ti-package text-gray-400 text-sm"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $product->code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm tabular-nums text-gray-700 dark:text-gray-300">{{ $product->stock }}</td>
                            <td class="text-sm text-gray-500">
                                @if($product->productBatches->isNotEmpty())
                                {{ $product->productBatches->first()->expiry_date->format('d/m/Y') }}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @php
                                $daysOverdue = $product->productBatches->isNotEmpty() ? now()->diffInDays($product->productBatches->first()->expiry_date, false) : 0;
                                @endphp
                                <span class="badge badge-danger">{{ abs((int) $daysOverdue) }} hari</span>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="action-btn-view" title="Lihat Detail">
                                    <i class="ti ti-eye text-base"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($data['expiredProducts']->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700/40 flex items-center justify-between text-xs">
                <span class="text-gray-400">
                    {{ $data['expiredProducts']->firstItem() }}–{{ $data['expiredProducts']->lastItem() }} dari {{ $data['expiredProducts']->total() }}
                </span>
                <div class="flex gap-1">
                    @if(!$data['expiredProducts']->onFirstPage())
                    <a href="{{ $data['expiredProducts']->previousPageUrl() }}" class="px-2.5 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Prev</a>
                    @endif
                    @if($data['expiredProducts']->hasMorePages())
                    <a href="{{ $data['expiredProducts']->nextPageUrl() }}" class="px-2.5 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Next</a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Expiring Products Section --}}
        @if(isset($data['expiringProducts']) && is_countable($data['expiringProducts']) && count($data['expiringProducts']) > 0)
        <div id="expiring-products" class="card overflow-hidden animate-fade-in-up">
            <div class="flex items-center justify-between p-5 pb-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                        <i class="ti ti-clock-exclamation text-amber-500 text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Akan Expired</h3>
                        <p class="text-xs text-amber-500">{{ count($data['expiringProducts']) }} produk segera expired</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Tgl Expired</th>
                            <th>Sisa Hari</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['expiringProducts'] as $product)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                        @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                        <i class="ti ti-package text-gray-400 text-sm"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $product->code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm tabular-nums text-gray-700 dark:text-gray-300">{{ $product->stock }}</td>
                            <td class="text-sm text-gray-500">
                                @if($product->productBatches->isNotEmpty())
                                {{ $product->productBatches->first()->expiry_date->format('d/m/Y') }}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @php
                                $daysLeft = $product->productBatches->isNotEmpty() ? now()->diffInDays($product->productBatches->first()->expiry_date, false) : 0;
                                @endphp
                                @if($daysLeft < 0)
                                    <span class="badge badge-danger">Expired</span>
                                    @elseif($daysLeft <= 7)
                                        <span class="badge badge-danger">{{ (int) $daysLeft }} hari</span>
                                        @elseif($daysLeft <= 30)
                                            <span class="badge badge-warning">{{ (int) $daysLeft }} hari</span>
                                            @else
                                            <span class="badge badge-success">{{ (int) $daysLeft }} hari</span>
                                            @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="action-btn-view" title="Lihat Detail">
                                    <i class="ti ti-eye text-base"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($data['expiringProducts']->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700/40 flex items-center justify-between text-xs">
                <span class="text-gray-400">
                    {{ $data['expiringProducts']->firstItem() }}–{{ $data['expiringProducts']->lastItem() }} dari {{ $data['expiringProducts']->total() }}
                </span>
                <div class="flex gap-1">
                    @if(!$data['expiringProducts']->onFirstPage())
                    <a href="{{ $data['expiringProducts']->previousPageUrl() }}" class="px-2.5 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Prev</a>
                    @endif
                    @if($data['expiringProducts']->hasMorePages())
                    <a href="{{ $data['expiringProducts']->nextPageUrl() }}" class="px-2.5 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">Next</a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endif

    </div>

    {{-- Notification Panel --}}
    <div id="notification-panel"
        class="fixed inset-0 z-30 transform translate-x-full transition-transform duration-300">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm transition-opacity opacity-0"
            id="notification-backdrop"></div>
        <div class="absolute inset-y-0 right-0 max-w-sm w-full bg-white dark:bg-gray-800 shadow-2xl transform translate-x-0 transition-transform border-l border-gray-100 dark:border-gray-700/40">
            <div class="h-full flex flex-col">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700/40 flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                            <i class="ti ti-bell text-emerald-500"></i>
                        </div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Notifikasi</h2>
                    </div>
                    <button id="close-notification" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="ti ti-x text-gray-400 text-lg"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    @if(($data['lowStockProducts'] ?? 0) > 0 || ($data['totalUpcomingCredits'] ?? 0) > 0 || (isset($data['expiredProducts']) && $data['expiredProducts']->count() > 0) || (isset($data['expiringProducts']) && $data['expiringProducts']->count() > 0))

                    @if(($data['lowStockProducts'] ?? 0) > 0)
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                <i class="ti ti-alert-triangle text-red-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-red-800 dark:text-red-300">Stok Rendah</h3>
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $data['lowStockProducts'] }} produk dengan stok rendah</p>
                                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 text-xs font-medium text-red-700 dark:text-red-300 mt-2 hover:text-red-900">
                                    Lihat Produk <i class="ti ti-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(($data['totalUpcomingCredits'] ?? 0) > 0)
                    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                                <i class="ti ti-clock text-amber-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300">Kredit Jatuh Tempo</h3>
                                <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">{{ $data['totalUpcomingCredits'] }} transaksi segera jatuh tempo</p>
                                <a href="{{ route('sales.credit') }}" class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 dark:text-amber-300 mt-2 hover:text-amber-900">
                                    Lihat Kredit <i class="ti ti-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($data['expiredProducts']) && $data['expiredProducts']->count() > 0)
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                <i class="ti ti-alert-octagon text-red-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-red-800 dark:text-red-300">Produk Expired</h3>
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $data['expiredProducts']->count() }} produk sudah expired</p>
                                <a href="#expired-products" id="view-expired-products" class="inline-flex items-center gap-1 text-xs font-medium text-red-700 dark:text-red-300 mt-2 hover:text-red-900">
                                    Lihat Produk <i class="ti ti-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($data['expiringProducts']) && $data['expiringProducts']->count() > 0)
                    <div class="p-4 rounded-xl bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-100 dark:border-yellow-500/20">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-500/20 flex items-center justify-center flex-shrink-0">
                                <i class="ti ti-clock-exclamation text-yellow-500"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">Akan Expired</h3>
                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">{{ $data['expiringProducts']->count() }} produk segera expired</p>
                                <a href="#expiring-products" id="view-expiring-products" class="inline-flex items-center gap-1 text-xs font-medium text-yellow-700 dark:text-yellow-300 mt-2 hover:text-yellow-900">
                                    Lihat Produk <i class="ti ti-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @else
                    <div class="text-center py-14">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-check text-emerald-500 text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Tidak ada notifikasi</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Semua sistem berjalan dengan baik.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Update time
        function updateTime() {
            const now = new Date();
            const t = [now.getHours(), now.getMinutes(), now.getSeconds()].map(n => String(n).padStart(2, '0')).join(':');
            const el = document.getElementById('current-time');
            if (el) el.textContent = t;
        }

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        // Dark mode aware chart colors
        function getChartColors() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                gridColor: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)',
                textColor: isDark ? 'rgba(255,255,255,0.5)' : 'rgba(0,0,0,0.5)',
                tooltipBg: isDark ? 'rgba(30,30,30,0.95)' : 'rgba(15,23,42,0.95)',
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 1000);

            // Refresh button
            const refreshBtn = document.getElementById('refresh-dashboard');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    const icon = this.querySelector('.ti-refresh');
                    if (icon) icon.style.animation = 'spin 0.6s linear infinite';
                    setTimeout(() => window.location.reload(), 400);
                });
            }

            // Notification panel
            const notifBtn = document.getElementById('notification-btn');
            const notifPanel = document.getElementById('notification-panel');
            const notifBackdrop = document.getElementById('notification-backdrop');
            const closeNotif = document.getElementById('close-notification');

            function openNotif() {
                if (!notifPanel) return;
                notifPanel.classList.remove('translate-x-full');
                notifBackdrop.classList.remove('opacity-0');
                document.body.classList.add('overflow-hidden');
            }

            function closeNotifPanel() {
                if (!notifPanel) return;
                notifPanel.classList.add('translate-x-full');
                notifBackdrop.classList.add('opacity-0');
                document.body.classList.remove('overflow-hidden');
            }

            if (notifBtn) notifBtn.addEventListener('click', openNotif);
            if (closeNotif) closeNotif.addEventListener('click', closeNotifPanel);
            if (notifBackdrop) notifBackdrop.addEventListener('click', closeNotifPanel);

            // Scroll handlers for expired/expiring links
            ['view-expired-products', 'view-expiring-products'].forEach(id => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        closeNotifPanel();
                        const target = document.getElementById(id.replace('view-', '').replace('-products', '-products'));
                        if (target) setTimeout(() => target.scrollIntoView({
                            behavior: 'smooth'
                        }), 350);
                    });
                }
            });

            // Lazy-load charts
            const chartObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.id;
                        if (id === 'dailySalesChart') initDailySalesChart();
                        if (id === 'topProductsChart') initTopProductsChart();
                        if (id === 'salesTrendChart') initSalesTrendChart();
                        chartObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('canvas').forEach(c => chartObserver.observe(c));
        });

        // Chart initializers
        function initDailySalesChart() {
            const c = getChartColors();
            new Chart(document.getElementById('dailySalesChart'), {
                type: 'line',
                data: {
                    labels: @json(
    $data['dailySales']
        ->pluck('date')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))
        ->values()
),
                    datasets: [{
                        label: 'Penjualan Harian',
                        data: @json(
    $data['dailySales']
        ->pluck('total')
        ->values()
),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7
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
                            backgroundColor: c.tooltipBg,
                            titleFont: {
                                size: 12,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: ctx => formatRupiah(ctx.raw)
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: c.gridColor
                            },
                            ticks: {
                                callback: v => formatRupiah(v),
                                padding: 10,
                                font: {
                                    size: 11
                                },
                                color: c.textColor
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10,
                                font: {
                                    size: 11
                                },
                                color: c.textColor
                            }
                        }
                    }
                }
            });
        }

        function initTopProductsChart() {
            const c = getChartColors();
            new Chart(document.getElementById('topProductsChart'), {
                type: 'bar',
                data: {
                    labels: @json(
    $data['topProducts']
        ->pluck('name')
        ->values()
),
                    datasets: [{
                        label: 'Unit Terjual',
                        data: @json(
    $data['topProducts']
        ->pluck('total_sold')
        ->values()
),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(14, 165, 233, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(244, 63, 94, 0.8)'
                        ],
                        borderRadius: 8,
                        maxBarThickness: 28,
                        borderSkipped: false
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
                            backgroundColor: c.tooltipBg,
                            titleFont: {
                                size: 12,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 8,
                                font: {
                                    size: 11
                                },
                                color: c.textColor
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                color: c.gridColor
                            },
                            ticks: {
                                stepSize: 1,
                                padding: 10,
                                font: {
                                    size: 11
                                },
                                color: c.textColor
                            }
                        }
                    }
                }
            });
        }

        function initSalesTrendChart() {
            const c = getChartColors();
            new Chart(document.getElementById('salesTrendChart'), {
                    type: 'line',
                    data: {
                        labels: @json(
    $data['salesTrend']
        ->pluck('date')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->format('M y'))
        ->values()
),
                    datasets: [{
                        label: 'Tren Penjualan',
                        data: @json(
    $data['salesTrend']
        ->pluck('total')
        ->values()
),
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7
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
                            backgroundColor: c.tooltipBg,
                            titleFont: {
                                size: 12,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: ctx => formatRupiah(ctx.raw)
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: c.gridColor
                            },
                            ticks: {
                                callback: v => formatRupiah(v),
                                padding: 10,
                                font: {
                                    size: 11
                                },
                                color: c.textColor
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10,
                                font: {
                                    size: 11
                                },
                                color: c.textColor
                            }
                        }
                    }
                }
            });
        }
    </script>
    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    @endpush
</x-app-layout>