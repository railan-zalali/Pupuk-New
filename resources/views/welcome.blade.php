<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistem Manajemen Pupuk - Solusi Terpadu untuk Bisnis Pertanian</title>
        <meta name="description" content="Platform manajemen pupuk dan produk pertanian yang komprehensif. Kelola inventori, penjualan, pembelian, dan laporan dengan mudah dan efisien.">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Tabler Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons-sprite.svg">
        
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Fallback styles */
                body { font-family: 'Plus Jakarta Sans', sans-serif; }
            </style>
        @endif
        
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        
        <style>
            /* Hero Gradients */
            .hero-gradient-dark {
                background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e3a8a 100%);
            }
            .hero-gradient-light {
                background: linear-gradient(135deg, #3b82f6 0%, #6366f1 50%, #8b5cf6 100%);
            }
            
            /* Glass Effects */
            .glass-effect {
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }
            .glass-effect-light {
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.9);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            }
            .glass-effect-dark {
                background: rgba(15, 23, 42, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.15);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            }
            
            /* Feature Cards */
            .feature-card {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid transparent;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            .feature-card:hover {
                transform: translateY(-12px) scale(1.02);
            }
            
            /* Dark Mode Feature Cards */
            .feature-card-dark {
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .feature-card-dark:hover {
                background: rgba(15, 23, 42, 0.8);
                border-color: rgba(255, 255, 255, 0.2);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            }
            
            /* Light Mode Feature Cards */
            .feature-card-light {
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(0, 0, 0, 0.05);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }
            .feature-card-light:hover {
                background: rgba(255, 255, 255, 0.95);
                border-color: rgba(99, 102, 241, 0.2);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            }
            
            /* Animations */
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .animate-pulse-slow {
                animation: pulse-slow 4s ease-in-out infinite;
            }
            @keyframes pulse-slow {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
            
            /* Background Patterns */
            .bg-pattern-dark {
                background-image: 
                    radial-gradient(circle at 25px 25px, rgba(255,255,255,0.1) 2px, transparent 0),
                    radial-gradient(circle at 75px 75px, rgba(99, 102, 241, 0.1) 1px, transparent 0);
                background-size: 100px 100px, 50px 50px;
            }
            .bg-pattern-light {
                background-image: 
                    radial-gradient(circle at 25px 25px, rgba(255,255,255,0.6) 2px, transparent 0),
                    radial-gradient(circle at 75px 75px, rgba(59, 130, 246, 0.1) 1px, transparent 0);
                background-size: 100px 100px, 50px 50px;
            }
            
            /* Navigation */
            .nav-glass-light {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.06);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }
            .nav-glass-dark {
                background: rgba(15, 23, 42, 0.9);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            }
            
            /* Text Gradients */
            .text-gradient-light {
                background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .text-gradient-dark {
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            /* Button Styles */
            .btn-primary-light {
                background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            }
            .btn-primary-light:hover {
                background: linear-gradient(135deg, #2563eb 0%, #5b21b6 100%);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            }
            
            .btn-primary-dark {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            }
            .btn-primary-dark:hover {
                background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%);
                box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
            }
            
            /* Stats Cards */
            .stats-card-light {
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(0, 0, 0, 0.05);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }
            .stats-card-dark {
                background: rgba(15, 23, 42, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50" :class="darkMode ? 'nav-glass-dark' : 'nav-glass-light'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                        <span class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Sistem Manajemen Pupuk</span>
                    </div>
                    
                    <!-- Navigation Links & Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg transition-colors" :class="darkMode ? 'text-white hover:bg-white/10' : 'text-gray-700 hover:bg-gray-100'">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>
                        
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg transition-colors font-medium" :class="darkMode ? 'bg-white/20 text-white hover:bg-white/30' : 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200'">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 transition-colors" :class="darkMode ? 'text-white hover:text-gray-200' : 'text-gray-700 hover:text-gray-900'">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg transition-colors font-medium" :class="darkMode ? 'bg-white text-indigo-600 hover:bg-gray-100' : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                                Daftar Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="min-h-screen flex items-center relative overflow-hidden" :class="darkMode ? 'hero-gradient-dark bg-pattern-dark' : 'hero-gradient-light bg-pattern-light'">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-72 h-72 rounded-full blur-3xl animate-float" :class="darkMode ? 'bg-indigo-500/20' : 'bg-white/30'"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 rounded-full blur-3xl animate-float animate-pulse-slow" :class="darkMode ? 'bg-purple-500/15' : 'bg-white/20'" style="animation-delay: -3s;"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] rounded-full blur-3xl opacity-30" :class="darkMode ? 'bg-gradient-to-r from-blue-600/10 to-purple-600/10' : 'bg-gradient-to-r from-blue-400/20 to-purple-400/20'"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Hero Content -->
                    <div :class="darkMode ? 'text-white' : 'text-gray-900'">
                        <h1 class="text-5xl lg:text-6xl font-bold leading-tight mb-6">
                            Revolusi
                            <span :class="darkMode ? 'text-gradient-dark' : 'text-gradient-light'">
                                Manajemen Pupuk
                            </span>
                            untuk Bisnis Anda
                        </h1>
                        <p :class="darkMode ? 'text-xl text-gray-200 mb-8 leading-relaxed' : 'text-xl text-gray-700 mb-8 leading-relaxed'">
                            Platform terpadu yang mengoptimalkan seluruh aspek bisnis pupuk dan produk pertanian. 
                            Dari inventori hingga laporan, semua dalam satu sistem yang powerful dan mudah digunakan.
                        </p>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-8">
                            @guest
                                <a href="{{ route('register') }}" :class="darkMode ? 'px-8 py-4 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg text-center' : 'px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg text-center'">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Mulai Gratis Sekarang
                                    </span>
                                </a>
                                <a href="{{ route('login') }}" :class="darkMode ? 'px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white hover:text-indigo-600 transition-all text-center' : 'px-8 py-4 border-2 border-gray-900 text-gray-900 rounded-xl font-semibold hover:bg-gray-900 hover:text-white transition-all text-center'">
                                    Masuk ke Akun
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" :class="darkMode ? 'px-8 py-4 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg text-center' : 'px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg text-center'">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Buka Dashboard
                                    </span>
                                </a>
                            @endguest
                        </div>
                        
                        <!-- Stats -->
                        <div :class="darkMode ? 'grid grid-cols-3 gap-6 pt-8 border-t border-white/20' : 'grid grid-cols-3 gap-6 pt-8 border-t border-gray-900/30'">
                            <div class="text-center">
                                <div :class="darkMode ? 'text-3xl font-bold text-gradient-dark' : 'text-3xl font-bold text-gradient-light'">500+</div>
                                <div :class="darkMode ? 'text-sm text-gray-200' : 'text-sm text-gray-600'">Produk Terdaftar</div>
                            </div>
                            <div class="text-center">
                                <div :class="darkMode ? 'text-3xl font-bold text-gradient-dark' : 'text-3xl font-bold text-gradient-light'">99.9%</div>
                                <div :class="darkMode ? 'text-sm text-gray-200' : 'text-sm text-gray-600'">Uptime Sistem</div>
                            </div>
                            <div class="text-center">
                                <div :class="darkMode ? 'text-3xl font-bold text-gradient-dark' : 'text-3xl font-bold text-gradient-light'">24/7</div>
                                <div :class="darkMode ? 'text-sm text-gray-200' : 'text-sm text-gray-600'">Support</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hero Image/Illustration -->
                    <div class="relative">
                        <div class="rounded-3xl p-8 animate-float" :class="darkMode ? 'glass-effect-dark' : 'glass-effect-light'">
                            <div class="rounded-2xl p-6 space-y-4" :class="darkMode ? 'bg-white/10' : 'bg-gray-100/80'">
                                <!-- Mock Dashboard Preview -->
                                <div class="flex items-center justify-between">
                                    <div class="h-3 rounded w-24" :class="darkMode ? 'bg-white/30' : 'bg-gray-400'"></div>
                                    <div class="h-3 bg-yellow-400 rounded w-16"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="rounded-lg p-4 space-y-2" :class="darkMode ? 'bg-white/20' : 'bg-white/70'">
                                        <div class="h-2 rounded w-16" :class="darkMode ? 'bg-white/40' : 'bg-gray-500'"></div>
                                        <div class="h-6 rounded w-12" :class="darkMode ? 'bg-white/60' : 'bg-gray-700'"></div>
                                    </div>
                                    <div class="rounded-lg p-4 space-y-2" :class="darkMode ? 'bg-white/20' : 'bg-white/70'">
                                        <div class="h-2 rounded w-20" :class="darkMode ? 'bg-white/40' : 'bg-gray-500'"></div>
                                        <div class="h-6 rounded w-16" :class="darkMode ? 'bg-white/60' : 'bg-gray-700'"></div>
                                    </div>
                                </div>
                                <div class="rounded-lg p-4 space-y-2" :class="darkMode ? 'bg-white/20' : 'bg-white/70'">
                                    <div class="h-2 rounded w-32" :class="darkMode ? 'bg-white/40' : 'bg-gray-500'"></div>
                                    <div class="space-y-1">
                                        <div class="h-2 rounded" :class="darkMode ? 'bg-white/30' : 'bg-gray-400'"></div>
                                        <div class="h-2 rounded w-4/5" :class="darkMode ? 'bg-white/30' : 'bg-gray-400'"></div>
                                        <div class="h-2 rounded w-3/5" :class="darkMode ? 'bg-white/30' : 'bg-gray-400'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section :class="darkMode ? 'py-20 bg-gradient-to-br from-slate-900 via-gray-900 to-slate-800' : 'py-20 bg-gradient-to-br from-blue-50 via-white to-indigo-50'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 :class="darkMode ? 'text-4xl font-bold text-white mb-4' : 'text-4xl font-bold text-gray-900 mb-4'">
                        Fitur Unggulan Sistem
                    </h2>
                    <p :class="darkMode ? 'text-xl text-gray-300 max-w-3xl mx-auto' : 'text-xl text-gray-700 max-w-3xl mx-auto'">
                        Dilengkapi dengan berbagai fitur canggih yang dirancang khusus untuk mengoptimalkan operasional bisnis pupuk dan produk pertanian Anda.
                    </p>
                </div>
                
                <!-- Features Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1: Inventory Management -->
                    <div :class="darkMode ? 'feature-card feature-card-dark rounded-2xl p-8' : 'feature-card feature-card-light rounded-2xl p-8'">
                        <div :class="darkMode ? 'w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-6' : 'w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mb-6 shadow-md'">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 :class="darkMode ? 'text-xl font-semibold text-white mb-3' : 'text-xl font-semibold text-gray-800 mb-3'">Manajemen Inventori</h3>
                        <p :class="darkMode ? 'text-gray-300 mb-4' : 'text-gray-700 mb-4'">Kelola stok produk dengan sistem FIFO, tracking batch, dan notifikasi otomatis untuk produk yang akan expired.</p>
                        <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Sistem FIFO otomatis
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tracking batch produk
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Alert expired produk
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Feature 2: Sales Management -->
                    <div :class="darkMode ? 'feature-card feature-card-dark rounded-2xl p-8' : 'feature-card feature-card-light rounded-2xl p-8'">
                        <div :class="darkMode ? 'w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-6' : 'w-12 h-12 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center mb-6 shadow-md'">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 :class="darkMode ? 'text-xl font-semibold text-white mb-3' : 'text-xl font-semibold text-gray-800 mb-3'">Manajemen Penjualan</h3>
                        <p :class="darkMode ? 'text-gray-300 mb-4' : 'text-gray-700 mb-4'">Proses penjualan yang efisien dengan sistem kasir terintegrasi dan manajemen customer yang komprehensif.</p>
                        <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                POS terintegrasi
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Database customer
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Riwayat transaksi
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Feature 3: Purchase Management -->
                    <div :class="darkMode ? 'feature-card feature-card-dark rounded-2xl p-8' : 'feature-card feature-card-light rounded-2xl p-8'">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Manajemen Pembelian</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Kelola pembelian dari supplier dengan sistem yang terintegrasi dan tracking yang akurat.</p>
                        <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Database supplier
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Purchase order
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Penerimaan barang
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Feature 4: Reporting -->
                    <div :class="darkMode ? 'feature-card feature-card-dark rounded-2xl p-8' : 'feature-card feature-card-light rounded-2xl p-8'">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Laporan & Analitik</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Dapatkan insight bisnis dengan laporan komprehensif dan analitik real-time.</p>
                        <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Dashboard real-time
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Export ke Excel
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Analisis profit
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Feature 5: User Management -->
                    <div :class="darkMode ? 'feature-card feature-card-dark rounded-2xl p-8' : 'feature-card feature-card-light rounded-2xl p-8'">
                        <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Manajemen User</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Sistem role dan permission yang fleksibel untuk mengatur akses user sesuai kebutuhan.</p>
                        <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Multi-role system
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Permission control
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Activity logging
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Feature 6: Cash Book -->
                    <div :class="darkMode ? 'feature-card feature-card-dark rounded-2xl p-8' : 'feature-card feature-card-light rounded-2xl p-8'">
                        <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Kas & Keuangan</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Kelola arus kas dan keuangan bisnis dengan pencatatan yang akurat dan transparan.</p>
                        <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Cash flow tracking
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Laporan keuangan
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Profit analysis
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section :class="darkMode ? 'py-20 bg-gradient-to-br from-gray-900 via-slate-900 to-gray-800' : 'py-20 bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Benefits Content -->
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
                            Mengapa Memilih Sistem Kami?
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                            Dirancang khusus untuk bisnis pupuk dan produk pertanian dengan pemahaman mendalam tentang kebutuhan industri.
                        </p>
                        
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Efisiensi Operasional</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Otomatisasi proses bisnis mengurangi waktu dan kesalahan manual hingga 80%.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Kontrol Stok Akurat</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Sistem FIFO dan tracking batch memastikan rotasi stok yang optimal dan meminimalkan expired.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Insight Bisnis Real-time</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Dashboard dan laporan memberikan visibilitas penuh terhadap performa bisnis Anda.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Skalabilitas Tinggi</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Sistem dapat berkembang seiring pertumbuhan bisnis Anda tanpa batasan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Benefits Stats -->
                    <div class="grid grid-cols-2 gap-6">
                        <div :class="darkMode ? 'stats-card-dark rounded-2xl p-8 text-center' : 'stats-card-light rounded-2xl p-8 text-center'">
                            <div :class="darkMode ? 'text-4xl font-bold text-gradient-dark mb-2' : 'text-4xl font-bold text-gradient-light mb-2'">80%</div>
                            <div :class="darkMode ? 'text-gray-300' : 'text-gray-600'">Pengurangan Waktu Operasional</div>
                        </div>
                        <div :class="darkMode ? 'stats-card-dark rounded-2xl p-8 text-center' : 'stats-card-light rounded-2xl p-8 text-center'">
                            <div :class="darkMode ? 'text-4xl font-bold text-gradient-dark mb-2' : 'text-4xl font-bold text-gradient-light mb-2'">95%</div>
                            <div :class="darkMode ? 'text-gray-300' : 'text-gray-600'">Akurasi Stok</div>
                        </div>
                        <div :class="darkMode ? 'stats-card-dark rounded-2xl p-8 text-center' : 'stats-card-light rounded-2xl p-8 text-center'">
                            <div :class="darkMode ? 'text-4xl font-bold text-gradient-dark mb-2' : 'text-4xl font-bold text-gradient-light mb-2'">24/7</div>
                            <div :class="darkMode ? 'text-gray-300' : 'text-gray-600'">Monitoring Real-time</div>
                        </div>
                        <div :class="darkMode ? 'stats-card-dark rounded-2xl p-8 text-center' : 'stats-card-light rounded-2xl p-8 text-center'">
                            <div :class="darkMode ? 'text-4xl font-bold text-gradient-dark mb-2' : 'text-4xl font-bold text-gradient-light mb-2'">∞</div>
                            <div :class="darkMode ? 'text-gray-300' : 'text-gray-600'">Skalabilitas</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20" :class="darkMode ? 'hero-gradient' : 'hero-gradient-light'">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 :class="darkMode ? 'text-4xl font-bold text-white mb-6' : 'text-4xl font-bold text-gray-900 mb-6'">
                    Siap Mengoptimalkan Bisnis Pupuk Anda?
                </h2>
                <p :class="darkMode ? 'text-xl text-gray-200 mb-8' : 'text-xl text-gray-700 mb-8'">
                    Bergabunglah dengan ratusan bisnis yang telah merasakan manfaat sistem manajemen pupuk terpadu kami.
                </p>
                
                @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" :class="darkMode ? 'px-8 py-4 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg' : 'px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg'">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Mulai Gratis Sekarang
                            </span>
                        </a>
                        <a href="{{ route('login') }}" :class="darkMode ? 'px-8 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white hover:text-indigo-600 transition-all' : 'px-8 py-4 border-2 border-gray-900 text-gray-900 rounded-xl font-semibold hover:bg-gray-900 hover:text-white transition-all'">
                            Sudah Punya Akun? Masuk
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" :class="darkMode ? 'inline-flex items-center px-8 py-4 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg' : 'inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg'">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Buka Dashboard
                    </a>
                @endguest
                
                <div class="mt-8 text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                    ✓ Setup gratis ✓ Support 24/7 ✓ Tanpa kontrak jangka panjang
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 transition-colors duration-300" :class="darkMode ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                            <span class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'">Sistem Manajemen Pupuk</span>
                        </div>
                        <p class="mb-4" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                            Platform terpadu untuk mengelola bisnis pupuk dan produk pertanian dengan efisien dan akurat.
                        </p>
                        <div class="text-sm" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">Fitur Utama</h3>
                        <ul class="space-y-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                            <li>Manajemen Inventori</li>
                            <li>Sistem Penjualan</li>
                            <li>Manajemen Pembelian</li>
                            <li>Laporan & Analitik</li>
                        </ul>
                    </div>
                    
                    <!-- Support -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">Support</h3>
                        <ul class="space-y-2" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                            <li>Dokumentasi</li>
                            <li>Tutorial</li>
                            <li>FAQ</li>
                            <li>Kontak Support</li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 text-center border-t" :class="darkMode ? 'border-gray-800 text-gray-400' : 'border-gray-300 text-gray-500'">
                    <p>&copy; {{ date('Y') }} Sistem Manajemen Pupuk. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
