/**
 * SimpleProductSelector - Komponen pemilihan produk yang sederhana dan efisien
 * untuk aplikasi penjualan pupuk
 */
class SimpleProductSelector {
    /**
     * Inisialisasi komponen SimpleProductSelector
     * @param {Object} options - Opsi konfigurasi
     */
    constructor(options) {
        // Default options
        this.options = {
            productGridSelector: '#sps-product-grid',
            searchInputSelector: '#sps-search',
            searchClearSelector: '#sps-search-clear',
            categorySelectSelector: '#sps-category-select',
            recentItemsSelector: '#sps-recent-items',
            maxRecentItems: 8,
            onProductSelected: null,
            ...options
        };

        // Elemen DOM
        this.productGrid = document.querySelector(this.options.productGridSelector);
        this.searchInput = document.querySelector(this.options.searchInputSelector);
        this.searchClear = document.querySelector(this.options.searchClearSelector);
        this.categorySelect = document.querySelector(this.options.categorySelectSelector);
        this.recentItemsContainer = document.querySelector(this.options.recentItemsSelector);

        // Data
        this.products = [];
        this.recentItems = this.loadRecentItems();

        // Inisialisasi
        this.init();
    }

    /**
     * Inisialisasi komponen
     */
    init() {
        // Mengumpulkan semua produk dari DOM
        this.collectProductsFromDOM();
        
        // Menambahkan event listeners
        this.setupEventListeners();
        
        // Render produk terbaru
        this.renderRecentItems();
        
        // Inisialisasi tombol clear search jika belum ada
        this.initSearchClearButton();
    }
    
    /**
     * Inisialisasi tombol clear search
     */
    initSearchClearButton() {
        // Jika tombol clear belum ada, buat baru
        if (!this.searchClear && this.searchInput) {
            const clearButton = document.createElement('button');
            clearButton.className = 'search-clear-btn';
            clearButton.setAttribute('type', 'button');
            clearButton.setAttribute('aria-label', 'Clear search');
            clearButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            `;
            
            // Tambahkan ke wrapper pencarian
            const searchWrapper = this.searchInput.parentElement;
            if (searchWrapper) {
                searchWrapper.appendChild(clearButton);
                this.searchClear = clearButton;
                
                // Tambahkan event listener
                this.searchClear.addEventListener('click', this.clearSearch.bind(this));
            }
            
            // Set display awal
            if (this.searchInput.value.trim()) {
                this.searchClear.style.display = 'flex';
            } else {
                this.searchClear.style.display = 'none';
            }
        }
    }

    /**
     * Mengumpulkan data produk dari DOM
     */
    collectProductsFromDOM() {
        const productElements = this.productGrid.querySelectorAll('.sps-product-item');
        this.products = Array.from(productElements).map(el => ({
            id: el.dataset.id,
            name: el.dataset.name,
            code: el.dataset.code,
            categoryId: el.dataset.category,
            element: el
        }));
    }

    /**
     * Menambahkan event listeners
     */
    setupEventListeners() {
        // Event listener untuk pencarian
        if (this.searchInput) {
            this.searchInput.addEventListener('input', this.handleSearch.bind(this));
        }

        // Event listener untuk tombol clear pencarian
        if (this.searchClear) {
            this.searchClear.addEventListener('click', this.clearSearch.bind(this));
        }

        // Event listener untuk filter kategori
        if (this.categorySelect) {
            this.categorySelect.addEventListener('change', this.handleCategoryFilter.bind(this));
        }

        // Event listener untuk klik produk
        if (this.productGrid) {
            const productItems = this.productGrid.querySelectorAll('.sps-product-item');
            productItems.forEach(item => {
                item.addEventListener('click', () => this.handleProductClick(item));
            });
        }
    }

    /**
     * Menangani pencarian produk
     */
    handleSearch(event) {
        const searchTerm = event.target.value.toLowerCase().trim();
        this.filterProducts(searchTerm, this.categorySelect ? this.categorySelect.value : '');
        
        // Tampilkan/sembunyikan tombol clear
        if (this.searchClear) {
            this.searchClear.style.display = searchTerm ? 'flex' : 'none';
        }
    }

    /**
     * Menangani filter berdasarkan kategori
     */
    handleCategoryFilter(event) {
        const categoryId = event.target.value;
        const searchTerm = this.searchInput ? this.searchInput.value.toLowerCase().trim() : '';
        this.filterProducts(searchTerm, categoryId);
    }

    /**
     * Filter produk berdasarkan pencarian dan kategori
     */
    filterProducts(searchTerm, categoryId) {
        this.products.forEach(product => {
            const nameMatch = product.name.toLowerCase().includes(searchTerm);
            const codeMatch = product.code.toLowerCase().includes(searchTerm);
            const categoryMatch = !categoryId || product.categoryId === categoryId;
            
            const isVisible = (nameMatch || codeMatch) && categoryMatch;
            product.element.style.display = isVisible ? 'block' : 'none';
        });

        // Tampilkan pesan jika tidak ada hasil
        this.showNoResultsMessage();
    }

    /**
     * Tampilkan pesan jika tidak ada hasil pencarian
     */
    showNoResultsMessage() {
        // Hapus pesan sebelumnya jika ada
        const existingMessage = this.productGrid.querySelector('.no-products-found');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Periksa apakah ada produk yang terlihat
        const visibleProducts = Array.from(this.productGrid.querySelectorAll('.sps-product-item'))
            .filter(item => item.style.display !== 'none');

        // Jika tidak ada produk yang terlihat, tampilkan pesan
        if (visibleProducts.length === 0) {
            const searchTerm = this.searchInput ? this.searchInput.value.trim() : '';
            const categoryId = this.categorySelect ? this.categorySelect.value : '';
            const categoryName = categoryId ? this.categorySelect.options[this.categorySelect.selectedIndex].text : '';
            
            const noResultsMessage = document.createElement('div');
            noResultsMessage.className = 'no-products-found';
            
            let message = 'Tidak ada produk yang ditemukan';
            if (searchTerm && categoryId) {
                message += `<span>untuk pencarian "${searchTerm}" dalam kategori "${categoryName}"</span>`;
            } else if (searchTerm) {
                message += `<span>untuk pencarian "${searchTerm}"</span>`;
            } else if (categoryId) {
                message += `<span>dalam kategori "${categoryName}"</span>`;
            }
            
            noResultsMessage.innerHTML = `
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p>${message}</p>
                <button class="sps-reset-search">Reset Pencarian</button>
            `;
            
            this.productGrid.appendChild(noResultsMessage);
            
            // Tambahkan event listener untuk tombol reset
            const resetButton = noResultsMessage.querySelector('.sps-reset-search');
            if (resetButton) {
                resetButton.addEventListener('click', () => this.clearSearch());
            }
        }
    }

    /**
     * Menghapus pencarian
     */
    clearSearch() {
        if (this.searchInput) {
            this.searchInput.value = '';
            this.searchInput.focus();
            
            // Reset kategori jika ada
            if (this.categorySelect) {
                this.categorySelect.value = '';
            }
            
            // Filter produk dengan nilai kosong
            this.filterProducts('', '');
            
            // Sembunyikan tombol clear
            if (this.searchClear) {
                this.searchClear.style.display = 'none';
            }
            
            // Tampilkan animasi reset
            this.showResetAnimation();
        }
    }
    
    /**
     * Menampilkan animasi reset pencarian
     */
    showResetAnimation() {
        // Tambahkan kelas animasi ke grid produk
        this.productGrid.classList.add('sps-reset-animation');
        
        // Hapus kelas setelah animasi selesai
        setTimeout(() => {
            this.productGrid.classList.remove('sps-reset-animation');
        }, 500);
    }

    /**
     * Menangani klik pada produk
     */
    handleProductClick(productElement) {
        const productId = productElement.dataset.id;
        const productName = productElement.dataset.name;
        
        // Tambahkan ke daftar produk terbaru
        this.addToRecentItems({
            id: productId,
            name: productName,
            code: productElement.dataset.code,
            categoryId: productElement.dataset.category
        });
        
        // Panggil callback jika ada
        if (typeof this.options.onProductSelected === 'function') {
            this.options.onProductSelected(productId);
        }

        // Efek visual saat produk dipilih
        this.showSelectionEffect(productElement);
    }

    /**
     * Menampilkan efek visual saat produk dipilih
     */
    showSelectionEffect(productElement) {
        // Tambahkan kelas untuk animasi
        productElement.classList.add('sps-product-selected');
        
        // Tambahkan notifikasi kecil
        this.showAddedNotification(productElement);
        
        // Hapus kelas setelah animasi selesai
        setTimeout(() => {
            productElement.classList.remove('sps-product-selected');
        }, 800);
    }
    
    /**
     * Menampilkan notifikasi kecil saat produk ditambahkan
     */
    showAddedNotification(productElement) {
        // Buat elemen notifikasi
        const notification = document.createElement('div');
        notification.className = 'sps-added-notification';
        notification.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>Ditambahkan</span>
        `;
        
        // Tambahkan ke body
        document.body.appendChild(notification);
        
        // Posisikan notifikasi di dekat elemen yang diklik
        const rect = productElement.getBoundingClientRect();
        notification.style.top = `${rect.top + window.scrollY + rect.height / 2 - 20}px`;
        notification.style.left = `${rect.left + window.scrollX + rect.width / 2}px`;
        
        // Animasikan notifikasi
        setTimeout(() => notification.classList.add('show'), 10);
        
        // Hapus notifikasi setelah beberapa saat
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 1500);
    }

    /**
     * Menambahkan produk ke daftar produk terbaru
     */
    addToRecentItems(product) {
        // Hapus produk dari daftar jika sudah ada
        this.recentItems = this.recentItems.filter(item => item.id !== product.id);
        
        // Tambahkan produk ke awal daftar
        this.recentItems.unshift(product);
        
        // Batasi jumlah produk terbaru
        if (this.recentItems.length > this.options.maxRecentItems) {
            this.recentItems = this.recentItems.slice(0, this.options.maxRecentItems);
        }
        
        // Simpan ke localStorage
        this.saveRecentItems();
        
        // Render ulang daftar produk terbaru
        this.renderRecentItems();
    }

    /**
     * Menyimpan daftar produk terbaru ke localStorage
     */
    saveRecentItems() {
        try {
            localStorage.setItem('sps_recent_items', JSON.stringify(this.recentItems));
        } catch (error) {
            console.error('Error saving recent items to localStorage:', error);
        }
    }

    /**
     * Memuat daftar produk terbaru dari localStorage
     */
    loadRecentItems() {
        try {
            const recentItems = localStorage.getItem('sps_recent_items');
            return recentItems ? JSON.parse(recentItems) : [];
        } catch (error) {
            console.error('Error loading recent items from localStorage:', error);
            return [];
        }
    }

    /**
     * Render daftar produk terbaru
     */
    renderRecentItems() {
        if (!this.recentItemsContainer) return;
        
        // Kosongkan container
        this.recentItemsContainer.innerHTML = '';
        
        // Jika tidak ada produk terbaru, sembunyikan container
        const recentProductsSection = document.querySelector('#sps-recent-products');
        if (this.recentItems.length === 0) {
            if (recentProductsSection) {
                recentProductsSection.style.display = 'none';
            }
            return;
        }
        
        // Tampilkan container
        if (recentProductsSection) {
            recentProductsSection.style.display = 'block';
        }
        
        // Render setiap produk terbaru
        this.recentItems.forEach(item => {
            const recentItem = document.createElement('div');
            recentItem.className = 'sps-recent-item';
            recentItem.dataset.id = item.id;
            
            recentItem.innerHTML = `
                <div class="sps-recent-item-content">
                    <h4 class="sps-recent-item-name">${item.name}</h4>
                    <p class="sps-recent-item-code">${item.code}</p>
                </div>
            `;
            
            // Event listener untuk klik pada produk terbaru
            recentItem.addEventListener('click', () => {
                if (typeof this.options.onProductSelected === 'function') {
                    this.options.onProductSelected(item.id);
                }
                
                // Efek visual saat produk dipilih
                this.showSelectionEffect(recentItem);
            });
            
            this.recentItemsContainer.appendChild(recentItem);
        });
    }
}