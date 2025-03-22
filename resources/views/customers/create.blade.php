<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('Tambah Pelanggan Baru') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">Isi informasi lengkap untuk menambah pelanggan baru</p>
            </div>

            <!-- Import Form -->
            <div class="w-full sm:w-auto bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-3">
                    @csrf
                    <div>
                        <x-input-label for="excel_file" :value="__('Import Data Pelanggan (Excel)')" class="text-sm font-medium text-gray-700" />
                        <div class="mt-1 flex items-center">
                            <label for="excel_file" class="relative cursor-pointer">
                                <div
                                    class="flex items-center space-x-2 px-3 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-md hover:bg-indigo-100 transition-colors duration-200">
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
                            <span id="file-name" class="ml-3 text-sm text-gray-500">Belum ada file dipilih</span>
                        </div>
                        <x-input-error :messages="$errors->get('excel_file')" class="mt-2" />
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
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    @endpush

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('customers.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Informasi Dasar -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <h3
                                class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informasi Dasar
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <x-input-label for="nik" :value="__('NIK')" class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                        </div>
                                        <x-text-input id="nik" name="nik" type="number"
                                            class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :value="old('nik')" placeholder="Masukkan NIK" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="nama" :value="__('Nama')" class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="nama" name="nama" type="text"
                                            class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :value="old('nama')" placeholder="Masukkan nama lengkap" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="alamat" :value="__('Alamat')"
                                        class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <textarea id="alamat" name="alamat" rows="3"
                                            class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                    </div>
                                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Wilayah -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <h3
                                class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Wilayah
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <x-input-label for="provinsi" :value="__('Provinsi')"
                                        class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                        </div>
                                        <select id="provinsi" name="provinsi_id"
                                            class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 select2"
                                            required>
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                        <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                                    </div>
                                    <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kabupaten" :value="__('Kabupaten')"
                                        class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <select id="kabupaten" name="kabupaten_id"
                                            class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 select2"
                                            required disabled>
                                            <option value="">Pilih Kabupaten</option>
                                        </select>
                                        <input type="hidden" name="kabupaten_nama" id="kabupaten_nama">
                                    </div>
                                    <x-input-error :messages="$errors->get('kabupaten_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kecamatan" :value="__('Kecamatan')"
                                        class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <select id="kecamatan" name="kecamatan_id"
                                            class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 select2"
                                            required disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                        <input type="hidden" name="kecamatan_nama" id="kecamatan_nama">
                                    </div>
                                    <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="desa" :value="__('Desa')"
                                        class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <select id="desa" name="desa_id"
                                            class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 select2"
                                            required disabled>
                                            <option value="">Pilih Desa</option>
                                        </select>
                                        <input type="hidden" name="desa_nama" id="desa_nama">
                                    </div>
                                    <x-input-error :messages="$errors->get('desa_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="window.history.back()"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Batal
                                </div>
                            </button>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Simpan Pelanggan
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Gagal memuat data provinsi:", textStatus, errorThrown);
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
