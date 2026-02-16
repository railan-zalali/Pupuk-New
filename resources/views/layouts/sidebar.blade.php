@php
use App\Helpers\MenuHelper;
$menuItems = MenuHelper::getSidebarMenu();
$menuGroups = [
'main' => [],
'admin' => [],
'system' => [],
'reports' => []
];

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

<aside class="sidebar h-screen overflow-y-auto border-r border-gray-100 dark:border-gray-700/40 flex-shrink-0 bg-white dark:bg-gray-800"
    :class="{
        'collapsed': sidebarCollapsed && !isMobile,
        'open': sidebarOpen && isMobile
    }">

    <!-- Logo -->
    <div class="flex items-center h-16 border-b border-gray-100 dark:border-gray-700/40 px-5 logo-container">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-emerald-500/20 group-hover:shadow-lg group-hover:shadow-emerald-500/30 transition-shadow duration-300">
                {{ substr(config('app.name', 'P'), 0, 1) }}
            </div>
            <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="text-base font-bold text-gray-800 dark:text-gray-100 logo-text truncate">
                {{ config('app.name', 'Laravel') }}
            </span>
            <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                {{ config('app.name', 'Laravel') }}
            </div>
        </a>
    </div>

    <!-- Sidebar content -->
    <div class="py-5 space-y-1">
        {{-- Main Navigation --}}
        @if (!empty($menuGroups['main']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                x-transition:enter="transition ease-out duration-200 delay-75"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="nav-title">MENU UTAMA</div>

            @foreach ($menuGroups['main'] as $item)
            @if (isset($item['submenu']))
            <div x-data="{ open: {{ $item['is_active'] ? 'true' : 'false' }} }">
                <button type="button"
                    class="nav-item group w-full text-left relative {{ $item['is_active'] ? 'active' : '' }}"
                    @click="open = !open">
                    <i class="nav-icon {{ $item['icon'] }}"></i>
                    <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                        x-transition class="flex-1">{{ $item['title'] }}</span>
                    <i x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                        class="ti ti-chevron-down text-xs transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                    <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                        {{ $item['title'] }}
                    </div>
                </button>
                <div x-show="open" x-collapse class="ml-5 mt-0.5 space-y-0.5 border-l-2 border-gray-100 dark:border-gray-700/40 pl-3">
                    @foreach ($item['submenu'] as $subItem)
                    <a href="{{ isset($subItem['route']) ? route($subItem['route']) : '#' }}"
                        class="nav-item group text-xs py-1.5 {{ collect($subItem['active_routes'] ?? [])->contains(function($pattern) { return fnmatch($pattern, Route::currentRouteName()); }) ? 'active' : '' }}">
                        <span x-show="sidebarOpen || !isMobile" x-transition>{{ $subItem['title'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
                class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                <i class="nav-icon {{ $item['icon'] }}"></i>
                <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                    x-transition>{{ $item['title'] }}</span>
                <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                    {{ $item['title'] }}
                </div>
            </a>
            @endif
            @endforeach
        </div>
        @endif

        {{-- Admin Menu --}}
        @if (!empty($menuGroups['admin']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                x-transition class="nav-title">ADMIN</div>

            @foreach ($menuGroups['admin'] as $item)
            <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
                class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                <i class="nav-icon {{ $item['icon'] }}"></i>
                <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                    x-transition>{{ $item['title'] }}</span>
                <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                    {{ $item['title'] }}
                </div>
            </a>
            @endforeach
        </div>
        @endif

        {{-- System Menu --}}
        @if (!empty($menuGroups['system']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                x-transition class="nav-title">SISTEM</div>

            @foreach ($menuGroups['system'] as $item)
            <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
                class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                <i class="nav-icon {{ $item['icon'] }}"></i>
                <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                    x-transition>{{ $item['title'] }}</span>
                <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                    {{ $item['title'] }}
                </div>
            </a>
            @endforeach
        </div>
        @endif

        {{-- Reports Menu --}}
        @if (!empty($menuGroups['reports']))
        <div class="nav-group">
            <div x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                x-transition class="nav-title">LAPORAN</div>

            @foreach ($menuGroups['reports'] as $item)
            @if (isset($item['submenu']))
            <div x-data="{ open: {{ $item['is_active'] ? 'true' : 'false' }} }">
                <button type="button"
                    class="nav-item group w-full text-left relative {{ $item['is_active'] ? 'active' : '' }}"
                    @click="open = !open">
                    <i class="nav-icon {{ $item['icon'] }}"></i>
                    <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                        x-transition class="flex-1">{{ $item['title'] }}</span>
                    <i x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                        class="ti ti-chevron-down text-xs transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                    <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                        {{ $item['title'] }}
                    </div>
                </button>
                <div x-show="open" x-collapse class="ml-5 mt-0.5 space-y-0.5 border-l-2 border-gray-100 dark:border-gray-700/40 pl-3">
                    @foreach ($item['submenu'] as $subItem)
                    <a href="{{ isset($subItem['route']) ? route($subItem['route']) : '#' }}"
                        class="nav-item group text-xs py-1.5 {{ collect($subItem['active_routes'] ?? [])->contains(function($pattern) { return fnmatch($pattern, Route::currentRouteName()); }) ? 'active' : '' }}">
                        <span x-show="sidebarOpen || !isMobile" x-transition>{{ $subItem['title'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
                class="nav-item group relative {{ $item['is_active'] ? 'active' : '' }}">
                <i class="nav-icon {{ $item['icon'] }}"></i>
                <span x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)"
                    x-transition>{{ $item['title'] }}</span>
                <div x-show="!isMobile && sidebarCollapsed" class="nav-tooltip">
                    {{ $item['title'] }}
                </div>
            </a>
            @endif
            @endforeach
        </div>
        @endif
    </div>

    <!-- Sidebar Footer - Mini user card -->
    <div class="mt-auto border-t border-gray-100 dark:border-gray-700/40 p-3"
        x-show="(sidebarOpen && isMobile) || (!isMobile && !sidebarCollapsed)" x-transition>
        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50/80 dark:bg-gray-700/30">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-semibold text-gray-700 dark:text-gray-200 truncate">{{ Auth::user()->name ?? 'User' }}</div>
                <div class="text-[10px] text-gray-400 dark:text-gray-500 truncate">{{ Auth::user()->roles->first()->name ?? 'User' }}</div>
            </div>
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