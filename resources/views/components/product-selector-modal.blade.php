@props([
    'products' => [],
    'categories' => [],
    'selectedProducts' => [],
    'multiple' => true,
    'modalId' => 'productSelectorModal',
    'title' => 'Pilih Produk'
])

<!-- Product Selector Modal -->
<div x-data="enhancedProductSelector(
        {{ json_encode($products) }}, 
        {{ json_encode($categories) }}, 
        {
            multiple: {{ $multiple ? 'true' : 'false' }},
            showFilters: true,
            enableAnimations: true,
            autoClose: {{ $multiple ? 'false' : 'true' }}
        }
    )"
    x-init="
        // Make this component globally accessible
        window.productSelectorComponent = $data;
        
        // Listen for external events
        $watch('isModalOpen', (value) => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        
        // Load initial data
        if (products.length === 0) {
            loadProducts();
        }
    "
    @keydown.escape.window="closeModal()"
    @open-product-selector.window="openModal()"
    class="product-selector-modal">
    
    <!-- Debug info (remove in production) -->
    <div x-show="false" x-text="'Modal open: ' + isModalOpen"></div>
    
    <!-- Modal Backdrop -->
    <div x-show="isModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"
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
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 w-full max-w-6xl">
                
                <!-- Modal Header -->
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ $title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pilih produk yang ingin ditambahkan ke transaksi</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Quick Actions -->
                            <button @click="productSelector.clearAllFilters()" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </button>
                            
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
                        
                        <!-- Search and Filters Section -->
                        <div class="mb-6">
                            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                                <!-- Search Bar -->
                                <div class="relative flex-1 max-w-md">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input x-model="searchQuery" 
                                           @input.debounce.300ms="filterProducts()"
                                           type="text" 
                                           placeholder="Cari produk, kode, atau kategori..."
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    
                                    <!-- Search Results Dropdown -->
                                    <div x-show="searchQuery.length > 0 && searchResults.length > 0" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                                        <template x-for="product in searchResults.slice(0, 8)" :key="product.id">
                                            <div @click="selectProductFromSearch(product)" 
                                                 class="cursor-pointer select-none relative py-3 pl-3 pr-9 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300" x-text="product.name.charAt(0).toUpperCase()"></span>
                                                    </div>
                                                    <div class="ml-3 flex-1">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="product.name"></p>
                                                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                                            <span x-text="formatCurrency(product.selling_price)"></span>
                                                            <span>•</span>
                                                            <span>Stok: </span><span x-text="product.stock"></span>
                                                            <span>•</span>
                                                            <span x-text="product.category?.name || 'Tanpa Kategori'"></span>
                                                        </div>
                                                    </div>
                                                    <div x-show="isSelected(product.id)" class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Filter and View Controls -->
                                <div class="flex items-center gap-3">
                                    <!-- Filter Toggle -->
                                    <button @click="showFilters = !showFilters" 
                                            :class="showFilters ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600'"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                        </svg>
                                        Filter
                                        <span x-show="hasActiveFilters()" class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">!</span>
                                    </button>
                                    
                                    <!-- View Mode Toggle -->
                                    <div class="flex rounded-lg shadow-sm">
                                        <button @click="viewMode = 'grid'" 
                                                :class="viewMode === 'grid' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600'"
                                                class="relative inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 dark:border-gray-600 text-sm font-medium focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                            </svg>
                                        </button>
                                        <button @click="viewMode = 'list'" 
                                                :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600'"
                                                class="relative inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-300 dark:border-gray-600 text-sm font-medium focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Advanced Filters Panel -->
                            <div x-show="showFilters" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <!-- Category Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                                        <select x-model="activeFilters.category" @change="filterProducts()" 
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Semua Kategori</option>
                                            <template x-for="category in categories" :key="category.id">
                                                <option :value="category.id" x-text="category.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <!-- Stock Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Stok</label>
                                        <select x-model="activeFilters.stock" @change="filterProducts()" 
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="all">Semua Produk</option>
                                            <option value="available">Tersedia (Stok > 0)</option>
                                            <option value="low">Stok Menipis</option>
                                            <option value="out">Stok Habis</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Sort By -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Urutkan</label>
                                        <select x-model="activeFilters.sortBy" @change="filterProducts()" 
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="name">Nama Produk</option>
                                            <option value="price">Harga</option>
                                            <option value="stock">Stok</option>
                                            <option value="category">Kategori</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Sort Order -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Urutan</label>
                                        <select x-model="activeFilters.sortOrder" @change="filterProducts()" 
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="asc">A-Z / Rendah-Tinggi</option>
                                            <option value="desc">Z-A / Tinggi-Rendah</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Price Range -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rentang Harga</label>
                                    <div class="flex gap-2">
                                        <input x-model="activeFilters.priceRange.min" @input.debounce.500ms="filterProducts()" 
                                               type="number" placeholder="Harga minimum" 
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="flex items-center text-gray-500 dark:text-gray-400">-</span>
                                        <input x-model="activeFilters.priceRange.max" @input.debounce.500ms="filterProducts()" 
                                               type="number" placeholder="Harga maksimum" 
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                                
                                <!-- Active Filters -->
                                <div x-show="hasActiveFilters()" class="mt-4 flex flex-wrap gap-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Filter aktif:</span>
                                    <template x-for="filter in getActiveFilters()" :key="filter.key">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">
                                            <span x-text="filter.label"></span>
                                            <button @click="removeFilter(filter.key)" class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full text-indigo-400 hover:bg-indigo-200 hover:text-indigo-500 focus:outline-none">
                                                <svg class="w-2 h-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                                    <path stroke-linecap="round" stroke-width="1.5" d="m1 1 6 6m0-6-6 6"></path>
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <button @click="clearAllFilters()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 font-medium">Hapus Semua</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selected Products Summary -->
                        <div x-show="selectedProducts.length > 0" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="mb-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-indigo-900 dark:text-indigo-100">
                                        <span x-text="selectedProducts.length"></span> produk dipilih
                                    </span>
                                </div>
                                <button @click="clearSelection()" 
                                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 font-medium">
                                    Hapus Semua
                                </button>
                            </div>
                            
                            <!-- Selected Products Preview -->
                            <div class="flex flex-wrap gap-2">
                                <template x-for="product in selectedProducts.slice(0, 5)" :key="product.id">
                                    <div class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600">
                                        <span x-text="product.name"></span>
                                        <button @click="removeFromSelection(product.id)" class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-500 focus:outline-none">
                                            <svg class="w-2 h-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                                <path stroke-linecap="round" stroke-width="1.5" d="m1 1 6 6m0-6-6 6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <div x-show="selectedProducts.length > 5" class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    +<span x-text="selectedProducts.length - 5"></span> lainnya
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products Display -->
                        <div class="min-h-[400px]">
                            <!-- Loading State -->
                            <div x-show="loading" class="flex justify-center items-center py-20">
                                <div class="flex flex-col items-center">
                                    <svg class="animate-spin h-10 w-10 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Memuat produk...</p>
                                </div>
                            </div>
                            
                            <!-- Empty State -->
                            <div x-show="!loading && filteredProducts.length === 0" class="text-center py-20">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v4M6 13h2m8 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v4m4 0h2"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Tidak ada produk ditemukan</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
                                <button @click="clearAllFilters()" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Reset Filter
                                </button>
                            </div>
                            
                            <!-- Grid View -->
                            <div x-show="!loading && filteredProducts.length > 0 && viewMode === 'grid'" 
                                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                <template x-for="product in paginatedProducts" :key="product.id">
                                    <div @click="toggleProduct(product)" 
                                         :class="isSelected(product.id) ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'hover:shadow-lg'"
                                         class="relative bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4 cursor-pointer transition-all duration-200 transform hover:scale-105">
                                        
                                        <!-- Selection Indicator -->
                                        <div x-show="isSelected(product.id)" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-0"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute top-3 right-3 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center z-10">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Product Image/Icon -->
                                        <div class="w-full h-36 bg-gray-100 dark:bg-gray-600 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                                            <template x-if="product.image_path">
                                                <img :src="product.image_path" :alt="product.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!product.image_path">
                                                <div class="text-3xl font-bold text-gray-400 dark:text-gray-500" x-text="product.name.charAt(0).toUpperCase()"></div>
                                            </template>
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <div class="space-y-2">
                                            <h3 class="font-medium text-gray-900 dark:text-gray-100 text-sm line-clamp-2 leading-tight" x-text="product.name"></h3>
                                            
                                            <div class="flex items-center justify-between">
                                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400" x-text="formatCurrency(product.selling_price)"></span>
                                                <div :class="getStockStatusClass(product.stock, product.min_stock)" 
                                                     class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                                    <span x-text="product.stock"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                                <div x-text="product.category?.name || 'Tanpa Kategori'"></div>
                                                <div x-show="product.code" class="font-mono" x-text="product.code"></div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- List View -->
                            <div x-show="!loading && filteredProducts.length > 0 && viewMode === 'list'" 
                                 class="space-y-3">
                                <template x-for="product in paginatedProducts" :key="product.id">
                                    <div @click="toggleProduct(product)" 
                                         :class="isSelected(product.id) ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50'"
                                         class="flex items-center p-4 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer transition-all duration-200">
                                        
                                        <!-- Product Image/Icon -->
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mr-4 overflow-hidden">
                                            <template x-if="product.image_path">
                                                <img :src="product.image_path" :alt="product.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!product.image_path">
                                                <div class="text-xl font-bold text-gray-400 dark:text-gray-500" x-text="product.name.charAt(0).toUpperCase()"></div>
                                            </template>
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 truncate" x-text="product.name"></h3>
                                                    <div class="mt-1 flex items-center space-x-4">
                                                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400" x-text="formatCurrency(product.selling_price)"></span>
                                                        <div :class="getStockStatusClass(product.stock, product.min_stock)" 
                                                             class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                                            Stok: <span x-text="product.stock"></span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                        <span x-text="product.category?.name || 'Tanpa Kategori'"></span>
                                                        <template x-if="product.code">
                                                            <span> • <span class="font-mono" x-text="product.code"></span></span>
                                                        </template>
                                                    </div>
                                                </div>
                                                
                                                <div x-show="isSelected(product.id)" class="flex-shrink-0 ml-4">
                                                    <div class="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div x-show="!loading && filteredProducts.length > pagination.itemsPerPage" 
                             class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Menampilkan <span x-text="((pagination.currentPage - 1) * pagination.itemsPerPage) + 1"></span> sampai 
                                <span x-text="Math.min(pagination.currentPage * pagination.itemsPerPage, filteredProducts.length)"></span> dari 
                                <span x-text="filteredProducts.length"></span> produk
                            </div>
                            
                            <div class="flex space-x-1">
                                <button @click="previousPage()" 
                                        :disabled="pagination.currentPage === 1"
                                        :class="pagination.currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                                        class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md transition-colors">
                                    Sebelumnya
                                </button>
                                
                                <template x-for="page in getVisiblePages()" :key="page">
                                    <button @click="goToPage(page)" 
                                            :class="page === pagination.currentPage ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                            class="px-3 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 rounded-md transition-colors"
                                            x-text="page">
                                    </button>
                                </template>
                                
                                <button @click="nextPage()" 
                                        :disabled="pagination.currentPage === pagination.totalPages"
                                        :class="pagination.currentPage === pagination.totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                                        class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md transition-colors">
                                    Selanjutnya
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 dark:border-gray-700">
                    <button @click="confirmSelection()" 
                            :disabled="selectedProducts.length === 0"
                            :class="selectedProducts.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                            class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto transition-colors">
                        <span x-show="selectedProducts.length === 0">Pilih Produk</span>
                        <span x-show="selectedProducts.length > 0">
                            Konfirmasi (<span x-text="selectedProducts.length"></span> produk)
                        </span>
                    </button>
                    
                    <button @click="closeModal()" 
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/product-selector.js') }}"></script>
@endpush