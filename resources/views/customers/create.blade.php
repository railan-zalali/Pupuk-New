<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-user-plus text-xl"></i>
                    </div>
                    {{ __('Tambah Pelanggan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Input data pelanggan baru atau import dari Excel
                </p>
            </div>

            <a href="{{ route('customers.index') }}" class="btn-secondary flex items-center gap-2">
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

        @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <i class="ti ti-check-circle text-emerald-500 text-lg"></i>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Manual Input -->
            <div class="lg:col-span-2 space-y-6 animate-fade-in-up delay-100">
                <form action="{{ route('customers.store') }}" method="POST" class="card">
                    @csrf
                    <!-- Personal Info -->
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 mb-4">
                            <i class="ti ti-id text-emerald-500"></i> Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="nik" value="NIK" class="mb-1.5" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ti ti-id-badge text-gray-400"></i>
                                    </div>
                                    <x-text-input id="nik" name="nik" type="number" class="pl-10 w-full" :value="old('nik')" required placeholder="16 digit NIK" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="nama" value="Nama Lengkap" class="mb-1.5" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ti ti-user text-gray-400"></i>
                                    </div>
                                    <x-text-input id="nama" name="nama" type="text" class="pl-10 w-full" :value="old('nama')" required placeholder="Nama sesuai KTP" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Info -->
                    <div class="p-6 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 mb-4">
                            <i class="ti ti-map-pin text-emerald-500"></i> Alamat Lengkap
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="alamat" value="Jalan / Dusun / RT RW" class="mb-1.5" />
                                <textarea id="alamat" name="alamat" rows="2" class="input-field w-full" placeholder="Detail alamat rumah...">{{ old('alamat') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="provinsi" value="Provinsi" class="mb-1.5" />
                                    <select id="provinsi" name="provinsi_id" class="select2 w-full" required>
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                                </div>

                                <div>
                                    <x-input-label for="kabupaten" value="Kabupaten/Kota" class="mb-1.5" />
                                    <select id="kabupaten" name="kabupaten_id" class="select2 w-full" required disabled>
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                    <input type="hidden" name="kabupaten_nama" id="kabupaten_nama">
                                </div>

                                <div>
                                    <x-input-label for="kecamatan" value="Kecamatan" class="mb-1.5" />
                                    <select id="kecamatan" name="kecamatan_id" class="select2 w-full" required disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    <input type="hidden" name="kecamatan_nama" id="kecamatan_nama">
                                </div>

                                <div>
                                    <x-input-label for="desa" value="Desa/Kelurahan" class="mb-1.5" />
                                    <select id="desa" name="desa_id" class="select2 w-full" required disabled>
                                        <option value="">Pilih Desa</option>
                                    </select>
                                    <input type="hidden" name="desa_nama" id="desa_nama">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-100 dark:border-gray-700/50 flex justify-end gap-3">
                        <button type="button" onclick="window.history.back()" class="btn-ghost">Batal</button>
                        <button type="submit" class="btn-primary">
                            <i class="ti ti-device-floppy"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Import -->
            <div class="lg:col-span-1 space-y-6 animate-fade-in-up delay-200">
                <div class="card overflow-hidden bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border-emerald-100 dark:border-emerald-800/30">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <i class="ti ti-file-spreadsheet text-xl"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Import Excel</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            Upload file Excel untuk import data pelanggan dalam jumlah banyak sekaligus.
                        </p>

                        <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="file" name="excel_file" id="excel_file" class="hidden" accept=".xlsx,.xls" required>

                            <label for="excel_file" class="block w-full border-2 border-dashed border-emerald-300 dark:border-emerald-700 rounded-xl p-6 text-center cursor-pointer hover:border-emerald-500 dark:hover:border-emerald-500 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-all group">
                                <i class="ti ti-cloud-upload text-3xl text-emerald-400 group-hover:text-emerald-600 transition-colors mb-2"></i>
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">
                                    Klik untuk upload
                                </span>
                                <span id="file-name" class="block text-xs text-gray-500 mt-1">Format: .xlsx, .xls</span>
                            </label>

                            <button type="submit" class="btn-primary w-full justify-center">
                                <i class="ti ti-upload"></i> Proses Import
                            </button>
                        </form>

                        <div class="mt-6 pt-6 border-t border-emerald-200/50 dark:border-emerald-800/30">
                            <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Format Excel</h4>
                            <a href="{{ route('customers.template.download') }}" class="flex items-center gap-2 p-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors group">
                                <div class="w-8 h-8 rounded bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                    <i class="ti ti-table"></i>
                                </div>
                                <div class="text-left">
                                    <div class="text-sm font-medium text-gray-800 dark:text-gray-200 group-hover:text-emerald-600 transition-colors">Download Template</div>
                                    <div class="text-xs text-gray-500">File kosong dengan header</div>
                                </div>
                                <i class="ti ti-download ml-auto text-gray-400 group-hover:text-emerald-500"></i>
                            </a>

                            <ul class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                                <li class="flex items-center gap-2"><i class="ti ti-check text-emerald-500"></i> NIK (16 digit)</li>
                                <li class="flex items-center gap-2"><i class="ti ti-check text-emerald-500"></i> Nama Lengkap</li>
                                <li class="flex items-center gap-2"><i class="ti ti-check text-emerald-500"></i> Alamat & Wilayah</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 Styling to match Tailwind */
        .select2-container--default .select2-selection--single {
            background-color: transparent;
            border-color: #d1d5db;
            border-radius: 0.75rem;
            /* xl */
            height: 42px;
            display: flex;
            align-items: center;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: #374151;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 1rem;
            color: #374151;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e5e7eb;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 0.5rem;
        }

        .select2-dropdown {
            border-radius: 0.75rem;
            border-color: #d1d5db;
            overflow: hidden;
        }

        .select2-search__field {
            border-radius: 0.5rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // File Input logic
            $('#excel_file').on('change', function() {
                const fileName = this.files[0] ? this.files[0].name : 'Format: .xlsx, .xls';
                $('#file-name').text(fileName);
                if (this.files[0]) {
                    $(this).parent().addClass('border-emerald-500 bg-emerald-50/50');
                }
            });

            // Initialize Select2
            $('.select2').select2({
                width: '100%'
            });

            // API Base URL
            const API_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

            // Load Provinces
            $.get(`${API_URL}/provinces.json`, function(provinces) {
                provinces.forEach(p => $('#provinsi').append(new Option(p.name, p.id)));
            });

            // Cascading Dropdowns
            $('#provinsi').on('change', function() {
                resetSelect('#kabupaten', 'Pilih Kabupaten');
                resetSelect('#kecamatan', 'Pilih Kecamatan');
                resetSelect('#desa', 'Pilih Desa');

                if (this.value) {
                    $('#provinsi_nama').val($("#provinsi option:selected").text());
                    loadData('regencies', this.value, '#kabupaten');
                }
            });

            $('#kabupaten').on('change', function() {
                resetSelect('#kecamatan', 'Pilih Kecamatan');
                resetSelect('#desa', 'Pilih Desa');

                if (this.value) {
                    $('#kabupaten_nama').val($("#kabupaten option:selected").text());
                    loadData('districts', this.value, '#kecamatan');
                }
            });

            $('#kecamatan').on('change', function() {
                resetSelect('#desa', 'Pilih Desa');

                if (this.value) {
                    $('#kecamatan_nama').val($("#kecamatan option:selected").text());
                    loadData('villages', this.value, '#desa');
                }
            });

            $('#desa').on('change', function() {
                if (this.value) {
                    $('#desa_nama').val($("#desa option:selected").text());
                }
            });

            // Helper functions
            function loadData(endpoint, parentId, targetSelector) {
                $(targetSelector).prop('disabled', true).html('<option>Memuat...</option>');
                $.get(`${API_URL}/${endpoint}/${parentId}.json`, function(data) {
                    $(targetSelector).empty().append(new Option($(targetSelector).data('placeholder') || 'Pilih...', ''));
                    data.forEach(item => $(targetSelector).append(new Option(item.name, item.id)));
                    $(targetSelector).prop('disabled', false);
                });
            }

            function resetSelect(selector, placeholder) {
                $(selector).empty().append(new Option(placeholder, '')).prop('disabled', true);
            }
        });
    </script>
    @endpush
</x-app-layout>