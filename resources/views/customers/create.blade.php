<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('Tambah Pelanggan Baru') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Isi informasi lengkap untuk menambah pelanggan
                    baru</p>
            </div>

            <!-- Import Form -->
            <div
                class="w-full sm:w-auto bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-3">
                    @csrf
                    <div>
                        <x-input-label for="excel_file" :value="__('Import Data Pelanggan (Excel)')"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <div class="mt-1 flex items-center">
                            <label for="excel_file" class="relative cursor-pointer">
                                <div
                                    class="flex items-center space-x-2 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-800/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3-3m0 0l3 3m-3-3v8" />
                                    </svg>
                                    <span class="text-sm font-medium">Pilih File Excel</span>
                                </div>
                                <input id="excel_file" name="excel_file" type="file" accept=".xlsx,.xls"
                                    class="sr-only" required />
                            </label>
                            <span id="file-name" class="ml-3 text-sm text-gray-500 dark:text-gray-400">Belum ada file
                                dipilih</span>
                        </div>
                        <x-input-error :messages="$errors->get('excel_file')" class="mt-2" />
                        <div class="mt-2">
                            <a href="{{ route('customers.template.download') }}"
                                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Template Excel
                            </a>
                        </div>
                    </div>

                    <div>
                        <x-primary-button type="submit" class="w-full justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                            </svg>
                            {{ __('Import Pelanggan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="rounded-lg bg-red-50 dark:bg-red-900/50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.293-4.293a1 1 0 011.414-1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 001.414 1.414L10 11.414l1.293 1.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Terdapat beberapa kesalahan:</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Content -->
        <div
            class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 space-y-6">
                <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Personal Info Section -->
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="nik" :value="__('NIK')" />
                                <x-text-input id="nik" name="nik" type="number" class="mt-1 block w-full"
                                    :value="old('nik')" required />
                                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nama" :value="__('Nama')" />
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                                    :value="old('nama')" required />
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Informasi Alamat
                        </h3>

                        <div class="space-y-4">
                            <div class="md:col-span-2">
                                <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
                                <textarea id="alamat" name="alamat" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">{{ old('alamat') }}</textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Provinsi -->
                                <div
                                    class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                    <x-input-label for="provinsi" :value="__('Provinsi')" />
                                    <select id="provinsi" name="provinsi_id"
                                        class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50"
                                        required>
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                                    <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
                                </div>

                                <!-- Kabupaten -->
                                <div
                                    class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                    <x-input-label for="kabupaten" :value="__('Kabupaten')" />
                                    <select id="kabupaten" name="kabupaten_id"
                                        class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50"
                                        required disabled>
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                    <input type="hidden" name="kabupaten_nama" id="kabupaten_nama">
                                    <x-input-error :messages="$errors->get('kabupaten_id')" class="mt-2" />
                                </div>

                                <!-- Kecamatan -->
                                <div
                                    class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                    <x-input-label for="kecamatan" :value="__('Kecamatan')" />
                                    <select id="kecamatan" name="kecamatan_id"
                                        class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50"
                                        required disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    <input type="hidden" name="kecamatan_nama" id="kecamatan_nama">
                                    <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
                                </div>

                                <!-- Desa -->
                                <div
                                    class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                    <x-input-label for="desa" :value="__('Desa')" />
                                    <select id="desa" name="desa_id"
                                        class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50"
                                        required disabled>
                                        <option value="">Pilih Desa</option>
                                    </select>
                                    <input type="hidden" name="desa_nama" id="desa_nama">
                                    <x-input-error :messages="$errors->get('desa_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Batal
                        </button>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Pelanggan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Input file handler
                const fileInput = document.getElementById('excel_file');
                const fileNameDisplay = document.getElementById('file-name');

                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        fileNameDisplay.textContent = this.files[0].name;
                    } else {
                        fileNameDisplay.textContent = 'Belum ada file dipilih';
                    }
                });

                // Initialize Select2
                $('.select2').select2({
                    theme: 'classic',
                    width: '100%'
                });

                // Load Provinsi
                $.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json', function(provinces) {
                    provinces.forEach(function(province) {
                        $('#provinsi').append(new Option(province.name, province.id));
                    });
                });

                // Event Provinsi Change
                $('#provinsi').on('change', function() {
                    $('#kabupaten').empty().append(new Option('Pilih Kabupaten', '')).prop('disabled', true);
                    $('#kecamatan').empty().append(new Option('Pilih Kecamatan', '')).prop('disabled', true);
                    $('#desa').empty().append(new Option('Pilih Desa', '')).prop('disabled', true);

                    if (this.value) {
                        $('#provinsi_nama').val($('#provinsi option:selected').text());
                        loadKabupaten(this.value);
                    }
                });

                // Event Kabupaten Change
                $('#kabupaten').on('change', function() {
                    $('#kecamatan').empty().append(new Option('Pilih Kecamatan', '')).prop('disabled', true);
                    $('#desa').empty().append(new Option('Pilih Desa', '')).prop('disabled', true);

                    if (this.value) {
                        $('#kabupaten_nama').val($('#kabupaten option:selected').text());
                        loadKecamatan(this.value);
                    }
                });

                // Event Kecamatan Change
                $('#kecamatan').on('change', function() {
                    $('#desa').empty().append(new Option('Pilih Desa', '')).prop('disabled', true);

                    if (this.value) {
                        $('#kecamatan_nama').val($('#kecamatan option:selected').text());
                        loadDesa(this.value);
                    }
                });

                // Event Desa Change
                $('#desa').on('change', function() {
                    if (this.value) {
                        $('#desa_nama').val($('#desa option:selected').text());
                    }
                });

                function loadKabupaten(provinceId) {
                    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`, function(
                        regencies) {
                        $('#kabupaten').prop('disabled', false);
                        regencies.forEach(function(regency) {
                            $('#kabupaten').append(new Option(regency.name, regency.id));
                        });
                    });
                }

                function loadKecamatan(regencyId) {
                    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`, function(
                        districts) {
                        $('#kecamatan').prop('disabled', false);
                        districts.forEach(function(district) {
                            $('#kecamatan').append(new Option(district.name, district.id));
                        });
                    });
                }

                function loadDesa(districtId) {
                    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`, function(
                        villages) {
                        $('#desa').prop('disabled', false);
                        villages.forEach(function(village) {
                            $('#desa').append(new Option(village.name, village.id));
                        });
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
