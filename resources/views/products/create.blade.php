<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Produk') }}</h2>

            <a href="{{ route('products.index') }}"
                class="inline-flex items-center p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
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
        <div
            class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6">
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Kategori
                            </label>
                            <select id="category_id" name="category_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Code -->
                        <div>
                            <x-input-label for="code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                            Kode Produk

                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                    Kode
                                </span>
                                <x-text-input type="text" id="code" name="code" value="{{ $productCode }}"
                                    readonly
                                    class="flex-1 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="name" value="Nama Produk"
                                class="font-medium text-gray-700 dark:text-gray-700" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm"
                                :value="old('name')" placeholder="Masukkan nama produk" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <textarea id="description" name="description"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50"
                                rows="3" placeholder="Deskripsi singkat tentang produk ini">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section: Harga & Stok -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700">
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <x-input-label for="purchase_price" value="Harga Beli"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">
                                    <span class="sm:text-sm">Rp</span>
                                </div>
                                <x-text-input id="purchase_price" name="purchase_price" type="number"
                                    class="mt-1 block w-full pl-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50"
                                    :value="old('purchase_price')" placeholder="0" required />
                            </div>
                            <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="selling_price" value="Harga Jual"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">
                                    <span class="sm:text-sm">Rp</span>
                                </div>
                                <x-text-input id="selling_price" name="selling_price" type="number"
                                    class="mt-1 block w-full pl-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50"
                                    :value="old('selling_price')" placeholder="0" required />
                            </div>
                            <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stock" value="Stok Awal"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <x-text-input id="stock" name="stock" type="number"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50"
                                :value="old('stock', 0)" min="0" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="min_stock" value="Stok Minimum"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <x-text-input id="min_stock" name="min_stock" type="number"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50"
                                :value="old('min_stock', 0)" min="0" required />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Notifikasi akan muncul jika stok
                                di
                                bawah nilai ini</p>
                            <x-input-error :messages="$errors->get('min_stock')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section: Gambar Produk -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700">
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
                                    <p class="mb-2 text-sm text-gray-700 dark:text-gray-300 font-medium">Klik untuk
                                        upload
                                        gambar atau ambil foto</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG maksimal 2MB</p>
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
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <!-- Camera Modal -->
                <div id="camera-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 dark:bg-opacity-80">
                    <div class="relative w-full max-w-lg mx-auto mt-10">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                            <div class="p-4">
                                <video id="camera-preview" class="w-full h-64 object-cover"></video>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button type="button" id="capture-photo"
                                        class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">Ambil
                                        Foto</button>
                                    <button type="button" id="close-camera"
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 mt-6">
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
    </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const imageInput = document.getElementById('image');
                const dropzoneContainer = document.getElementById('dropzone-container');
                const placeholderArea = document.getElementById('placeholder-area');
                const imagePreview = document.getElementById('image-preview');
                const imagePreviewImg = imagePreview.querySelector('img');
                const removeImageBtn = document.getElementById('remove-image');

                // Show preview when image is selected
                imageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            imagePreviewImg.src = e.target.result;
                            placeholderArea.classList.add('hidden');
                            imagePreview.classList.remove('hidden');
                            removeImageBtn.classList.remove('hidden');
                            dropzoneContainer.classList.add('border-indigo-300', 'bg-indigo-50');
                            dropzoneContainer.classList.remove('border-gray-300', 'bg-gray-50');
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
                    dropzoneContainer.classList.remove('border-indigo-300', 'bg-indigo-50');
                    dropzoneContainer.classList.add('border-gray-300', 'bg-gray-50');
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
                    dropzoneContainer.classList.add('border-indigo-400', 'bg-indigo-50');
                }

                function unhighlight() {
                    dropzoneContainer.classList.remove('border-indigo-400');
                    if (!imageInput.files.length) {
                        dropzoneContainer.classList.remove('bg-indigo-50');
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
            });
            // Camera functionality
            const cameraButton = document.getElementById('camera-button');
            const cameraModal = document.getElementById('camera-modal');
            const cameraPreview = document.getElementById('camera-preview');
            const captureButton = document.getElementById('capture-photo');
            const closeCamera = document.getElementById('close-camera');
            let stream = null;

            // Open camera modal and start stream
            cameraButton.addEventListener('click', async () => {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment' // Use back camera if available
                        }
                    });
                    cameraPreview.srcObject = stream;
                    cameraPreview.play();
                    cameraModal.classList.remove('hidden');
                } catch (err) {
                    console.error("Error accessing camera:", err);
                    alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin untuk menggunakan kamera.");
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
                }, 'image/jpeg');
            });

            // Close camera modal and stop stream
            closeCamera.addEventListener('click', () => {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                cameraModal.classList.add('hidden');
            });
        </script>
    @endpush
</x-app-layout>
