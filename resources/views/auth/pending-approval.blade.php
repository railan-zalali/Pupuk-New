<x-guest-layout>
    <!-- Page Header -->
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mb-4">
            <i class="ti ti-clock text-yellow-500 text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Menunggu Persetujuan</h2>
        <p class="text-white/70 text-sm">Akun Anda sedang menunggu persetujuan dari administrator</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-300 text-sm flex items-center">
            <i class="ti ti-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Information Card -->
    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-6 mb-6">
        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="ti ti-info-circle text-blue-400 text-lg mt-0.5"></i>
                </div>
                <div>
                    <h3 class="text-white font-medium mb-1">Apa yang terjadi selanjutnya?</h3>
                    <p class="text-white/70 text-sm">
                        Administrator akan meninjau pendaftaran Anda dan memberikan persetujuan dalam waktu 1-2 hari kerja.
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="ti ti-mail text-blue-400 text-lg mt-0.5"></i>
                </div>
                <div>
                    <h3 class="text-white font-medium mb-1">Notifikasi Email</h3>
                    <p class="text-white/70 text-sm">
                        Anda akan menerima email konfirmasi setelah akun Anda disetujui atau ditolak.
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="ti ti-help-circle text-blue-400 text-lg mt-0.5"></i>
                </div>
                <div>
                    <h3 class="text-white font-medium mb-1">Butuh Bantuan?</h3>
                    <p class="text-white/70 text-sm">
                        Jika Anda memiliki pertanyaan, silakan hubungi administrator sistem.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3">
        <a href="{{ route('login') }}" 
            class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 backdrop-blur-sm border border-white/30 hover:border-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 flex items-center justify-center space-x-2">
            <i class="ti ti-login"></i>
            <span>Kembali ke Login</span>
        </a>

        <div class="text-center">
            <span class="text-white/50 text-sm">Sudah disetujui? </span>
            <a href="{{ route('login') }}" class="text-white hover:text-white/80 underline text-sm">
                Masuk sekarang
            </a>
        </div>
    </div>
</x-guest-layout>