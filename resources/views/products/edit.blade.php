<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Produk') }}</h2>

            <div class="mt-4 sm:mt-0 flex space-x-2">
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

        <!-- Form Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (Main Form) -->
            <div class="lg:col-span-2">
                <div
                    class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
                        class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div
                            class="rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6">
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
                                <!-- Category -->
                                <div>
                                    <label for="category_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kategori
                                    </label>
                                    <select id="category_id" name="category_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Supplier -->
                                <div>
                                    <label for="supplier_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Supplier
                                    </label>
                                    <select id="supplier_id" name="supplier_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
                                        <option value="">Pilih Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Product Code -->
                                <div>
                                    <label for="code"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kode Produk
                                    </label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                            Kode
                                        </span>
                                        <input type="text" id="code" name="code"
                                            value="{{ old('code', $product->code) }}" readonly
                                            class="flex-1 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    </div>
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama Produk
                                    </label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $product->name) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Deskripsi
                                    </label>
                                    <textarea id="description" name="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Stock Section -->
                        <div
                            class="rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6">
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

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Purchase Price -->
                                <div>
                                    <label for="purchase_price"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Harga Beli
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" id="purchase_price" name="purchase_price"
                                            value="{{ old('purchase_price', $product->purchase_price) }}" required
                                            class="block w-full pl-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
                                    </div>
                                </div>

                                <!-- Selling Price -->
                                <div>
                                    <label for="selling_price"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Harga Jual
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" id="selling_price" name="selling_price"
                                            value="{{ old('selling_price', $product->selling_price) }}" required
                                            class="block w-full pl-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
                                    </div>
                                </div>

                                <!-- Current Stock -->
                                <div>
                                    <label for="current_stock"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Stok Saat Ini
                                    </label>
                                    <input type="number" id="current_stock" value="{{ $product->stock }}" readonly
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-600">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Stok dapat disesuaikan di
                                        halaman detail produk</p>
                                </div>

                                <!-- Minimum Stock -->
                                <div>
                                    <label for="min_stock"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Stok Minimum
                                    </label>
                                    <input type="number" id="min_stock" name="min_stock"
                                        value="{{ old('min_stock', $product->min_stock) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="window.history.back()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column (Image Upload) -->
            <div>
                <div
                    class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Gambar Produk
                    </h3>

                    <div class="space-y-4">
                        <!-- Current Image Preview -->
                        <div id="current-image-container"
                            class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            @if ($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                    class="object-cover w-full h-full">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Image Upload Controls -->
                        <div class="space-y-3">
                            <input type="file" id="image" name="image" class="hidden" accept="image/*">

                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" onclick="document.getElementById('image').click()"
                                    class="flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Pilih File
                                </button>

                                <button type="button" id="camera-button"
                                    class="flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ambil Foto
                                </button>
                            </div>

                            @if ($product->image_path)
                                <form action="{{ route('products.deleteImage', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')"
                                        class="w-full flex justify-center items-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus Gambar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Elements
                const form = document.getElementById('product-form');
                const imageInput = document.getElementById('image');
                const selectImageBtn = document.getElementById('select-image-btn');
                const deleteImageBtn = document.getElementById('delete-image-btn');
                const deleteImageForm = document.getElementById('delete-image-form');
                const currentImageContainer = document.getElementById('current-image-container');
                const previewContainer = document.getElementById('preview-container');
                const imagePreview = document.getElementById('image-preview');
                const previewActions = document.getElementById('preview-actions');
                const cancelPreviewBtn = document.getElementById('cancel-preview-btn');
                const imageExistsActions = document.getElementById('image-exists-actions');

                // Live preview elements
                const previewName = document.getElementById('preview-name');
                const previewCategory = document.getElementById('preview-category');
                const previewPrice = document.getElementById('preview-price');
                const previewStock = document.getElementById('preview-stock');

                // Form elements for live preview
                const nameInput = document.getElementById('name');
                const categorySelect = document.getElementById('category_id');
                const sellingPriceInput = document.getElementById('selling_price');
                const currentStockInput = document.getElementById('current_stock');
                const minStockInput = document.getElementById('min_stock');

                // Image selection
                selectImageBtn.addEventListener('click', function() {
                    imageInput.click();
                });

                // Image preview
                imageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            currentImageContainer.classList.add('hidden');
                            previewContainer.classList.remove('hidden');
                            previewActions.classList.remove('hidden');
                            if (imageExistsActions) {
                                imageExistsActions.classList.add('hidden');
                            }
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // Cancel preview
                cancelPreviewBtn.addEventListener('click', function() {
                    imageInput.value = '';
                    previewContainer.classList.add('hidden');
                    currentImageContainer.classList.remove('hidden');
                    previewActions.classList.add('hidden');
                    if (imageExistsActions) {
                        imageExistsActions.classList.remove('hidden');
                    }
                });

                // Delete image
                if (deleteImageBtn) {
                    deleteImageBtn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to delete this image?')) {
                            deleteImageForm.submit();
                        }
                    });
                }

                // Live preview updates
                function updatePreview() {
                    // Update name
                    previewName.textContent = nameInput.value || '{{ $product->name }}';

                    // Update category
                    const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
                    previewCategory.textContent = selectedCategory ? selectedCategory.text :
                        '{{ $product->category->name }}';

                    // Update price
                    const price = sellingPriceInput.value || {{ $product->selling_price }};
                    previewPrice.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);

                    // Update stock status
                    const currentStock = parseInt(currentStockInput.value) || {{ $product->stock }};
                    const minStock = parseInt(minStockInput.value) || {{ $product->min_stock }};

                    if (currentStock > minStock) {
                        previewStock.className =
                            'inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-green-800';
                        previewStock.textContent = 'In Stock';
                    } else {
                        previewStock.className =
                            'inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-red-100 text-red-800';
                        previewStock.textContent = 'Low Stock';
                    }
                }

                // Add event listeners for live preview
                nameInput.addEventListener('input', updatePreview);
                categorySelect.addEventListener('change', updatePreview);
                sellingPriceInput.addEventListener('input', updatePreview);
                minStockInput.addEventListener('input', updatePreview);

                // Alert auto-dismiss
                const successAlert = document.getElementById('success-alert');
                const errorAlert = document.getElementById('error-alert');

                if (successAlert) {
                    setTimeout(() => {
                        successAlert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                        setTimeout(() => {
                            successAlert.remove();
                        }, 500);
                    }, 5000);
                }
            });
        </script>
    @endpush

</x-app-layout>
