<x-guest-layout>
    <!-- Page Header -->
    <div class="text-center mb-8">
        <div class="mx-auto w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mb-6 animate-pulse">
            <i class="ti ti-clock-hour-4 text-yellow-400 text-4xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Menunggu Persetujuan</h2>
        <p class="text-white/70 text-sm">Akun Anda sedang dalam antrean verifikasi</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-300 text-sm flex items-center animate-fade-in-up">
        <i class="ti ti-check-circle mr-2 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Information Card -->
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 mb-8 shadow-inner">
        <div class="space-y-5">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <i class="ti ti-info-circle text-blue-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm mb-1">Status Peninjauan</h3>
                    <p class="text-white/60 text-xs leading-relaxed">
                        Administrator kami sedang meninjau detail pendaftaran Anda. Proses ini biasanya memakan waktu 1-24 jam kerja.
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <i class="ti ti-mail-forward text-purple-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm mb-1">Notifikasi</h3>
                    <p class="text-white/60 text-xs leading-relaxed">
                        Anda akan menerima email otomatis segera setelah akun Anda diaktifkan.
                    </p>
                </div>
            </div>

            <div class="w-full border-t border-white/10 my-2"></div>

            <div class="text-center">
                <p class="text-white/40 text-[10px] uppercase tracking-wider font-semibold">Butuh bantuan mendesak?</p>
                <a href="#" class="text-indigo-400 hover:text-indigo-300 text-xs mt-1 inline-flex items-center transition-colors">
                    <i class="ti ti-brand-whatsapp mr-1"></i> Hubungi Admin
                </a>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-4">
        <a href="{{ route('login') }}"
            class="w-full block text-center bg-white/10 hover:bg-white/20 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 backdrop-blur-sm border border-white/20 hover:border-white/30 focus:outline-none focus:ring-2 focus:ring-white/20 group">
            <span class="flex items-center justify-center gap-2">
                <i class="ti ti-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Login
            </span>
        </a>

        <div class="flex justify-center">
            <p class="text-white/40 text-xs">
                Sudah disetujui? <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 underline underline-offset-2">Coba Login</a>
            </p>
        </div>
    </div>
</x-guest-layout>