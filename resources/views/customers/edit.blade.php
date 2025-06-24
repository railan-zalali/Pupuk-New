<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Edit Pelanggan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Edit informasi dan data pelanggan</p>
            </div>
        </div>
        @if (session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-900/50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400 dark:text-green-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        @push('styles')
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        @endpush

        <!-- Edit Form -->
        <div
            class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 space-y-6">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 mb-6">
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
                                        :value="old('nik', $customer->nik)" required />
                                    <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="nama" :value="__('Nama')" />
                                    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                                        :value="old('nama', $customer->nama)" required />
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
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300">{{ old('alamat', $customer->alamat) }}</textarea>
                                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Provinsi -->
                                    <div
                                        class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                        <x-input-label for="provinsi" :value="__('Provinsi')" />
                                        <select id="provinsi" name="provinsi_id"
                                            class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                                            required>
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                        <input type="hidden" name="provinsi_nama" id="provinsi_nama"
                                            value="{{ old('provinsi_nama', $customer->provinsi_nama) }}">
                                        <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
                                    </div>

                                    <!-- Kabupaten -->
                                    <div
                                        class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                        <x-input-label for="kabupaten" :value="__('Kabupaten')" />
                                        <select id="kabupaten" name="kabupaten_id"
                                            class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                                            required disabled>
                                            <option value="">Pilih Kabupaten</option>
                                        </select>
                                        <input type="hidden" name="kabupaten_nama" id="kabupaten_nama"
                                            value="{{ old('kabupaten_nama', $customer->kabupaten_nama) }}">
                                        <x-input-error :messages="$errors->get('kabupaten_id')" class="mt-2" />
                                    </div>

                                    <!-- Kecamatan -->
                                    <div
                                        class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                        <x-input-label for="kecamatan" :value="__('Kecamatan')" />
                                        <select id="kecamatan" name="kecamatan_id"
                                            class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                                            required disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                        <input type="hidden" name="kecamatan_nama" id="kecamatan_nama"
                                            value="{{ old('kecamatan_nama', $customer->kecamatan_nama) }}">
                                        <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
                                    </div>

                                    <!-- Desa -->
                                    <div
                                        class="bg-white dark:bg-gray-800 p-4 rounded-md border border-gray-200 dark:border-gray-700">
                                        <x-input-label for="desa" :value="__('Desa')" />
                                        <select id="desa" name="desa_id"
                                            class="mt-1 block w-full select2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                                            required disabled>
                                            <option value="">Pilih Desa</option>
                                        </select>
                                        <input type="hidden" name="desa_nama" id="desa_nama"
                                            value="{{ old('desa_nama', $customer->desa_nama) }}">
                                        <x-input-error :messages="$errors->get('desa_id')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    theme: 'classic',
                    width: '100%'
                });

                // Load Provinsi
                $.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json', function(provinces) {
                    provinces.forEach(function(province) {
                        let selected = "{{ old('provinsi_id', $customer->provinsi_id) }}" == province
                            .id ? 'selected' : '';
                        $('#provinsi').append(new Option(province.name, province.id, false, selected));
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
                            let selected = "{{ old('kabupaten_id', $customer->kabupaten_id) }}" ==
                                regency.id ? 'selected' : '';
                            $('#kabupaten').append(new Option(regency.name, regency.id, false,
                                selected));
                        });
                    });
                }

                function loadKecamatan(regencyId) {
                    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`, function(
                        districts) {
                        $('#kecamatan').prop('disabled', false);
                        districts.forEach(function(district) {
                            let selected = "{{ old('kecamatan_id', $customer->kecamatan_id) }}" ==
                                district.id ? 'selected' : '';
                            $('#kecamatan').append(new Option(district.name, district.id, false,
                                selected));
                        });
                    });
                }

                function loadDesa(districtId) {
                    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`, function(
                        villages) {
                        $('#desa').prop('disabled', false);
                        villages.forEach(function(village) {
                            let selected = "{{ old('desa_id', $customer->desa_id) }}" == village.id ?
                                'selected' : '';
                            $('#desa').append(new Option(village.name, village.id, false, selected));
                        });
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
