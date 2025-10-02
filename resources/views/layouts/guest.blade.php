<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Sistem Manajemen Pupuk</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            
            .auth-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                background-size: 400% 400%;
                animation: gradientShift 15s ease infinite;
            }
            
            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            .glass-effect {
                backdrop-filter: blur(16px);
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .dark .glass-effect {
                background: rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen auth-bg flex items-center justify-center p-4">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></g></svg>');"></div>
            </div>

            <!-- Main Auth Container -->
            <div class="w-full max-w-md relative z-10">
                <!-- Logo and Branding -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/20 glass-effect mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain" />
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">{{ config('app.name', 'Laravel') }}</h1>
                    <p class="text-white/80 text-sm">Sistem Manajemen Pupuk & Pertanian</p>
                </div>

                <!-- Auth Card -->
                <div class="glass-effect rounded-2xl p-8 shadow-2xl">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center mt-6">
                    <p class="text-white/60 text-xs">
                        © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Semua hak dilindungi.
                    </p>
                </div>

                <!-- Dark Mode Toggle -->
                <div class="absolute top-4 right-4">
                    <button @click="darkMode = !darkMode" 
                        class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors text-white">
                        <i class="ti ti-sun text-lg" x-show="darkMode"></i>
                        <i class="ti ti-moon text-lg" x-show="!darkMode"></i>
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Check for system color scheme preference
            if (!localStorage.getItem('darkMode')) {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                localStorage.setItem('darkMode', prefersDark);
            }
        </script>
    </body>
</html>
