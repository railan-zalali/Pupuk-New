<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mb-4">
            <i class="ti ti-lock-access text-red-400 text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Konfirmasi Kata Sandi</h2>
        <p class="text-white/60 text-sm">
            {{ __('Ini adalah area aman. Harap konfirmasi kata sandi Anda sebelum melanjutkan.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <!-- Password -->
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

        <div class="flex justify-end mt-4">
            <button type="submit"
                :disabled="loading"
                class="w-full py-3.5 px-4 rounded-xl text-white font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 border border-transparent">
                <span x-show="!loading" class="flex items-center justify-center gap-2">
                    <i class="ti ti-check"></i> {{ __('Konfirmasi') }}
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