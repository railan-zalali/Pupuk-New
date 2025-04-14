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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .app-header {
            @apply bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3;
        }

        .content-card {
            @apply bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700;
        }

        .btn-primary {
            @apply inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors;
        }

        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors;
        }

        .nav-link {
            @apply flex items-center gap-2 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors;
        }

        .nav-link.active {
            @apply bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto"
            :class="sidebarOpen ? 'main-content' : 'main-content expanded'">

            <!-- Header -->
            <header class="app-header">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <!-- Toggle Sidebar Button -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="ti ti-menu-2 text-xl text-gray-500 dark:text-gray-400"></i>
                        </button>

                        <!-- Page title -->
                        <span class="text-lg font-semibold hidden md:block">{{ config('app.name', 'Laravel') }}</span>
                    </div>

                    <div class="flex items-center gap-6">
                        <!-- Dark mode toggle -->
                        <button @click="darkMode = !darkMode"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i x-show="!darkMode" class="ti ti-sun text-xl text-amber-500"></i>
                            <i x-show="darkMode" class="ti ti-moon text-xl text-blue-400"></i>
                        </button>

                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                                <i class="ti ti-bell text-xl text-gray-500 dark:text-gray-400"></i>
                                <span
                                    class="absolute top-1 right-1 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                            </button>

                            <!-- Notifications dropdown -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-2 z-50 border border-gray-200 dark:border-gray-700"
                                x-cloak x-transition>
                                <div class="px-4 py-2.5 font-semibold border-b border-gray-100 dark:border-gray-700">
                                    Notifikasi
                                </div>
                                <div class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada notifikasi baru
                                </div>
                            </div>
                        </div>

                        <!-- User profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-medium">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                                <i class="ti ti-chevron-down text-gray-500 dark:text-gray-400"></i>
                            </button>

                            <!-- Profile dropdown -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-2 z-50 border border-gray-200 dark:border-gray-700"
                                x-cloak x-transition>
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <i class="ti ti-user text-gray-500 dark:text-gray-400"></i>
                                    <span>{{ __('Profile') }}</span>
                                </a>
                                <a href="#"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <i class="ti ti-settings text-gray-500 dark:text-gray-400"></i>
                                    <span>{{ __('Settings') }}</span>
                                </a>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors">
                                        <i class="ti ti-logout"></i>
                                        <span>{{ __('Log Out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow p-6">
                <!-- Page header -->
                @isset($header)
                    <div class="max-w-7xl mx-auto mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $header }}</h1>
                    </div>
                @endisset

                <!-- Content area -->
                <div class="max-w-7xl mx-auto">
                    <div class="content-card p-6">
                        {{ $slot }}
                    </div>
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
