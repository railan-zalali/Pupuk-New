<x-guest-layout>
    <!-- Page Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Buat Akun Baru</h2>
        <p class="text-white/70 text-sm">Bergabunglah dengan sistem manajemen pupuk kami</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-white/90">
                <i class="ti ti-user mr-2"></i>Nama Lengkap
            </label>
            <div class="relative">
                <input id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap Anda"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200 backdrop-blur-sm" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="ti ti-user-circle text-white/40"></i>
                </div>
            </div>
            @error('name')
                <p class="text-red-300 text-xs mt-1 flex items-center">
                    <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

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
                    autocomplete="new-password"
                    placeholder="Buat kata sandi yang kuat"
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

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-white/90">
                <i class="ti ti-lock-check mr-2"></i>Konfirmasi Kata Sandi
            </label>
            <div class="relative" x-data="{ showConfirmPassword: false }">
                <input id="password_confirmation" 
                    :type="showConfirmPassword ? 'text' : 'password'" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi Anda"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-200 backdrop-blur-sm pr-12" />
                <button type="button" 
                    @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/40 hover:text-white/60 transition-colors">
                    <i class="ti ti-eye" x-show="!showConfirmPassword"></i>
                    <i class="ti ti-eye-off" x-show="showConfirmPassword"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="text-red-300 text-xs mt-1 flex items-center">
                    <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start space-x-3">
            <input id="terms" 
                type="checkbox" 
                required
                class="w-4 h-4 text-indigo-600 bg-white/10 border-white/20 rounded focus:ring-indigo-500 focus:ring-2 mt-1">
            <label for="terms" class="text-sm text-white/70 leading-relaxed">
                Saya menyetujui 
                <a href="#" class="text-white hover:text-white/80 underline">Syarat & Ketentuan</a> 
                dan 
                <a href="#" class="text-white hover:text-white/80 underline">Kebijakan Privasi</a>
                yang berlaku.
            </label>
        </div>

        <!-- Register Button -->
        <button type="submit" 
            class="w-full bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 backdrop-blur-sm border border-white/30 hover:border-white/40 focus:outline-none focus:ring-2 focus:ring-white/30 flex items-center justify-center space-x-2">
            <i class="ti ti-user-plus"></i>
            <span>Buat Akun</span>
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

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-white/70 text-sm">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" 
                    class="text-white font-medium hover:text-white/80 transition-colors ml-1">
                    Masuk sekarang
                </a>
            </p>
        </div>
    </form>

    <!-- Password Requirements -->
    <div class="mt-8 p-4 bg-white/5 rounded-xl border border-white/10">
        <div class="flex items-start space-x-3">
            <i class="ti ti-shield-check text-white/60 mt-0.5"></i>
            <div>
                <h4 class="text-white/80 font-medium text-sm mb-2">Persyaratan Kata Sandi</h4>
                <ul class="text-white/60 text-xs space-y-1">
                    <li class="flex items-center"><i class="ti ti-check text-green-400 mr-2"></i>Minimal 8 karakter</li>
                    <li class="flex items-center"><i class="ti ti-check text-green-400 mr-2"></i>Kombinasi huruf dan angka</li>
                    <li class="flex items-center"><i class="ti ti-check text-green-400 mr-2"></i>Setidaknya satu huruf besar</li>
                </ul>
            </div>
        </div>
    </div>
</x-guest-layout>
