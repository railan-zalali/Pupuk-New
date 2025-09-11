<!-- Product Selector Modal -->
<div id="product-selector-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <!-- Modal Header -->
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Pilih Produk untuk Pembelian
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Pilih produk yang ingin dibeli dari supplier yang dipilih
                            </p>
                        </div>
                    </div>
                    <button type="button" id="close-modal" class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6">
                <div id="purchase-product-selector-container" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Product selector will be rendered here -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="add-selected-products" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah <span id="selected-products-count">0</span> Produk
                </button>
                <button type="button" id="cancel-selection" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
class PurchaseProductSelectorModal {
    constructor() {
        this.modal = document.getElementById('product-selector-modal');
        this.productSelector = null;
        this.onProductsSelected = null;
        this.currentSupplierId = null;
        
        this.init();
    }

    init() {
        this.attachEventListeners();
    }

    attachEventListeners() {
        // Close modal events
        const closeBtn = document.getElementById('close-modal');
        const cancelBtn = document.getElementById('cancel-selection');
        const overlay = this.modal.querySelector('.bg-gray-500');
        
        [closeBtn, cancelBtn, overlay].forEach(element => {
            element.addEventListener('click', () => this.close());
        });

        // Add selected products
        const addBtn = document.getElementById('add-selected-products');
        addBtn.addEventListener('click', () => this.addSelectedProducts());

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.close();
            }
        });
    }

    open(supplierId, onProductsSelected = null) {
        this.currentSupplierId = supplierId;
        this.onProductsSelected = onProductsSelected;
        
        if (!supplierId) {
            alert('Pilih supplier terlebih dahulu');
            return;
        }

        // Show modal
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Initialize product selector if not exists
        if (!this.productSelector) {
            this.productSelector = new PurchaseProductSelector('purchase-product-selector-container');
            
            // Listen for selection changes
            const container = document.getElementById('purchase-product-selector-container');
            container.addEventListener('selectionChange', (e) => {
                this.updateAddButton(e.detail.count);
            });
        }

        // Set supplier and load products
        this.productSelector.setSupplier(supplierId);
    }

    close() {
        this.modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Clear selection
        if (this.productSelector) {
            this.productSelector.clearSelection();
        }
        
        this.updateAddButton(0);
    }

    addSelectedProducts() {
        if (!this.productSelector) return;
        
        const selectedProducts = this.productSelector.getSelectedProducts();
        
        if (selectedProducts.length === 0) {
            alert('Pilih produk terlebih dahulu');
            return;
        }

        // Call the callback function if provided
        if (this.onProductsSelected) {
            this.onProductsSelected(selectedProducts);
        }

        // Close modal
        this.close();
    }

    updateAddButton(count) {
        const addBtn = document.getElementById('add-selected-products');
        const countSpan = document.getElementById('selected-products-count');
        
        countSpan.textContent = count;
        addBtn.disabled = count === 0;
        
        if (count === 0) {
            addBtn.innerHTML = `
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah <span id="selected-products-count">0</span> Produk
            `;
        } else {
            addBtn.innerHTML = `
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah <span id="selected-products-count">${count}</span> Produk
            `;
        }
    }
}

// Initialize modal when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.purchaseProductSelectorModal = new PurchaseProductSelectorModal();
});
</script>

<style>
/* Custom scrollbar for modal content */
#purchase-product-selector-container::-webkit-scrollbar {
    width: 6px;
}

#purchase-product-selector-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#purchase-product-selector-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#purchase-product-selector-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Dark mode scrollbar */
.dark #purchase-product-selector-container::-webkit-scrollbar-track {
    background: #374151;
}

.dark #purchase-product-selector-container::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark #purchase-product-selector-container::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Animation for modal */
#product-selector-modal {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Product card hover effects */
.product-card {
    transition: all 0.2s ease-in-out;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.dark .product-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}
</style>