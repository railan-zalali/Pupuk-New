<x-app-layout>
    <div class="space-y-6 max-w-full overflow-hidden">
        <!-- Page Heading -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Produk') }}</h2>

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

        <div
            class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
                class="p-4 md:p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
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
                                <x-text-input type="text" id="code" name="code" value="{{ $product->code }}"
                                    readonly
                                    class="flex-1 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" />
                            </div>
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="name" value="Nama Produk" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ $product->name }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Kategori" />
                            <select id="category_id" name="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
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
                                    <option value="{{ $supplier->id }}"
                                        {{ $supplierId == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">{{ $product->description }}</textarea>
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
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
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
                                    min="0" step="1" value="{{ $product->purchase_price }}"
                                    class="block w-full pl-10" required />
                            </div>
                            <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="selling_price" value="Harga Jual" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                </div>
                                <x-text-input type="number" name="selling_price" id="selling_price" min="0"
                                    step="1" value="{{ $product->selling_price }}" class="block w-full pl-10"
                                    required />
                            </div>
                            <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stock" value="Stok" />
                            <x-text-input type="number" name="stock" id="stock" min="0"
                                step="1" value="{{ $product->stock }}" class="mt-1 block w-full" readonly />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Stok hanya dapat diubah melalui
                                menu penyesuaian stok</p>
                        </div>

                        <div>
                            <x-input-label for="min_stock" value="Minimal Stok" />
                            <x-text-input type="number" name="min_stock" id="min_stock" min="0"
                                step="1" value="{{ $product->min_stock }}" class="mt-1 block w-full"
                                required />
                            <x-input-error :messages="$errors->get('min_stock')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section: Units of Measure -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
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
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Kelola satuan untuk produk ini.</p>
                    </div>

                    <div id="units-container">
                        @foreach ($product->productUnits as $index => $productUnit)
                            <div
                                class="unit-row grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <input type="hidden" name="units[{{ $index }}][id]"
                                    value="{{ $productUnit->id }}">
                                <div>
                                    <x-input-label value="Satuan" />
                                    <select name="units[{{ $index }}][unit_id]"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                        required>
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $productUnit->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->abbreviation }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Konversi" />
                                    @if ($productUnit->is_default)
                                        <input type="hidden" name="units[{{ $index }}][conversion_factor]"
                                            value="1">
                                        <x-text-input type="number" value="1" min="1" step="1"
                                            class="mt-1 block w-full" readonly />
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">1 = Satuan Dasar</p>
                                    @else
                                        <x-text-input type="number"
                                            name="units[{{ $index }}][conversion_factor]"
                                            value="{{ $productUnit->conversion_factor }}" min="1"
                                            step="1" class="mt-1 block w-full" required />
                                    @endif
                                </div>
                                <div>
                                    <x-input-label value="Harga Beli" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <x-text-input type="number"
                                            name="units[{{ $index }}][purchase_price]"
                                            value="{{ $productUnit->purchase_price }}" min="0" step="1"
                                            class="block w-full pl-10" required />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Harga Jual" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <x-text-input type="number" name="units[{{ $index }}][selling_price]"
                                            value="{{ $productUnit->selling_price }}" min="0" step="1"
                                            class="block w-full pl-10" required />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Tanggal Expired" />
                                    <x-text-input type="date" name="units[{{ $index }}][expire_date]"
                                        value="{{ $productUnit->expire_date ? $productUnit->expire_date->format('Y-m-d') : '' }}"
                                        class="mt-1 block w-full" />
                                </div>
                                <div class="flex items-end">
                                    @if ($productUnit->is_default)
                                        <input type="hidden" name="units[{{ $index }}][is_default]"
                                            value="1">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Satuan Dasar
                                        </span>
                                    @else
                                        <div class="flex items-center">
                                            <input type="checkbox" name="units[{{ $index }}][is_default]"
                                                id="is_default_{{ $index }}"
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                value="1">
                                            <label for="is_default_{{ $index }}"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                Satuan Utama
                                            </label>
                                        </div>
                                        <button type="button"
                                            class="remove-unit ml-auto text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-4">
                            <button type="button" id="add-unit-btn"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors">
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
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700 mt-6">
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Gambar Produk
                    </h3>

                    <div class="mt-1">
                        <div class="flex space-x-4 mb-4">
                            <label for="image" class="cursor-pointer flex-1">
                                <div
                                    class="px-4 py-2 text-center border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="w-5 h-5 inline mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Upload Gambar
                                </div>
                            </label>
                            <button type="button" id="camera-button"
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 inline mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4H19a2 2 0 002 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Ambil Foto
                            </button>
                        </div>

                        <input type="file" id="image" name="image" class="hidden" accept="image/*">

                        <div id="dropzone-container"
                            class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 bg-gray-50 dark:bg-gray-800 overflow-hidden">
                            @if ($product->image_path)
                                <div id="image-preview" class="absolute inset-0 flex items-center justify-center">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                        class="max-h-full max-w-full object-contain">
                                </div>
                                <div id="image-overlay"
                                    class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <button type="button" id="remove-image"
                                        class="bg-red-600 text-white p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <div id="placeholder-container"
                                    class="flex flex-col items-center justify-center p-5 text-center">
                                    <svg class="w-14 h-14 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        SVG, PNG, JPG or GIF (Maks. 2MB)
                                    </p>
                                </div>
                            @endif

                            <div id="new-image-preview"
                                class="absolute inset-0 flex items-center justify-center hidden">
                                <img src="" alt="Preview" class="max-h-full max-w-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const unitsContainer = document.getElementById('units-container');
                const addUnitBtn = document.getElementById('add-unit-btn');
                let unitIndex = {{ count($product->productUnits) }};

                // Fungsi untuk membuat baris satuan baru
                function createUnitRow() {
                    const template = `
                        <div class="unit-row grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <x-input-label value="Satuan" />
                                <select name="units[${unitIndex}][unit_id]"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                    required>
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label value="Konversi" />
                                <x-text-input type="number" name="units[${unitIndex}][conversion_factor]"
                                    value="1" min="1" step="1" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label value="Harga Beli" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <x-text-input type="number" name="units[${unitIndex}][purchase_price]"
                                        value="0" min="0" step="1" class="block w-full pl-10" required />
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Harga Jual" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                    </div>
                                    <x-text-input type="number" name="units[${unitIndex}][selling_price]"
                                        value="0" min="0" step="1" class="block w-full pl-10" required />
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Tanggal Expired" />
                                <x-text-input type="date" name="units[${unitIndex}][expire_date]"
                                    class="mt-1 block w-full" />
                            </div>
                            <div class="flex items-end">
                                <div class="flex items-center">
                                    <input type="checkbox" name="units[${unitIndex}][is_default]"
                                        id="is_default_${unitIndex}"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                        value="1">
                                    <label for="is_default_${unitIndex}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Satuan Utama
                                    </label>
                                </div>
                                <button type="button" class="remove-unit ml-auto text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                    unitsContainer.insertAdjacentHTML('beforeend', template);
                    unitIndex++;
                }

                // Event listener untuk tombol tambah satuan
                addUnitBtn.addEventListener('click', createUnitRow);

                // Event listener untuk tombol hapus satuan
                unitsContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-unit')) {
                        const unitRow = e.target.closest('.unit-row');
                        if (unitRow) {
                            unitRow.remove();
                        }
                    }
                });

                // Event listener untuk checkbox satuan utama
                unitsContainer.addEventListener('change', function(e) {
                    if (e.target.matches('input[type="checkbox"][name$="[is_default]"]')) {
                        const checkboxes = unitsContainer.querySelectorAll(
                            'input[type="checkbox"][name$="[is_default]"]');
                        checkboxes.forEach(checkbox => {
                            if (checkbox !== e.target) {
                                checkbox.checked = false;
                            }
                        });
                    }
                });

                // Handle image upload
                const imageInput = document.getElementById('image');
                const dropzoneContainer = document.getElementById('dropzone-container');
                const placeholderContainer = document.getElementById('placeholder-container');
                const newImagePreview = document.getElementById('new-image-preview');
                const imagePreview = document.getElementById('image-preview');
                const removeImageBtn = document.getElementById('remove-image');
                const cameraButton = document.getElementById('camera-button');

                imageInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (imagePreview) {
                                imagePreview.classList.add('hidden');
                            }
                            if (placeholderContainer) {
                                placeholderContainer.classList.add('hidden');
                            }

                            const img = newImagePreview.querySelector('img');
                            img.src = e.target.result;
                            newImagePreview.classList.remove('hidden');
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // Handle drag and drop
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
                    dropzoneContainer.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                }

                function unhighlight() {
                    dropzoneContainer.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                }

                dropzoneContainer.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    imageInput.files = files;

                    const event = new Event('change', {
                        bubbles: true
                    });
                    imageInput.dispatchEvent(event);
                }

                // Remove image
                if (removeImageBtn) {
                    removeImageBtn.addEventListener('click', function() {
                        const removeImageInput = document.createElement('input');
                        removeImageInput.type = 'hidden';
                        removeImageInput.name = 'remove_image';
                        removeImageInput.value = '1';
                        dropzoneContainer.appendChild(removeImageInput);

                        if (imagePreview) {
                            imagePreview.classList.add('hidden');
                        }

                        if (placeholderContainer) {
                            placeholderContainer.classList.remove('hidden');
                        } else {
                            const placeholder = document.createElement('div');
                            placeholder.id = 'placeholder-container';
                            placeholder.className = 'flex flex-col items-center justify-center p-5 text-center';
                            placeholder.innerHTML = `
                            <svg class="w-14 h-14 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                SVG, PNG, JPG or GIF (Maks. 2MB)
                            </p>
                        `;
                            dropzoneContainer.appendChild(placeholder);
                        }

                        document.getElementById('image-overlay').classList.add('hidden');
                    });
                }

                // Camera functionality
                if (cameraButton) {
                    cameraButton.addEventListener('click', async function() {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            alert('Kamera tidak didukung di browser ini');
                            return;
                        }

                        try {
                            const stream = await navigator.mediaDevices.getUserMedia({
                                video: true
                            });
                            const videoElement = document.getElementById('camera-preview');
                            videoElement.srcObject = stream;
                            videoElement.play();

                            const cameraContainer = document.createElement('div');
                            cameraContainer.className =
                                'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                            cameraContainer.id = 'camera-container';

                            cameraContainer.innerHTML = `
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg max-w-lg w-full mx-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ambil Foto</h3>
                                    <button id="close-camera" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <video id="camera-preview" class="w-full h-64 bg-black rounded-lg mb-4"></video>
                                <div class="flex justify-center">
                                    <button id="capture-photo" class="bg-indigo-600 text-white px-4 py-2 rounded-full">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4H19a2 2 0 002 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `;

                            document.body.appendChild(cameraContainer);

                            document.getElementById('close-camera').addEventListener('click', function() {
                                stream.getTracks().forEach(track => track.stop());
                                cameraContainer.remove();
                            });

                            document.getElementById('capture-photo').addEventListener('click', function() {
                                const canvas = document.createElement('canvas');
                                canvas.width = videoElement.videoWidth;
                                canvas.height = videoElement.videoHeight;
                                canvas.getContext('2d').drawImage(videoElement, 0, 0);

                                canvas.toBlob(function(blob) {
                                    const file = new File([blob], "camera-photo.jpg", {
                                        type: "image/jpeg"
                                    });

                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(file);
                                    imageInput.files = dataTransfer.files;

                                    const event = new Event('change', {
                                        bubbles: true
                                    });
                                    imageInput.dispatchEvent(event);

                                    stream.getTracks().forEach(track => track.stop());
                                    cameraContainer.remove();
                                }, 'image/jpeg');
                            });
                        } catch (err) {
                            alert('Error accessing camera: ' + err.message);
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
