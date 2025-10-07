<x-guest-layout>
    <!-- Page Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Selamat Datang Kembali</h2>
        <p class="text-white/70 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-white/90">
                <i class="ti ti-mail mr-2"></i>Alamat Email
            </label>
            <div class="relative">
                <input id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="Masukkan email Anda"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200 backdrop-blur-sm" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="ti ti-at text-white/40"></i>
                </div>
            </div>
            @error('email')
                <p class="text-red-300 text-xs mt-1 flex items-center">
                    <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-white/90">
                <i class="ti ti-lock mr-2"></i>Kata Sandi
            </label>
            <div class="relative" x-data="{ showPassword: false }">
                <input id="password" 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi Anda"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200 backdrop-blur-sm pr-12" />
                <button type="button" 
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/40 hover:text-white/60 transition-colors">
                    <i class="ti ti-eye" x-show="!showPassword"></i>
                    <i class="ti ti-eye-off" x-show="showPassword"></i>
                </button>
            </div>
            @error('password')
                <p class="text-red-300 text-xs mt-1 flex items-center">
                    <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" 
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 text-indigo-600 bg-white/10 border-white/20 rounded focus:ring-indigo-500 focus:ring-2">
                <span class="ml-2 text-sm text-white/70">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                    class="text-sm text-white/70 hover:text-white transition-colors">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" 
            class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 backdrop-blur-sm border border-white/30 hover:border-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 flex items-center justify-center space-x-2">
            <i class="ti ti-login"></i>
            <span>Masuk ke Akun</span>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/20"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-transparent text-white/60">atau</span>
            </div>
        </div>

        <!-- Register Link
        <div class="text-center">
            <p class="text-white/70 text-sm">
                Belum memiliki akun?
                <a href="{{ route('register') }}" 
                    class="text-white font-medium hover:text-white/80 transition-colors ml-1">
                    Daftar sekarang
                </a>
            </p>
        </div> -->
    </form>

    <!-- Additional Info -->
    <div class="mt-8 p-4 bg-white/5 rounded-xl border border-white/10">
        <div class="flex items-start space-x-3">
            <i class="ti ti-info-circle text-white/60 mt-0.5"></i>
            <div>
                <h4 class="text-white/80 font-medium text-sm">Sistem Manajemen Pupuk</h4>
                <p class="text-white/60 text-xs mt-1">
                    Platform terintegrasi untuk mengelola penjualan, pembelian, dan inventori produk pupuk & pertanian.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
