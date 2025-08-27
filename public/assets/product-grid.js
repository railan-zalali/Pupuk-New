/**
 * Product Grid Component for Sales
 * 
 * This script implements a modern product selection interface with:
 * - Grid display of products with images
 * - Category filtering
 * - Advanced search with autocomplete
 * - Quick add functionality
 * - Virtual keyboard for touchscreens
 * - Recent/frequent items section
 * - Barcode scanner integration
 */

class ProductGrid {
    constructor(options) {
        this.options = Object.assign({
            containerSelector: '#product-grid-container',
            categoriesSelector: '#product-categories',
            searchSelector: '#product-search',
            recentItemsSelector: '#recent-items',
            productsData: [],
            categoriesData: [],
            onProductSelect: null,
            apiEndpoint: '/api/products',
            recentItemsCount: 8,
            gridColumns: 4,
            enableBarcode: true,
            enableVirtualKeyboard: true
        }, options);

        // DOM elements
        this.container = document.querySelector(this.options.containerSelector);
        this.categoriesContainer = document.querySelector(this.options.categoriesSelector);
        this.searchInput = document.querySelector(this.options.searchSelector);
        this.recentItemsContainer = document.querySelector(this.options.recentItemsSelector);
        
        // State
        this.products = this.options.productsData || [];
        this.categories = this.options.categoriesData || [];
        this.filteredProducts = [...this.products];
        this.selectedCategory = 'all';
        this.recentItems = this.loadRecentItems();
        
        // Initialize
        this.init();
    }
    
    init() {
        this.renderCategories();
        this.renderProducts();
        this.renderRecentItems();
        this.setupEventListeners();
        
        if (this.options.enableBarcode) {
            this.initBarcodeScanner();
        }
        
        if (this.options.enableVirtualKeyboard) {
            this.initVirtualKeyboard();
        }
    }
    
    renderCategories() {
        if (!this.categoriesContainer) return;
        
        let html = `
            <div class="category-item ${this.selectedCategory === 'all' ? 'active' : ''}" data-category="all">
                <span>Semua Kategori</span>
            </div>
        `;
        
        this.categories.forEach(category => {
            html += `
                <div class="category-item ${this.selectedCategory === category.id ? 'active' : ''}" data-category="${category.id}">
                    <span>${category.name}</span>
                </div>
            `;
        });
        
        this.categoriesContainer.innerHTML = html;
        
        // Add event listeners to category items
        const categoryItems = this.categoriesContainer.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                // Remove active class from all items
                categoryItems.forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                item.classList.add('active');
                
                const categoryId = item.dataset.category;
                this.filterByCategory(categoryId);
            });
        });
    }
    
    renderProducts() {
        if (!this.container) return;
        
        let html = '<div class="product-grid">';
        
        this.filteredProducts.forEach(product => {
            const stockClass = product.stock > 10 ? 'in-stock' : (product.stock > 0 ? 'low-stock' : 'out-of-stock');
            const stockText = product.stock > 0 ? `Stok: ${product.stock}` : 'Stok Habis';
            const imageUrl = product.image_path ? `/storage/${product.image_path}` : '/images/no-image.png';
            
            html += `
                <div class="product-card" data-product-id="${product.id}" data-product-code="${product.code}">
                    <div class="product-image">
                        <img src="${imageUrl}" alt="${product.name}" onerror="this.src='/images/no-image.png'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        <div class="product-price">Rp ${this.formatNumber(product.selling_price)}</div>
                        <div class="product-stock ${stockClass}">${stockText}</div>
                    </div>
                    <button class="quick-add-btn" ${product.stock <= 0 ? 'disabled' : ''} data-product-id="${product.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            `;
        });
        
        html += '</div>';
        
        if (this.filteredProducts.length === 0) {
            html = '<div class="no-products">Tidak ada produk yang ditemukan</div>';
        }
        
        this.container.innerHTML = html;
        
        // Add event listeners to product cards
        const productCards = this.container.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('click', (e) => {
                // Don't trigger if clicking the quick-add button
                if (e.target.closest('.quick-add-btn')) return;
                
                const productId = card.dataset.productId;
                this.selectProduct(productId);
            });
        });
        
        // Add event listeners to quick-add buttons
        const quickAddButtons = this.container.querySelectorAll('.quick-add-btn');
        quickAddButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const productId = button.dataset.productId;
                this.quickAddProduct(productId);
            });
        });
    }
    
    renderRecentItems() {
        if (!this.recentItemsContainer) return;
        
        let html = '<h3>Produk Terakhir Dipilih</h3><div class="recent-items-grid">';
        
        if (this.recentItems.length === 0) {
            html += '<div class="no-recent">Belum ada produk yang dipilih</div>';
        } else {
            this.recentItems.forEach(productId => {
                const product = this.products.find(p => p.id == productId);
                if (!product) return;
                
                const imageUrl = product.image_path ? `/storage/${product.image_path}` : '/images/no-image.png';
                
                html += `
                    <div class="recent-item" data-product-id="${product.id}">
                        <div class="recent-item-image">
                            <img src="${imageUrl}" alt="${product.name}" onerror="this.src='/images/no-image.png'">
                        </div>
                        <div class="recent-item-name">${product.name}</div>
                    </div>
                `;
            });
        }
        
        html += '</div>';
        
        this.recentItemsContainer.innerHTML = html;
        
        // Add event listeners to recent items
        const recentItems = this.recentItemsContainer.querySelectorAll('.recent-item');
        recentItems.forEach(item => {
            item.addEventListener('click', () => {
                const productId = item.dataset.productId;
                this.selectProduct(productId);
            });
        });
    }
    
    setupEventListeners() {
        // Search input
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                this.filterBySearch(searchTerm);
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl+B for barcode scanner
            if (e.ctrlKey && e.key === 'b' && this.options.enableBarcode) {
                e.preventDefault();
                this.openBarcodeScanner();
            }
        });
    }
    
    filterByCategory(categoryId) {
        this.selectedCategory = categoryId;
        
        if (categoryId === 'all') {
            this.filteredProducts = [...this.products];
        } else {
            this.filteredProducts = this.products.filter(product => product.category_id == categoryId);
        }
        
        this.renderProducts();
    }
    
    filterBySearch(searchTerm) {
        if (!searchTerm) {
            this.filterByCategory(this.selectedCategory);
            return;
        }
        
        // Filter products by category first
        let categoryFiltered = this.selectedCategory === 'all' 
            ? [...this.products] 
            : this.products.filter(product => product.category_id == this.selectedCategory);
        
        // Then filter by search term
        this.filteredProducts = categoryFiltered.filter(product => {
            return (
                product.name.toLowerCase().includes(searchTerm) ||
                product.code.toLowerCase().includes(searchTerm) ||
                (product.description && product.description.toLowerCase().includes(searchTerm))
            );
        });
        
        this.renderProducts();
    }
    
    selectProduct(productId) {
        const product = this.products.find(p => p.id == productId);
        if (!product) return;
        
        // Add to recent items
        this.addToRecentItems(productId);
        
        // Call the onProductSelect callback if provided
        if (typeof this.options.onProductSelect === 'function') {
            this.options.onProductSelect(product);
        }
    }
    
    quickAddProduct(productId) {
        const product = this.products.find(p => p.id == productId);
        if (!product) return;
        
        // Show quantity selector modal
        this.showQuantitySelector(product);
    }
    
    showQuantitySelector(product) {
        // Create modal for quantity selection
        const modal = document.createElement('div');
        modal.className = 'quantity-selector-modal';
        modal.innerHTML = `
            <div class="quantity-selector-content">
                <div class="quantity-selector-header">
                    <h3>Pilih Jumlah</h3>
                    <button class="close-btn">&times;</button>
                </div>
                <div class="quantity-selector-body">
                    <div class="product-info">
                        <h4>${product.name}</h4>
                        <div class="product-price">Rp ${this.formatNumber(product.selling_price)}</div>
                    </div>
                    <div class="quantity-control">
                        <button class="quantity-btn minus">-</button>
                        <input type="number" class="quantity-input" value="1" min="1" max="${product.stock}">
                        <button class="quantity-btn plus">+</button>
                    </div>
                    <div class="quick-quantity">
                        <button class="quick-quantity-btn" data-value="1">1</button>
                        <button class="quick-quantity-btn" data-value="2">2</button>
                        <button class="quick-quantity-btn" data-value="5">5</button>
                        <button class="quick-quantity-btn" data-value="10">10</button>
                    </div>
                </div>
                <div class="quantity-selector-footer">
                    <button class="cancel-btn">Batal</button>
                    <button class="add-btn">Tambahkan</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Show modal with animation
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
        
        // Setup event listeners
        const closeBtn = modal.querySelector('.close-btn');
        const cancelBtn = modal.querySelector('.cancel-btn');
        const addBtn = modal.querySelector('.add-btn');
        const minusBtn = modal.querySelector('.minus');
        const plusBtn = modal.querySelector('.plus');
        const quantityInput = modal.querySelector('.quantity-input');
        const quickQuantityBtns = modal.querySelectorAll('.quick-quantity-btn');
        
        // Close modal function
        const closeModal = () => {
            modal.classList.remove('active');
            setTimeout(() => {
                document.body.removeChild(modal);
            }, 300);
        };
        
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        
        // Quantity controls
        minusBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });
        
        plusBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value < product.stock) {
                quantityInput.value = value + 1;
            }
        });
        
        // Quick quantity buttons
        quickQuantityBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                let value = parseInt(btn.dataset.value);
                if (value <= product.stock) {
                    quantityInput.value = value;
                } else {
                    quantityInput.value = product.stock;
                }
            });
        });
        
        // Add button
        addBtn.addEventListener('click', () => {
            const quantity = parseInt(quantityInput.value);
            
            // Add to recent items
            this.addToRecentItems(product.id);
            
            // Call the onProductSelect callback if provided
            if (typeof this.options.onProductSelect === 'function') {
                this.options.onProductSelect(product, quantity);
            }
            
            closeModal();
        });
        
        // Initialize virtual keyboard for quantity input if enabled
        if (this.options.enableVirtualKeyboard) {
            quantityInput.addEventListener('focus', () => {
                this.showVirtualKeyboard(quantityInput);
            });
        }
    }
    
    addToRecentItems(productId) {
        // Remove if already exists
        const index = this.recentItems.indexOf(productId.toString());
        if (index !== -1) {
            this.recentItems.splice(index, 1);
        }
        
        // Add to the beginning
        this.recentItems.unshift(productId.toString());
        
        // Limit to specified count
        if (this.recentItems.length > this.options.recentItemsCount) {
            this.recentItems = this.recentItems.slice(0, this.options.recentItemsCount);
        }
        
        // Save to localStorage
        this.saveRecentItems();
        
        // Update the UI
        this.renderRecentItems();
    }
    
    loadRecentItems() {
        try {
            const stored = localStorage.getItem('recentProducts');
            return stored ? JSON.parse(stored) : [];
        } catch (e) {
            console.error('Error loading recent items:', e);
            return [];
        }
    }
    
    saveRecentItems() {
        try {
            localStorage.setItem('recentProducts', JSON.stringify(this.recentItems));
        } catch (e) {
            console.error('Error saving recent items:', e);
        }
    }
    
    initBarcodeScanner() {
        // Add barcode scanner button to the UI
        const scannerBtn = document.createElement('button');
        scannerBtn.className = 'barcode-scanner-btn';
        scannerBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <span>Scan Barcode</span>
        `;
        
        // Add to the document
        if (this.searchInput && this.searchInput.parentNode) {
            this.searchInput.parentNode.appendChild(scannerBtn);
        }
        
        // Add event listener
        scannerBtn.addEventListener('click', () => {
            this.openBarcodeScanner();
        });
    }
    
    openBarcodeScanner() {
        // Create modal for barcode scanner
        const modal = document.createElement('div');
        modal.className = 'barcode-scanner-modal';
        modal.innerHTML = `
            <div class="barcode-scanner-content">
                <div class="barcode-scanner-header">
                    <h3>Scan Barcode Produk</h3>
                    <button class="close-btn">&times;</button>
                </div>
                <div class="barcode-scanner-body">
                    <div class="camera-container">
                        <video id="barcode-scanner-video" playsinline></video>
                        <div class="scanner-overlay">
                            <div class="scanner-line"></div>
                        </div>
                    </div>
                    <div class="manual-input">
                        <p>Atau masukkan kode barcode secara manual:</p>
                        <input type="text" id="barcode-manual-input" placeholder="Masukkan kode barcode...">
                        <button id="barcode-submit">Cari</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Show modal with animation
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
        
        // Setup event listeners
        const closeBtn = modal.querySelector('.close-btn');
        const videoElement = modal.querySelector('#barcode-scanner-video');
        const manualInput = modal.querySelector('#barcode-manual-input');
        const submitBtn = modal.querySelector('#barcode-submit');
        
        // Close modal function
        const closeModal = () => {
            // Stop video stream if active
            if (videoElement.srcObject) {
                const tracks = videoElement.srcObject.getTracks();
                tracks.forEach(track => track.stop());
            }
            
            modal.classList.remove('active');
            setTimeout(() => {
                document.body.removeChild(modal);
            }, 300);
        };
        
        closeBtn.addEventListener('click', closeModal);
        
        // Initialize barcode scanner
        this.initBarcodeVideo(videoElement, closeModal);
        
        // Manual input
        submitBtn.addEventListener('click', () => {
            const barcode = manualInput.value.trim();
            if (barcode) {
                this.findProductByBarcode(barcode, closeModal);
            }
        });
        
        manualInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const barcode = manualInput.value.trim();
                if (barcode) {
                    this.findProductByBarcode(barcode, closeModal);
                }
            }
        });
        
        // Initialize virtual keyboard for manual input if enabled
        if (this.options.enableVirtualKeyboard) {
            manualInput.addEventListener('focus', () => {
                this.showVirtualKeyboard(manualInput);
            });
        }
    }
    
    initBarcodeVideo(videoElement, closeModalCallback) {
        // Check if browser supports getUserMedia
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Browser tidak mendukung akses kamera. Gunakan input manual.');
            return;
        }
        
        // Get access to the camera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                videoElement.srcObject = stream;
                videoElement.setAttribute('playsinline', true);
                videoElement.play();
                
                // Use a barcode detection library here
                // For example, with QuaggaJS or ZXing
                // This is a simplified example
                this.startBarcodeDetection(videoElement, closeModalCallback);
            })
            .catch(err => {
                console.error('Error accessing camera:', err);
                alert('Tidak dapat mengakses kamera. Gunakan input manual.');
            });
    }
    
    startBarcodeDetection(videoElement, closeModalCallback) {
        // This is a placeholder for actual barcode detection
        // In a real implementation, you would use a library like QuaggaJS or ZXing
        
        // For demonstration purposes, we'll just log a message
        console.log('Barcode detection would start here with a proper library');
        
        // In a real implementation, you would have code like:
        /*
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: videoElement
            },
            decoder: {
                readers: ["ean_reader", "ean_8_reader", "code_128_reader"]
            }
        }, function(err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
        });
        
        Quagga.onDetected(result => {
            const code = result.codeResult.code;
            this.findProductByBarcode(code, closeModalCallback);
        });
        */
    }
    
    findProductByBarcode(barcode, closeModalCallback) {
        // Find product by barcode/code
        const product = this.products.find(p => p.code === barcode);
        
        if (product) {
            // Close the modal
            if (typeof closeModalCallback === 'function') {
                closeModalCallback();
            }
            
            // Select the product
            this.selectProduct(product.id);
        } else {
            alert('Produk dengan kode barcode tersebut tidak ditemukan');
        }
    }
    
    // Load products from API
    loadProducts() {
        // Use the API endpoint from options
        const endpoint = this.options.apiEndpoint || '/api/products';
        
        // Show loading state
        if (this.container) {
            this.container.innerHTML = '<div class="loading">Memuat produk...</div>';
        }
        
        // Fetch products from API
        fetch(endpoint)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update products data
                this.products = data;
                this.filteredProducts = [...this.products];
                
                // Render products
                this.renderProducts();
            })
            .catch(error => {
                console.error('Error loading products:', error);
                if (this.container) {
                    this.container.innerHTML = '<div class="error">Gagal memuat produk. Silakan coba lagi.</div>';
                }
            });
    }
    
    // Load categories from API
    loadCategories() {
        // Use the categories endpoint
        const endpoint = '/api/categories';
        
        // Show loading state
        if (this.categoriesContainer) {
            this.categoriesContainer.innerHTML = '<div class="loading">Memuat kategori...</div>';
        }
        
        // Fetch categories from API
        fetch(endpoint)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update categories data
                this.categories = data;
                
                // Render categories
                this.renderCategories();
            })
            .catch(error => {
                console.error('Error loading categories:', error);
                if (this.categoriesContainer) {
                    this.categoriesContainer.innerHTML = '<div class="error">Gagal memuat kategori.</div>';
                }
            });
    }
    
    initVirtualKeyboard() {
        // We'll create the keyboard when needed
        console.log('Virtual keyboard initialized and ready to be shown when needed');
    }
    
    showVirtualKeyboard(inputElement) {
        // Check if a keyboard is already open
        if (document.querySelector('.virtual-keyboard')) {
            return;
        }
        
        // Create the keyboard
        const keyboard = document.createElement('div');
        keyboard.className = 'virtual-keyboard';
        
        // For a numeric keyboard
        const keys = ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'Clear', '0', 'Delete'];
        
        let keyboardHtml = '<div class="keyboard-keys">';
        keys.forEach(key => {
            let className = 'keyboard-key';
            if (key === 'Clear' || key === 'Delete') {
                className += ' keyboard-key-wide';
            }
            
            keyboardHtml += `<button type="button" class="${className}" data-key="${key}">${key}</button>`;
        });
        keyboardHtml += '</div>';
        
        keyboard.innerHTML = keyboardHtml;
        
        // Add to the document
        document.body.appendChild(keyboard);
        
        // Show with animation
        setTimeout(() => {
            keyboard.classList.add('active');
        }, 10);
        
        // Add event listeners to keys
        const keyElements = keyboard.querySelectorAll('.keyboard-key');
        keyElements.forEach(keyElement => {
            keyElement.addEventListener('click', () => {
                const key = keyElement.dataset.key;
                
                if (key === 'Delete') {
                    // Delete the last character
                    inputElement.value = inputElement.value.slice(0, -1);
                } else if (key === 'Clear') {
                    // Clear the input
                    inputElement.value = '';
                } else {
                    // Add the key to the input
                    inputElement.value += key;
                }
                
                // Trigger input event
                const event = new Event('input', { bubbles: true });
                inputElement.dispatchEvent(event);
                
                // Keep focus on the input
                inputElement.focus();
            });
        });
        
        // Close keyboard when clicking outside
        const closeKeyboard = (e) => {
            if (!keyboard.contains(e.target) && e.target !== inputElement) {
                keyboard.classList.remove('active');
                setTimeout(() => {
                    document.body.removeChild(keyboard);
                }, 300);
                document.removeEventListener('click', closeKeyboard);
            }
        };
        
        // Add a small delay before adding the event listener
        setTimeout(() => {
            document.addEventListener('click', closeKeyboard);
        }, 100);
    }
    
    formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
}

// Export for use in other scripts
window.ProductGrid = ProductGrid;