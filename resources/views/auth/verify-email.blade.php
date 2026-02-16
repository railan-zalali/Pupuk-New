<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-emerald-500/20 rounded-full flex items-center justify-center mb-4 animate-bounce">
            <i class="ti ti-mail-check text-emerald-400 text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Verifikasi Email Anda</h2>
        <div class="text-white/70 text-sm leading-relaxed">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda.') }}
        </div>
        <div class="text-white/60 text-xs mt-2 italic">
            {{ __('Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang baru.') }}
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
    <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-xl text-emerald-300 text-sm flex items-start animate-fade-in-up">
        <i class="ti ti-circle-check mr-2 mt-0.5 flex-shrink-0"></i>
        <span>{{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}</span>
    </div>
    @endif

    <div class="mt-8 flex flex-col space-y-4">
        <form method="POST" action="{{ route('verification.send') }}" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <button type="submit"
                :disabled="loading"
                class="w-full py-3.5 px-4 rounded-xl text-white font-semibold shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 border border-transparent">
                <span x-show="!loading" class="flex items-center justify-center gap-2">
                    <i class="ti ti-send"></i> {{ __('Kirim Ulang Email Verifikasi') }}
                </span>
                <span x-show="loading" class="flex items-center justify-center gap-2" style="display: none;">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-white/60 hover:text-white text-sm transition-colors flex items-center justify-center gap-2 mx-auto">
                <i class="ti ti-logout"></i> {{ __('Keluar / Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>