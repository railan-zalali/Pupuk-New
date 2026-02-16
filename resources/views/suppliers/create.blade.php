<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-truck-loading text-xl"></i>
                    </div>
                    {{ __('Tambah Supplier') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Input data supplier baru untuk sistem
                </p>
            </div>

            <a href="{{ route('suppliers.index') }}" class="btn-secondary flex items-center gap-2">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 animate-fade-in-up">
            <div class="flex items-start gap-3">
                <i class="ti ti-alert-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Terjadi kesalahan input:</h3>
                    <ul class="mt-1 text-sm text-red-700 dark:text-red-300 list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="card animate-fade-in-up delay-100">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 mb-6 border-b border-gray-100 dark:border-gray-700/50 pb-4">
                    <i class="ti ti-info-circle text-emerald-500"></i> Informasi Supplier
                </h3>

                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Supplier -->
                        <div class="col-span-1">
                            <x-input-label for="name" value="Nama Supplier" class="mb-1.5" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-building-store text-gray-400"></i>
                                </div>
                                <x-text-input id="name" name="name" type="text" class="pl-10 w-full" :value="old('name')" required placeholder="PT. Pupuk Maju Jaya" />
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div class="col-span-1">
                            <x-input-label for="phone" value="Nomor Telepon" class="mb-1.5" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-phone text-gray-400"></i>
                                </div>
                                <x-text-input id="phone" name="phone" type="text" class="pl-10 w-full" :value="old('phone')" required placeholder="081234567890" />
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="col-span-1 md:col-span-2">
                            <x-input-label for="address" value="Alamat Lengkap" class="mb-1.5" />
                            <div class="relative">
                                <textarea id="address" name="address" rows="3" class="input-field w-full pl-10 pt-2.5" required placeholder="Jl. Raya Industri No. 123...">{{ old('address') }}</textarea>
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <i class="ti ti-map-pin text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-span-1 md:col-span-2">
                            <x-input-label for="description" value="Keterangan / Deskripsi (Opsional)" class="mb-1.5" />
                            <div class="relative">
                                <textarea id="description" name="description" rows="3" class="input-field w-full pl-10 pt-2.5" placeholder="Catatan tambahan...">{{ old('description') }}</textarea>
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <i class="ti ti-notes text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700/50">
                        <button type="button" onclick="window.history.back()" class="btn-ghost">Batal</button>
                        <button type="submit" class="btn-primary">
                            <i class="ti ti-device-floppy"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>