<aside class="sidebar h-screen overflow-y-auto border-r border-gray-200 dark:border-gray-700 flex-shrink-0 bg-white dark:bg-gray-800 transition-all duration-300 ease-in-out shadow-lg"
    :class="{
        'collapsed': sidebarCollapsed && !isMobile,
        'open': sidebarOpen && isMobile
    }">

    <!-- Logo -->
    <div class="flex items-center h-16 border-b border-gray-100 dark:border-gray-800 px-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white font-bold shadow-lg transform group-hover:scale-105 transition-all duration-200">
                {{ substr(config('app.name', 'L'), 0, 1) }}
            </div>
            <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-4"
                class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                {{ config('app.name', 'Laravel') }}
            </span>
        </a>
    </div>

    <!-- Sidebar content -->
    <div class="py-6" style="zoom: 0.8;">
        <!-- Main Navigation -->
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-4"
                class="nav-title">MENU UTAMA</div>

            <a href="{{ route('dashboard') }}" class="nav-item group {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="nav-icon ti ti-dashboard group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Dashboard</span>
            </a>
            <a href="{{ route('sales.index') }}"
                class="nav-item group {{ request()->routeIs('sales.index') || request()->routeIs('sales.create') || request()->routeIs('sales.show') || request()->routeIs('sales.edit') ? 'active' : '' }}">
                <i class="nav-icon ti ti-shopping-cart group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Penjualan</span>
            </a>
            <a href="{{ route('sales.drafts') }}"
                class="nav-item group {{ request()->routeIs('sales.drafts') ? 'active' : '' }}">
                <i class="nav-icon ti ti-file-invoice group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Draft Penjualan</span>
            </a>
            <a href="{{ route('purchases.index') }}"
                class="nav-item group {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-truck-delivery group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Pembelian</span>
            </a>


            <a href="{{ route('products.index') }}"
                class="nav-item group {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-box group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Produk</span>
            </a>

            <a href="{{ route('customers.index') }}"
                class="nav-item group {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-users group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Pelanggan</span>
            </a>

            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('categories.index') }}"
                    class="nav-item group {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-category group-hover:scale-110 transition-transform duration-200"></i>
                    <span x-show="sidebarOpen" 
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4">Kategori</span>
                </a>

                <a href="{{ route('suppliers.index') }}"
                    class="nav-item group {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-building-factory group-hover:scale-110 transition-transform duration-200"></i>
                    <span x-show="sidebarOpen" 
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4">Pemasok</span>
                </a>
            @endif
            {{-- <a href="{{ route('cash-book.index') }}"
                class="nav-item {{ request()->routeIs('cash-book.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-book"></i>
                <span x-show="sidebarOpen" x-transition>Buku Kas</span>
            </a> --}}
        </div>

        <!-- Admin Menu -->
        @if (auth()->user()->hasRole('admin'))
            <div class="nav-group">
                <div x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-100"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4"
                    class="nav-title">ADMIN</div>

                <a href="{{ route('users.index') }}"
                    class="nav-item group {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-user-circle group-hover:scale-110 transition-transform duration-200"></i>
                    <span x-show="sidebarOpen" 
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4">Pengguna</span>
                </a>

                <a href="{{ route('roles.index') }}"
                    class="nav-item group {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-shield-lock group-hover:scale-110 transition-transform duration-200"></i>
                    <span x-show="sidebarOpen" 
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4">Hak Akses</span>
                </a>
            </div>

            <div class="nav-group">
                <div x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-100"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4"
                    class="nav-title">SISTEM</div>

                <a href="{{ route('settings.index') }}"
                    class="nav-item group {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-settings group-hover:scale-110 transition-transform duration-200"></i>
                    <span x-show="sidebarOpen" 
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4">Pengaturan</span>
                </a>

            </div>
        @endif

        <!-- Reports Menu -->
        <div class="nav-group">
            <div x-show="sidebarOpen" 
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-4"
                class="nav-title">Laporan</div>

            <a href="{{ route('reports.index') }}"
                class="nav-item group {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="nav-icon ti ti-report group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Semua Laporan</span>
            </a>

            <a href="{{ route('reports.sales') }}"
                class="nav-item group {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                <i class="nav-icon ti ti-chart-bar group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Laporan Penjualan</span>
            </a>

            <a href="{{ route('reports.stock') }}"
                class="nav-item group {{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                <i class="nav-icon ti ti-chart-area group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Laporan Stok</span>
            </a>

            <a href="{{ route('reports.stock-in') }}"
                class="nav-item group {{ request()->routeIs('reports.stock-in') ? 'active' : '' }}">
                <i class="nav-icon ti ti-arrow-bar-to-down group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Stok Masuk</span>
            </a>

            <a href="{{ route('reports.stock-out') }}"
                class="nav-item group {{ request()->routeIs('reports.stock-out') ? 'active' : '' }}">
                <i class="nav-icon ti ti-arrow-bar-to-up group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Stok Keluar</span>
            </a>

            <a href="{{ route('reports.profit-loss') }}"
                class="nav-item group {{ request()->routeIs('reports.profit-loss') ? 'active' : '' }}">
                <i class="nav-icon ti ti-chart-pie group-hover:scale-110 transition-transform duration-200"></i>
                <span x-show="sidebarOpen" 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-4">Laporan Laba Rugi</span>
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
