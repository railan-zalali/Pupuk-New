<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Railan Zalali">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/product-selector.css') }}" rel="stylesheet" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100"
    x-data="{
        sidebarOpen: true,
        sidebarCollapsed: false,
        isMobile: window.innerWidth < 1024,
        init() {
            this.handleResize();
            window.addEventListener('resize', () => this.handleResize());
        },
        handleResize() {
            this.isMobile = window.innerWidth < 1024;
            if (window.innerWidth >= 1024) {
                this.sidebarOpen = true;
            } else {
                this.sidebarOpen = false;
                this.sidebarCollapsed = false;
            }
        },
        toggleSidebar() {
            if (this.isMobile) {
                this.sidebarOpen = !this.sidebarOpen;
            } else {
                this.sidebarCollapsed = !this.sidebarCollapsed;
            }
        },
        closeSidebar() {
            if (this.isMobile) {
                this.sidebarOpen = false;
            }
        }
    }">
    <div class="flex h-screen overflow-hidden">
        <!-- Backdrop for mobile/tablet -->
        <div x-show="sidebarOpen && isMobile"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeSidebar()"
             class="sidebar-backdrop"></div>

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto main-content">

            <!-- Header -->
            <header class="sticky top-0 z-10 border-b border-gray-200/60 dark:border-gray-700/40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl">
                <div class="flex items-center justify-between px-4 lg:px-6 h-16">
                    <div class="flex items-center gap-3">
                        <!-- Toggle Sidebar -->
                        <button @click="toggleSidebar()"
                            class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors group">
                            <i class="ti ti-menu-2 text-xl text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200 transition-colors"></i>
                        </button>

                        <!-- Breadcrumb / Page Info -->
                        <div class="hidden sm:flex items-center gap-2 text-sm">
                            <a href="{{ route('dashboard') }}" class="text-gray-400 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                <i class="ti ti-home text-base"></i>
                            </a>
                            @isset($header)
                                <i class="ti ti-chevron-right text-gray-300 dark:text-gray-600 text-xs"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-200">{{ $header }}</span>
                            @endisset
                        </div>
                    </div>

                    <div class="flex items-center gap-1 sm:gap-2">
                        <!-- Search Toggle (optional) -->
                        <button class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors group hidden sm:flex">
                            <i class="ti ti-search text-lg text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300"></i>
                        </button>

                        <!-- Dark mode toggle -->
                        <button @click="darkMode = !darkMode"
                            class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors group">
                            <i x-show="!darkMode"
                               x-transition:enter="transition ease-out duration-200"
                               x-transition:enter-start="opacity-0 rotate-90"
                               x-transition:enter-end="opacity-100 rotate-0"
                               class="ti ti-sun text-lg text-amber-500 group-hover:text-amber-600"></i>
                            <i x-show="darkMode"
                               x-transition:enter="transition ease-out duration-200"
                               x-transition:enter-start="opacity-0 rotate-90"
                               x-transition:enter-end="opacity-100 rotate-0"
                               class="ti ti-moon-stars text-lg text-sky-400 group-hover:text-sky-300"></i>
                        </button>

                        <!-- User profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2.5 p-1.5 pl-1.5 pr-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                                <i class="ti ti-chevron-down text-xs text-gray-400 hidden md:block transition-transform duration-200"
                                   :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Profile dropdown -->
                            <div x-show="open" @click.away="open = false"
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-1.5 z-50 overflow-hidden">

                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700/60">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold shadow-sm">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">
                                                {{ Auth::user()->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ Auth::user()->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-1.5">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <i class="ti ti-user text-base text-gray-400"></i>
                                        {{ __('Profile') }}
                                    </a>
                                    @if(Route::has('settings.index'))
                                    <a href="{{ route('settings.index') }}"
                                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <i class="ti ti-settings text-base text-gray-400"></i>
                                        {{ __('Pengaturan') }}
                                    </a>
                                    @endif
                                </div>

                                <div class="border-t border-gray-100 dark:border-gray-700/60 pt-1.5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                            <i class="ti ti-logout text-base"></i>
                                            {{ __('Keluar') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow p-4 lg:p-6">
                @isset($header)
                    <div class="max-w-7xl mx-auto mb-1">
                        {{-- Breadcrumb already shown in header --}}
                    </div>
                @endisset

                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        if (!localStorage.getItem('darkMode')) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            localStorage.setItem('darkMode', prefersDark);
        }
    </script>

    @stack('scripts')
    <script type="module">
        if (window.Alpine) {
            window.Alpine.start();
        } else {
            document.addEventListener('DOMContentLoaded', () => {
                if (window.Alpine) {
                    window.Alpine.start();
                }
            });
        }
    </script>
</body>

</html>
