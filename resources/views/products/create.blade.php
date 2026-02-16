<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-package-import text-xl"></i>
                    </div>
                    {{ __('Tambah Produk') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    <a href="{{ route('products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
                    <span class="mx-1">•</span>
                    <span>Tambah Baru</span>
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
        <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 animate-fade-in-up">
            <i class="ti ti-alert-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Ada kesalahan dalam pengisian form:</h3>
                <ul class="mt-1 text-sm text-red-600 dark:text-red-300 list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button type="button" data-target="single-product"
                    class="tab-btn active group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors border-emerald-500 text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-box text-lg mr-2"></i>
                    <span>Input Produk Tunggal</span>
                </button>

                <button type="button" data-target="batch-product"
                    class="tab-btn group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                    <i class="ti ti-boxes text-lg mr-2"></i>
                    <span>Input Batch</span>
                </button>
            </nav>
        </div>

        <!-- Single Product Form -->
        <div id="single-product" class="tab-content animate-fade-in-up delay-100">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="is_batch" value="0">

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
                                <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                    <i class="ti ti-barcode"></i>
                                </span>
                                <input type="text" id="code" name="code" value="{{ $productCode }}" readonly
                                    class="flex-1 rounded-r-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <x-input-label for="name" value="Nama Produk" />
                            <input type="text" id="name" name="name" required
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                placeholder="Nama produk...">
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Kategori" />
                            <select id="category_id" name="category_id" required
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="supplier_id" value="Supplier" />
                            <select id="supplier_id" name="supplier_id" required
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                placeholder="Deskripsi produk (opsional)..."></textarea>
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
                                <input type="number" name="purchase_price" id="purchase_price" min="0" step="1" required
                                    class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    placeholder="0">
                            </div>
                        </div>

                        <div>
                            <x-input-label for="selling_price" value="Harga Jual" />
                            <div class="mt-1 relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="selling_price" id="selling_price" min="0" step="1" required
                                    class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    placeholder="0">
                            </div>
                        </div>

                        <div>
                            <x-input-label for="stock" value="Stok Awal" />
                            <input type="number" name="stock" id="stock" min="0" step="1" required
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                placeholder="0">
                        </div>

                        <div>
                            <x-input-label for="min_stock" value="Minimal Stok" />
                            <input type="number" name="min_stock" id="min_stock" min="0" step="1" required
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                placeholder="0">
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
                                <option value="FIFO">FIFO (First In, First Out)</option>
                                <option value="FEFO">FEFO (First Expired, First Out)</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                FIFO: Stok lama keluar duluan | FEFO: Stok kedaluwarsa keluar duluan
                            </p>
                        </div>

                        <div>
                            <x-input-label for="expiry_warning_days" value="Peringatan Kedaluwarsa (Hari)" />
                            <input type="number" name="expiry_warning_days" id="expiry_warning_days" min="0" step="1" value="30"
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="requires_expiry_date" id="requires_expiry_date" value="1"
                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Wajib memiliki tanggal kedaluwarsa</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="is_perishable" id="is_perishable" value="1"
                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Produk mudah rusak/kedaluwarsa</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="strict_expiry_validation" id="strict_expiry_validation" value="1"
                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Validasi ketat tanggal kedaluwarsa</span>
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

                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Satuan dasar (Pcs) ditambahkan otomatis. Anda bisa menambah satuan lain.
                    </p>

                    <div id="units-container" class="space-y-4">
                        <!-- Base unit row -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                                <div>
                                    <x-input-label value="Satuan" />
                                    <select name="units[0][unit_id]" required
                                        class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $unit->is_base_unit ? 'selected' : '' }}>
                                            {{ $unit->name }} ({{ $unit->abbreviation }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Konversi" />
                                    <input type="number" name="units[0][conversion_factor]" value="1" readonly
                                        class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-500 sm:text-sm">
                                    <p class="text-xs text-gray-400 mt-1">1 = Satuan Dasar</p>
                                </div>
                                <div>
                                    <x-input-label value="Harga Beli" />
                                    <div class="mt-1 relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="units[0][purchase_price]" required
                                            class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Harga Jual" />
                                    <div class="mt-1 relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="units[0][selling_price]" required
                                            class="block w-full pl-10 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Tanggal Expired" />
                                    <input type="date" name="units[0][expire_date]"
                                        class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                </div>
                                <div class="flex items-end pb-1">
                                    <input type="hidden" name="units[0][is_default]" value="1">
                                    <span class="badge badge-success w-full justify-center">Satuan Dasar</span>
                                </div>
                            </div>
                        </div>
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
                            <label for="image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 relative overflow-hidden group transition-all">
                                <div id="placeholder-area" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center mb-4 text-emerald-500 group-hover:scale-110 transition-transform">
                                        <i class="ti ti-cloud-upload text-3xl"></i>
                                    </div>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG (MAX. 2MB)</p>
                                </div>
                                <div id="image-preview" class="absolute inset-0 hidden bg-white dark:bg-gray-800">
                                    <img src="" alt="Preview" class="w-full h-full object-contain">
                                    <button type="button" id="remove-image" class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <button type="button" id="camera-button" class="btn-secondary">
                                <i class="ti ti-camera"></i>
                                <span>Ambil Foto</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="window.history.back()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>

        <!-- Batch Product Form -->
        <div id="batch-product" class="tab-content hidden animate-fade-in-up delay-100">
            <form action="{{ route('products.store-batch') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="is_batch" value="1">

                <div class="card p-6">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                        <i class="ti ti-settings text-emerald-500 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Informasi Umum</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="batch_supplier_id" value="Supplier" />
                            <select id="batch_supplier_id" name="supplier_id" required
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="batch_category_id" value="Kategori Default" />
                            <select id="batch_category_id" name="default_category_id"
                                class="mt-1 block w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-list text-emerald-500 text-lg"></i>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Daftar Produk</h3>
                        </div>
                        <button type="button" id="add-product-row" class="btn-primary py-1.5 px-3 text-sm">
                            <i class="ti ti-plus"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-3 py-2">No</th>
                                    <th class="px-3 py-2">Kategori</th>
                                    <th class="px-3 py-2">Nama Produk</th>
                                    <th class="px-3 py-2">Harga Beli</th>
                                    <th class="px-3 py-2">Harga Jual</th>
                                    <th class="px-3 py-2">Stok</th>
                                    <th class="px-3 py-2">Min Stok</th>
                                    <th class="px-3 py-2">Satuan</th>
                                    <th class="px-3 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="product-rows" class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr class="product-row">
                                    <td class="px-3 py-3 text-sm text-gray-500 row-number">1</td>
                                    <td class="px-3 py-3">
                                        <select name="products[0][category_id]" required
                                            class="block w-32 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500 category-select">
                                            <option value="">Pilih...</option>
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-3">
                                        <input type="text" name="products[0][name]" required placeholder="Nama..."
                                            class="block w-40 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                    </td>
                                    <td class="px-3 py-3">
                                        <input type="number" name="products[0][purchase_price]" required placeholder="0"
                                            class="block w-24 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                    </td>
                                    <td class="px-3 py-3">
                                        <input type="number" name="products[0][selling_price]" required placeholder="0"
                                            class="block w-24 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                    </td>
                                    <td class="px-3 py-3">
                                        <input type="number" name="products[0][stock]" required placeholder="0"
                                            class="block w-20 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                    </td>
                                    <td class="px-3 py-3">
                                        <input type="number" name="products[0][min_stock]" required placeholder="0"
                                            class="block w-20 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                    </td>
                                    <td class="px-3 py-3">
                                        <select name="products[0][unit_id]" required
                                            class="block w-24 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                            @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-3">
                                        <button type="button" class="delete-row text-red-500 hover:text-red-700">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="window.history.back()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Semua</button>
                </div>
            </form>
        </div>

        <!-- Camera Modal -->
        <div id="camera-modal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-scale-in">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Ambil Foto</h3>
                        <button type="button" id="close-camera" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="ti ti-x text-xl"></i>
                        </button>
                    </div>
                    <div class="p-4 bg-black">
                        <video id="camera-preview" class="w-full h-64 object-cover rounded-lg"></video>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 flex justify-center gap-4">
                        <button type="button" id="capture-photo" class="w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center shadow-lg transform hover:scale-110 transition-all">
                            <i class="ti ti-camera text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Logic
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.dataset.target;

                    // Update buttons
                    tabButtons.forEach(b => {
                        b.classList.remove('active', 'border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
                        b.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    });
                    btn.classList.add('active', 'border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
                    btn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');

                    // Show content
                    tabContents.forEach(c => {
                        c.classList.add('hidden');
                        if (c.id === target) c.classList.remove('hidden');
                    });
                });
            });

            // Add Unit Logic (Single Product)
            const unitsContainer = document.getElementById('units-container');
            const addUnitBtn = document.getElementById('add-unit-btn');
            let unitIndex = 1;

            if (addUnitBtn) {
                addUnitBtn.addEventListener('click', () => {
                    const newRow = document.createElement('div');
                    newRow.className = 'p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 relative group animate-fade-in-up';
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

            // Image Upload & Camera (simplified)
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('image-preview');
            const placeholder = document.getElementById('placeholder-area');
            const removeImgBtn = document.getElementById('remove-image');

            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.querySelector('img').src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });

                removeImgBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Stop bubbling to label
                    imageInput.value = '';
                    imagePreview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                });
            }

            // Batch Rows Logic
            const addRowBtn = document.getElementById('add-product-row');
            const rowsContainer = document.getElementById('product-rows');
            let batchIndex = 1;

            if (addRowBtn) {
                addRowBtn.addEventListener('click', () => {
                    const tr = document.createElement('tr');
                    tr.className = 'product-row animate-fade-in-up';
                    tr.innerHTML = `
                        <td class="px-3 py-3 text-sm text-gray-500 row-number"></td>
                        <td class="px-3 py-3">
                            <select name="products[${batchIndex}][category_id]" required
                                class="block w-32 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                <option value="">Pilih...</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-3 py-3">
                            <input type="text" name="products[${batchIndex}][name]" required
                                class="block w-40 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                        </td>
                        <td class="px-3 py-3">
                            <input type="number" name="products[${batchIndex}][purchase_price]" required
                                class="block w-24 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                        </td>
                        <td class="px-3 py-3">
                            <input type="number" name="products[${batchIndex}][selling_price]" required
                                class="block w-24 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                        </td>
                        <td class="px-3 py-3">
                            <input type="number" name="products[${batchIndex}][stock]" required
                                class="block w-20 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                        </td>
                        <td class="px-3 py-3">
                            <input type="number" name="products[${batchIndex}][min_stock]" required
                                class="block w-20 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                        </td>
                        <td class="px-3 py-3">
                            <select name="products[${batchIndex}][unit_id]" required
                                class="block w-24 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs focus:ring-emerald-500">
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-3 py-3">
                            <button type="button" class="delete-row text-red-500 hover:text-red-700">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    `;
                    rowsContainer.appendChild(tr);
                    updateRowNumbers();
                    batchIndex++;
                });

                rowsContainer.addEventListener('click', (e) => {
                    if (e.target.closest('.delete-row')) {
                        e.target.closest('tr').remove();
                        updateRowNumbers();
                    }
                });

                function updateRowNumbers() {
                    const rows = rowsContainer.querySelectorAll('.product-row');
                    rows.forEach((row, index) => {
                        row.querySelector('.row-number').textContent = index + 1;
                    });
                }
            }

            // Camera Logic (Reuse from purchase/create if possible, but implementing simple version here for compactness)
            const cameraBtn = document.getElementById('camera-button');
            const cameraModal = document.getElementById('camera-modal');
            const closeCameraBtn = document.getElementById('close-camera');
            const captureBtn = document.getElementById('capture-photo');
            const video = document.getElementById('camera-preview');
            let stream = null;

            if (cameraBtn) {
                cameraBtn.addEventListener('click', async () => {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                        video.srcObject = stream;
                        video.play();
                        cameraModal.classList.remove('hidden');
                    } catch (err) {
                        alert('Kamera tidak dapat diakses');
                    }
                });

                closeCameraBtn.addEventListener('click', () => {
                    stopCamera();
                    cameraModal.classList.add('hidden');
                });

                captureBtn.addEventListener('click', () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    canvas.toBlob(blob => {
                        const file = new File([blob], "camera.jpg", {
                            type: "image/jpeg"
                        });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        imageInput.files = dataTransfer.files;

                        // Trigger change event
                        const event = new Event('change');
                        imageInput.dispatchEvent(event);

                        stopCamera();
                        cameraModal.classList.add('hidden');
                    }, 'image/jpeg');
                });

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>