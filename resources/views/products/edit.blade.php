<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-edit text-xl"></i>
                    </div>
                    {{ __('Edit Produk') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    <a href="{{ route('products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
                    <span class="mx-1">•</span>
                    <span>{{ $product->name }}</span>
                </p>
            </div>


            <div class="flex gap-2">
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <i class="ti ti-arrow-left text-base"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div
                class="flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 animate-fade-in-up">
                <i class="ti ti-alert-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Ada kesalahan dalam pengisian form:
                    </h3>
                    <ul class="mt-1 text-sm text-red-600 dark:text-red-300 list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6 animate-fade-in-up delay-100">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                    <i class="ti ti-info-circle text-emerald-500 text-lg"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Informasi Dasar</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="code" value="Kode Produk" />
                        <div class="mt-1 flex rounded-xl shadow-sm">
                            <span
                                class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                <i class="ti ti-barcode"></i>
                            </span>
                            <input type="text" id="code" name="code"
                                value="{{ old('code', $product->code) }}" readonly
                                class="flex-1 rounded-r-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <x-input-label for="name" value="Nama Produk" />
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                            required
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    </div>

                    <div>
                        <x-input-label for="category_id" value="Kategori" />
                        <select id="category_id" name="category_id" required
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="supplier_id" value="Supplier" />
                        <select id="supplier_id" name="supplier_id" required
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="description" value="Deskripsi" />
                        <textarea id="description" name="description" rows="3"
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Price & Stock -->
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                    <i class="ti ti-currency-dollar text-emerald-500 text-lg"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Harga & Stok</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <x-input-label for="purchase_price" value="Harga Beli" />
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="purchase_price" id="purchase_price" min="0"
                                step="1" required value="{{ old('purchase_price', $product->purchase_price) }}"
                                class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <x-input-label for="selling_price" value="Harga Jual" />
                        <div class="mt-1 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="selling_price" id="selling_price" min="0" step="1"
                                required value="{{ old('selling_price', $product->selling_price) }}"
                                class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <x-input-label for="stock" value="Stok Saat Ini" />
                        <input type="number" name="stock" id="stock" min="0" step="1" required
                            value="{{ old('stock', $product->stock) }}"
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    </div>

                    <div>
                        <x-input-label for="min_stock" value="Minimal Stok" />
                        <input type="number" name="min_stock" id="min_stock" min="0" step="1"
                            required value="{{ old('min_stock', $product->min_stock) }}"
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Config -->
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                    <i class="ti ti-settings text-emerald-500 text-lg"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Konfigurasi Stok</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="stock_method" value="Metode Stok" />
                        <select name="stock_method" id="stock_method"
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            <option value="FIFO"
                                {{ old('stock_method', $product->stock_method) == 'FIFO' ? 'selected' : '' }}>FIFO
                                (First In, First Out)</option>
                            <option value="FEFO"
                                {{ old('stock_method', $product->stock_method) == 'FEFO' ? 'selected' : '' }}>FEFO
                                (First Expired, First Out)</option>
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            FIFO: Stok lama keluar duluan | FEFO: Stok kedaluwarsa keluar duluan
                        </p>
                    </div>

                    <div>
                        <x-input-label for="expiry_warning_days" value="Peringatan Kedaluwarsa (Hari)" />
                        <input type="number" name="expiry_warning_days" id="expiry_warning_days" min="0"
                            step="1" value="{{ old('expiry_warning_days', $product->expiry_warning_days) }}"
                            class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <div class="space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="requires_expiry_date" id="requires_expiry_date"
                                    value="1"
                                    {{ old('requires_expiry_date', $product->requires_expiry_date) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Wajib memiliki tanggal
                                    kedaluwarsa</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="is_perishable" id="is_perishable" value="1"
                                    {{ old('is_perishable', $product->is_perishable) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Produk mudah
                                    rusak/kedaluwarsa</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="strict_expiry_validation" id="strict_expiry_validation"
                                    value="1"
                                    {{ old('strict_expiry_validation', $product->strict_expiry_validation) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Validasi ketat tanggal
                                    kedaluwarsa</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Units -->
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                    <i class="ti ti-ruler-2 text-emerald-500 text-lg"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Satuan Produk</h3>
                </div>

                <div id="units-container" class="space-y-4">
                    @foreach ($product->units as $index => $productUnit)
                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 relative group animate-fade-in-up">
                            @if (!$productUnit->is_base_unit)
                                <button type="button"
                                    class="remove-unit absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @endif

                            <input type="hidden" name="units[{{ $index }}][id]"
                                value="{{ $productUnit->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
                                    <select name="units[{{ $index }}][unit_id]" required
                                        class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $productUnit->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->abbreviation }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konversi</label>
                                    <input type="number" name="units[{{ $index }}][conversion_factor]"
                                        required min="1" value="{{ $productUnit->conversion_factor }}"
                                        class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        {{ $productUnit->is_base_unit ? 'readonly' : '' }}>
                                    @if ($productUnit->is_base_unit)
                                        <p class="text-xs text-gray-400 mt-1">Satuan Dasar</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga
                                        Beli</label>
                                    <div class="mt-1 relative rounded-xl shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="units[{{ $index }}][purchase_price]"
                                            required value="{{ $productUnit->purchase_price }}"
                                            class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga
                                        Jual</label>
                                    <div class="mt-1 relative rounded-xl shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="units[{{ $index }}][selling_price]"
                                            required value="{{ $productUnit->selling_price }}"
                                            class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expired</label>
                                    <input type="date" name="units[{{ $index }}][expire_date]"
                                        value="{{ $productUnit->expire_date }}"
                                        class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                                <div class="flex items-end pb-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="units[{{ $index }}][is_default]"
                                            value="1"
                                            {{ $productUnit->is_base_unit ? 'checked onclick=return false;' : ($productUnit->is_default ? 'checked' : '') }}
                                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                        <span class="text-sm">Utama</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="button" id="add-unit-btn" class="btn-secondary">
                        <i class="ti ti-plus"></i>
                        <span>Tambah Satuan Lain</span>
                    </button>
                </div>
            </div>

            <!-- Image -->
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                    <i class="ti ti-photo text-emerald-500 text-lg"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Gambar Produk</h3>
                </div>

                <div class="mt-2 text-center">
                    <div class="flex items-center justify-center w-full">
                        <label for="image"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 relative overflow-hidden group transition-all">
                            <div id="placeholder-area"
                                class="flex flex-col items-center justify-center pt-5 pb-6 {{ $product->image ? 'hidden' : '' }}">
                                <div
                                    class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center mb-4 text-emerald-500 group-hover:scale-110 transition-transform">
                                    <i class="ti ti-cloud-upload text-3xl"></i>
                                </div>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                        class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG (MAX. 2MB)</p>
                            </div>

                            <div id="image-preview"
                                class="absolute inset-0 {{ $product->image ? '' : 'hidden' }} bg-white dark:bg-gray-800">
                                <img src="{{ $product->image ? Storage::url($product->image) : '' }}" alt="Preview"
                                    class="w-full h-full object-contain">
                                <button type="button" id="remove-image"
                                    class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition-colors z-10">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                            <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="window.history.back()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add Unit Logic
                const unitsContainer = document.getElementById('units-container');
                const addUnitBtn = document.getElementById('add-unit-btn');
                // Start index after existing units
                let unitIndex = {{ $product->units->count() }};

                if (addUnitBtn) {
                    addUnitBtn.addEventListener('click', () => {
                        const newRow = document.createElement('div');
                        newRow.className =
                            'p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 relative group animate-fade-in-up';
                        newRow.innerHTML = `
                        <button type="button" class="remove-unit absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors">
                            <i class="ti ti-trash"></i>
                        </button>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
                                <select name="units[${unitIndex}][unit_id]" required
                                    class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konversi</label>
                                <input type="number" name="units[${unitIndex}][conversion_factor]" required min="1"
                                    class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli</label>
                                <div class="mt-1 relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="units[${unitIndex}][purchase_price]" required
                                        class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual</label>
                                <div class="mt-1 relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="units[${unitIndex}][selling_price]" required
                                        class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expired</label>
                                <input type="date" name="units[${unitIndex}][expire_date]"
                                    class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            </div>
                            <div class="flex items-end pb-2">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="units[${unitIndex}][is_default]" value="1"
                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="text-sm">Utama</span>
                                </label>
                            </div>
                        </div>
                     `;
                        unitsContainer.appendChild(newRow);
                        unitIndex++;
                    });

                    unitsContainer.addEventListener('click', (e) => {
                        if (e.target.closest('.remove-unit')) {
                            e.target.closest('.relative').remove();
                        }
                    });
                }

                const imageInput = document.getElementById('image');
                const imagePreview = document.getElementById('image-preview');
                const placeholder = document.getElementById('placeholder-area');
                const removeImgBtn = document.getElementById('remove-image');

                if (imageInput && imagePreview && placeholder && removeImgBtn) {
                    imageInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = imagePreview.querySelector('img');
                                if (img) img.src = e.target.result;
                                imagePreview.classList.remove('hidden');
                                placeholder.classList.add('hidden');
                            }
                            reader.readAsDataURL(file);
                        }
                    });

                    removeImgBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        imageInput.value = '';
                        const img = imagePreview.querySelector('img');
                        if (img) img.src = '';
                        imagePreview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
