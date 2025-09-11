class PurchaseProductSelector {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        this.options = {
            apiUrl: '/api/purchases/products',
            categoriesUrl: '/api/purchases/products/categories',
            perPage: 1000,
            searchDelay: 300,
            ...options
        };
        
        this.products = [];
        this.categories = [];
        this.filteredProducts = [];
        this.selectedProducts = new Map();
        this.currentPage = 1;
        this.totalPages = 1;
        this.searchTimeout = null;
        this.supplierId = null;
        
        this.init();
    }

    init() {
        this.render();
        this.attachEventListeners();
        this.loadCategories();
    }

    render() {
        this.container.innerHTML = `
            <div class="purchase-product-selector bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Pilih Produk untuk Pembelian
                    </h3>
                </div>

                <!-- Filters -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" id="product-search" placeholder="Cari produk..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-300">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <select id="category-filter" class="w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-300">
                                <option value="">Semua Kategori</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <select id="sort-filter" class="w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-300">
                                <option value="name-asc">Nama A-Z</option>
                                <option value="name-desc">Nama Z-A</option>
                                <option value="stock-asc">Stok Terendah</option>
                                <option value="stock-desc">Stok Tertinggi</option>
                                <option value="purchase_price-asc">Harga Terendah</option>
                                <option value="purchase_price-desc">Harga Tertinggi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Selected Products Summary -->
                <div id="selected-summary" class="p-4 bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-700 hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            <span id="selected-count">0</span> produk dipilih
                        </span>
                        <button type="button" id="clear-selection" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                            Hapus Semua
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loading-state" class="p-8 text-center">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memuat produk...
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="products-grid" class="p-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="products-container">
                        <!-- Products will be rendered here -->
                    </div>
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="p-8 text-center hidden">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4.5"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada produk</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih supplier terlebih dahulu atau ubah filter pencarian.</p>
                </div>
            </div>
        `;
    }

    attachEventListeners() {
        // Search input
        const searchInput = document.getElementById('product-search');
        searchInput.addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadProducts();
            }, this.options.searchDelay);
        });

        // Category filter
        const categoryFilter = document.getElementById('category-filter');
        categoryFilter.addEventListener('change', () => {
            this.loadProducts();
        });

        // Sort filter
        const sortFilter = document.getElementById('sort-filter');
        sortFilter.addEventListener('change', () => {
            this.loadProducts();
        });

        // Clear selection
        const clearButton = document.getElementById('clear-selection');
        clearButton.addEventListener('click', () => {
            this.clearSelection();
        });
    }

    async loadProducts() {
        if (!this.supplierId) {
            this.showEmptyState();
            return;
        }

        this.showLoading();

        try {
            const params = new URLSearchParams({
                per_page: this.options.perPage,
                supplier_id: this.supplierId,
                page: this.currentPage
            });

            const search = document.getElementById('product-search').value.trim();
            if (search) {
                params.append('search', search);
            }

            const categoryId = document.getElementById('category-filter').value;
            if (categoryId) {
                params.append('category_id', categoryId);
            }

            const sortValue = document.getElementById('sort-filter').value;
            const [sortBy, sortOrder] = sortValue.split('-');
            params.append('sort_by', sortBy);
            params.append('sort_order', sortOrder);

            const response = await fetch(`${this.options.apiUrl}?${params}`);
            const data = await response.json();

            if (data.success) {
                this.products = data.data;
                this.totalPages = data.pagination.last_page;
                this.renderProducts();
            } else {
                throw new Error(data.message || 'Gagal memuat produk');
            }
        } catch (error) {
            console.error('Error loading products:', error);
            this.showEmptyState();
        }
    }

    async loadCategories() {
        try {
            const response = await fetch(this.options.categoriesUrl);
            const data = await response.json();

            if (data.success) {
                this.categories = data.data;
                this.renderCategories();
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    renderCategories() {
        const categoryFilter = document.getElementById('category-filter');
        const currentValue = categoryFilter.value;
        
        categoryFilter.innerHTML = '<option value="">Semua Kategori</option>';
        
        this.categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = `${category.name} (${category.products_count})`;
            categoryFilter.appendChild(option);
        });
        
        categoryFilter.value = currentValue;
    }

    renderProducts() {
        const container = document.getElementById('products-container');
        
        if (this.products.length === 0) {
            this.showEmptyState();
            return;
        }

        container.innerHTML = '';
        
        this.products.forEach(product => {
            const productCard = this.createProductCard(product);
            container.appendChild(productCard);
        });

        this.showProductsGrid();
    }

    createProductCard(product) {
        const isSelected = this.selectedProducts.has(product.id);
        const stockClass = product.is_low_stock ? 'text-red-600' : 'text-green-600';
        const selectedClass = isSelected ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : '';
        
        const card = document.createElement('div');
        card.className = `product-card relative bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 cursor-pointer hover:shadow-md transition-all duration-200 ${selectedClass}`;
        card.dataset.productId = product.id;
        
        card.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                        ${product.name}
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        ${product.code || 'Tanpa Kode'}
                    </p>
                    ${product.category ? `
                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded mt-2">
                            ${product.category.name}
                        </span>
                    ` : ''}
                </div>
                <div class="ml-2 flex-shrink-0">
                    ${isSelected ? `
                        <div class="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    ` : `
                        <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-600 rounded-full"></div>
                    `}
                </div>
            </div>
            
            <div class="mt-3 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Stok:</span>
                    <span class="text-xs font-medium ${stockClass}">
                        ${product.stock} ${product.unit || 'pcs'}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Harga:</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        Rp ${this.formatNumber(product.purchase_price)}
                    </span>
                </div>
                ${product.supplier_price && product.supplier_price !== product.default_price ? `
                    <div class="text-xs text-blue-600 dark:text-blue-400">
                        Harga supplier: Rp ${this.formatNumber(product.supplier_price)}
                    </div>
                ` : ''}
            </div>
        `;
        
        card.addEventListener('click', () => {
            this.toggleProduct(product);
        });
        
        return card;
    }

    toggleProduct(product) {
        if (this.selectedProducts.has(product.id)) {
            this.selectedProducts.delete(product.id);
        } else {
            this.selectedProducts.set(product.id, {
                ...product,
                quantity: 1,
                unit_id: null,
                conversion_factor: 1
            });
        }
        
        this.updateProductCard(product.id);
        this.updateSelectedSummary();
        this.notifySelectionChange();
    }

    updateProductCard(productId) {
        const card = document.querySelector(`[data-product-id="${productId}"]`);
        if (!card) return;
        
        const isSelected = this.selectedProducts.has(productId);
        const checkboxContainer = card.querySelector('.ml-2.flex-shrink-0');
        
        if (isSelected) {
            card.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            checkboxContainer.innerHTML = `
                <div class="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            `;
        } else {
            card.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            checkboxContainer.innerHTML = `
                <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-600 rounded-full"></div>
            `;
        }
    }

    updateSelectedSummary() {
        const summary = document.getElementById('selected-summary');
        const countElement = document.getElementById('selected-count');
        
        const count = this.selectedProducts.size;
        countElement.textContent = count;
        
        if (count > 0) {
            summary.classList.remove('hidden');
        } else {
            summary.classList.add('hidden');
        }
    }

    clearSelection() {
        this.selectedProducts.clear();
        
        // Update all product cards
        document.querySelectorAll('.product-card').forEach(card => {
            const productId = parseInt(card.dataset.productId);
            this.updateProductCard(productId);
        });
        
        this.updateSelectedSummary();
        this.notifySelectionChange();
    }

    setSupplier(supplierId) {
        this.supplierId = supplierId;
        this.clearSelection();
        this.loadProducts();
    }

    getSelectedProducts() {
        return Array.from(this.selectedProducts.values());
    }

    notifySelectionChange() {
        const event = new CustomEvent('selectionChange', {
            detail: {
                selectedProducts: this.getSelectedProducts(),
                count: this.selectedProducts.size
            }
        });
        this.container.dispatchEvent(event);
    }

    showLoading() {
        document.getElementById('loading-state').classList.remove('hidden');
        document.getElementById('products-grid').classList.add('hidden');
        document.getElementById('empty-state').classList.add('hidden');
    }

    showProductsGrid() {
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('products-grid').classList.remove('hidden');
        document.getElementById('empty-state').classList.add('hidden');
    }

    showEmptyState() {
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('products-grid').classList.add('hidden');
        document.getElementById('empty-state').classList.remove('hidden');
    }

    formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number || 0);
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PurchaseProductSelector;
}