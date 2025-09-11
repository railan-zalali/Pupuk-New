@props([
    'products' => [],
    'categories' => [],
    'selectedProducts' => [],
    'multiple' => true,
    'showFilters' => true
])

<div x-data="enhancedProductSelector({{ json_encode($products) }}, {{ json_encode($categories) }}, { multiple: {{ $multiple ? 'true' : 'false' }}, showFilters: {{ $showFilters ? 'true' : 'false' }} })" 
     class="product-selector bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
    
    <!-- Header dengan Search dan Filter Toggle -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
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
                       placeholder="Cari produk..."
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                
                <!-- Search Results Dropdown -->
                <div x-show="searchQuery.length > 0 && searchResults.length > 0" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                    <template x-for="product in searchResults.slice(0, 5)" :key="product.id">
                        <div @click="selectProductFromSearch(product)" 
                             class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-gray-200 dark:bg-gray-600 rounded-md flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300" x-text="product.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="product.name"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <span x-text="formatCurrency(product.selling_price)"></span>
                                        <span class="mx-1">•</span>
                                        <span>Stok: </span><span x-text="product.stock"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Filter Toggle & View Mode -->
            <div class="flex items-center gap-2">
                @if($showFilters)
                <button @click="showFilters = !showFilters" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter
                </button>
                @endif
                
                <!-- View Mode Toggle -->
                <div class="flex rounded-md shadow-sm">
                    <button @click="viewMode = 'grid'" 
                            :class="viewMode === 'grid' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600'"
                            class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 text-sm font-medium focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button @click="viewMode = 'list'" 
                            :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600'"
                            class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 text-sm font-medium focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Filters Panel -->
        @if($showFilters)
        <div x-show="showFilters" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                    <select x-model="selectedCategory" @change="filterProducts()" 
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
                    <select x-model="stockFilter" @change="filterProducts()" 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all">Semua Produk</option>
                        <option value="available">Tersedia (Stok > 0)</option>
                        <option value="low">Stok Menipis</option>
                        <option value="out">Stok Habis</option>
                    </select>
                </div>
                
                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rentang Harga</label>
                    <div class="flex gap-2">
                        <input x-model="priceRange.min" @input.debounce.500ms="filterProducts()" 
                               type="number" placeholder="Min" 
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input x-model="priceRange.max" @input.debounce.500ms="filterProducts()" 
                               type="number" placeholder="Max" 
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
            
            <!-- Active Filters -->
            <div x-show="hasActiveFilters()" class="mt-3 flex flex-wrap gap-2">
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
        @endif
    </div>
    
    <!-- Selected Products Summary -->
    <div x-show="selectedProducts.length > 0" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="px-4 py-3 bg-indigo-50 dark:bg-indigo-900/20 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
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
        <div class="mt-2 flex flex-wrap gap-2">
            <template x-for="product in selectedProducts.slice(0, 3)" :key="product.id">
                <div class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600">
                    <span x-text="product.name"></span>
                    <button @click="removeFromSelection(product.id)" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-500 focus:outline-none">
                        <svg class="w-2 h-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                            <path stroke-linecap="round" stroke-width="1.5" d="m1 1 6 6m0-6-6 6"></path>
                        </svg>
                    </button>
                </div>
            </template>
            <div x-show="selectedProducts.length > 3" class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                +<span x-text="selectedProducts.length - 3"></span> lainnya
            </div>
        </div>
    </div>
    
    <!-- Products Grid/List -->
    <div class="p-4">
        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center py-12">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Memuat produk...</p>
            </div>
        </div>
        
        <!-- Empty State -->
        <div x-show="!loading && filteredProducts.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v4M6 13h2m8 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v4m4 0h2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada produk ditemukan</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Coba ubah filter atau kata kunci pencarian Anda.</p>
        </div>
        
        <!-- Grid View -->
        <div x-show="!loading && filteredProducts.length > 0 && viewMode === 'grid'" 
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <template x-for="product in filteredProducts" :key="product.id">
                <div @click="toggleProduct(product)" 
                     :class="isSelected(product.id) ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'hover:shadow-md'"
                     class="relative bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4 cursor-pointer transition-all duration-200 transform hover:scale-105">
                    
                    <!-- Selection Indicator -->
                    <div x-show="isSelected(product.id)" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-0"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute top-2 right-2 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    
                    <!-- Product Image/Icon -->
                    <div class="w-full h-32 bg-gray-100 dark:bg-gray-600 rounded-lg mb-3 flex items-center justify-center">
                        <template x-if="product.image_path">
                            <img :src="product.image_path" :alt="product.name" class="w-full h-full object-cover rounded-lg">
                        </template>
                        <template x-if="!product.image_path">
                            <div class="text-2xl font-bold text-gray-400 dark:text-gray-500" x-text="product.name.charAt(0).toUpperCase()"></div>
                        </template>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="space-y-2">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100 text-sm line-clamp-2" x-text="product.name"></h3>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400" x-text="formatCurrency(product.selling_price)"></span>
                            <div class="flex items-center">
                                <div :class="getStockStatusClass(product.stock, product.min_stock)" 
                                     class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                    <span x-text="product.stock"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <span x-text="product.category?.name || 'Tanpa Kategori'"></span>
                            <template x-if="product.code">
                                <span> • <span x-text="product.code"></span></span>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        
        <!-- List View -->
        <div x-show="!loading && filteredProducts.length > 0 && viewMode === 'list'" 
             class="space-y-2">
            <template x-for="product in filteredProducts" :key="product.id">
                <div @click="toggleProduct(product)" 
                     :class="isSelected(product.id) ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50'"
                     class="flex items-center p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer transition-all duration-200">
                    
                    <!-- Product Image/Icon -->
                    <div class="flex-shrink-0 w-12 h-12 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center mr-4">
                        <template x-if="product.image_path">
                            <img :src="product.image_path" :alt="product.name" class="w-full h-full object-cover rounded-lg">
                        </template>
                        <template x-if="!product.image_path">
                            <div class="text-lg font-bold text-gray-400 dark:text-gray-500" x-text="product.name.charAt(0).toUpperCase()"></div>
                        </template>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100 truncate" x-text="product.name"></h3>
                            <div x-show="isSelected(product.id)" class="flex-shrink-0 ml-2">
                                <div class="w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-1 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400" x-text="formatCurrency(product.selling_price)"></span>
                                <div :class="getStockStatusClass(product.stock, product.min_stock)" 
                                     class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                    Stok: <span x-text="product.stock"></span>
                                </div>
                            </div>
                            
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="product.category?.name || 'Tanpa Kategori'"></span>
                                <template x-if="product.code">
                                    <span> • <span x-text="product.code"></span></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Pagination -->
    <div x-show="!loading && filteredProducts.length > itemsPerPage" 
         class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Menampilkan <span x-text="((currentPage - 1) * itemsPerPage) + 1"></span> sampai 
                <span x-text="Math.min(currentPage * itemsPerPage, filteredProducts.length)"></span> dari 
                <span x-text="filteredProducts.length"></span> produk
            </div>
            
            <div class="flex space-x-1">
                <button @click="previousPage()" 
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md transition-colors">
                    Sebelumnya
                </button>
                
                <template x-for="page in getVisiblePages()" :key="page">
                    <button @click="goToPage(page)" 
                            :class="page === currentPage ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            class="px-3 py-1 text-sm font-medium border border-gray-300 dark:border-gray-600 rounded-md transition-colors"
                            x-text="page">
                    </button>
                </template>
                
                <button @click="nextPage()" 
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md transition-colors">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function productSelector(products, categories, selectedProducts = [], multiple = true) {
    return {
        // Data
        products: products || [],
        categories: categories || [],
        selectedProducts: selectedProducts || [],
        filteredProducts: [],
        searchResults: [],
        
        // UI State
        searchQuery: '',
        viewMode: 'grid', // 'grid' or 'list'
        showFilters: false,
        loading: false,
        
        // Filters
        selectedCategory: '',
        stockFilter: 'all', // 'all', 'available', 'low', 'out'
        priceRange: {
            min: '',
            max: ''
        },
        
        // Pagination
        currentPage: 1,
        itemsPerPage: 12,
        
        // Computed
        get totalPages() {
            return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
        },
        
        get paginatedProducts() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredProducts.slice(start, end);
        },
        
        // Initialize
        init() {
            this.filteredProducts = [...this.products];
            this.filterProducts();
        },
        
        // Product Selection
        toggleProduct(product) {
            if (this.isSelected(product.id)) {
                this.removeFromSelection(product.id);
            } else {
                this.addToSelection(product);
            }
        },
        
        addToSelection(product) {
            if (!multiple) {
                this.selectedProducts = [product];
            } else {
                if (!this.isSelected(product.id)) {
                    this.selectedProducts.push(product);
                }
            }
            this.emitSelectionChange();
        },
        
        removeFromSelection(productId) {
            this.selectedProducts = this.selectedProducts.filter(p => p.id !== productId);
            this.emitSelectionChange();
        },
        
        isSelected(productId) {
            return this.selectedProducts.some(p => p.id === productId);
        },
        
        clearSelection() {
            this.selectedProducts = [];
            this.emitSelectionChange();
        },
        
        selectProductFromSearch(product) {
            this.addToSelection(product);
            this.searchQuery = '';
            this.searchResults = [];
        },
        
        // Filtering
        filterProducts() {
            let filtered = [...this.products];
            
            // Search filter
            if (this.searchQuery.trim()) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(product => 
                    product.name.toLowerCase().includes(query) ||
                    (product.code && product.code.toLowerCase().includes(query)) ||
                    (product.category && product.category.name.toLowerCase().includes(query))
                );
                
                // Update search results for autocomplete
                this.searchResults = filtered.slice(0, 10);
            } else {
                this.searchResults = [];
            }
            
            // Category filter
            if (this.selectedCategory) {
                filtered = filtered.filter(product => 
                    product.category_id == this.selectedCategory
                );
            }
            
            // Stock filter
            if (this.stockFilter !== 'all') {
                filtered = filtered.filter(product => {
                    switch (this.stockFilter) {
                        case 'available':
                            return product.stock > 0;
                        case 'low':
                            return product.stock > 0 && product.stock <= (product.min_stock || 10);
                        case 'out':
                            return product.stock <= 0;
                        default:
                            return true;
                    }
                });
            }
            
            // Price range filter
            if (this.priceRange.min !== '') {
                filtered = filtered.filter(product => 
                    product.selling_price >= parseFloat(this.priceRange.min)
                );
            }
            if (this.priceRange.max !== '') {
                filtered = filtered.filter(product => 
                    product.selling_price <= parseFloat(this.priceRange.max)
                );
            }
            
            this.filteredProducts = filtered;
            this.currentPage = 1; // Reset to first page
        },
        
        // Filter Management
        hasActiveFilters() {
            return this.selectedCategory || 
                   this.stockFilter !== 'all' || 
                   this.priceRange.min !== '' || 
                   this.priceRange.max !== '';
        },
        
        getActiveFilters() {
            const filters = [];
            
            if (this.selectedCategory) {
                const category = this.categories.find(c => c.id == this.selectedCategory);
                filters.push({
                    key: 'category',
                    label: `Kategori: ${category?.name || 'Unknown'}`
                });
            }
            
            if (this.stockFilter !== 'all') {
                const labels = {
                    'available': 'Stok Tersedia',
                    'low': 'Stok Menipis',
                    'out': 'Stok Habis'
                };
                filters.push({
                    key: 'stock',
                    label: labels[this.stockFilter]
                });
            }
            
            if (this.priceRange.min !== '' || this.priceRange.max !== '') {
                let label = 'Harga: ';
                if (this.priceRange.min !== '' && this.priceRange.max !== '') {
                    label += `${this.formatCurrency(this.priceRange.min)} - ${this.formatCurrency(this.priceRange.max)}`;
                } else if (this.priceRange.min !== '') {
                    label += `≥ ${this.formatCurrency(this.priceRange.min)}`;
                } else {
                    label += `≤ ${this.formatCurrency(this.priceRange.max)}`;
                }
                filters.push({
                    key: 'price',
                    label: label
                });
            }
            
            return filters;
        },
        
        removeFilter(filterKey) {
            switch (filterKey) {
                case 'category':
                    this.selectedCategory = '';
                    break;
                case 'stock':
                    this.stockFilter = 'all';
                    break;
                case 'price':
                    this.priceRange.min = '';
                    this.priceRange.max = '';
                    break;
            }
            this.filterProducts();
        },
        
        clearAllFilters() {
            this.selectedCategory = '';
            this.stockFilter = 'all';
            this.priceRange.min = '';
            this.priceRange.max = '';
            this.searchQuery = '';
            this.filterProducts();
        },
        
        // Pagination
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        
        getVisiblePages() {
            const pages = [];
            const maxVisible = 5;
            let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(this.totalPages, start + maxVisible - 1);
            
            if (end - start + 1 < maxVisible) {
                start = Math.max(1, end - maxVisible + 1);
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        },
        
        // Utilities
        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount || 0);
        },
        
        getStockStatusClass(stock, minStock = 10) {
            if (stock <= 0) {
                return 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200';
            } else if (stock <= minStock) {
                return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200';
            } else {
                return 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200';
            }
        },
        
        // Events
        emitSelectionChange() {
            // Emit custom event for parent components to listen
            this.$dispatch('product-selection-changed', {
                selectedProducts: this.selectedProducts
            });
        }
    }
}
</script>
@endpush