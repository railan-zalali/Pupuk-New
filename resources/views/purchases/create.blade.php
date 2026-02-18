<x-app-layout>
    @push('scripts')
        @vite(['resources/js/purchase-form.js'])
    @endpush

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 font-sans" 
         x-data="purchaseForm({{ json_encode($products) }})">
        
        <!-- Navbar / Top Bar -->
        <header class="sticky top-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm h-16">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex items-center justify-between h-full gap-4">
                    <!-- Brand / Back -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('purchases.index') }}" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 transition-colors" title="Kembali">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900 dark:text-white leading-none">Pembelian Baru</h1>
                            <span class="text-xs text-gray-500 font-mono">{{ $invoiceNumber }}</span>
                        </div>
                    </div>

                    <!-- Center Search (Global) -->
                    <div class="flex-1 max-w-xl relative">
                        <div class="relative">
                            <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" 
                                   x-ref="searchInput"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="handleSearch()"
                                   @keydown.down.prevent="activeSearchIndex = Math.min(activeSearchIndex + 1, searchResults.length - 1)"
                                   @keydown.up.prevent="activeSearchIndex = Math.max(activeSearchIndex - 1, 0)"
                                   @keydown.enter.prevent="if(activeSearchIndex >= 0) selectSearchResult(searchResults[activeSearchIndex])"
                                   @keydown.escape="showSearchResults = false"
                                   placeholder="Cari produk untuk dibeli..." 
                                   class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-700 border-none rounded-full focus:ring-2 focus:ring-emerald-500 text-sm transition-all">
                            
                            <!-- Dropdown Results -->
                             <div x-show="showSearchResults && searchResults.length > 0" 
                                 @click.away="showSearchResults = false"
                                 x-transition.opacity.duration.200ms
                                 class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                                <ul class="max-h-[60vh] overflow-y-auto py-1">
                                    <template x-for="(product, index) in searchResults" :key="product.id">
                                        <li @click="selectSearchResult(product)"
                                            @mouseenter="activeSearchIndex = index"
                                            :class="{'bg-emerald-50 dark:bg-emerald-900/30': activeSearchIndex === index}"
                                            class="px-4 py-3 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-0 flex justify-between items-center group">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white" x-text="product.name"></div>
                                                <div class="text-xs text-gray-500 flex gap-2">
                                                    <span x-text="product.code"></span>
                                                    <span :class="product.actual_stock > 0 ? 'text-emerald-600' : 'text-red-500'" x-text="product.actual_stock > 0 ? 'Stok: ' + product.actual_stock : 'Stok Habis'"></span>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Klik untuk tambah
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-3">
                        <button type="button" @click="showProductModal = true" class="hidden md:flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-full text-sm font-medium transition-colors">
                            <i class="ti ti-layout-grid"></i>
                            Katalog Produk
                        </button>
                        <button @click="submitForm()" 
                                :disabled="cart.length === 0 || isProcessing"
                                class="flex items-center gap-2 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-medium shadow-md shadow-emerald-600/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="isProcessing ? 'Menyimpan...' : 'Simpan Pembelian'"></span>
                            <i class="ti ti-check" x-show="!isProcessing"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Layout -->
        <main class="max-w-full mx-auto p-4 sm:p-6 lg:p-8">
            <form x-ref="form" action="{{ route('purchases.store') }}" method="POST" class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-8rem)]">
                @csrf
                
                <!-- Left Panel: Cart (Flexible height) -->
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
                    <!-- Cart Header -->
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                            Item Pembelian
                        </h2>
                        <span class="text-xs font-mono text-gray-400" x-text="cart.length + ' Items'"></span>
                    </div>

                    <!-- Cart List -->
                    <div class="flex-1 overflow-y-auto p-0 custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 z-10 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3">Produk</th>
                                    <th class="px-6 py-3 w-32">Qty</th>
                                    <th class="px-6 py-3 w-32">Satuan</th>
                                    <th class="px-6 py-3 w-40 text-right">Harga Beli</th>
                                    <th class="px-6 py-3 w-40 text-right">Subtotal</th>
                                    <th class="px-6 py-3 w-16"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <template x-for="(item, index) in cart" :key="index">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                                        <!-- Hidden Inputs -->
                                        <input type="hidden" :name="'product_id[' + index + ']'" :value="item.product_id">
                                        <input type="hidden" :name="'unit_id[' + index + ']'" :value="item.unit_id">
                                        <input type="hidden" :name="'quantity[' + index + ']'" :value="item.quantity">
                                        <input type="hidden" :name="'purchase_price[' + index + ']'" :value="item.price">
                                        <input type="hidden" :name="'conversion_factor[' + index + ']'" :value="item.conversion_factor">

                                        <td class="px-6 py-4 align-top">
                                            <div class="font-medium text-gray-900 dark:text-white" x-text="item.name"></div>
                                            <div class="text-xs text-gray-500 font-mono" x-text="item.code"></div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <input type="number" x-model="item.quantity" @input="updateSubtotal(item)" min="1" class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:ring-emerald-500 focus:border-emerald-500 text-center">
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <select @change="updateItemUnit(item, $event.target.value)"
                                                    class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                                <template x-for="unit in item.units" :key="unit.id">
                                                    <option :value="unit.id" :selected="unit.id == item.unit_id" x-text="unit.name"></option>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <input type="number" x-model="item.price" @input="updateSubtotal(item)" min="0" class="w-full rounded-lg border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:ring-emerald-500 focus:border-emerald-500 text-right">
                                        </td>
                                        <td class="px-6 py-4 align-top text-right font-bold text-gray-900 dark:text-white">
                                            <span x-text="formatCurrency(item.subtotal)"></span>
                                        </td>
                                        <td class="px-6 py-4 align-top text-right">
                                            <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                
                                <!-- Empty State -->
                                <tr x-show="cart.length === 0">
                                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <i class="ti ti-shopping-cart-plus text-3xl text-gray-400"></i>
                                            </div>
                                            <p class="font-medium">Belum ada item pembelian</p>
                                            <p class="text-sm mt-1">Cari produk atau gunakan katalog untuk menambahkan item.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cart Footer -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Total Estimasi</span>
                            <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Info (Fixed width) -->
                <div class="w-full lg:w-96 flex flex-col gap-6">
                    
                    <!-- Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 space-y-5">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <i class="ti ti-info-circle text-emerald-500"></i>
                            <h3 class="font-bold text-gray-800 dark:text-white">Informasi Pembelian</h3>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tanggal Pembelian</label>
                            <input type="date" name="date" x-model="date" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jatuh Tempo</label>
                            <input type="date" name="due_date" x-model="dueDate" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Catatan</label>
                            <textarea name="notes" x-model="notes" rows="4" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:ring-emerald-500 focus:border-emerald-500 resize-none" placeholder="Catatan tambahan..."></textarea>
                        </div>

                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800 text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                            <i class="ti ti-info-circle mr-1"></i>
                            Sistem akan otomatis memisahkan Purchase Order (PO) berdasarkan supplier masing-masing produk.
                        </div>
                    </div>
                </div>
            </form>
        </main>

        <!-- SLIDE-OVER PRODUCT CATALOG -->
        <div x-show="showProductModal" 
             style="display: none;"
             class="relative z-50" 
             aria-labelledby="slide-over-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="showProductModal"
                 x-transition:enter="ease-in-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"
                 @click="showProductModal = false"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        
                        <!-- Panel -->
                        <div x-show="showProductModal"
                             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full"
                             class="pointer-events-auto w-screen max-w-5xl">
                            
                            <div class="flex h-full flex-col bg-white dark:bg-gray-800 shadow-2xl">
                                <!-- Drawer Header -->
                                <div class="px-4 py-6 sm:px-6 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 z-10">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="slide-over-title">Katalog Produk</h2>
                                            <p class="text-sm text-gray-500 mt-1">Pilih produk untuk ditambahkan ke daftar pembelian.</p>
                                        </div>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" @click="showProductModal = false" class="rounded-full p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                <i class="ti ti-x text-2xl"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Search & Filter -->
                                    <div class="mt-6 flex gap-4">
                                        <div class="relative flex-1">
                                            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                            <input type="text" x-model="productModalSearch" x-ref="productSearch" placeholder="Cari nama, kode..." class="w-full pl-11 pr-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-xl border-none focus:ring-2 focus:ring-emerald-500 transition-shadow">
                                        </div>
                                    </div>
                                </div>

                                <!-- Drawer Body -->
                                <div class="flex flex-1 overflow-hidden relative">
                                    <!-- Categories Sidebar -->
                                    <div class="w-64 bg-gray-50 dark:bg-gray-800/50 border-r border-gray-100 dark:border-gray-700 overflow-y-auto p-4 hidden md:block">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">Kategori</h3>
                                        <div class="space-y-1">
                                            <button @click="productModalCategory = ''" 
                                                    :class="productModalCategory === '' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 font-bold' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                                    class="w-full text-left px-4 py-3 rounded-xl text-sm transition-colors flex justify-between items-center">
                                                <span>Semua</span>
                                                <i class="ti ti-chevron-right text-xs opacity-50"></i>
                                            </button>
                                            <template x-for="cat in categories" :key="cat.id">
                                                <button @click="productModalCategory = cat.id" 
                                                        :class="productModalCategory === cat.id ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 font-bold' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                                        class="w-full text-left px-4 py-3 rounded-xl text-sm transition-colors flex justify-between items-center">
                                                    <span x-text="cat.name"></span>
                                                    <span x-show="productModalCategory === cat.id" class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Product List -->
                                    <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-white dark:bg-gray-900 relative custom-scrollbar">
                                         <!-- Empty State -->
                                         <div x-show="filteredModalProducts.length === 0" class="h-full flex flex-col items-center justify-center text-center opacity-60">
                                            <i class="ti ti-search-off text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500">Tidak ada produk yang cocok</p>
                                            <button @click="productModalSearch = ''; productModalCategory = ''" class="text-emerald-600 font-bold mt-2 hover:underline">Reset Filter</button>
                                        </div>

                                        <!-- List View -->
                                        <div class="pb-20 space-y-2">
                                            <template x-for="product in filteredModalProducts" :key="product.id">
                                                <div @click="toggleModalProduct(product)" 
                                                     class="flex items-center gap-4 p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-emerald-300 cursor-pointer transition-all group"
                                                     :class="isModalProductSelected(product.id) ? 'bg-emerald-50/50 border-emerald-500' : ''">
                                                    
                                                    <!-- Checkbox visual -->
                                                    <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0"
                                                         :class="isModalProductSelected(product.id) ? 'bg-emerald-500 border-emerald-500' : 'border-gray-300 bg-white group-hover:border-emerald-400'">
                                                        <i x-show="isModalProductSelected(product.id)" class="ti ti-check text-white text-[10px] font-bold"></i>
                                                    </div>

                                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-400">
                                                        <i class="ti ti-package"></i>
                                                    </div>

                                                    <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                                                        <div>
                                                            <h4 class="font-bold text-gray-900 dark:text-white truncate" x-text="product.name"></h4>
                                                            <div class="text-xs text-gray-500 font-mono" x-text="product.code"></div>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-xs text-gray-500">Stok: </span>
                                                            <span class="font-bold" :class="product.actual_stock > 0 ? 'text-emerald-600' : 'text-red-500'" x-text="product.actual_stock"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Drawer Footer -->
                                <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 px-4 py-6 sm:px-6 bg-gray-50 dark:bg-gray-800">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg" x-text="selectedModalProducts.length"></div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-900 dark:text-white">Produk Dipilih</span>
                                                <button @click="selectedModalProducts = []" x-show="selectedModalProducts.length > 0" class="text-xs text-red-500 hover:underline text-left">Hapus Semua</button>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" @click="showProductModal = false">Batal</button>
                                            <button type="button" class="inline-flex justify-center rounded-xl border border-transparent bg-emerald-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed" 
                                                    @click="addSelectedProducts()"
                                                    :disabled="selectedModalProducts.length === 0">
                                                Tambahkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>