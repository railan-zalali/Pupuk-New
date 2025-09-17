@props([
    'product' => null,
    'selectedBatches' => [],
    'maxQuantity' => null,
    'modalId' => 'batchSelectorModal',
    'title' => 'Pilih Batch Produk'
])

<!-- Batch Selector Component -->
<div x-data="batchSelector({
        product: {{ $product ? json_encode($product) : 'null' }},
        selectedBatches: {{ json_encode($selectedBatches) }},
        maxQuantity: {{ $maxQuantity ?? 'null' }}
    })"
    x-init="
        // Make this component globally accessible
        window.batchSelectorComponent = $data;
        
        // Listen for external events
        $watch('isModalOpen', (value) => {
            if (value) {
                document.body.style.overflow = 'hidden';
                if (product) {
                    loadBatches();
                }
            } else {
                document.body.style.overflow = '';
            }
        });
    "
    @keydown.escape.window="closeModal()"
    @open-batch-selector.window="openModal($event.detail)"
    class="batch-selector-modal">
    
    <!-- Modal Backdrop -->
    <div x-show="isModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50"
         @click="closeModal()"></div>
    
    <!-- Modal Panel -->
    <div x-show="isModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-50 overflow-y-auto">
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 w-full max-w-4xl">
                
                <!-- Modal Header -->
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ $title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="product ? `Pilih batch untuk ${product.name}` : 'Pilih batch produk'"></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <!-- FEFO Info -->
                            <div class="flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">FEFO</span>
                            </div>
                            
                            <button @click="closeModal()" 
                                    class="rounded-md bg-white dark:bg-gray-700 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6" style="max-height: 70vh; overflow-y: auto;">
                    <div class="w-full">
                        
                        <!-- Product Info -->
                        <div x-show="product" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 h-12 w-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-lg font-medium text-indigo-600 dark:text-indigo-400" x-text="product ? product.name.charAt(0).toUpperCase() : ''"></span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="product ? product.name : ''"></h4>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span x-text="product ? `Kode: ${product.code}` : ''"></span>
                                        <span x-text="product ? `Stok Total: ${product.stock}` : ''"></span>
                                        <span x-show="product && product.stock_method" x-text="product ? `Metode: ${product.stock_method.toUpperCase()}` : ''"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quantity Input -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jumlah yang Dibutuhkan
                            </label>
                            <div class="flex items-center space-x-4">
                                <input x-model.number="requiredQuantity" 
                                       @input="calculateBatchAllocation()"
                                       type="number" 
                                       min="1" 
                                       :max="maxQuantity || (product ? product.stock : null)"
                                       class="block w-32 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 text-sm"
                                       placeholder="0">
                                <span class="text-sm text-gray-500 dark:text-gray-400" x-text="product ? product.base_unit : ''"></span>
                                <button @click="autoAllocateBatches()" 
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Auto FEFO
                                </button>
                            </div>
                        </div>
                        
                        <!-- Loading State -->
                        <div x-show="loading" class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Memuat batch...</span>
                        </div>
                        
                        <!-- Batch List -->
                        <div x-show="!loading && batches.length > 0" class="space-y-3">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Batch Tersedia (Urutan FEFO)
                                </h5>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <span x-text="batches.length"></span> batch tersedia
                                </div>
                            </div>
                            
                            <template x-for="(batch, index) in batches" :key="batch.id">
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                     :class="getBatchStatusClass(batch)">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium"
                                                         :class="getBatchPriorityClass(index)">
                                                        <span x-text="index + 1"></span>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-4">
                                                        <h6 class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="batch.batch_number"></h6>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                                              :class="getExpiryStatusClass(batch)">
                                                            <span x-text="getExpiryStatusText(batch)"></span>
                                                        </span>
                                                    </div>
                                                    <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                        <span x-text="batch.expiry_date ? `Kadaluarsa: ${formatDate(batch.expiry_date)}` : 'Tanpa tanggal kadaluarsa'"></span>
                                                        <span x-text="`Sisa: ${batch.remaining_quantity}`"></span>
                                                        <span x-text="`Harga: ${formatCurrency(batch.purchase_price)}`"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center space-x-2">
                                                <label class="text-xs text-gray-500 dark:text-gray-400">Ambil:</label>
                                                <input x-model.number="batch.selected_quantity" 
                                                       @input="updateBatchSelection(batch)"
                                                       type="number" 
                                                       min="0" 
                                                       :max="batch.remaining_quantity"
                                                       class="block w-20 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 text-xs"
                                                       placeholder="0">
                                            </div>
                                            <button @click="selectFullBatch(batch)" 
                                                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                                                Pilih Semua
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <!-- No Batches -->
                        <div x-show="!loading && batches.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-6m-10 0H4m6 0v5m4-5v5"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada batch tersedia</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Produk ini tidak memiliki batch yang tersedia.</p>
                        </div>
                        
                        <!-- Selection Summary -->
                        <div x-show="getSelectedBatches().length > 0" class="mt-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                            <h6 class="text-sm font-medium text-indigo-900 dark:text-indigo-100 mb-2">Ringkasan Pilihan</h6>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-indigo-700 dark:text-indigo-300">Total Batch Dipilih:</span>
                                    <span class="font-medium text-indigo-900 dark:text-indigo-100" x-text="getSelectedBatches().length"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-indigo-700 dark:text-indigo-300">Total Kuantitas:</span>
                                    <span class="font-medium text-indigo-900 dark:text-indigo-100" x-text="getTotalSelectedQuantity()"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-indigo-700 dark:text-indigo-300">Rata-rata Harga:</span>
                                    <span class="font-medium text-indigo-900 dark:text-indigo-100" x-text="formatCurrency(getAveragePrice())"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="confirmSelection()" 
                            :disabled="getSelectedBatches().length === 0"
                            :class="getSelectedBatches().length === 0 ? 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600'"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Konfirmasi Pilihan
                    </button>
                    <button @click="closeModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>