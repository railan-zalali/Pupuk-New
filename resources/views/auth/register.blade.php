<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white mb-2 tracking-tight">Buat Akun</h2>
        <p class="text-white/60 text-sm">Mulai kelola bisnis pertanian Anda</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ loading: false, terms: false }" @submit="if(!terms) { $event.preventDefault(); return; } loading = true">
        @csrf

        <!-- Name -->
        <div class="floating-input">
            <input id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder=" " />
            <label for="name">Nama Lengkap</label>
        </div>
        @error('name')
        <p class="text-red-300 text-xs mt-1 flex items-center animate-pulse">
            <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
        </p>
        @enderror

        <!-- Email Address -->
        <div class="floating-input">
            <input id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
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
                    autocomplete="new-password"
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

        <!-- Confirm Password -->
        <div class="space-y-1">
            <div class="floating-input" x-data="{ showConfirmPassword: false }">
                <input id="password_confirmation"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder=" "
                    class="pr-12" />
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>

                <button type="button"
                    @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/40 hover:text-white/80 transition-colors z-10 focus:outline-none">
                    <i class="ti" :class="showConfirmPassword ? 'ti-eye-off' : 'ti-eye'"></i>
                </button>
            </div>
            @error('password_confirmation')
            <p class="text-red-300 text-xs mt-1 flex items-center animate-pulse">
                <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
            </p>
            @enderror
        </div>

        <!-- Terms -->
        <div class="flex items-start space-x-3 pt-2">
            <div class="flex items-center h-5">
                <input id="terms"
                    type="checkbox"
                    name="terms"
                    x-model="terms"
                    required
                    class="w-4 h-4 rounded border-white/20 bg-white/5 checked:bg-emerald-500 checked:border-transparent focus:ring-emerald-500 focus:ring-offset-0 transition-colors cursor-pointer">
            </div>
            <label for="terms" class="text-xs text-white/60 leading-relaxed cursor-pointer select-none">
                Saya menyetujui <a href="#" class="text-white hover:underline decoration-white/50 underline-offset-2">Syarat & Ketentuan</a> dan <a href="#" class="text-white hover:underline decoration-white/50 underline-offset-2">Kebijakan Privasi</a> yang berlaku.
            </label>
        </div>

        <!-- Register Button -->
        <button type="submit"
            :disabled="loading || !terms"
            class="w-full py-3.5 px-4 rounded-xl text-white font-semibold shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 border border-transparent">
            <span x-show="!loading" class="flex items-center justify-center gap-2">
                <i class="ti ti-user-plus"></i> Buat Akun
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
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/10"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase tracking-wider font-semibold">
                <span class="px-3 bg-transparent text-white/30">Sudah punya akun?</span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full py-3 px-4 rounded-xl text-white/80 font-medium border border-white/20 hover:bg-white/5 hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/20">
                Masuk ke Akun
            </a>
        </div>
    </form>
</x-guest-layout>