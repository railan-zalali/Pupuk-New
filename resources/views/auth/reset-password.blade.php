<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Reset Kata Sandi</h2>
        <p class="text-white/60 text-sm">
            Buat kata sandi baru untuk akun Anda
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="floating-input">
            <input id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
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
        <div class="floating-input" x-data="{ showPassword: false }">
            <input id="password"
                :type="showPassword ? 'text' : 'password'"
                name="password"
                required
                autocomplete="new-password"
                placeholder=" "
                class="pr-12" />
            <label for="password">Kata Sandi Baru</label>

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

        <!-- Confirm Password -->
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

        <div class="flex items-center justify-end mt-4">
            <button type="submit"
                :disabled="loading"
                class="w-full py-3.5 px-4 rounded-xl text-white font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 border border-transparent">
                <span x-show="!loading" class="flex items-center justify-center gap-2">
                    <i class="ti ti-refresh"></i> Reset Password
                </span>
                <span x-show="loading" class="flex items-center justify-center gap-2" style="display: none;">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </form>
</x-guest-layout>