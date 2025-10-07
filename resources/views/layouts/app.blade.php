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
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/product-selector.css') }}" rel="stylesheet" />

    <style>
        body {
            /* zoom: 0.8; */
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .app-header {
            /* Header styles are now inline for better control */
        }

        .content-card {
            @apply bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700;
        }

        .btn-primary {
            @apply inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors;
        }

        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
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
                // Desktop: sidebar is always visible, just toggle collapsed state
                this.sidebarOpen = true;
            } else {
                // Mobile/Tablet: sidebar can be hidden/shown
                this.sidebarOpen = false;
                this.sidebarCollapsed = false;
            }
        },
        toggleSidebar() {
            if (this.isMobile) {
                // Mobile/Tablet: toggle visibility
                this.sidebarOpen = !this.sidebarOpen;
            } else {
                // Desktop: toggle collapsed state
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
            <header class="app-header sticky top-0 z-10 backdrop-blur-sm bg-white/80 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="max-w-7xl mx-auto flex items-center justify-between px-3 sm:px-4 py-3">
                    <div class="flex items-center gap-2 sm:gap-4">
                        <!-- Toggle Sidebar Button -->
                        <button @click="toggleSidebar()"
                            class="p-2 sm:p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-105 active:scale-95 group">
                            <i class="ti ti-menu-2 text-lg sm:text-xl text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-all duration-200" 
                               :class="{ 'rotate-90': !sidebarOpen }"></i>
                        </button>

                        <!-- Page title -->
                        <div class="hidden sm:flex items-center gap-2">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-gradient-to-r from-indigo-500 to-violet-500 flex items-center justify-center">
                                <i class="ti ti-leaf text-white text-sm sm:text-lg"></i>
                            </div>
                            <span class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-200">{{ config('app.name', 'Laravel') }}</span>
                        </div>
                        
                        <!-- Mobile page title -->
                        <div class="flex sm:hidden items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-gradient-to-r from-indigo-500 to-violet-500 flex items-center justify-center">
                                <i class="ti ti-leaf text-white text-xs"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ substr(config('app.name', 'Laravel'), 0, 10) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 sm:gap-3">


                        <!-- Dark mode toggle -->
                        <button @click="darkMode = !darkMode"
                            class="p-2 sm:p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-105 active:scale-95 group">
                            <i x-show="!darkMode" 
                               x-transition:enter="transition ease-out duration-200"
                               x-transition:enter-start="opacity-0 rotate-90"
                               x-transition:enter-end="opacity-100 rotate-0"
                               x-transition:leave="transition ease-in duration-200"
                               x-transition:leave-start="opacity-100 rotate-0"
                               x-transition:leave-end="opacity-0 rotate-90"
                               class="ti ti-sun text-lg sm:text-xl text-amber-500 group-hover:text-amber-600 transition-colors duration-200"></i>
                            <i x-show="darkMode" 
                               x-transition:enter="transition ease-out duration-200"
                               x-transition:enter-start="opacity-0 rotate-90"
                               x-transition:enter-end="opacity-100 rotate-0"
                               x-transition:leave="transition ease-in duration-200"
                               x-transition:leave-start="opacity-100 rotate-0"
                               x-transition:leave-end="opacity-0 rotate-90"
                               class="ti ti-moon text-lg sm:text-xl text-blue-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </button>

                        <!-- Notifications -->
                        


                        <!-- User profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 group hover:scale-105 active:scale-95">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 flex items-center justify-center text-white font-medium shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors duration-200">{{ Auth::user()->name }}</span>
                                <i class="ti ti-chevron-down text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-all duration-200" 
                                   :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Profile dropdown -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-60 bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-xl shadow-xl py-2 z-50 border border-gray-200 dark:border-gray-700"
                                x-cloak 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-1">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                    <i class="ti ti-user text-gray-500 dark:text-gray-400 group-hover:text-indigo-500 transition-colors duration-200"></i>
                                    <span class="group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors duration-200">{{ __('Profile') }}</span>
                                </a>
                                <a href="#"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                    <i class="ti ti-settings text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors duration-200"></i>
                                    <span class="group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors duration-200">{{ __('Settings') }}</span>
                                </a>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all duration-200 group">
                                        <i class="ti ti-logout group-hover:scale-110 transition-transform duration-200"></i>
                                        <span class="group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors duration-200">{{ __('Log Out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow p-3 md:p-4 lg:p-6">
                <!-- Page header -->
                @isset($header)
                    <div class="max-w-7xl mx-auto mb-4 lg:mb-6">
                        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $header }}</h1>
                    </div>
                @endisset

                <!-- Content area -->
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        // Check for system color scheme preference
        if (!localStorage.getItem('darkMode')) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            localStorage.setItem('darkMode', prefersDark);
        }
    </script>

    @stack('scripts')
</body>

</html>
