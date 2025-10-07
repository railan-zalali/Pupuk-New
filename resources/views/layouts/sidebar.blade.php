@php
use App\Helpers\MenuHelper;
$menuItems = MenuHelper::getSidebarMenu();
$menuGroups = [
    'main' => [],
    'admin' => [],
    'system' => [],
    'reports' => []
];

// Group menu items
foreach ($menuItems as $item) {
    if ($item['title'] === 'Pengaturan') {
        $menuGroups['system'][] = $item;
    } elseif (in_array($item['permission'] ?? '', ['manage-users', 'manage-roles'])) {
        $menuGroups['admin'][] = $item;
    } elseif (($item['permission'] ?? '') === 'access-reports') {
        $menuGroups['reports'][] = $item;
    } else {
        $menuGroups['main'][] = $item;
    }
}
@endphp

<aside class="sidebar h-screen overflow-y-auto border-r border-gray-200 dark:border-gray-700 flex-shrink-0 bg-white dark:bg-gray-800 transition-all duration-300 ease-in-out shadow-lg"
    :class="{
        'collapsed': sidebarCollapsed && !isMobile,
        'open': sidebarOpen && isMobile
    }">

    <!-- Logo -->
    <div class="flex items-center h-16 border-b border-gray-100 dark:border-gray-800 px-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900 logo-container">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group relative">
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
                class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent logo-text">
                {{ config('app.name', 'Laravel') }}
            </span>
            <!-- Tooltip untuk collapsed state -->
            <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                {{ config('app.name', 'Laravel') }}
            </div>
        </a>
    </div>

    <!-- Sidebar content -->
    <div class="py-6" style="zoom: 0.8;">
        <!-- Main Navigation -->
        @if (!empty($menuGroups['main']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-4"
                class="nav-title">MENU UTAMA</div>

            @foreach ($menuGroups['main'] as $item)
                @if (isset($item['submenu']))
                    <!-- Menu with submenu -->
                    <div x-data="{ open: {{ $item['is_active'] ? 'true' : 'false' }} }">
                        <button type="button" 
                            class="nav-item group w-full text-left relative {{ $item['is_active'] ? 'active' : '' }}"
                            @click="open = !open">
                            <i class="nav-icon {{ $item['icon'] }} group-hover:scale-110 transition-transform duration-200"></i>
                            <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                                x-transition:enter="transition ease-out duration-300 delay-150"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-x-0"
                                x-transition:leave-end="opacity-0 transform translate-x-4"
                                class="flex-1">{{ $item['title'] }}</span>
                            <i x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" class="ti ti-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            <!-- Tooltip untuk collapsed state -->
                            <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                                {{ $item['title'] }}
                            </div>
                        </button>
                        <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                            @foreach ($item['submenu'] as $subItem)
                                <a href="{{ isset($subItem['route']) ? route($subItem['route']) : '#' }}" 
                                   class="nav-item group text-sm {{ collect($subItem['active_routes'] ?? [])->contains(function($pattern) { return fnmatch($pattern, Route::currentRouteName()); }) ? 'active' : '' }}">
                                    <span x-show="sidebarOpen" 
                                        x-transition:enter="transition ease-out duration-300 delay-150"
                                        x-transition:enter-start="opacity-0 transform translate-x-4"
                                        x-transition:enter-end="opacity-100 transform translate-x-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform translate-x-0"
                                        x-transition:leave-end="opacity-0 transform translate-x-4">{{ $subItem['title'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Simple menu item -->
                    <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" 
                       class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                        <i class="nav-icon {{ $item['icon'] }} group-hover:scale-110 transition-transform duration-200"></i>
                        <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 transform translate-x-4"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform translate-x-0"
                            x-transition:leave-end="opacity-0 transform translate-x-4">{{ $item['title'] }}</span>
                        <!-- Tooltip untuk collapsed state -->
                        <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                            {{ $item['title'] }}
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
        @endif

        <!-- Admin Menu -->
        @if (!empty($menuGroups['admin']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-4"
                class="nav-title">ADMIN</div>

            @foreach ($menuGroups['admin'] as $item)
                <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" 
                   class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                    <i class="nav-icon {{ $item['icon'] }} group-hover:scale-110 transition-transform duration-200"></i>
                    <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4">{{ $item['title'] }}</span>
                    <!-- Tooltip untuk collapsed state -->
                    <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                        {{ $item['title'] }}
                    </div>
                </a>
            @endforeach
        </div>
        @endif

        <!-- System Menu -->
        @if (!empty($menuGroups['system']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-4"
                class="nav-title">SISTEM</div>

            @foreach ($menuGroups['system'] as $item)
                <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" 
                   class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                    <i class="nav-icon {{ $item['icon'] }} group-hover:scale-110 transition-transform duration-200"></i>
                    <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform translate-x-4">{{ $item['title'] }}</span>
                    <!-- Tooltip untuk collapsed state -->
                    <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                        {{ $item['title'] }}
                    </div>
                </a>
            @endforeach
        </div>
        @endif

        <!-- Reports Menu -->
        @if (!empty($menuGroups['reports']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-4"
                class="nav-title">LAPORAN</div>

            @foreach ($menuGroups['reports'] as $item)
                @if (isset($item['submenu']))
                    <!-- Reports with submenu -->
                    <div x-data="{ open: {{ $item['is_active'] ? 'true' : 'false' }} }">
                        <button type="button" 
                            class="nav-item group w-full text-left relative {{ $item['is_active'] ? 'active' : '' }}"
                            @click="open = !open">
                            <i class="nav-icon {{ $item['icon'] }} group-hover:scale-110 transition-transform duration-200"></i>
                            <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                                x-transition:enter="transition ease-out duration-300 delay-150"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-x-0"
                                x-transition:leave-end="opacity-0 transform translate-x-4"
                                class="flex-1">{{ $item['title'] }}</span>
                            <i x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" class="ti ti-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            <!-- Tooltip untuk collapsed state -->
                            <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                                {{ $item['title'] }}
                            </div>
                        </button>
                        <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                            @foreach ($item['submenu'] as $subItem)
                                <a href="{{ isset($subItem['route']) ? route($subItem['route']) : '#' }}" 
                                   class="nav-item group text-sm {{ collect($subItem['active_routes'] ?? [])->contains(function($pattern) { return fnmatch($pattern, Route::currentRouteName()); }) ? 'active' : '' }}">
                                    <span x-show="sidebarOpen" 
                                        x-transition:enter="transition ease-out duration-300 delay-150"
                                        x-transition:enter-start="opacity-0 transform translate-x-4"
                                        x-transition:enter-end="opacity-100 transform translate-x-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform translate-x-0"
                                        x-transition:leave-end="opacity-0 transform translate-x-4">{{ $subItem['title'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Simple report menu item -->
                    <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" 
                       class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                        <i class="nav-icon {{ $item['icon'] }} group-hover:scale-110 transition-transform duration-200"></i>
                        <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" 
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 transform translate-x-4"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform translate-x-0"
                            x-transition:leave-end="opacity-0 transform translate-x-4">{{ $item['title'] }}</span>
                        <!-- Tooltip untuk collapsed state -->
                        <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                            {{ $item['title'] }}
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
        @endif

    </div>
</aside>

<!-- Mobile overlay -->
<div x-show="sidebarOpen && window.innerWidth < 1024" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 lg:hidden">
</div>
