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
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
            position: relative;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: 0;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out alternate;
        }

        .blob-1 {
            top: 10%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: linear-gradient(to right, #4f46e5, #818cf8);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation-delay: 0s;
        }

        .blob-2 {
            bottom: 20%;
            right: 10%;
            width: 350px;
            height: 350px;
            background: linear-gradient(to right, #db2777, #f472b6);
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            animation-delay: -5s;
        }

        .blob-3 {
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            background: linear-gradient(to right, #059669, #34d399);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation-delay: -2s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            100% {
                transform: translate(20px, 20px) rotate(10deg);
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            /* Very subtle white */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        /* Floating Input Styles */
        .floating-input {
            position: relative;
        }

        .floating-input input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1rem 1rem 0.5rem 1rem;
            width: 100%;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.2s;
        }

        .floating-input input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.05);
        }

        .floating-input label {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
            pointer-events: none;
            transition: all 0.2s;
        }

        .floating-input input:focus~label,
        .floating-input input:not(:placeholder-shown)~label {
            top: 0.25rem;
            left: 1rem;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-gradient::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-gradient:hover::after {
            left: 100%;
        }
    </style>
</head>

<body class="antialiased font-sans text-gray-900 dark:text-gray-100">
    <div class="min-h-screen auth-bg flex items-center justify-center p-4 relative">
        <!-- Animated Blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <!-- Pattern Overlay -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); mask-image: linear-gradient(to bottom, transparent, black, transparent);"></div>

        <!-- Main Auth Container -->
        <div class="w-full max-w-md relative z-10 animate-fade-in-up">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 glass-card mb-4 group transition-transform hover:scale-105 duration-300">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain drop-shadow-lg" />
                </a>
                <h1 class="text-2xl font-bold text-white tracking-tight">{{ config('app.name', 'Laravel') }}</h1>
                <p class="text-white/60 text-sm mt-1 font-medium">Sistem Manajemen Pupuk & Pertanian</p>
            </div>

            <!-- Auth Card -->
            <div class="glass-card rounded-3xl p-8 shadow-2xl relative overflow-hidden">
                <!-- Top Shine -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-white/40 text-xs">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. <span class="hidden sm:inline">All rights reserved.</span>
                </p>
            </div>
        </div>

        <!-- Dark Mode Toggle (Optional, usually Auth pages are unique theme but good to have) -->
        <!-- 
            <div class="absolute top-6 right-6 z-20">
                <button @click="darkMode = !darkMode" 
                    class="p-2.5 rounded-full bg-white/5 hover:bg-white/10 transition-colors text-white/70 hover:text-white backdrop-blur-sm border border-white/10">
                    <i class="ti ti-sun text-xl" x-show="darkMode"></i>
                    <i class="ti ti-moon text-xl" x-show="!darkMode"></i>
                </button>
            </div> 
            -->
    </div>
</body>

</html>