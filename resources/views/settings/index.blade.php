<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Pengaturan Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6 sm:p-8">
                <header class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Informasi Toko') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Informasi ini akan ditampilkan pada nota dan dokumen lainnya.') }}
                    </p>
                </header>

                @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3 animate-fade-in-up">
                    <i class="ti ti-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('settings.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Toko -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Logo Toko') }}</label>
                            <div class="flex items-start gap-6">
                                @if($settings->logo_path)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo Toko" class="h-24 w-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <div class="absolute inset-0 bg-black/50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                                        Current
                                    </div>
                                </div>
                                @endif

                                <div class="flex-1">
                                    <label class="block w-full cursor-pointer">
                                        <input type="file" name="logo" id="logo" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2.5 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-emerald-50 file:text-emerald-700
                                            hover:file:bg-emerald-100
                                            dark:file:bg-emerald-900/30 dark:file:text-emerald-300
                                            cursor-pointer focus:outline-none 
                                        " />
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Format: JPG, PNG. Maksimal: 2MB.
                                    </p>
                                    @error('logo')
                                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nama Toko -->
                        <div class="relative">
                            <input type="text" id="store_name" name="store_name"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder=" "
                                value="{{ old('store_name', $settings->store_name) }}"
                                required />
                            <label for="store_name"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Nama Toko') }}
                            </label>
                            @error('store_name')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="relative">
                            <input type="text" id="store_phone" name="store_phone"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder=" "
                                value="{{ old('store_phone', $settings->store_phone) }}" />
                            <label for="store_phone"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Nomor Telepon') }}
                            </label>
                            @error('store_phone')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="relative md:col-span-2">
                            <input type="email" id="store_email" name="store_email"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder=" "
                                value="{{ old('store_email', $settings->store_email) }}" />
                            <label for="store_email"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Email Toko') }}
                            </label>
                            @error('store_email')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Toko -->
                        <div class="relative md:col-span-2">
                            <textarea id="store_address" name="store_address" rows="3"
                                class="peer input-primary w-full pb-2 pt-6 px-4 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 placeholder-transparent"
                                placeholder=" ">{{ old('store_address', $settings->store_address) }}</textarea>
                            <label for="store_address"
                                class="absolute left-4 top-4 text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-3 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 pointer-events-none">
                                {{ __('Alamat Toko') }}
                            </label>
                            @error('store_address')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="btn-primary">
                            {{ __('Simpan Pengaturan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>