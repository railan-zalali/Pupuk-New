<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Selamat Datang</h2>
        <p class="text-white/60 text-sm">Masuk untuk mengelola sistem pupuk Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <!-- Email Address -->
        <div class="floating-input">
            <input id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder=" " />
            <label for="email">Alamat Email</label>
        </div>
        @error('email')
        <p class="text-red-300 text-xs mt-1 flex items-center animate-pulse">
            <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
        </p>
        @enderror

        <!-- Password -->
        <div class="space-y-1">
            <div class="floating-input" x-data="{ showPassword: false }">
                <input id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder=" "
                    class="pr-12" />
                <label for="password">Kata Sandi</label>

                <button type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/40 hover:text-white/80 transition-colors z-10 focus:outline-none">
                    <i class="ti" :class="showPassword ? 'ti-eye-off' : 'ti-eye'"></i>
                </button>
            </div>
            @error('password')
            <p class="text-red-300 text-xs mt-1 flex items-center animate-pulse">
                <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
            </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center group cursor-pointer">
                <div class="relative flex items-center">
                    <input id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="peer h-4 w-4 cursor-pointer appearance-none rounded border border-white/20 bg-white/5 checked:border-transparent checked:bg-indigo-500 transition-all">
                    <i class="ti ti-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[10px] text-white opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                </div>
                <span class="ml-2 text-sm text-white/60 group-hover:text-white/80 transition-colors">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-300 hover:text-indigo-200 transition-colors">
                Lupa kata sandi?
            </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit"
            :disabled="loading"
            class="w-full py-3.5 px-4 rounded-xl text-white font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 border border-transparent">
            <span x-show="!loading" class="flex items-center justify-center gap-2">
                Masuk Sekarang <i class="ti ti-arrow-right"></i>
            </span>
            <span x-show="loading" class="flex items-center justify-center gap-2" style="display: none;">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>

        <!-- Divider -->
        <!-- <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/10"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-transparent text-white/40">atau masuk dengan</span>
            </div>
        </div> -->

        <!-- Register Link -->
        <!-- <div class="text-center mt-6">
            <p class="text-white/50 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-white font-medium hover:text-indigo-300 transition-colors ml-1">
                    Daftar akun baru
                </a>
            </p>
        </div> -->
    </form>
</x-guest-layout>