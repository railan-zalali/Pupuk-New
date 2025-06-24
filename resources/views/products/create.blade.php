<x-app-layout>
    <div class="space-y-6 max-w-full overflow-hidden">
        <!-- Page Heading -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Produk') }}</h2>

            <div class="mt-2 sm:mt-0">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="rounded-lg bg-red-50 dark:bg-red-900/50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
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

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <ul class="flex flex-nowrap -mb-px" id="input-tabs">
                <li class="mr-2">
                    <a href="#" data-target="single-product"
                        class="tab-btn active inline-block p-4 border-b-2 border-blue-600 rounded-t-lg text-blue-600 dark:text-blue-500 dark:border-blue-500 whitespace-nowrap">Input
                        Produk Tunggal</a>
                </li>
                <li class="mr-2">
                    <a href="#" data-target="batch-product"
                        class="tab-btn inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 whitespace-nowrap">Input
                        Batch</a>
                </li>
            </ul>
        </div>

        <!-- Single Product Form -->
        <div id="single-product" class="tab-content">
            <div
                class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-4 md:p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="is_batch" value="0">

                    <!-- Basic Information -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Informasi Dasar
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="code"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kode Produk
                                </x-input-label>

                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                        Kode
                                    </span>
                                    <x-text-input type="text" id="code" name="code"
                                        value="{{ $productCode }}" readonly
                                        class="flex-1 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" />
                                </div>
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="name" value="Nama Produk" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="category_id" value="Kategori" />
                                <select id="category_id" name="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="supplier_id" value="Supplier" />
                                <select id="supplier_id" name="supplier_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                    required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" value="Deskripsi" />
                                <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"></textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Section: Harga & Stok -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Harga & Stok
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <x-input-label for="purchase_price" value="Harga Beli" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <x-text-input type="number" name="purchase_price" id="purchase_price"
                                        min="0" step="1" class="block w-full pl-10" required />
                                </div>
                                <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="selling_price" value="Harga Jual" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <x-text-input type="number" name="selling_price" id="selling_price"
                                        min="0" step="1" class="block w-full pl-10" required />
                                </div>
                                <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="stock" value="Stok Awal" />
                                <x-text-input type="number" name="stock" id="stock" min="0"
                                    step="1" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="min_stock" value="Minimal Stok" />
                                <x-text-input type="number" name="min_stock" id="min_stock" min="0"
                                    step="1" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('min_stock')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Section: Units of Measure -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            Satuan Produk
                        </h3>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Tambahkan satuan untuk produk ini.
                                Satuan dasar (Pcs) sudah ditambahkan secara otomatis.</p>
                        </div>

                        <div id="units-container" class="space-y-4">
                            <!-- Base unit row (always present) -->
                            <div
                                class="unit-row grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <x-input-label value="Satuan" />
                                    <select name="units[0][unit_id]"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                        required>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $unit->is_base_unit ? 'selected' : '' }}>{{ $unit->name }}
                                                ({{ $unit->abbreviation }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Konversi" />
                                    <input type="number" step="1" name="units[0][conversion_factor]"
                                        value="1" min="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                        required readonly />
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">1 = Satuan Dasar</p>
                                </div>
                                <div>
                                    <x-input-label value="Harga Beli" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" step="1" name="units[0][purchase_price]"
                                            value="{{ old('purchase_price') }}" min="0"
                                            class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                            required />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Harga Jual" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" step="1" name="units[0][selling_price]"
                                            value="{{ old('selling_price') }}" min="0"
                                            class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                            required />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Tanggal Expired" />
                                    <x-text-input type="date" name="units[0][expire_date]"
                                        class="mt-1 block w-full" />
                                </div>
                                <div class="flex items-end">
                                    <input type="hidden" name="units[0][is_default]" value="1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Satuan Dasar
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" id="add-unit-btn"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Satuan Lain
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Gambar Produk -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Gambar Produk
                        </h3>

                        <div class="mt-1">
                            <div class="flex flex-col sm:flex-row gap-3 mb-4">
                                <label for="image" class="cursor-pointer flex-1">
                                    <div
                                        class="px-4 py-2 text-center border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                        <svg class="w-5 h-5 inline mr-2 text-gray-500 dark:text-gray-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Upload Gambar
                                    </div>
                                </label>
                                <button type="button" id="camera-button"
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                    <svg class="w-5 h-5 inline mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ambil Foto
                                </button>
                            </div>

                            <label for="image" class="cursor-pointer block">
                                <div id="dropzone-container"
                                    class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 bg-gray-50 dark:bg-gray-800 overflow-hidden">
                                    <!-- Placeholder state -->
                                    <div id="placeholder-area"
                                        class="flex flex-col items-center justify-center p-6 text-center">
                                        <svg class="mb-3 h-14 w-14 text-gray-400 dark:text-gray-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-700 dark:text-gray-300 font-medium">Klik
                                            untuk upload gambar atau ambil foto</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG maksimal
                                            2MB
                                        </p>
                                    </div>

                                    <!-- Preview state (hidden initially) -->
                                    <div id="image-preview"
                                        class="absolute inset-0 flex items-center justify-center w-full h-full hidden">
                                        <img src="#" alt="Preview Gambar"
                                            class="max-h-full max-w-full object-contain">
                                    </div>

                                    <!-- Remove button (hidden initially) -->
                                    <button type="button" id="remove-image"
                                        class="absolute top-2 right-2 hidden bg-red-500 text-white rounded-full p-1 shadow-sm hover:bg-red-600 focus:outline-none transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*"
                                    capture="environment" />
                            </label>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6">
                        <button type="button" onclick="window.history.back()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Batch Product Form -->
        <div id="batch-product" class="tab-content hidden">
            <div
                class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
                <form action="{{ route('products.store-batch') }}" method="POST" enctype="multipart/form-data"
                    class="p-4 md:p-6 space-y-6" id="batch-form">
                    @csrf
                    <input type="hidden" name="is_batch" value="1">

                    <!-- Supplier Selection -->
                    <div
                        class="rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 md:p-6">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Informasi Umum
                            </span>
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="batch_supplier_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                                <select id="batch_supplier_id" name="supplier_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="batch_category_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori
                                    Default</label>
                                <select id="batch_category_id" name="default_category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih Kategori Default --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opsional - akan digunakan
                                    sebagai default untuk produk baru</p>
                            </div>
                        </div>
                    </div>

                    <!-- Product List -->
                    <div
                        class="rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 md:p-6">
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Daftar Produk
                            </h3>
                            <button type="button" id="add-product-row"
                                class="px-3 py-1.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 flex items-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Produk
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            No</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kategori</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nama Produk</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Harga Beli</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Harga Jual</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Stok</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Min Stok</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Satuan</th>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                                    id="product-rows">
                                    <!-- Product rows will be added here dynamically -->
                                    <tr class="product-row">
                                        <td
                                            class="px-3 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 row-number">
                                            1</td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <select name="products[0][category_id]" required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm category-select">
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <input type="text" name="products[0][name]" placeholder="Nama Produk"
                                                required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <div class="relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">
                                                    <span class="text-xs">Rp</span>
                                                </div>
                                                <input type="number" name="products[0][purchase_price]"
                                                    placeholder="0" required
                                                    class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <div class="relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">
                                                    <span class="text-xs">Rp</span>
                                                </div>
                                                <input type="number" name="products[0][selling_price]"
                                                    placeholder="0" required
                                                    class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <input type="number" name="products[0][stock]" placeholder="0"
                                                min="0" required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <input type="number" name="products[0][min_stock]" placeholder="0"
                                                min="0" required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <select name="products[0][unit_id]" required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                                <option value="">Pilih Satuan</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <textarea name="products[0][description]" placeholder="Deskripsi (opsional)" class="hidden"></textarea>
                                            <button type="button"
                                                class="delete-row text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6">
                        <button type="button" onclick="window.history.back()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                            Simpan Semua Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Camera Modal -->
        <div id="camera-modal"
            class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 dark:bg-opacity-80 flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Ambil Foto Produk</h3>
                    <video id="camera-preview" class="w-full h-64 object-cover bg-black rounded"></video>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" id="capture-photo"
                            class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                            Ambil Foto
                        </button>
                        <button type="button" id="close-camera"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Input numerik untuk harga dan stok
                const numericInputs = document.querySelectorAll('input[type="number"]');
                numericInputs.forEach(input => {
                    // Format angka saat input diubah
                    input.addEventListener('input', function() {
                        if (this.value) {
                            this.value = parseInt(this.value) || 0;
                        }
                    });

                    // Format angka saat input kehilangan fokus
                    input.addEventListener('blur', function() {
                        if (this.value === '') {
                            this.value = '0';
                        }
                    });
                });

                // Tab Navigation
                const tabButtons = document.querySelectorAll('.tab-btn');
                const tabContents = document.querySelectorAll('.tab-content');

                tabButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Remove active class from all buttons and hide all contents
                        tabButtons.forEach(btn => btn.classList.remove('active', 'border-blue-600',
                            'text-blue-600', 'dark:text-blue-500', 'dark:border-blue-500'));
                        tabButtons.forEach(btn => btn.classList.add('border-transparent',
                            'hover:text-gray-600', 'hover:border-gray-300'));
                        tabContents.forEach(content => content.classList.add('hidden'));

                        // Add active class to clicked button and show corresponding content
                        this.classList.add('active', 'border-blue-600', 'text-blue-600',
                            'dark:text-blue-500', 'dark:border-blue-500');
                        this.classList.remove('border-transparent', 'hover:text-gray-600',
                            'hover:border-gray-300');

                        const targetId = this.getAttribute('data-target');
                        document.getElementById(targetId).classList.remove('hidden');
                    });
                });

                // Single Product Form Functionality
                const imageInput = document.getElementById('image');
                const dropzoneContainer = document.getElementById('dropzone-container');
                const placeholderArea = document.getElementById('placeholder-area');
                const imagePreview = document.getElementById('image-preview');
                const imagePreviewImg = imagePreview?.querySelector('img');
                const removeImageBtn = document.getElementById('remove-image');

                const addUnitBtn = document.getElementById('add-unit-btn');
                const unitsContainer = document.getElementById('units-container');
                let unitCounter = 1;

                // Add new unit row
                if (addUnitBtn && unitsContainer) {
                    addUnitBtn.addEventListener('click', function() {
                        const unitRow = document.createElement('div');
                        unitRow.className =
                            'unit-row grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg relative';

                        unitRow.innerHTML = `
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
                                <select name="units[${unitCounter}][unit_id]" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konversi</label>
                                <input type="number" step="1" name="units[${unitCounter}][conversion_factor]" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300" required>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Berapa banyak satuan dasar</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" step="1" name="units[${unitCounter}][purchase_price]" min="0" class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" step="1" name="units[${unitCounter}][selling_price]" min="0" class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Expired</label>
                                <x-text-input type="date" name="units[${unitCounter}][expire_date]"
                                    class="mt-1 block w-full" />
                            </div>
                            <div class="flex items-end">
                                <div class="flex items-center">
                                    <input type="checkbox" name="units[${unitCounter}][is_default]" id="is_default_${unitCounter}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
                                    <label for="is_default_${unitCounter}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Satuan Utama
                                    </label>
                                </div>
                                <button type="button" class="remove-unit ml-auto text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        `;

                        unitsContainer.appendChild(unitRow);
                        unitCounter++;

                        // Add event listener to the new remove button
                        const removeBtn = unitRow.querySelector('.remove-unit');
                        removeBtn.addEventListener('click', function() {
                            unitRow.remove();
                        });

                        // Add event listener to the new default checkbox
                        const defaultCheckbox = unitRow.querySelector('input[type="checkbox"]');
                        defaultCheckbox.addEventListener('change', function() {
                            if (this.checked) {
                                // Uncheck all other default checkboxes
                                document.querySelectorAll('input[name^="units"][name$="[is_default]"]')
                                    .forEach(checkbox => {
                                        if (checkbox !== this) {
                                            checkbox.checked = false;
                                        }
                                    });

                                // Hide the default unit badge on the first row
                                const defaultBadge = document.querySelector(
                                    '.unit-row:first-child .inline-flex');
                                if (defaultBadge) {
                                    defaultBadge.classList.add('hidden');
                                }

                                // Remove the hidden input on the first row
                                const defaultInput = document.querySelector(
                                    'input[name="units[0][is_default]"]');
                                if (defaultInput) {
                                    defaultInput.value = "0";
                                }
                            } else {
                                // If unchecked and no other default is selected, revert to the first unit
                                const anyChecked = Array.from(document.querySelectorAll(
                                        'input[name^="units"][name$="[is_default]"]:checked')).length >
                                    0;
                                if (!anyChecked) {
                                    const defaultBadge = document.querySelector(
                                        '.unit-row:first-child .inline-flex');
                                    if (defaultBadge) {
                                        defaultBadge.classList.remove('hidden');
                                    }

                                    const defaultInput = document.querySelector(
                                        'input[name="units[0][is_default]"]');
                                    if (defaultInput) {
                                        defaultInput.value = "1";
                                    }
                                }
                            }
                        });
                    });
                }

                // Sync purchase and selling price with the main form fields
                const mainPurchasePrice = document.getElementById('purchase_price');
                const mainSellingPrice = document.getElementById('selling_price');
                const baseUnitPurchasePrice = document.querySelector('input[name="units[0][purchase_price]"]');
                const baseUnitSellingPrice = document.querySelector('input[name="units[0][selling_price]"]');

                if (mainPurchasePrice && mainSellingPrice && baseUnitPurchasePrice && baseUnitSellingPrice) {
                    // Sync from main form to unit form
                    mainPurchasePrice.addEventListener('input', function() {
                        baseUnitPurchasePrice.value = this.value;
                    });

                    mainSellingPrice.addEventListener('input', function() {
                        baseUnitSellingPrice.value = this.value;
                    });

                    // Sync from unit form to main form
                    baseUnitPurchasePrice.addEventListener('input', function() {
                        mainPurchasePrice.value = this.value;
                    });

                    baseUnitSellingPrice.addEventListener('input', function() {
                        mainSellingPrice.value = this.value;
                    });
                }

                // Show preview when image is selected
                if (imageInput && imagePreviewImg && placeholderArea && removeImageBtn) {
                    imageInput.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                imagePreviewImg.src = e.target.result;
                                placeholderArea.classList.add('hidden');
                                imagePreview.classList.remove('hidden');
                                removeImageBtn.classList.remove('hidden');
                                dropzoneContainer.classList.add('border-indigo-300',
                                    'dark:border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                                dropzoneContainer.classList.remove('border-gray-300',
                                    'dark:border-gray-600', 'bg-gray-50', 'dark:bg-gray-800');
                            }

                            reader.readAsDataURL(this.files[0]);
                        }
                    });

                    // Remove image
                    removeImageBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        imageInput.value = '';
                        imagePreviewImg.src = '#';
                        placeholderArea.classList.remove('hidden');
                        imagePreview.classList.add('hidden');
                        removeImageBtn.classList.add('hidden');
                        dropzoneContainer.classList.remove('border-indigo-300', 'dark:border-indigo-500',
                            'bg-indigo-50', 'dark:bg-indigo-900/20');
                        dropzoneContainer.classList.add('border-gray-300', 'dark:border-gray-600', 'bg-gray-50',
                            'dark:bg-gray-800');
                    });

                    // Enhance drag & drop functionality
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropzoneContainer.addEventListener(eventName, preventDefaults, false);
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropzoneContainer.addEventListener(eventName, highlight, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        dropzoneContainer.addEventListener(eventName, unhighlight, false);
                    });

                    function highlight() {
                        dropzoneContainer.classList.add('border-indigo-400', 'dark:border-indigo-400', 'bg-indigo-50',
                            'dark:bg-indigo-900/20');
                    }

                    function unhighlight() {
                        dropzoneContainer.classList.remove('border-indigo-400', 'dark:border-indigo-400');
                        if (!imageInput.files.length) {
                            dropzoneContainer.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                        }
                    }

                    dropzoneContainer.addEventListener('drop', handleDrop, false);

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;

                        if (files.length) {
                            imageInput.files = files;
                            // Trigger change event manually
                            const event = new Event('change', {
                                bubbles: true
                            });
                            imageInput.dispatchEvent(event);
                        }
                    }
                }

                // Batch Product Form Functionality
                const addProductRowBtn = document.getElementById('add-product-row');
                const productRowsContainer = document.getElementById('product-rows');
                const batchForm = document.getElementById('batch-form');
                let productRowCounter = 1;

                if (addProductRowBtn && productRowsContainer) {
                    // Add new product row
                    addProductRowBtn.addEventListener('click', function() {
                        const newRow = document.createElement('tr');
                        newRow.className = 'product-row';

                        newRow.innerHTML = `
                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 row-number">
                                ${productRowCounter + 1}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <select name="products[${productRowCounter}][category_id]" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <input type="text" name="products[${productRowCounter}][name]" placeholder="Nama Produk" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">
                                        <span class="text-xs">Rp</span>
                                    </div>
                                    <input type="number" name="products[${productRowCounter}][purchase_price]" placeholder="0" required
                                        class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">
                                        <span class="text-xs">Rp</span>
                                    </div>
                                    <input type="number" name="products[${productRowCounter}][selling_price]" placeholder="0" required
                                        class="block w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <input type="number" name="products[${productRowCounter}][stock]" placeholder="0" min="0" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <input type="number" name="products[${productRowCounter}][min_stock]" placeholder="0" min="0" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <select name="products[${productRowCounter}][unit_id]" required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 text-sm">
                                    <option value="">Pilih Satuan</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <textarea name="products[${productRowCounter}][description]" placeholder="Deskripsi (opsional)" class="hidden"></textarea>
                                <button type="button" class="delete-row text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        `;

                        productRowsContainer.appendChild(newRow);
                        productRowCounter++;

                        // Apply default category if set
                        const defaultCategory = document.getElementById('batch_category_id');
                        if (defaultCategory && defaultCategory.value) {
                            const newCategorySelect = newRow.querySelector('select[name$="[category_id]"]');
                            if (newCategorySelect) {
                                newCategorySelect.value = defaultCategory.value;
                            }
                        }

                        // Add event listener to delete button
                        const deleteBtn = newRow.querySelector('.delete-row');
                        deleteBtn.addEventListener('click', function() {
                            newRow.remove();
                            updateRowNumbers();
                        });
                    });

                    // Add event listeners to existing delete buttons
                    document.querySelectorAll('.delete-row').forEach(btn => {
                        btn.addEventListener('click', function() {
                            // Don't delete if it's the last row
                            if (document.querySelectorAll('.product-row').length > 1) {
                                this.closest('tr').remove();
                                updateRowNumbers();
                            } else {
                                alert('Minimal harus ada satu produk');
                            }
                        });
                    });

                    // Function to update row numbers
                    function updateRowNumbers() {
                        document.querySelectorAll('.row-number').forEach((cell, index) => {
                            cell.textContent = index + 1;
                        });

                        // Also update the name attributes to ensure they're sequential
                        document.querySelectorAll('.product-row').forEach((row, index) => {
                            row.querySelectorAll('[name^="products["]').forEach(input => {
                                const name = input.getAttribute('name');
                                const newName = name.replace(/products\[\d+\]/, `products[${index}]`);
                                input.setAttribute('name', newName);
                            });
                        });
                    }

                    // Validate batch form before submission
                    if (batchForm) {
                        batchForm.addEventListener('submit', function(e) {
                            e.preventDefault(); // Prevent default submission temporarily

                            const supplier = document.getElementById('batch_supplier_id');
                            if (!supplier.value) {
                                alert('Silakan pilih supplier terlebih dahulu');
                                supplier.focus();
                                return false;
                            }

                            let isValid = true;
                            let invalidFields = [];

                            document.querySelectorAll('.product-row').forEach((row, index) => {
                                const categorySelect = row.querySelector('[name$="[category_id]"]');
                                const nameInput = row.querySelector('[name$="[name]"]');
                                const purchasePriceInput = row.querySelector(
                                    '[name$="[purchase_price]"]');
                                const sellingPriceInput = row.querySelector(
                                    '[name$="[selling_price]"]');
                                const stockInput = row.querySelector('[name$="[stock]"]');
                                const minStockInput = row.querySelector('[name$="[min_stock]"]');
                                const unitInput = row.querySelector('[name$="[unit_id]"]');

                                if (!categorySelect.value) {
                                    isValid = false;
                                    invalidFields.push(`Kategori pada baris ${index + 1}`);
                                    categorySelect.classList.add('border-red-500',
                                        'dark:border-red-500');
                                } else {
                                    categorySelect.classList.remove('border-red-500',
                                        'dark:border-red-500');
                                }

                                if (!nameInput.value) {
                                    isValid = false;
                                    invalidFields.push(`Nama produk pada baris ${index + 1}`);
                                    nameInput.classList.add('border-red-500', 'dark:border-red-500');
                                } else {
                                    nameInput.classList.remove('border-red-500', 'dark:border-red-500');
                                }

                                if (!purchasePriceInput.value) {
                                    isValid = false;
                                    invalidFields.push(`Harga beli pada baris ${index + 1}`);
                                    purchasePriceInput.classList.add('border-red-500',
                                        'dark:border-red-500');
                                } else {
                                    purchasePriceInput.classList.remove('border-red-500',
                                        'dark:border-red-500');
                                }

                                if (!sellingPriceInput.value) {
                                    isValid = false;
                                    invalidFields.push(`Harga jual pada baris ${index + 1}`);
                                    sellingPriceInput.classList.add('border-red-500',
                                        'dark:border-red-500');
                                } else {
                                    sellingPriceInput.classList.remove('border-red-500',
                                        'dark:border-red-500');
                                }

                                if (!stockInput.value) {
                                    isValid = false;
                                    invalidFields.push(`Stok pada baris ${index + 1}`);
                                    stockInput.classList.add('border-red-500', 'dark:border-red-500');
                                } else {
                                    stockInput.classList.remove('border-red-500',
                                        'dark:border-red-500');
                                }

                                if (!minStockInput.value) {
                                    isValid = false;
                                    invalidFields.push(`Minimal stok pada baris ${index + 1}`);
                                    minStockInput.classList.add('border-red-500',
                                        'dark:border-red-500');
                                } else {
                                    minStockInput.classList.remove('border-red-500',
                                        'dark:border-red-500');
                                }

                                if (!unitInput.value) {
                                    isValid = false;
                                    invalidFields.push(`Satuan pada baris ${index + 1}`);
                                    unitInput.classList.add('border-red-500', 'dark:border-red-500');
                                } else {
                                    unitInput.classList.remove('border-red-500', 'dark:border-red-500');
                                }
                            });

                            if (!isValid) {
                                alert('Silakan lengkapi semua data produk:\n' + invalidFields.join('\n'));
                                return false;
                            }

                            // Proceed with form submission
                            this.submit();
                        });
                    }
                }

                const defaultCategorySelect = document.getElementById('batch_category_id');
                if (defaultCategorySelect) {
                    defaultCategorySelect.addEventListener('change', function() {
                        if (this.value) {
                            // Apply to all empty category selects
                            document.querySelectorAll('.category-select').forEach(select => {
                                if (!select.value) {
                                    select.value = this.value;
                                }
                            });
                        }
                    });
                }

                // Camera functionality
                const cameraButton = document.getElementById('camera-button');
                const cameraModal = document.getElementById('camera-modal');
                const cameraPreview = document.getElementById('camera-preview');
                const captureButton = document.getElementById('capture-photo');
                const closeCamera = document.getElementById('close-camera');
                let stream = null;

                if (cameraButton && cameraModal && cameraPreview && captureButton && closeCamera) {
                    // Open camera modal and start stream
                    cameraButton.addEventListener('click', async () => {
                        try {
                            stream = await navigator.mediaDevices.getUserMedia({
                                video: {
                                    facingMode: 'environment' // Use back camera if available
                                }
                            });
                            cameraPreview.srcObject = stream;
                            await cameraPreview.play();
                            cameraModal.classList.remove('hidden');
                        } catch (err) {
                            console.error("Error accessing camera:", err);
                            alert(
                                "Tidak dapat mengakses kamera. Pastikan Anda memberikan izin untuk menggunakan kamera."
                            );
                        }
                    });

                    // Capture photo
                    captureButton.addEventListener('click', () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = cameraPreview.videoWidth;
                        canvas.height = cameraPreview.videoHeight;
                        canvas.getContext('2d').drawImage(cameraPreview, 0, 0);

                        // Convert canvas to file
                        canvas.toBlob((blob) => {
                            const file = new File([blob], "camera-capture.jpg", {
                                type: "image/jpeg"
                            });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);

                            // Update file input and trigger change event
                            const imageInput = document.getElementById('image');
                            imageInput.files = dataTransfer.files;

                            // Trigger change event manually
                            const event = new Event('change', {
                                bubbles: true
                            });
                            imageInput.dispatchEvent(event);

                            // Close camera
                            closeCamera.click();
                        }, 'image/jpeg', 0.9); // 90% quality
                    });

                    // Close camera modal and stop stream
                    closeCamera.addEventListener('click', () => {
                        if (stream) {
                            stream.getTracks().forEach(track => track.stop());
                            stream = null;
                        }
                        cameraModal.classList.add('hidden');
                    });

                    // Also close when clicking outside the modal content
                    cameraModal.addEventListener('click', (e) => {
                        if (e.target === cameraModal) {
                            closeCamera.click();
                        }
                    });

                    // Close on escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !cameraModal.classList.contains('hidden')) {
                            closeCamera.click();
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
