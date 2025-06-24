<aside class="sidebar h-screen overflow-y-auto border-r border-gray-200 dark:border-gray-700 flex-shrink-0"
    :class="{
        'collapsed': !sidebarOpen,
        'open': sidebarOpen && window.innerWidth < 1024
    }">

    <!-- Logo -->
    <div class="flex items-center h-16 border-b border-gray-100 dark:border-gray-800 px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                {{ substr(config('app.name', 'L'), 0, 1) }}
            </div>
            <span x-show="sidebarOpen" x-transition
                class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">
                {{ config('app.name', 'Laravel') }}
            </span>
        </a>
    </div>

    <!-- Sidebar content -->
    <div class="py-6" style="zoom: 0.8;">
        <!-- Main Navigation -->
        <div class="nav-group">
            <div x-show="sidebarOpen" x-transition class="nav-title">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="nav-icon ti ti-dashboard"></i>
                <span x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            <a href="{{ route('sales.index') }}" class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-shopping-cart"></i>
                <span x-show="sidebarOpen" x-transition>Penjualan</span>
            </a>


            <a href="{{ route('sales.drafts') }}"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 
              {{ request()->routeIs('sales.drafts') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300' }}">
                <i class="ti ti-file-text w-5 h-5 mr-3"></i>
                <span class="flex-1">Draft Transaksi</span>
                @php
                    $draftCount = \App\Models\Sale::where('status', 'draft')->count();
                    $expiringDrafts = \App\Models\Sale::where('status', 'draft')
                        ->where('created_at', '<=', \Carbon\Carbon::now()->subDays(27))
                        ->count();
                @endphp
                @if ($draftCount > 0)
                    <span
                        class="inline-flex items-center justify-center px-2 py-0.5 ml-2 text-xs font-medium rounded-full 
                {{ $expiringDrafts > 0 ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $draftCount }}
                    </span>
                @endif
            </a>



            <a href="{{ route('purchases.index') }}"
                class="nav-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-truck-delivery"></i>
                <span x-show="sidebarOpen" x-transition>Pembelian</span>
            </a>

            <a href="{{ route('products.index') }}"
                class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-box"></i>
                <span x-show="sidebarOpen" x-transition>Produk</span>
            </a>

            <a href="{{ route('categories.index') }}"
                class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-category"></i>
                <span x-show="sidebarOpen" x-transition>Kategori</span>
            </a>

            <a href="{{ route('customers.index') }}"
                class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-users"></i>
                <span x-show="sidebarOpen" x-transition>Pelanggan</span>
            </a>

            <a href="{{ route('suppliers.index') }}"
                class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-building-factory"></i>
                <span x-show="sidebarOpen" x-transition>Pemasok</span>
            </a>
            {{-- <a href="{{ route('cash-book.index') }}"
                class="nav-item {{ request()->routeIs('cash-book.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-book"></i>
                <span x-show="sidebarOpen" x-transition>Buku Kas</span>
            </a> --}}
        </div>

        <!-- Admin Menu -->
        @if (auth()->user()->hasRole('admin'))
            <div class="nav-group">
                <div x-show="sidebarOpen" x-transition class="nav-title">Admin</div>

                <a href="{{ route('users.index') }}"
                    class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-user-circle"></i>
                    <span x-show="sidebarOpen" x-transition>Pengguna</span>
                </a>

                <a href="{{ route('roles.index') }}"
                    class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-shield-lock"></i>
                    <span x-show="sidebarOpen" x-transition>Hak Akses</span>
                </a>
            </div>

            <div class="nav-group">
                <div x-show="sidebarOpen" x-transition class="nav-title">Sistem</div>

                {{-- <a href="{{ route('settings.index') }}" --}}
                <a href="" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-settings"></i>
                    <span x-show="sidebarOpen" x-transition>Pengaturan</span>
                </a>

                <a href="" {{-- <a href="{{ route('activity-logs.index') }}" --}}
                    class="nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-history"></i>
                    <span x-show="sidebarOpen" x-transition>Log Aktivitas</span>
                </a>
            </div>
        @endif

        <!-- Reports Menu -->
        <div class="nav-group">
            <div x-show="sidebarOpen" x-transition class="nav-title">Laporan</div>

            <a href="{{ route('reports.index') }}"
                class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="nav-icon ti ti-report"></i>
                <span x-show="sidebarOpen" x-transition>Semua Laporan</span>
            </a>

            <a href="{{ route('reports.sales') }}"
                class="nav-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                <i class="nav-icon ti ti-chart-bar"></i>
                <span x-show="sidebarOpen" x-transition>Laporan Penjualan</span>
            </a>

            <a href="{{ route('reports.stock') }}"
                class="nav-item {{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                <i class="nav-icon ti ti-chart-area"></i>
                <span x-show="sidebarOpen" x-transition>Laporan Stok</span>
            </a>

            <a href="{{ route('reports.profit-loss') }}"
                class="nav-item {{ request()->routeIs('reports.profit-loss') ? 'active' : '' }}">
                <i class="nav-icon ti ti-chart-pie"></i>
                <span x-show="sidebarOpen" x-transition>Laporan Laba Rugi</span>
            </a>
        </div>

    </div>
</aside>

<!-- Mobile overlay -->
<div x-show="sidebarOpen && window.innerWidth < 1024" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 lg:hidden">
</div>
