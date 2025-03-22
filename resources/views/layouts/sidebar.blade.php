<aside class="sidebar h-screen overflow-y-auto border-r border-gray-200 dark:border-gray-700 flex-shrink-0"
    :class="{
        'collapsed': !sidebarOpen,
        'open': sidebarOpen && window.innerWidth < 1024
    }">

    <!-- Logo -->
    <div class="flex items-center justify-center h-16 border-b border-gray-100 dark:border-gray-800">
        <a href="{{ route('dashboard') }}" class="block py-4">
            <span x-show="sidebarOpen"
                class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">
                {{ config('app.name', 'Laravel') }}
            </span>
            <span x-show="!sidebarOpen" class="text-xl font-bold">
                {{ substr(config('app.name', 'L'), 0, 1) }}
            </span>
        </a>
    </div>

    <!-- Sidebar content -->
    <div class="py-4">
        <!-- Main Navigation -->
        <div class="nav-group">
            <div x-show="sidebarOpen" class="nav-title">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="nav-icon ti ti-dashboard"></i>
                <span x-show="sidebarOpen">{{ __('Dashboard') }}</span>
            </a>

            <a href="{{ route('products.index') }}"
                class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-box"></i>
                <span x-show="sidebarOpen">{{ __('Produk') }}</span>
            </a>

            <a href="{{ route('customers.index') }}"
                class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-users"></i>
                <span x-show="sidebarOpen">{{ __('Pelanggan') }}</span>
            </a>

            <a href="{{ route('sales.index') }}"
                class="nav-item {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                <i class="nav-icon ti ti-receipt"></i>
                <span x-show="sidebarOpen">{{ __('Sales') }}</span>
            </a>

            <a href="{{ route('sales.drafts') }}"
                class="nav-item {{ request()->routeIs('sales.drafts') ? 'active' : '' }}">
                <div class="flex items-center w-full">
                    <i class="nav-icon ti ti-notes"></i>
                    <span x-show="sidebarOpen" class="flex-grow">{{ __('Draft Transaksi') }}</span>

                    @php
                        $draftCount = App\Models\Sale::where('status', 'draft')->count();
                    @endphp

                    @if ($draftCount > 0)
                        <span class="badge badge-primary">{{ $draftCount }}</span>
                    @endif
                </div>
            </a>
        </div>

        <!-- Admin Menu -->
        @if (auth()->user()->hasRole('admin'))
            <div class="nav-group">
                <div x-show="sidebarOpen" class="nav-title">Admin</div>

                <a href="{{ route('suppliers.index') }}"
                    class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-truck-delivery"></i>
                    <span x-show="sidebarOpen">{{ __('Supplier') }}</span>
                </a>

                <a href="{{ route('purchases.index') }}"
                    class="nav-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-shopping-cart"></i>
                    <span x-show="sidebarOpen">{{ __('Purchases') }}</span>
                </a>

                <a href="{{ route('categories.index') }}"
                    class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-category"></i>
                    <span x-show="sidebarOpen">{{ __('Kategori') }}</span>
                </a>
            </div>

            <div class="nav-group">
                <div x-show="sidebarOpen" class="nav-title">Sistem</div>

                <a href="{{ route('users.index') }}"
                    class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-user-circle"></i>
                    <span x-show="sidebarOpen">{{ __('Pengguna') }}</span>
                </a>

                <a href="{{ route('roles.index') }}"
                    class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="nav-icon ti ti-shield-lock"></i>
                    <span x-show="sidebarOpen">{{ __('Role') }}</span>
                </a>
            </div>
        @endif

        <!-- Reports Menu -->
        <div class="nav-group">
            <div x-show="sidebarOpen" class="nav-title">Laporan</div>

            <a href="{{ route('reports.index') }}"
                class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="nav-icon ti ti-chart-bar"></i>
                <span x-show="sidebarOpen">{{ __('Reports') }}</span>
            </a>
        </div>
    </div>
</aside>

<!-- Mobile overlay -->
<div x-show="sidebarOpen && window.innerWidth < 1024" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black bg-opacity-30 z-40 lg:hidden" x-cloak></div>
