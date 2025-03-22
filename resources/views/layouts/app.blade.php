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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --color-primary: #6366f1;
            --color-primary-hover: #4f46e5;
            --color-secondary: #0ea5e9;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-info: #3b82f6;

            --color-sidebar-bg: #f8fafc;
            --color-sidebar-active: rgba(99, 102, 241, 0.1);
            --color-sidebar-hover: rgba(99, 102, 241, 0.05);
            --color-sidebar-text: #64748b;
            --color-sidebar-active-text: #4f46e5;

            --transition-normal: all 0.3s ease;
        }

        .dark {
            --color-primary: #818cf8;
            --color-primary-hover: #6366f1;
            --color-sidebar-bg: #1e293b;
            --color-sidebar-active: rgba(129, 140, 248, 0.2);
            --color-sidebar-hover: rgba(129, 140, 248, 0.1);
            --color-sidebar-text: #94a3b8;
            --color-sidebar-active-text: #818cf8;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            width: 280px;
            background-color: var(--color-sidebar-bg);
            transition: var(--transition-normal);
            z-index: 50;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .main-content {
            transition: var(--transition-normal);
            /* margin-left: 280px; */
            width: calc(100% - 280px);
        }

        .main-content.expanded {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        @media (max-width: 1024px) {
            .sidebar {
                left: -280px;
                position: fixed;
                height: 100vh;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        .nav-group {
            margin-bottom: 1rem;
        }

        .nav-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.75rem 1.5rem 0.5rem;
            color: var(--color-sidebar-text);
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--color-sidebar-text);
            border-radius: 0.5rem;
            margin: 0.25rem 0.75rem;
            transition: var(--transition-normal);
        }

        .nav-item:hover {
            background-color: var(--color-sidebar-hover);
            color: var(--color-sidebar-active-text);
        }

        .nav-item.active {
            background-color: var(--color-sidebar-active);
            color: var(--color-sidebar-active-text);
            font-weight: 500;
        }

        .nav-icon {
            margin-right: 0.75rem;
            font-size: 1.25rem;
            width: 1.25rem;
            height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: var(--transition-normal);
        }

        .dark .content-card {
            background-color: #1e293b;
            color: #e2e8f0;
        }

        .toggle-theme {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: var(--transition-normal);
        }

        .toggle-theme:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        .dark .toggle-theme:hover {
            background-color: rgba(129, 140, 248, 0.2);
        }

        .badge {
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-primary {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--color-primary);
        }

        .dark .badge-primary {
            background-color: rgba(129, 140, 248, 0.2);
            color: var(--color-primary);
        }

        .app-header {
            height: 70px;
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition-normal);
        }

        .dark .app-header {
            background-color: #0f172a;
            border-color: #334155;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
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
            <header class="app-header sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <!-- Toggle Sidebar Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="focus:outline-none">
                        <i class="ti ti-menu-2 text-xl text-gray-500 dark:text-gray-400"></i>
                    </button>

                    <!-- Page title (optional) -->
                    <span class="text-lg font-medium hidden md:block">{{ config('app.name', 'Laravel') }}</span>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dark mode toggle -->
                    <button @click="darkMode = !darkMode" class="toggle-theme">
                        <i x-show="!darkMode" class="ti ti-sun text-amber-500"></i>
                        <i x-show="darkMode" class="ti ti-moon text-blue-400"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-1 relative">
                            <i class="ti ti-bell text-xl text-gray-500 dark:text-gray-400"></i>
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>

                        <!-- Notifications dropdown -->
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50"
                            x-cloak>
                            <div class="px-4 py-2 font-medium border-b border-gray-100 dark:border-gray-700">
                                Notifikasi
                            </div>
                            <div class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                Tidak ada notifikasi baru
                            </div>
                        </div>
                    </div>

                    <!-- User profile -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                            <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-white">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                            <i class="ti ti-chevron-down text-gray-500 dark:text-gray-400"></i>
                        </button>

                        <!-- Profile dropdown -->
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50"
                            x-cloak>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="ti ti-user mr-2"></i> {{ __('Profile') }}
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="ti ti-settings mr-2"></i> {{ __('Settings') }}
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-red-500">
                                    <i class="ti ti-logout mr-2"></i> {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow p-6">
                <!-- Page header (optional) -->
                @isset($header)
                    <div class="page-header">
                        <h1 class="text-2xl font-bold">{{ $header }}</h1>
                    </div>
                @endisset

                <!-- Content area -->
                <div class="content-card p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        // Check for system color scheme preference on first load
        if (!localStorage.getItem('darkMode')) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            localStorage.setItem('darkMode', prefersDark);
        }
    </script>

    @stack('scripts')
</body>

</html>
