/**
 * Batch Selector Component with FEFO (First Expired, First Out) Logic
 * 
 * This component provides an enhanced UI for selecting product batches
 * with automatic FEFO ordering and intelligent batch allocation.
 */

// Alpine.js component for batch selector
function batchSelector(config = {}) {
    return {
        // Configuration
        product: config.product || null,
        maxQuantity: config.maxQuantity || null,
        
        // State
        isModalOpen: false,
        loading: false,
        batches: [],
        selectedBatches: config.selectedBatches || [],
        requiredQuantity: 0,
        
        // Initialize component
        init() {
            console.log('Batch Selector initialized', this.product);
        },
        
        // Modal controls
        openModal(productData = null) {
            if (productData) {
                this.product = productData.product || productData;
                this.maxQuantity = productData.maxQuantity || null;
                this.requiredQuantity = productData.requiredQuantity || 0;
            }
            
            this.isModalOpen = true;
            
            if (this.product) {
                this.loadBatches();
            }
        },
        
        closeModal() {
            this.isModalOpen = false;
            this.resetSelection();
        },
        
        // Load batches for the selected product
        async loadBatches() {
            if (!this.product || !this.product.id) {
                console.error('No product selected for batch loading');
                return;
            }
            
            this.loading = true;
            
            try {
                const response = await fetch(`/api/products/${this.product.id}/batches`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                // Sort batches by FEFO logic
                this.batches = this.sortBatchesByFEFO(data.batches || []);
                
                // Initialize selection quantities
                this.batches.forEach(batch => {
                    batch.selected_quantity = 0;
                });
                
                console.log('Batches loaded:', this.batches);
                
            } catch (error) {
                console.error('Error loading batches:', error);
                
                // Fallback: use mock data for development
                this.batches = this.getMockBatches();
                
            } finally {
                this.loading = false;
            }
        },
        
        // Sort batches using FEFO (First Expired, First Out) logic
        sortBatchesByFEFO(batches) {
            return batches
                .filter(batch => batch.remaining_quantity > 0)
                .sort((a, b) => {
                    // First, prioritize by expiry date (earliest first)
                    if (a.expiry_date && b.expiry_date) {
                        const dateA = new Date(a.expiry_date);
                        const dateB = new Date(b.expiry_date);
                        
                        if (dateA.getTime() !== dateB.getTime()) {
                            return dateA - dateB;
                        }
                    }
                    
                    // If one has expiry date and other doesn't, prioritize the one with expiry date
                    if (a.expiry_date && !b.expiry_date) return -1;
                    if (!a.expiry_date && b.expiry_date) return 1;
                    
                    // If both don't have expiry dates, sort by production date (FIFO)
                    if (a.production_date && b.production_date) {
                        return new Date(a.production_date) - new Date(b.production_date);
                    }
                    
                    // Finally, sort by creation date (FIFO)
                    return new Date(a.created_at) - new Date(b.created_at);
                });
        },
        
        // Auto-allocate batches using FEFO logic
        autoAllocateBatches() {
            if (!this.requiredQuantity || this.requiredQuantity <= 0) {
                alert('Masukkan jumlah yang dibutuhkan terlebih dahulu');
                return;
            }
            
            // Reset all selections
            this.batches.forEach(batch => {
                batch.selected_quantity = 0;
            });
            
            let remainingQuantity = this.requiredQuantity;
            
            // Allocate from batches in FEFO order
            for (let batch of this.batches) {
                if (remainingQuantity <= 0) break;
                
                const availableInBatch = batch.remaining_quantity;
                const toTake = Math.min(remainingQuantity, availableInBatch);
                
                batch.selected_quantity = toTake;
                remainingQuantity -= toTake;
            }
            
            if (remainingQuantity > 0) {
                alert(`Stok tidak mencukupi. Kekurangan: ${remainingQuantity} unit`);
            }
        },
        
        // Calculate batch allocation based on required quantity
        calculateBatchAllocation() {
            // This method can be used for real-time allocation suggestions
            // For now, we'll just validate the required quantity
            if (this.requiredQuantity > this.getTotalAvailableQuantity()) {
                console.warn('Required quantity exceeds available stock');
            }
        },
        
        // Update batch selection
        updateBatchSelection(batch) {
            // Ensure selected quantity doesn't exceed available quantity
            if (batch.selected_quantity > batch.remaining_quantity) {
                batch.selected_quantity = batch.remaining_quantity;
            }
            
            // Ensure selected quantity is not negative
            if (batch.selected_quantity < 0) {
                batch.selected_quantity = 0;
            }
        },
        
        // Select full batch
        selectFullBatch(batch) {
            batch.selected_quantity = batch.remaining_quantity;
        },
        
        // Get selected batches
        getSelectedBatches() {
            return this.batches.filter(batch => batch.selected_quantity > 0);
        },
        
        // Get total selected quantity
        getTotalSelectedQuantity() {
            return this.getSelectedBatches().reduce((total, batch) => {
                return total + (batch.selected_quantity || 0);
            }, 0);
        },
        
        // Get total available quantity
        getTotalAvailableQuantity() {
            return this.batches.reduce((total, batch) => {
                return total + (batch.remaining_quantity || 0);
            }, 0);
        },
        
        // Get average price of selected batches
        getAveragePrice() {
            const selectedBatches = this.getSelectedBatches();
            if (selectedBatches.length === 0) return 0;
            
            const totalValue = selectedBatches.reduce((total, batch) => {
                return total + (batch.selected_quantity * batch.purchase_price);
            }, 0);
            
            const totalQuantity = this.getTotalSelectedQuantity();
            
            return totalQuantity > 0 ? totalValue / totalQuantity : 0;
        },
        
        // Confirm selection and emit event
        confirmSelection() {
            const selectedBatches = this.getSelectedBatches();
            
            if (selectedBatches.length === 0) {
                alert('Pilih setidaknya satu batch');
                return;
            }
            
            // Emit custom event with selected batches
            window.dispatchEvent(new CustomEvent('batch-selected', {
                detail: {
                    product: this.product,
                    batches: selectedBatches,
                    totalQuantity: this.getTotalSelectedQuantity(),
                    averagePrice: this.getAveragePrice()
                }
            }));
            
            this.closeModal();
        },
        
        // Reset selection
        resetSelection() {
            this.batches.forEach(batch => {
                batch.selected_quantity = 0;
            });
            this.requiredQuantity = 0;
        },
        
        // Utility methods
        formatDate(dateString) {
            if (!dateString) return '-';
            
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        },
        
        formatCurrency(amount) {
            if (!amount) return 'Rp 0';
            
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        },
        
        // Get batch status class for styling
        getBatchStatusClass(batch) {
            const daysToExpiry = this.getDaysToExpiry(batch.expiry_date);
            
            if (daysToExpiry !== null) {
                if (daysToExpiry < 0) {
                    return 'border-red-300 bg-red-50 dark:border-red-600 dark:bg-red-900/20';
                } else if (daysToExpiry <= 7) {
                    return 'border-orange-300 bg-orange-50 dark:border-orange-600 dark:bg-orange-900/20';
                } else if (daysToExpiry <= 30) {
                    return 'border-yellow-300 bg-yellow-50 dark:border-yellow-600 dark:bg-yellow-900/20';
                }
            }
            
            return 'border-gray-200 dark:border-gray-600';
        },
        
        // Get batch priority class for FEFO ordering indicator
        getBatchPriorityClass(index) {
            if (index === 0) {
                return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
            } else if (index === 1) {
                return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
            } else if (index === 2) {
                return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400';
            } else {
                return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            }
        },
        
        // Get expiry status class
        getExpiryStatusClass(batch) {
            const daysToExpiry = this.getDaysToExpiry(batch.expiry_date);
            
            if (daysToExpiry !== null) {
                if (daysToExpiry < 0) {
                    return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                } else if (daysToExpiry <= 7) {
                    return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400';
                } else if (daysToExpiry <= 30) {
                    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                } else {
                    return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                }
            }
            
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
        },
        
        // Get expiry status text
        getExpiryStatusText(batch) {
            const daysToExpiry = this.getDaysToExpiry(batch.expiry_date);
            
            if (daysToExpiry !== null) {
                if (daysToExpiry < 0) {
                    return 'Kadaluarsa';
                } else if (daysToExpiry === 0) {
                    return 'Kadaluarsa Hari Ini';
                } else if (daysToExpiry <= 7) {
                    return `${daysToExpiry} hari lagi`;
                } else if (daysToExpiry <= 30) {
                    return `${daysToExpiry} hari lagi`;
                } else {
                    return 'Masih Fresh';
                }
            }
            
            return 'Tanpa Kadaluarsa';
        },
        
        // Calculate days to expiry
        getDaysToExpiry(expiryDate) {
            if (!expiryDate) return null;
            
            const today = new Date();
            const expiry = new Date(expiryDate);
            const diffTime = expiry - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            return diffDays;
        },
        
        // Mock data for development/testing
        getMockBatches() {
            const today = new Date();
            
            return [
                {
                    id: 1,
                    batch_number: 'BTH001',
                    production_date: new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    expiry_date: new Date(today.getTime() + 5 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    quantity: 100,
                    remaining_quantity: 25,
                    purchase_price: 15000,
                    created_at: new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000).toISOString(),
                    selected_quantity: 0
                },
                {
                    id: 2,
                    batch_number: 'BTH002',
                    production_date: new Date(today.getTime() - 20 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    expiry_date: new Date(today.getTime() + 15 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    quantity: 150,
                    remaining_quantity: 80,
                    purchase_price: 14500,
                    created_at: new Date(today.getTime() - 20 * 24 * 60 * 60 * 1000).toISOString(),
                    selected_quantity: 0
                },
                {
                    id: 3,
                    batch_number: 'BTH003',
                    production_date: new Date(today.getTime() - 10 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    expiry_date: new Date(today.getTime() + 45 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    quantity: 200,
                    remaining_quantity: 150,
                    purchase_price: 15500,
                    created_at: new Date(today.getTime() - 10 * 24 * 60 * 60 * 1000).toISOString(),
                    selected_quantity: 0
                }
            ];
        }
    };
}

// Global functions for external access
window.batchSelector = batchSelector;

// Initialize batch selector when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Batch Selector script loaded');
    
    // Make batch selector globally accessible
    window.openBatchSelector = function(productData) {
        if (window.batchSelectorComponent) {
            window.batchSelectorComponent.openModal(productData);
        } else {
            console.error('Batch selector component not found');
        }
    };
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { batchSelector };
}