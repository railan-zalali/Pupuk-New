<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Product') }}
            </h2>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="mx-2 text-gray-400">/</span>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">
                                Products
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <span class="mx-2 text-gray-400">/</span>
                            <span class="text-gray-500">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Messages -->
            @if (session('success'))
                <div id="success-alert"
                    class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md"
                    role="alert">
                    <div class="flex items-center">
                        <div class="py-1">
                            <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div id="error-alert"
                    class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md">
                    <div class="flex items-center mb-2">
                        <svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="font-medium">Please correct the following errors:</p>
                    </div>
                    <ul class="list-disc ml-10 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Left column (form) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form id="product-form" method="POST" action="{{ route('products.update', $product) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Basic Info -->
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Basic Information</h3>

                                <!-- Product Code -->
                                <div class="mb-4">
                                    <x-input-label for="code" value="Product Code" />
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                            Code
                                        </span>
                                        <input type="text" name="code" id="code"
                                            value="{{ old('code', $product->code) }}"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                            readonly>
                                    </div>
                                    @error('code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Product Name -->
                                <div class="mb-4">
                                    <x-input-label for="name" value="Product Name" />
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $product->name) }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-4">
                                    <x-input-label for="category_id" value="Category" />
                                    <select id="category_id" name="category_id"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required>
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <x-input-label for="description" value="Description" />
                                    <textarea id="description" name="description" rows="3"
                                        class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing & Stock -->
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Pricing & Stock</h3>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <!-- Purchase Price -->
                                    <div>
                                        <x-input-label for="purchase_price" value="Purchase Price (Rp)" />
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" name="purchase_price" id="purchase_price"
                                                value="{{ old('purchase_price', $product->purchase_price) }}"
                                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 sm:text-sm border-gray-300 rounded-md"
                                                min="0" required>
                                        </div>
                                        @error('purchase_price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Selling Price -->
                                    <div>
                                        <x-input-label for="selling_price" value="Selling Price (Rp)" />
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" name="selling_price" id="selling_price"
                                                value="{{ old('selling_price', $product->selling_price) }}"
                                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 sm:text-sm border-gray-300 rounded-md"
                                                min="0" required>
                                        </div>
                                        @error('selling_price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Current Stock (read-only) -->
                                    <div>
                                        <x-input-label for="current_stock" value="Current Stock" />
                                        <input type="number" id="current_stock" value="{{ $product->stock }}"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md shadow-sm sm:text-sm"
                                            readonly>
                                        <p class="mt-1 text-xs text-gray-500">Stock can be adjusted on the product
                                            detail page</p>
                                    </div>

                                    <!-- Minimum Stock -->
                                    <div>
                                        <x-input-label for="min_stock" value="Minimum Stock Level" />
                                        <input type="number" name="min_stock" id="min_stock"
                                            value="{{ old('min_stock', $product->min_stock) }}"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            min="0" required>
                                        @error('min_stock')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Product image - hidden, actual UI is in the right column -->
                            <input type="file" name="image" id="image" class="hidden" accept="image/*">

                            <!-- Form buttons -->
                            <div class="flex justify-end space-x-3 mt-8">
                                <a href="{{ route('products.index') }}"
                                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Update Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right column (image and preview) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full md:w-80">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Image</h3>

                        <!-- Current image or placeholder -->
                        <div class="mb-6 bg-gray-100 rounded-lg overflow-hidden">
                            <div id="current-image-container" class="w-full h-64 flex items-center justify-center">
                                @if ($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                        class="max-h-full max-w-full object-contain" id="current-product-image">
                                @else
                                    <div class="text-center p-4">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No image</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Image preview (hidden initially) -->
                            <div id="preview-container" class="hidden w-full h-64 flex items-center justify-center">
                                <img src="#" alt="Preview" class="max-h-full max-w-full object-contain"
                                    id="image-preview">
                            </div>
                        </div>

                        <!-- Image controls -->
                        <div class="flex flex-col space-y-3">
                            <button type="button" id="select-image-btn"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Select Image
                            </button>

                            @if ($product->image_path)
                                <div id="image-exists-actions" class="flex justify-between">
                                    <a href="{{ Storage::url($product->image_path) }}" target="_blank"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>

                                    <button type="button" id="delete-image-btn"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            @endif

                            <div id="preview-actions" class="hidden space-x-2">
                                <button type="button" id="cancel-preview-btn"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                            </div>
                        </div>

                        <!-- Delete image form (hidden) -->
                        <form id="delete-image-form" action="{{ route('products.deleteImage', $product) }}"
                            method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>

                    <!-- Product Info Preview -->
                    <div class="p-6 bg-white">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Preview</h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="block text-gray-500 font-medium">Name</span>
                                <span id="preview-name" class="block">{{ $product->name }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 font-medium">Category</span>
                                <span id="preview-category" class="block">{{ $product->category->name }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 font-medium">Selling Price</span>
                                <span id="preview-price" class="block">Rp
                                    {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 font-medium">Stock Status</span>
                                <span id="preview-stock"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium 
                                    {{ $product->stock > $product->min_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->stock > $product->min_stock ? 'In Stock' : 'Low Stock' }}
                                </span>
                            </div>
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
