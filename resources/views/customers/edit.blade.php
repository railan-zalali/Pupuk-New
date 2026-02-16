<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-edit text-xl"></i>
                    </div>
                    {{ __('Edit Pelanggan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Perbarui informasi data pelanggan
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

        <div class="card animate-fade-in-up delay-100">
            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Personal Info -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 mb-4">
                        <i class="ti ti-id text-emerald-500"></i> Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="nik" value="NIK" class="mb-1.5" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-id-badge text-gray-400"></i>
                                </div>
                                <x-text-input id="nik" name="nik" type="number" class="pl-10 w-full" :value="old('nik', $customer->nik)" required />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="nama" value="Nama Lengkap" class="mb-1.5" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-user text-gray-400"></i>
                                </div>
                                <x-text-input id="nama" name="nama" type="text" class="pl-10 w-full" :value="old('nama', $customer->nama)" required />
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
                            <textarea id="alamat" name="alamat" rows="2" class="input-field w-full">{{ old('alamat', $customer->alamat) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="provinsi" value="Provinsi" class="mb-1.5" />
                                <select id="provinsi" name="provinsi_id" class="select2 w-full" required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <input type="hidden" name="provinsi_nama" id="provinsi_nama" value="{{ old('provinsi_nama', $customer->provinsi_nama) }}">
                            </div>

                            <div>
                                <x-input-label for="kabupaten" value="Kabupaten/Kota" class="mb-1.5" />
                                <select id="kabupaten" name="kabupaten_id" class="select2 w-full" required>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                                <input type="hidden" name="kabupaten_nama" id="kabupaten_nama" value="{{ old('kabupaten_nama', $customer->kabupaten_nama) }}">
                            </div>

                            <div>
                                <x-input-label for="kecamatan" value="Kecamatan" class="mb-1.5" />
                                <select id="kecamatan" name="kecamatan_id" class="select2 w-full" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <input type="hidden" name="kecamatan_nama" id="kecamatan_nama" value="{{ old('kecamatan_nama', $customer->kecamatan_nama) }}">
                            </div>

                            <div>
                                <x-input-label for="desa" value="Desa/Kelurahan" class="mb-1.5" />
                                <select id="desa" name="desa_id" class="select2 w-full" required>
                                    <option value="">Pilih Desa</option>
                                </select>
                                <input type="hidden" name="desa_nama" id="desa_nama" value="{{ old('desa_nama', $customer->desa_nama) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 dark:border-gray-700/50 flex justify-end gap-3">
                    <button type="button" onclick="window.history.back()" class="btn-ghost">Batal</button>
                    <button type="submit" class="btn-primary">
                        <i class="ti ti-device-floppy"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: transparent;
            border-color: #d1d5db;
            border-radius: 0.75rem;
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
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
            const API_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

            // Initial Load for Edit Mode
            const initialProv = "{{ old('provinsi_id', $customer->provinsi_id) }}";
            const initialKab = "{{ old('kabupaten_id', $customer->kabupaten_id) }}";
            const initialKec = "{{ old('kecamatan_id', $customer->kecamatan_id) }}";
            const initialDesa = "{{ old('desa_id', $customer->desa_id) }}";

            // Load Provinces
            $.get(`${API_URL}/provinces.json`, function(provinces) {
                provinces.forEach(p => {
                    const selected = p.id == initialProv ? 'selected' : '';
                    $('#provinsi').append(new Option(p.name, p.id, false, selected));
                });
                if (initialProv) loadData('regencies', initialProv, '#kabupaten', initialKab);
            });

            // Cascading Logic
            $('#provinsi').on('change', function() {
                resetCascading('#kabupaten', '#kecamatan', '#desa');
                if (this.value) {
                    $('#provinsi_nama').val($("#provinsi option:selected").text());
                    loadData('regencies', this.value, '#kabupaten');
                }
            });

            $('#kabupaten').on('change', function() {
                resetCascading('#kecamatan', '#desa');
                if (this.value) {
                    $('#kabupaten_nama').val($("#kabupaten option:selected").text());
                    loadData('districts', this.value, '#kecamatan');
                }
            });

            $('#kecamatan').on('change', function() {
                resetCascading('#desa');
                if (this.value) {
                    $('#kecamatan_nama').val($("#kecamatan option:selected").text());
                    loadData('villages', this.value, '#desa');
                }
            });

            $('#desa').on('change', function() {
                if (this.value) $('#desa_nama').val($("#desa option:selected").text());
            });

            function loadData(endpoint, parentId, target, selectedId = null) {
                $.get(`${API_URL}/${endpoint}/${parentId}.json`, function(data) {
                    // Keep the selected value if we are chaining loads
                    data.forEach(item => {
                        const isSelected = item.id == selectedId ? 'selected' : '';
                        $(target).append(new Option(item.name, item.id, false, isSelected));
                    });

                    // If we just loaded this level and have a selectedId, trigger change to load next level
                    if (selectedId) {
                        $(target).trigger('change');
                        // Wait a bit to ensure next level loading ?? 
                        // Actually trigger change might be async if manual trigger, 
                        // but here we are in callback.
                        // Recursive loading: if target is kabupaten and we have initialKec, load kecamatan
                        if (target === '#kabupaten' && initialKec) loadData('districts', initialKab, '#kecamatan', initialKec);
                        if (target === '#kecamatan' && initialDesa) loadData('villages', initialKec, '#desa', initialDesa);
                    }
                });
            }

            function resetCascading(...selectors) {
                selectors.forEach(sel => $(sel).empty().append(new Option($(sel).find('option:first').text(), '')));
            }
        });
    </script>
    @endpush
</x-app-layout>