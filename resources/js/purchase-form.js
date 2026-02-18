document.addEventListener("alpine:init", () => {
    Alpine.data("purchaseForm", (initialProducts) => ({
        products: initialProducts,
        cart: [],
        date: new Date().toISOString().split("T")[0],
        dueDate: new Date(new Date().setDate(new Date().getDate() + 30))
            .toISOString()
            .split("T")[0],
        notes: "",

        // Search State
        searchQuery: "",
        searchResults: [],
        showSearchResults: false,
        activeSearchIndex: -1,

        // Modal State
        showProductModal: false,
        productModalSearch: "",
        productModalCategory: "",
        productModalView: "list", // Default to list for purchasing
        selectedModalProducts: [],
        categories: [],

        // UI State
        isProcessing: false,

        init() {
            this.extractCategories();

            // Watch date to auto-update due date
            this.$watch("date", (value) => {
                const d = new Date(value);
                d.setDate(d.getDate() + 30);
                this.dueDate = d.toISOString().split("T")[0];
            });

            // Focus search when modal opens
            this.$watch("showProductModal", (value) => {
                if (value) {
                    this.$nextTick(() => {
                        this.$refs.productSearch?.focus();
                    });
                }
            });
        },

        extractCategories() {
            const uniqueCategories = new Map();
            this.products.forEach((p) => {
                if (p.category) {
                    uniqueCategories.set(p.category.id, p.category.name);
                }
            });
            this.categories = Array.from(uniqueCategories, ([id, name]) => ({
                id,
                name,
            }));
        },

        // Search Logic
        handleSearch() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                this.showSearchResults = false;
                return;
            }

            const query = this.searchQuery.toLowerCase();
            this.searchResults = this.products
                .filter(
                    (product) =>
                        product.name.toLowerCase().includes(query) ||
                        (product.code &&
                            product.code.toLowerCase().includes(query)),
                )
                .slice(0, 10);

            this.showSearchResults = true;
            this.activeSearchIndex = -1;
        },

        selectSearchResult(product) {
            this.addToCart(product);
            this.searchQuery = "";
            this.showSearchResults = false;
        },

        // Cart Management
        addToCart(product) {
            // Ensure units exist
            if (!product.units || product.units.length === 0) {
                alert("Produk ini tidak memiliki satuan yang valid.");
                return;
            }

            // Find default unit or first unit (using pivot data)
            const unit =
                product.units.find((u) => u.pivot.is_default == 1) ||
                product.units[0];

            // Check if item exists (same product + same unit)
            const existingItem = this.cart.find(
                (item) =>
                    item.product_id === product.id && item.unit_id === unit.id,
            );

            if (existingItem) {
                existingItem.quantity++;
                this.updateSubtotal(existingItem);
            } else {
                // Use purchase_price from pivot
                let price = parseFloat(unit.pivot.purchase_price) || 0;

                this.cart.push({
                    product_id: product.id,
                    name: product.name,
                    code: product.code,
                    unit_id: unit.id,
                    unit_name: unit.name, // Unit name is directly on the unit model
                    quantity: 1,
                    price: price,
                    conversion_factor: unit.pivot.conversion_factor,
                    units: product.units,
                    subtotal: price,
                    stock: product.actual_stock, // For reference only
                });
            }
        },

        updateItemUnit(item, unitId) {
            const unit = item.units.find((u) => u.id == unitId);
            if (unit) {
                item.unit_id = unit.id;
                item.unit_name = unit.name;
                item.conversion_factor = unit.pivot.conversion_factor;
                // Update price to the unit's default purchase price
                item.price = parseFloat(unit.pivot.purchase_price) || 0;
                this.updateSubtotal(item);
            }
        },

        updateSubtotal(item) {
            item.subtotal =
                (parseFloat(item.quantity) || 0) *
                (parseFloat(item.price) || 0);
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        get total() {
            return this.cart.reduce((sum, item) => sum + item.subtotal, 0);
        },

        // Modal Logic
        get filteredModalProducts() {
            return this.products.filter((product) => {
                const matchesSearch =
                    product.name
                        .toLowerCase()
                        .includes(this.productModalSearch.toLowerCase()) ||
                    (product.code &&
                        product.code
                            .toLowerCase()
                            .includes(this.productModalSearch.toLowerCase()));
                const matchesCategory =
                    this.productModalCategory === "" ||
                    (product.category &&
                        product.category.id == this.productModalCategory);
                return matchesSearch && matchesCategory;
            });
        },

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
            this.selectedModalProducts.forEach((productId) => {
                const product = this.products.find((p) => p.id === productId);
                if (product) this.addToCart(product);
            });
            this.selectedModalProducts = [];
            this.showProductModal = false;
        },

        formatCurrency(value) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        },

        submitForm() {
            if (this.cart.length === 0) {
                alert("Harap pilih minimal satu produk.");
                return;
            }
            this.isProcessing = true;
            this.$refs.form.submit();
        },
    }));
});
