/**
 * Product Selector Alpine.js Component
 * Modern UI/UX for product selection with grid/catalog view
 */

// Product Selector Store
document.addEventListener('alpine:init', () => {
    Alpine.store('productSelector', {
        // Global state
        isOpen: false,
        selectedProducts: [],
        
        // Methods
        open() {
            this.isOpen = true;
        },
        
        close() {
            this.isOpen = false;
        },
        
        addProduct(product) {
            if (!this.selectedProducts.find(p => p.id === product.id)) {
                this.selectedProducts.push(product);
            }
        },
        
        removeProduct(productId) {
            this.selectedProducts = this.selectedProducts.filter(p => p.id !== productId);
        },
        
        clearAll() {
            this.selectedProducts = [];
        }
    });
});

// Enhanced Product Selector Component
function enhancedProductSelector(initialProducts = [], initialCategories = [], options = {}) {
    return {
        // Configuration
        config: {
            multiple: options.multiple ?? true,
            showFilters: options.showFilters ?? true,
            showSearch: options.showSearch ?? true,
            showCategories: options.showCategories ?? true,
            itemsPerPage: options.itemsPerPage ?? 12,
            enableAnimations: options.enableAnimations ?? true,
            autoClose: options.autoClose ?? false,
            ...options
        },
        
        // Data
        products: initialProducts || [],
        categories: initialCategories || [],
        selectedProducts: [],
        filteredProducts: [],
        searchResults: [],
        
        // UI State
        searchQuery: '',
        viewMode: 'grid', // 'grid' or 'list'
        showFilters: false,
        loading: false,
        isModalOpen: false,
        
        // Filters
        activeFilters: {
            category: '',
            stock: 'all', // 'all', 'available', 'low', 'out'
            priceRange: { min: '', max: '' },
            supplier: '',
            sortBy: 'name', // 'name', 'price', 'stock', 'category'
            sortOrder: 'asc' // 'asc', 'desc'
        },
        
        // Pagination
        pagination: {
            currentPage: 1,
            itemsPerPage: 12,
            totalItems: 0,
            totalPages: 0
        },
        
        // Animation states
        animations: {
            productEnter: 'transition ease-out duration-300 transform',
            productEnterStart: 'opacity-0 scale-95 translate-y-4',
            productEnterEnd: 'opacity-100 scale-100 translate-y-0',
            productLeave: 'transition ease-in duration-200 transform',
            productLeaveStart: 'opacity-100 scale-100 translate-y-0',
            productLeaveEnd: 'opacity-0 scale-95 translate-y-4'
        },
        
        // Initialize
        init() {
            this.filteredProducts = [...this.products];
            this.updatePagination();
            this.filterProducts();
            
            // Listen for external events
            this.$watch('searchQuery', () => {
                this.debounceSearch();
            });
            
            // Auto-save selection to localStorage
            this.$watch('selectedProducts', () => {
                this.saveSelectionToStorage();
                this.emitSelectionChange();
            });
            
            // Load saved selection
            this.loadSelectionFromStorage();
        },
        
        // Product Management
        async loadProducts(endpoint = '/api/products') {
            this.loading = true;
            try {
                // Add per_page parameter to get all products (use a large number)
                const url = new URL(endpoint, window.location.origin);
                if (!url.searchParams.has('per_page')) {
                    url.searchParams.set('per_page', '1000'); // Get all products
                }
                
                const response = await fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    // Handle different response structures
                    if (data.data && Array.isArray(data.data)) {
                        this.products = data.data;
                    } else if (Array.isArray(data.products)) {
                        this.products = data.products;
                    } else if (Array.isArray(data)) {
                        this.products = data;
                    } else {
                        this.products = [];
                        console.warn('Unexpected API response structure:', data);
                    }
                    
                    // Load categories separately
                    this.loadCategories();
                    this.filterProducts();
                }
            } catch (error) {
                console.error('Error loading products:', error);
                this.showNotification('Gagal memuat produk', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        // Load categories from API
        async loadCategories() {
            try {
                const response = await fetch('/api/products/categories', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success && Array.isArray(data.data)) {
                        this.categories = data.data;
                    } else {
                        this.categories = [];
                        console.warn('Unexpected categories API response structure:', data);
                    }
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                this.categories = [];
            }
        },
        
        // Selection Methods
        toggleProduct(product) {
            if (this.isSelected(product.id)) {
                this.removeFromSelection(product.id);
            } else {
                this.addToSelection(product);
            }
        },
        
        addToSelection(product) {
            // Check stock availability
            if (product.stock <= 0) {
                this.showNotification('Produk tidak tersedia (stok habis)', 'warning');
                return;
            }
            
            if (!this.config.multiple) {
                this.selectedProducts = [product];
                if (this.config.autoClose) {
                    this.closeModal();
                }
            } else {
                if (!this.isSelected(product.id)) {
                    this.selectedProducts.push(product);
                }
            }
            
            this.showNotification(`${product.name} ditambahkan`, 'success');
        },
        
        removeFromSelection(productId) {
            const product = this.selectedProducts.find(p => p.id === productId);
            this.selectedProducts = this.selectedProducts.filter(p => p.id !== productId);
            
            if (product) {
                this.showNotification(`${product.name} dihapus`, 'info');
            }
        },
        
        isSelected(productId) {
            return this.selectedProducts.some(p => p.id === productId);
        },
        
        clearSelection() {
            this.selectedProducts = [];
            this.showNotification('Semua produk dihapus dari pilihan', 'info');
        },
        
        confirmSelection() {
            if (this.selectedProducts.length === 0) {
                this.showNotification('Pilih setidaknya satu produk', 'warning');
                return;
            }
            
            // Dispatch event with selected products
            this.$dispatch('product-selected', {
                products: this.selectedProducts
            });
            
            // Also dispatch to window for global listeners
            window.dispatchEvent(new CustomEvent('product-selected', {
                detail: {
                    products: this.selectedProducts
                }
            }));
            
            this.showNotification(`${this.selectedProducts.length} produk dikonfirmasi`, 'success');
            
            // Close modal and clear selection if configured
            this.closeModal();
            if (this.config.clearAfterConfirm !== false) {
                this.selectedProducts = [];
            }
        },
        
        selectProductFromSearch(product) {
            this.addToSelection(product);
            this.searchQuery = '';
            this.searchResults = [];
        },
        
        // Advanced Filtering
        filterProducts() {
            let filtered = [...this.products];
            
            // Search filter
            if (this.searchQuery.trim()) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(product => 
                    this.searchInProduct(product, query)
                );
                
                // Update search results for autocomplete
                this.searchResults = filtered.slice(0, 10);
            } else {
                this.searchResults = [];
            }
            
            // Category filter
            if (this.activeFilters.category) {
                filtered = filtered.filter(product => 
                    product.category_id == this.activeFilters.category
                );
            }
            
            // Stock filter
            filtered = this.applyStockFilter(filtered);
            
            // Price range filter
            filtered = this.applyPriceFilter(filtered);
            
            // Supplier filter
            if (this.activeFilters.supplier) {
                filtered = filtered.filter(product => 
                    product.supplier_id == this.activeFilters.supplier
                );
            }
            
            // Sorting
            filtered = this.applySorting(filtered);
            
            this.filteredProducts = filtered;
            this.updatePagination();
            this.pagination.currentPage = 1; // Reset to first page
        },
        
        searchInProduct(product, query) {
            const searchFields = [
                product.name,
                product.code,
                product.category?.name,
                product.supplier?.name,
                product.description
            ];
            
            return searchFields.some(field => 
                field && field.toLowerCase().includes(query)
            );
        },
        
        applyStockFilter(products) {
            switch (this.activeFilters.stock) {
                case 'available':
                    return products.filter(product => product.stock > 0);
                case 'low':
                    return products.filter(product => 
                        product.stock > 0 && product.stock <= (product.min_stock || 10)
                    );
                case 'out':
                    return products.filter(product => product.stock <= 0);
                default:
                    return products;
            }
        },
        
        applyPriceFilter(products) {
            let filtered = products;
            
            if (this.activeFilters.priceRange.min !== '') {
                filtered = filtered.filter(product => 
                    product.selling_price >= parseFloat(this.activeFilters.priceRange.min)
                );
            }
            
            if (this.activeFilters.priceRange.max !== '') {
                filtered = filtered.filter(product => 
                    product.selling_price <= parseFloat(this.activeFilters.priceRange.max)
                );
            }
            
            return filtered;
        },
        
        applySorting(products) {
            const { sortBy, sortOrder } = this.activeFilters;
            
            return products.sort((a, b) => {
                let aValue, bValue;
                
                switch (sortBy) {
                    case 'name':
                        aValue = a.name.toLowerCase();
                        bValue = b.name.toLowerCase();
                        break;
                    case 'price':
                        aValue = a.selling_price;
                        bValue = b.selling_price;
                        break;
                    case 'stock':
                        aValue = a.stock;
                        bValue = b.stock;
                        break;
                    case 'category':
                        aValue = a.category?.name?.toLowerCase() || '';
                        bValue = b.category?.name?.toLowerCase() || '';
                        break;
                    default:
                        return 0;
                }
                
                if (aValue < bValue) return sortOrder === 'asc' ? -1 : 1;
                if (aValue > bValue) return sortOrder === 'asc' ? 1 : -1;
                return 0;
            });
        },
        
        // Debounced search
        debounceSearch: null,
        
        setupDebounce() {
            this.debounceSearch = this.debounce(() => {
                this.filterProducts();
            }, 300);
        },
        
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // Filter Management
        hasActiveFilters() {
            return this.activeFilters.category || 
                   this.activeFilters.stock !== 'all' || 
                   this.activeFilters.priceRange.min !== '' || 
                   this.activeFilters.priceRange.max !== '' ||
                   this.activeFilters.supplier;
        },
        
        getActiveFilters() {
            const filters = [];
            
            if (this.activeFilters.category) {
                const category = this.categories.find(c => c.id == this.activeFilters.category);
                filters.push({
                    key: 'category',
                    label: `Kategori: ${category?.name || 'Unknown'}`,
                    value: this.activeFilters.category
                });
            }
            
            if (this.activeFilters.stock !== 'all') {
                const labels = {
                    'available': 'Stok Tersedia',
                    'low': 'Stok Menipis',
                    'out': 'Stok Habis'
                };
                filters.push({
                    key: 'stock',
                    label: labels[this.activeFilters.stock],
                    value: this.activeFilters.stock
                });
            }
            
            if (this.activeFilters.priceRange.min !== '' || this.activeFilters.priceRange.max !== '') {
                let label = 'Harga: ';
                if (this.activeFilters.priceRange.min !== '' && this.activeFilters.priceRange.max !== '') {
                    label += `${this.formatCurrency(this.activeFilters.priceRange.min)} - ${this.formatCurrency(this.activeFilters.priceRange.max)}`;
                } else if (this.activeFilters.priceRange.min !== '') {
                    label += `≥ ${this.formatCurrency(this.activeFilters.priceRange.min)}`;
                } else {
                    label += `≤ ${this.formatCurrency(this.activeFilters.priceRange.max)}`;
                }
                filters.push({
                    key: 'priceRange',
                    label: label,
                    value: this.activeFilters.priceRange
                });
            }
            
            return filters;
        },
        
        removeFilter(filterKey) {
            switch (filterKey) {
                case 'category':
                    this.activeFilters.category = '';
                    break;
                case 'stock':
                    this.activeFilters.stock = 'all';
                    break;
                case 'priceRange':
                    this.activeFilters.priceRange.min = '';
                    this.activeFilters.priceRange.max = '';
                    break;
                case 'supplier':
                    this.activeFilters.supplier = '';
                    break;
            }
            this.filterProducts();
        },
        
        clearAllFilters() {
            this.activeFilters = {
                category: '',
                stock: 'all',
                priceRange: { min: '', max: '' },
                supplier: '',
                sortBy: 'name',
                sortOrder: 'asc'
            };
            this.searchQuery = '';
            this.filterProducts();
        },
        
        // Pagination
        updatePagination() {
            this.pagination.totalItems = this.filteredProducts.length;
            this.pagination.totalPages = Math.ceil(this.pagination.totalItems / this.pagination.itemsPerPage);
        },
        
        get paginatedProducts() {
            const start = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage;
            const end = start + this.pagination.itemsPerPage;
            return this.filteredProducts.slice(start, end);
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.pagination.totalPages) {
                this.pagination.currentPage = page;
            }
        },
        
        previousPage() {
            if (this.pagination.currentPage > 1) {
                this.pagination.currentPage--;
            }
        },
        
        nextPage() {
            if (this.pagination.currentPage < this.pagination.totalPages) {
                this.pagination.currentPage++;
            }
        },
        
        getVisiblePages() {
            const pages = [];
            const maxVisible = 5;
            const current = this.pagination.currentPage;
            const total = this.pagination.totalPages;
            
            let start = Math.max(1, current - Math.floor(maxVisible / 2));
            let end = Math.min(total, start + maxVisible - 1);
            
            if (end - start + 1 < maxVisible) {
                start = Math.max(1, end - maxVisible + 1);
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        },
        
        // Modal Management
        openModal() {
            this.isModalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeModal() {
            this.isModalOpen = false;
            document.body.style.overflow = '';
        },
        
        // Storage Management
        saveSelectionToStorage() {
            if (this.config.persistSelection) {
                localStorage.setItem('productSelector_selection', JSON.stringify(this.selectedProducts));
            }
        },
        
        loadSelectionFromStorage() {
            if (this.config.persistSelection) {
                const saved = localStorage.getItem('productSelector_selection');
                if (saved) {
                    try {
                        this.selectedProducts = JSON.parse(saved);
                    } catch (error) {
                        console.error('Error loading saved selection:', error);
                    }
                }
            }
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
        
        getStockStatusText(stock, minStock = 10) {
            if (stock <= 0) {
                return 'Habis';
            } else if (stock <= minStock) {
                return 'Menipis';
            } else {
                return 'Tersedia';
            }
        },
        
        // Notifications
        showNotification(message, type = 'info') {
            // Emit notification event for parent to handle
            this.$dispatch('product-selector-notification', {
                message,
                type,
                timestamp: Date.now()
            });
        },
        
        // Events
        emitSelectionChange() {
            this.$dispatch('product-selection-changed', {
                selectedProducts: this.selectedProducts,
                count: this.selectedProducts.length
            });
        },
        
        // Keyboard Navigation
        handleKeydown(event) {
            switch (event.key) {
                case 'Escape':
                    if (this.isModalOpen) {
                        this.closeModal();
                    }
                    break;
                case 'Enter':
                    if (this.searchResults.length > 0) {
                        this.selectProductFromSearch(this.searchResults[0]);
                    }
                    break;
            }
        },
        
        // Export/Import
        exportSelection() {
            const data = {
                products: this.selectedProducts,
                timestamp: new Date().toISOString(),
                count: this.selectedProducts.length
            };
            
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `product-selection-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
            URL.revokeObjectURL(url);
        },
        
        async importSelection(file) {
            try {
                const text = await file.text();
                const data = JSON.parse(text);
                
                if (data.products && Array.isArray(data.products)) {
                    this.selectedProducts = data.products;
                    this.showNotification(`${data.products.length} produk berhasil diimpor`, 'success');
                } else {
                    throw new Error('Format file tidak valid');
                }
            } catch (error) {
                this.showNotification('Gagal mengimpor file: ' + error.message, 'error');
            }
        }
    }
}

// Quick Selection Helper
function quickProductSelect(productId, products) {
    const product = products.find(p => p.id === productId);
    if (product) {
        Alpine.store('productSelector').addProduct(product);
    }
}

// Bulk Selection Helper
function bulkProductSelect(productIds, products) {
    productIds.forEach(id => {
        const product = products.find(p => p.id === id);
        if (product) {
            Alpine.store('productSelector').addProduct(product);
        }
    });
}

// Global functions for integration
window.openProductSelector = function() {
    console.log('Opening product selector...');
    
    // Try to use globally accessible component
    if (window.productSelectorComponent && typeof window.productSelectorComponent.openModal === 'function') {
        console.log('Using global component');
        window.productSelectorComponent.openModal();
        return;
    }
    
    // Try to find Alpine component instance
    const modalElement = document.querySelector('[x-data*="enhancedProductSelector"]');
    if (modalElement && modalElement._x_dataStack) {
        const component = modalElement._x_dataStack[0];
        if (component && typeof component.openModal === 'function') {
            console.log('Using found component');
            component.openModal();
            return;
        }
    }
    
    // Fallback: use Alpine store
    if (window.Alpine && Alpine.store('productSelector')) {
        console.log('Using Alpine store');
        Alpine.store('productSelector').open();
    } else {
        console.error('Product selector not found. Make sure Alpine.js is loaded and component is initialized.');
        console.log('Available:', {
            productSelectorComponent: !!window.productSelectorComponent,
            Alpine: !!window.Alpine,
            modalElement: !!modalElement
        });
    }
};

window.closeProductSelector = function() {
    const modalElement = document.querySelector('[x-data*="enhancedProductSelector"]');
    if (modalElement && modalElement._x_dataStack) {
        const component = modalElement._x_dataStack[0];
        if (component && typeof component.closeModal === 'function') {
            component.closeModal();
            return;
        }
    }
    
    if (window.Alpine && Alpine.store('productSelector')) {
        Alpine.store('productSelector').close();
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product Selector JS loaded successfully');
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        enhancedProductSelector,
        quickProductSelect,
        bulkProductSelect
    };
}