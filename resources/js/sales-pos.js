document.addEventListener('alpine:init', () => {
    Alpine.data('posSystem', (initialProducts, initialCustomers, initialInvoiceNumber, draftData) => ({
        // State
        products: initialProducts,
        customers: initialCustomers,
        invoiceNumber: initialInvoiceNumber,
        categories: [],
        
        // Cart
        cart: [],
        customer: draftData?.customer_id || '',
        newCustomerName: '',
        date: new Date().toISOString().split('T')[0],
        notes: draftData?.notes || '',
        
        // Payment
        paymentMethod: 'cash', // cash, transfer, credit
        paidAmount: 0,
        
        // Search
        searchQuery: '',
        searchResults: [],
        showSearchResults: false,
        activeSearchIndex: -1,
        
        // Vehicle Info
        showVehicleInfo: false,
        vehicleType: 'Truk',
        vehicleNumber: '',

        // Product Modal State
        showProductModal: false,
        productModalSearch: '',
        productModalCategory: '',
        productModalView: 'grid', // 'grid' or 'list'
        selectedModalProducts: [], // Array of product IDs

        // Customer Modal State
        showCustomerModal: false,
        tempCustomer: {
            name: '',
            phone: '',
            address: ''
        },

        // UI States
        isProcessing: false,
        showSuccessModal: false,
        successMessage: '',
        
        init() {
            // Extract categories from products
            this.extractCategories();

            // Load draft data if available
            if (draftData) {
                this.invoiceNumber = draftData.invoice_number;
                this.date = draftData.date.split('T')[0];
                this.customer = draftData.customer_id;
                this.paymentMethod = draftData.payment_method;
                this.vehicleType = draftData.vehicle_type || 'Truk';
                this.vehicleNumber = draftData.vehicle_number || '';
                
                if (draftData.sale_details) {
                    draftData.sale_details.forEach(detail => {
                        const product = this.products.find(p => p.id === detail.product_id);
                        if (product) {
                            this.addToCart(product, detail.quantity, detail.unit_id);
                        }
                    });
                }
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.altKey && e.key.toLowerCase() === 'a') {
                    e.preventDefault();
                    this.$refs.searchInput.focus();
                }
                if (e.altKey && e.key.toLowerCase() === 's') {
                    e.preventDefault();
                    this.processTransaction();
                }
                if (e.altKey && e.key.toLowerCase() === 'm') {
                    e.preventDefault();
                    this.showProductModal = true;
                }
            });

            // Watch for paid amount changes to prevent negative values
            this.$watch('paidAmount', (value) => {
                if (value < 0) this.paidAmount = 0;
            });

            // Focus search input when product modal opens
            this.$watch('showProductModal', (value) => {
                if (value) {
                    this.$nextTick(() => {
                        this.$refs.productSearch.focus();
                    });
                }
            });
        },

        extractCategories() {
            const uniqueCategories = new Map();
            this.products.forEach(p => {
                if (p.category) {
                    uniqueCategories.set(p.category.id, p.category.name);
                }
            });
            this.categories = Array.from(uniqueCategories, ([id, name]) => ({ id, name }));
        },

        // Computed Properties (Helpers)
        get subtotal() {
            return this.cart.reduce((sum, item) => sum + item.subtotal, 0);
        },

        get discount() {
            return 0; // Implement discount logic if needed
        },

        get total() {
            return this.subtotal - this.discount;
        },

        get change() {
            if (this.paymentMethod === 'credit') return 0;
            return Math.max(0, this.paidAmount - this.total);
        },

        get remaining() {
            if (this.paymentMethod !== 'credit') return 0;
            return Math.max(0, this.total - this.paidAmount);
        },

        get isValid() {
            return this.cart.length > 0 && (this.customer || this.newCustomerName);
        },

        get filteredModalProducts() {
            return this.products.filter(product => {
                const matchesSearch = product.name.toLowerCase().includes(this.productModalSearch.toLowerCase()) || 
                                      (product.code && product.code.toLowerCase().includes(this.productModalSearch.toLowerCase()));
                const matchesCategory = this.productModalCategory === '' || (product.category && product.category.id == this.productModalCategory);
                return matchesSearch && matchesCategory;
            });
        },

        // Methods
        formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        },

        handleSearch() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                this.showSearchResults = false;
                return;
            }

            const query = this.searchQuery.toLowerCase();
            this.searchResults = this.products.filter(product => 
                product.name.toLowerCase().includes(query) || 
                (product.code && product.code.toLowerCase().includes(query))
            ).slice(0, 10); // Limit to 10 results
            
            this.showSearchResults = true;
            this.activeSearchIndex = -1;
        },

        selectSearchResult(product) {
            this.addToCart(product);
            this.searchQuery = '';
            this.showSearchResults = false;
            this.$refs.searchInput.focus();
        },

        addToCart(product, quantity = 1, unitId = null) {
            // Determine unit (default or first available)
            let unit = null;
            if (unitId) {
                unit = product.product_units.find(u => u.id == unitId);
            } else {
                unit = product.product_units.find(u => u.is_default) || product.product_units[0];
            }

            if (!unit) {
                alert('Produk ini tidak memiliki satuan yang valid.');
                return;
            }

            const existingItem = this.cart.find(item => item.product_id === product.id && item.unit_id === unit.id);

            if (existingItem) {
                existingItem.quantity += parseFloat(quantity);
                this.updateItemSubtotal(existingItem);
            } else {
                this.cart.push({
                    product_id: product.id,
                    name: product.name,
                    code: product.code,
                    unit_id: unit.id,
                    unit_name: unit.unit.name,
                    quantity: parseFloat(quantity),
                    price: unit.selling_price,
                    subtotal: parseFloat(quantity) * unit.selling_price,
                    units: product.product_units, // Store available units for switching
                    stock: product.stock // For validation
                });
            }
        },

        updateItemUnit(item, newUnitId) {
            const unit = item.units.find(u => u.id == newUnitId);
            if (unit) {
                item.unit_id = unit.id;
                item.unit_name = unit.unit.name;
                item.price = unit.selling_price;
                this.updateItemSubtotal(item);
            }
        },

        updateItemQuantity(item, delta) {
            const newQty = parseFloat(item.quantity) + delta;
            if (newQty > 0) {
                item.quantity = newQty;
                this.updateItemSubtotal(item);
            }
        },
        
        updateItemSubtotal(item) {
            item.subtotal = item.quantity * item.price;
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        setQuickAmount(amount) {
            if (amount === 'exact') {
                this.paidAmount = this.total;
            } else {
                this.paidAmount = amount;
            }
        },

        // Product Modal Methods
        toggleModalProduct(product) {
            const index = this.selectedModalProducts.indexOf(product.id);
            if (index === -1) {
                this.selectedModalProducts.push(product.id);
            } else {
                this.selectedModalProducts.splice(index, 1);
            }
        },

        isModalProductSelected(productId) {
            return this.selectedModalProducts.includes(productId);
        },

        addSelectedProducts() {
            this.selectedModalProducts.forEach(productId => {
                const product = this.products.find(p => p.id === productId);
                if (product) {
                    this.addToCart(product);
                }
            });
            this.selectedModalProducts = [];
            this.showProductModal = false;
        },

        // Customer Modal Methods
        openCustomerModal() {
            this.tempCustomer = { name: '', phone: '', address: '' };
            this.showCustomerModal = true;
            // Focus on name input after modal opens
            setTimeout(() => this.$refs.newCustomerNameInput.focus(), 100);
        },

        saveNewCustomer() {
            if (!this.tempCustomer.name.trim()) {
                alert('Nama pelanggan wajib diisi');
                return;
            }

            // In a real app, you might want to save to DB via AJAX here.
            // For now, we'll use the existing "new_customer_name" logic 
            // but effectively "select" this new customer in the UI.
            
            this.newCustomerName = this.tempCustomer.name;
            this.customer = ''; // Clear selected ID so backend uses the name
            
            // Add to customers list for display (optimistic)
            // We use a temporary ID (string) to distinguish from DB IDs
            const tempId = 'new_' + Date.now();
            this.customers.push({
                id: '', // Empty ID triggers new customer logic in controller if selected? 
                        // Wait, controller checks `new_customer_name` input.
                        // If I select a value in the dropdown, `customer_id` is sent.
                        // If I want to use `new_customer_name`, `customer_id` should be empty or ignored?
                        // Controller: $customerId = $request->customer_id; if (!empty($newCustomerName)) { ... create ... $customerId = $customer->id }
                        // So if newCustomerName is present, it creates a customer.
                nama: this.tempCustomer.name,
                kecamatan_nama: 'Baru',
                kabupaten_nama: ''
            });
            
            // Actually, to make the dropdown work nicely with "new" customers without reloading:
            // The controller logic prefers `new_customer_name` if present.
            // But if I add it to the dropdown, the user selects it, and it has an ID.
            // Since it's not in DB, it has no ID.
            // Strategy: Set `customer` (the model for select) to empty string, 
            // and ensure `newCustomerName` is filled.
            // And maybe show a UI indicator "Pelanggan Baru: [Name]" instead of the select?
            // Or just append to the select options with a special value?
            
            // Simplest approach: Just fill the newCustomerName and clear customer select.
            // And maybe show a visual tag.
            
            this.showCustomerModal = false;
        },

        processTransaction() {
            if (!this.isValid) {
                alert('Mohon lengkapi data transaksi (Pelanggan & Item).');
                return;
            }

            if (this.paymentMethod === 'credit' && this.paidAmount >= this.total) {
                if (!confirm('Jumlah bayar >= Total. Apakah anda yakin ingin mencatat sebagai Kredit? (Status akan Lunas)')) {
                    return;
                }
            }
            
            if (this.paymentMethod !== 'credit' && this.paidAmount < this.total) {
                alert('Pembayaran kurang. Mohon gunakan metode Kredit atau bayar lunas.');
                return;
            }

            this.isProcessing = true;
            this.$refs.form.submit();
        }
    }));
});